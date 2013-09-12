<?php


class StreamVideoVimeo{
	var $responseObj = null;
	var $url = '';
	var $videoId = null;
	
	public function init($url)
	{
		jimport('joomla.http.http');
		$this->url 		= $url;
		$this->videoId 	= $this->getId();
		$feedURL = 'http://vimeo.com/api/v2/video/'.$this->videoId.'.json';
		
		$options = new JRegistry();
		$transport = new JHttpTransportCurl($options);
		$http = new JHttp($options, $transport);
		
		$response =  $http->get( $feedURL );
		$this->responseObj = json_decode($response->body);
		$this->responseObj = $this->responseObj[0]; // only take the first response
		return true;
	}
	
	/*
	 * Return true if successfully connect to remote video provider
	 * and the video is valid
	 */	 	 
	public function isValid()
	{
                
		// Connect and get the remote video
		if ( $this->xmlContent == 'Invalid id')
		{
			$this->setError(JText::_('COM_COMMUNITY_VIDEOS_INVALID_VIDEO_ID_ERROR'));
			return false;
		}
		
		if($this->xmlContent == 'Video not found')
		{
			$this->setError(JText::_('COM_COMMUNITY_VIDEOS_YOUTUBE_ERROR'));
			return false;
		}

		
	
		
		
		return true;
	}
	
	/**
	 * Extract YouTube video id from the video url submitted by the user
	 * 	 	
	 * @access	public
	 * @param	video url
	 * @return videoid	 
	 */
	public function getId()
	{
		if($this->videoId){
			return $this->videoId;
		}
		
		preg_match_all('~
	        https?://(www\.)?vimeo\.com/(\d+)
	        ~ix',
				$this->url, $matches);
				

    	if( isset($matches) && !empty($matches) ){
			return $matches[2][0];
		}
		
		return false;
	}
	
	/**
	 * Return the video provider's name
	 * 
	 */
	public function getType()
	{
		return 'vimeo';
	}
	
	public function getTitle()
	{
		return $this->responseObj->title;
	}
	
	public function getDescription()
	{
		return $this->responseObj->description;
	}
	
	public function getDuration()
	{
		return $this->responseObj->duration;
	}
	
	/**
	 * Get video's thumbnail URL from videoid
	 * 
	 * @access 	public
	 * @param 	videoid
	 * @return url
	 */
	public function getThumbnail()
	{
		return $this->responseObj->thumbnail_small;
	}
	
	/**
	 * 
	 * 
	 * @return $embedvideo specific embeded code to play the video
	 */
	public function getViewHTML($videoId, $videoWidth, $videoHeight)
	{
		$config = CFactory::getConfig();
		if (!$videoId)
		{
			$videoId	= $this->getId();
		}

		$html = '';
		if($config->get('use_youtube_iframe_embed'))
		{
			// Use new iframe embed method
			$html = '<iframe class="youtube-player" type="text/html" width="'.$videoWidth.'" height="'.$videoHeight.'" src="http://www.youtube.com/embed/'.$videoId.'" frameborder="0">
				</iframe>';
		}
		else
		{
			$html = "<embed src=\"http://www.youtube.com/v/" .$videoId. "&hl=en&fs=1&hd=1&showinfo=0&rel=0\" type=\"application/x-shockwave-flash\" allowscriptaccess=\"always\" allowfullscreen=\"true\" width=\"".$videoWidth."\" height=\"".$videoHeight. "\" wmode=\"transparent\"></embed>";
		}
		
		return $html;
	}
}