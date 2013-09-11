<?php
class StreamVideo {

	/**
	 *	return an array of video urls from the given message
	 */ 	 	
	public function getURL($message){
		$urls = array();
		// Get all links from message
		// From, stackoverflow.com/questions/5830387/php-regex-find-all-youtube-video-ids-in-string
		preg_match_all('~
	        # Match non-linked youtube URL in the wild. (Rev:20111012)
	        https?://         # Required scheme. Either http or https.
	        (?:[0-9A-Z-]+\.)? # Optional subdomain.
	        (?:               # Group host alternatives.
	          youtu\.be/      # Either youtu.be,
	        | youtube\.com    # or youtube.com followed by
	          \S*             # Allow anything up to VIDEO_ID,
	          [^\w\-\s]       # but char before ID is non-ID char.
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
	        [?=&+%\.\w-]*        # Consume any URL (query) remainder.
	        ~ix',
				$message, $matches);
				
		
    	if( isset($matches) && !empty($matches) ){
			$urls = array_merge($urls, $matches[0]);
		}
		
		// match vimeo
		preg_match_all('~
	        https?://(www\.)?vimeo\.com/(\d+)
	        ~ix',
				$message, $matches);
		
    	if( isset($matches) && !empty($matches) ){
			$urls = array_merge($urls, $matches[0]);
		}
		
		return $urls;
	}
	
	public static function getType($message){
		preg_match_all('~
	        # Match non-linked youtube URL in the wild. (Rev:20111012)
	        https?://         # Required scheme. Either http or https.
	        (?:[0-9A-Z-]+\.)? # Optional subdomain.
	        (?:               # Group host alternatives.
	          youtu\.be/      # Either youtu.be,
	        | youtube\.com    # or youtube.com followed by
	          \S*             # Allow anything up to VIDEO_ID,
	          [^\w\-\s]       # but char before ID is non-ID char.
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
	        [?=&+%\.\w-]*        # Consume any URL (query) remainder.
	        ~ix',
				$message, $matches);
    	if( isset($matches) && !empty($matches) && count($matches[0]) > 0 ){
			return 'youtube';
		}
		
		// match vimeo
		preg_match_all('~
	        https?://(www\.)?vimeo\.com/(\d+)
	        ~ix',
				$message, $matches);
		
    	if( isset($matches) && !empty($matches)  && count($matches[0]) > 0){
			return 'vimeo';
		}
		
		return false;
	}
	
	/**
	 * Return video object
	 */	 	
	public static function getVideo($type)
	{
		StreamFactory::load('libraries.videos.'.$type);
		$classname = 'StreamVideo'. ucfirst($type);
		return new $classname;
	}
}
