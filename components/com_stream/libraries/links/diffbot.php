<?php

/**
 * Diffbot is a service to fetch content of a url of an article or blog and return the content in the json format
 * @url: http://www.diffbot.com/
 */

/* override joomla library and handle ourselves */
jimport('joomla.http.transport.xcurl');

class StreamLinksDiffbot
{
	//const DEVELOPER_KEY = "89ad0ff7c4af43d6133bd779a57fe383";
	const DEFAULT_LENGTH_OF_TEXT = 200;
	/* unit is in seconds */
	const MAXIMUM_RESPONSE_TIME = 3;
	const MAXIMUM_RESPONSE_TIME_FOR_IMAGE = 8;

	/* for url that failed to fetch the param will be the json_encoded value of the following */
	public $ERROR_TAG_WITH_IGNORE = array('ignore'=>true);
	/* for url that exceed to many refetch attempt */
	public $ERROR_TAG_WITH_JUMP = array('jump'=>true);

	/**
	 * @return string JSON string
	 */
	public function grab($url, $opt=array()) {
		/* some request need to be ignored */
		if (self::ignore($url)) {
			/* certain link should fetch the information */
			return json_encode($this->ERROR_TAG_WITH_IGNORE);
		}

		jimport('joomla.http.http');
		$jxConfig = new JXConfig();
		/* pass token in url since cannot be set to request body */
		//$uri = 'http://www.diffbot.com/api/article?token='.self::DEVELOPER_KEY.'&url='.$url;
		$uri = 'http://www.diffbot.com/api/article?token='.$jxConfig->get(JXConfig::DIFFBOT).'&url='.$url;
		$u = JUri::getInstance($uri);
		$registry = new JRegistry();
		$curl = new JHttpTransportXCurl($registry);
		$timeout = (!empty($opt) && is_numeric($opt['timeout'])) ? $opt['timeout'] : self::MAXIMUM_RESPONSE_TIME;
		/* expected object is JHttpResponse */
		$result = new JHttpResponse;
		try {
			/* passing token by data wont work, just use to pass timeout option */
			$result = $curl->request('GET', $u, null, null, $timeout);

			/* response is set to return empty $result->body if exceeding timeout */
			if (!$result->body) { 
				throw new Exception('Timeout exceeded');
			}
		}
		catch( Exception $e) {
			/* catch more error codes */
			if (!empty($result->code) && $result->code != 200) {
				return false;
			}
			/* to check the content of retrieved value */
			$body = json_decode($result->body);

			/* if its an errornous request fallback to error handler */
			if (!empty($body->error)) {
				/* now $body contains $body->error and $body->errorCode */
				$err = array('error'=>$body->error,
							 'code'=>$body->errorCode);
				$err = array_merge($err, $this->ERROR_TAG_WITH_IGNORE);
				$result->body = json_encode($err);
			}
			else {
				/* under some circumstances, the service will failed to load the excerpt from the url
				 * if so, tagged with ignore as this will exhaust the request if we keep fetching them */
				$result->body = json_encode($this->ERROR_TAG_WITH_IGNORE);
			}
		}
		/* preserve the json result and format using format() */
		return $result->body;
	}

	/**
	 * Store image inside our asset server
	 */
	public function imageToAsset($url) {
		$path = '';
		$uri = $url;
		$u = JUri::getInstance($uri);
		$registry = new JRegistry();
		$curl = new JHttpTransportXCurl($registry);
		/* expected object is JHttpResponse */
		$result = new JHttpResponse;
		try {
			/* passing token by data wont work, just use to pass timeout option */
			$result = $curl->request('GET', $u, null, null, self::MAXIMUM_RESPONSE_TIME_FOR_IMAGE);
		}
		catch (Exception $e) {
			return false;
		}

		$body = json_decode($result->body);

		if (!empty($body->error) && $body->error) {
			return false;
		}
		if (empty($body->path)) {
			return false;
		}
		return $body->path;
	}

	/**
	 * Certain situation need some condition before can be displayed
	 * @param $json String json string
	 * @return mixed array/stdClass
	 */
	public function format($json, $toObj = true) {
		// evil characters
		$dirty = array('<', '>');
		$clean = array('&lt;', '&gt;');

		$body = json_decode($json);
		$data['icon'] = (!empty($body->icon)) ? $body->icon : '';
		$data['text'] = (!empty($body->text)) ? $body->text : '';
		// make sure the enclosing tags work as display only
		$data['text'] = str_replace($dirty, $clean, $data['text']);

		if (strlen($data['text']) > self::DEFAULT_LENGTH_OF_TEXT) {
			$data['text'] = substr($data['text'], 0, self::DEFAULT_LENGTH_OF_TEXT) . '&hellip;';
		}

		$data['title'] = (!empty($body->title)) ? $body->title : '';
		$data['title'] = str_replace($dirty, $clean, $data['title']);

		// @TODO: data retrieved are not always organized try finding a pattern
		// its better to hide the image container if the image is missing
		$correctMedia = (!empty($body->media[0])) ? $body->media[0] : '';

		$data['media_link'] = (!empty($correctMedia->link)) ? $correctMedia->link : '';
		$data['media_type'] = (!empty($correctMedia->type)) ? $correctMedia->type : '';
		$data['url'] = (!empty($body->url)) ? $body->url : '';
		$data['url'] = str_replace($dirty, $clean, $data['url']);

		if ($toObj) {
			return JArrayHelper::toObject($data);
		}
		return $data;
	}

	public function ignore($url) {
		$pattern = '/.*((youtu\.?be|slideshare|vimeo).*|\.(exe|dmg|zip|gz|bz2|dll))$/';
		preg_match($pattern, $url, $match);
		return (!empty($match[1])) ? true : false;
	}

	/**
	 * Whenever a request exceed StreamLinks::LINKS_MAINTENANCE_REFETCH_DURATION or StreamLinkDiffbot::MAXIMUM_RESPONSE_TIME
	 * It will be tag with ignore to avoid same fetch on probably dead/slow url
	 * Further fetching will be done with cron
	 * @deprecated: since getIgnoreRegex() added
	 */
	public function getIgnoreTag() {
		$errorTag = json_encode($this->ERROR_TAG_WITH_IGNORE);
		// since the addition of attempt parameter we need to look for less specific ignore tag
		$errorTag = str_replace(array('{', '}'), '', $errorTag);
		return $errorTag;
	}

	/**
	 * Since the implementation of StreamLinksDiffbot::getJumpTag or the implementation to skip certain exhaustible request
	 * the $table->params has been added data that contains more data than getIgnoreTag()
	 * if a search is made to look for the ignore tag, it will fails, this regex search is needed to look for the correct ignored result
	 */
	public function getIgnoreRegex() {
		return '(\{\"ignore\":true[\},]|.+\"ignore\":[0-9]+\})';

	}

	/**
	 * Whenever the StreamLinks::LINK_REFETCH_LIMIT_ATTEMPT is exceeded
	 * particular link will be marked as the following tag to avoid exhausting service request
	 */
	public function getJumpTag() {
		return json_encode($this->ERROR_TAG_WITH_JUMP);
	}

	public function errorHandler() {
		// send email to the system notifying expiring keys or whatnot
	}
}
?>