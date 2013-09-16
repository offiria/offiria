<?php

jimport('joomla.user.helper');
include_once(JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'html'.DS.'string.php');
class StreamLinks
{
	const DEFAULT_LINKS_SERVICE = 'diffbot';
	const ASSET_STORAGE_PATH = 'https://assets.offiria.com';
	const IMAGE_THUMBNAIL_WIDTH = 46;
	const IMAGE_THUMBNAIL_HEIGHT = 46;
	const LINKS_MAINTENANCE_REFETCH_DURATION = 9;

	// limit for how many url should query to be refetch 
	const LINK_REFETCH_LIMIT_URL = 3;
	// limit for refetching the same url
	const LINK_REFETCH_LIMIT_ATTEMPT = 5;

	private static function getInstance() {
		include_once(JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'links'.DS.self::DEFAULT_LINKS_SERVICE.'.php');
		$className = 'StreamLinks'.ucwords(self::DEFAULT_LINKS_SERVICE);
		return new $className;
	}

	public static function grab($url, $opt=array()) {
		$service = self::getInstance();
		$result = $service->grab($url, $opt);

		/* error will return false value */
		if (count($result) <= 0 || $result == false) {
			return false;
		}

		return $result;
	}

	/**
	 * imageToAsset is used to send the url to ASSET_STORAGE_PATH and store the entries inside the parameter
	 * this approach is no longer valid and is deprecated
	 * @deprecated: @see imageAssetThumbnailPath();
	 */
	public function imageToAsset($url) {
		$service = self::getInstance();
		return $service->imageToAsset($url);
	}

	/**
	 * imageAssetThumbnailPath will simply append post/prefix to a url and load the image directly
	 * @return String string of correct asset path
	 */
	public static function imageAssetThumbnailPath($url) {
		// return self::ASSET_STORAGE_PATH.'/?'.
		// 	http_build_query(array('url'=>urlencode($url), 
		// 						   'w'=>self::IMAGE_THUMBNAIL_WIDTH, 
		// 						   'h'=>self::IMAGE_THUMBNAIL_HEIGHT));

		return $url;
	}

	public static function format($json) {
		$service = self::getInstance();
		if (!is_string ($json)) {
			return false;
		}
		$params = json_decode($json);

		/* some link is provided base on a very slow server and will cause error to our system if we kept fetching them
		 * thus, the erronous link will be tagged with ignore*/
		if (!empty($params->ignore) && $params->ignore) {
			return false;
		}

		if (strlen($json) > 0) {
			return $service->format($json);
		}		
		return false;
	}

	/**
	 * Maintenance
	 * This will run in maintenance mode where the initial fetch fail to load
	 */
	public static function refetch() {
		$service = self::getInstance();
		$EMPTY_TIMESTAMP = '0000-00-00 00:00:00';
		$IGNORE_TAG = $service->getIgnoreTag();
		$IGNORE_REGEX = $service->getIgnoreRegex();
		$JUMP_TAG = $service->getJumpTag();
		$db = JFactory::getDbo();
		// query to look for params='$IGNORE_TAG' is for old data compatibility
		$q = "SELECT id FROM #__stream_links WHERE params REGEXP '$IGNORE_REGEX' ORDER BY id DESC LIMIT " . self::LINK_REFETCH_LIMIT_URL;
		$db->setQuery($q);
		$ids = $db->loadResultArray();
		$table = JTable::getInstance('Link', 'StreamTable');

		/* exit if no maintenance needed */
		if (count($ids) == 0) {
			return false;
		}

		foreach($ids as $id) {
			$table->load($id);
			/* provide an option to increase the timeout of retrieval, in case its possible to fetch by
			 * giving less restriction on timeout  */
			$opt = array('timeout'=>self::LINKS_MAINTENANCE_REFETCH_DURATION);
			$params = StreamLinks::grab($table->link, $opt);

			// update the timestamp once its valid, so the refetch will not affect the same item again
			if (!preg_match($IGNORE_REGEX, $params)) {
				$table->params = $params;
				$date = new JDate();
				$table->timestamp = $date->format('Y-m-d h:i:s');
				$table->store(true);
			}
			else {
				// we are going to tag the ignored tag with an IGNORE_TAG and ATTEMPT_COUNT
				$params = json_decode($table->params);
				$attempt = (!empty($params->attempt_count)) ? $params->attempt_count + 1 : 1;
				if ($attempt >= self::LINK_REFETCH_LIMIT_ATTEMPT) {
					// by assigning the jump tag, this function (refetch()) will no longer run on this entry
					$table->params = $JUMP_TAG;
				}
				else {
					// else continue the possibility to refetch
					$table->params = json_encode(array('ignore'=>true, 'attempt_count'=>$attempt));
				}
				$table->store(true);
			}
		}
		if (!self::sendLog('ikmal@azrul.com', json_encode($ids))) {
			return false;
		}
		return true;
	}

	/**
	 * After several refetch attempt based on LINK_REFETCH_LIMIT_ATTEMPT
	 * The link will be marked so it will be escaped (jump)
	 * But after a week, the same link should start on clean slate and attempt to refetch() should be made
	 * @see refetch()
	 */
	public function unjump() {
		$service = self::getInstance();
		$JUMP_TAG = $service->getJumpTag();

		$db = JFactory::getDbo();
		/* empty the params so next execution of refetch will allow this queried link to perform refetch */
		$q = "UPDATE #__stream_links SET params='' WHERE params='$JUMP_TAG'";
		return $db->query($q);
	}

	public static function sendLog($email, $data) {
		if (mail($email,'StreamLinks::refetch', $data)) {
			return true;
		}
		return false;
	}
}
