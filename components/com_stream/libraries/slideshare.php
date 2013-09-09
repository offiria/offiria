<?php
class StreamSlideshare {

	/**
	 *	return an array of video urls from the given message
	 */ 	 	
	public function getURL($message){
		// Get all links from message
		// From, stackoverflow.com/questions/5830387/php-regex-find-all-youtube-video-ids-in-string
		preg_match_all('~
	        # Match non-linked youtube URL in the wild. (Rev:20111012)
	        http://www.slideshare.net([A-Z|a-z|0-9|/|-]*)
	        ~ix', 
			$message, $matches);
    	if( isset($matches) && !empty($matches) ){
			return $matches[0];
		}
		
		return FALSE;
	}
}
