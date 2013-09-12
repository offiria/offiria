<?php


class StreamVideoYoutube{
	var $xmlContent = null;
	var $url = '';
	var $videoId = null;
	
	
	public function init($url)
	{
		jimport('joomla.http.http');
		$this->url 		= $url;
		$this->videoId 	= $this->getId();
		$feedURL = 'http://gdata.youtube.com/feeds/api/videos/' . $this->videoId;
		
		$options = new JRegistry();
		$transport = new JHttpTransportCurl($options);
		$http = new JHttp($options, $transport);
		
		$response =  $http->get( $feedURL );
		$this->xmlContent = $response->body;
		
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
	        # Match non-linked youtube URL in the wild. (Rev:20111012)
	        https?://         # Required scheme. Either http or https.
	        (?:[0-9A-Z-]+\.)? # Optional subdomain.
	        (?:               # Group host alternatives.
	          youtu\.be/      # Either youtu.be,
	        | youtube\.com    # or youtube.com followed by
	          \S*             # Allow anything up to VIDEO_ID,
	          [^\w\-\s;]       # but char before ID is non-ID char.
	        )                 # End host alternatives.
	        ([\w\-]{11})      # $1: VIDEO_ID is exactly 11 chars.
	        (?=[^\w\-]|$)     # Assert next char is non-ID or EOS.
	        (?!               # Assert URL is not pre-linked.
	          [?=&+%\w]*      # Allow URL (query) remainder.
	          (?:             # Group pre-linked alternatives.
	            [\'"][^<>]*>  # Either inside a start tag,
	          | </a>          # or inside <a> element text contents.
	          )               # End recognized pre-linked alts.
	        )                 # End negative lookahead assertion.
	        [?=&+%\w]*        # Consume any URL (query) remainder.
	        ~ix', 
			$this->url, $matches);
		
    	if( isset($matches) && !empty($matches[1]) ){
    		return $matches[1][0];
		}
		
		return false;
	}
	
	/**
	 * Return the video provider's name
	 * 
	 */
	public function getType()
	{
		return 'youtube';
	}
	
	public function getTitle()
	{
		$title = '';
		// Store video title
		$pattern =  "/<title type='text'>(.*?)<\/title>/i";
		preg_match_all($pattern, $this->xmlContent, $matches);
		if($matches)
		{
		    $title = $matches[1][0];
		}
		
		return $title;
	}
	
	public function getDescription()
	{
		$description = '';	
		// Store description
		$pattern =  "/<content type='text'>(.*?)<\/content>/s";
		preg_match_all($pattern, $this->xmlContent, $matches);
		if($matches && !empty($matches[1][0]) )
		{
		    $description = $matches[1][0];
		}
		
		return $description;
	}
	
	public function getDuration()
	{
		$duration = 0;
		// Store duration
		$pattern =  "/seconds='(.+?)'/i";
		preg_match_all($pattern, $this->xmlContent, $matches);
		if($matches)
		{
			$duration = $matches[1][0];
		}
		
		return $duration;
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
		return 'https://img.youtube.com/vi/' . $this->getId() . '/default.jpg';
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