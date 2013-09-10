<?php

jimport('joomla.user.helper');
include_once(JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'html'.DS.'string.php');
class StreamMessage
{
	public static function format($str, $options = array())
	{
		// it need to be escaped first
		$str = StreamTemplate::escape($str);
		
		// Value return from getMentionUserId
		// array( $userId => array('@username', 'username'));
		$mentionedUserId = self::getMentionUserId( $str );
		
		// Check options for required processing
		$uriBase = '';
		if (isset($options['fullUri']) && $options['fullUri'])
		{			
			$uri		= JURI::getInstance();
			$uriBase	= $uri->toString( array('scheme', 'host', 'port'));
		}
		
		// Replacer will replace the url only after autolink perform 
		// to avoid autolink automatically linking on <a> tag urls again
		$replacer = array();
		
		foreach( $mentionedUserId as $userid => $username )
		{
			$userid = $username[3];
			if( !empty($userid))
			{
				$user = JXFactory::getUser($userid);
				$uid = '%username.replace.'.$userid.'%';
				$replacer[$uid] = $uriBase.JRoute::_('index.php?option=com_profile&view=display&user='.$username[1]);
				$str = str_ireplace($username[0], '<a href="'.$uid.'">'. StreamTemplate::escape($user->name) .'</a>', $str);
			}
		}
		// !important: We need to autolink before adding hash tag as regex hashtag will disturb tag inside a url
		// example: http://lightglitch.github.com/bootstrap-xtra/#grid-system
		// character #grid-system will be add as a tag and break the url
		// make sure the url is safely converted via StreamMessage::autoLink() and then grab the hashtag
		$str = StreamMessage::autoLink($str);

		// Autolinked #hashtag
		preg_match_all("".
					   "/[\n\s#]#(?!\.)([^\s\",']+[^\n.\s])|^#([^\s]+)/"
					   ."", $str, $matches, PREG_SET_ORDER);

		if(!empty( $matches )) 
		{
			foreach( $matches as $hashtag )
			{
				// match might be inside second capture and no deeper
				$hashtag1 = (!empty($hashtag[1])) ? $hashtag[1] : $hashtag[2];
				$uid = '%hashtag.replace.'.$hashtag1.'%';
				$replacer[$uid] = $uriBase.JRoute::_('index.php?option=com_stream&view=company&task=tagFilter&search=HASHTAG'.urlencode($hashtag1) );
				$str = str_ireplace($hashtag[0], '<a class="tag" href="'.$uid.'">'.$hashtag1.'</a>', $str);
			}
		}

		// Nl2br , convert multiple lines breaks to just 1
		$str	= str_ireplace(array("\r\n", "\r", "\n"), "<br />", $str );
		
		// Ps:// i split the ? and > to assist with editor syntax highlighting
		$str = preg_replace("/(<br\s*\/?".">\s*){3,}/", "<br /><br />", $str);
		
		// Replace back <a> tags 
		foreach($replacer as $replaceNeedle => $replaceVal)
		{
			$str = str_replace($replaceNeedle, $replaceVal, $str);
		}
		
		
		// Hashtag repplacer
		$str = str_replace('HASHTAG', '%23' , $str);		
		// if the tag is started and generated like so ##tag, the url will be precede with
		// another # symbol normalize this behaviour
		$str = str_replace('%23#', '%23' , $str);		
		return $str;	
	}
	
	/**
	 * Return the hashtags of the messages
	 * @param  [type] $message [description]
	 * @return [type]          [description]
	 */
	public static function getHashtags($message){
		preg_match_all("/(?!.*\=)#(\w\w+)/", $message, $matches, PREG_SET_ORDER);
		$result = array();
		if(!empty( $matches )) 
		{
			foreach( $matches as $hashtag )
			{
				$result[] = $hashtag[1]; 
			}
		}
		
		return $result;
	}
	
	public static function sortAttachment($fileA, $fileB){
		if($fileA->getParam('has_preview') && !$fileB->getParam('has_preview')){
			return 1;
		}
		
		if(!$fileA->getParam('has_preview') && $fileB->getParam('has_preview')){
			return -1;
		}
		
		// Both file has preview. Compare the ID to make sure we maintain original
		if($fileA->id > $fileB->id)
			return 1;
		else
			return  -1;
	}
	
	/**
	 * Return attachment view for the given stream
	 */
	public static function getAttachmentHTML($stream)
	{
		
		$my = JXFactory::getUser();
		$data = json_decode($stream->raw);
		$html = '';

		if (!function_exists('whoMakesAction')) {
			/**
			 * Call this function to retrieve the item/message viewer
			 * @param JTable $stream the current StreamTable
			 * @param int $item_id the owner of the item (for example: id of the file or id of a message contains a link)
			 * @param String $type type of the item (since tracking is done by id, being specific is safer. eg: file_220, link_220)
			 */
			function whoMakesAction($stream, $item_id = 0, $type = NULL) {
				// get list of avatar who viewed the stream 
				$whoMakesAction = $stream->whoMakesAction($item_id, $type);
				$avatarListWhoMakeAction = '';

				if ($whoMakesAction && count($whoMakesAction) > 0) {
					// Do rename the variable if its too long or easily mistyped
					$avatarListWhoMakeAction .= '<div class="user-horizontal-list message-reader-list">';
					if ($type=='video') {
						// change language from READ to SEEN if its a video
						$avatarListWhoMakeAction .= '<span class="small">' . JText::_('COM_STREAM_LABEL_SEEN_BY') . ' ';
					}
					else {
						$avatarListWhoMakeAction .= '<span class="small">' . JText::_('COM_STREAM_LABEL_READ_BY') . ' ';
					}
					/* $avatarListWhoMakeAction .= count($whoMakesAction) . ' reader'; */
					$avatarListWhoMakeAction .= '<a href="#showReaders" data-content="<ul>';
					foreach($whoMakesAction as $user_id) {
						// there will be 0 as user which in return will load current user
						if ($user_id != 0 && $user_id != NULL) {
							$user = JXFactory::getUser($user_id);
							$avatarListWhoMakeAction .= StreamTemplate::escape('<li><a href="'.$user->getURL().'">'.$user->name.'</a></li>');
						}
					}
					$avatarListWhoMakeAction .= '</ul>">';
					$label = (count($whoMakesAction) > 1) ? JText::_('COM_STREAM_LABEL_USERS') : JText::_('COM_STREAM_LABEL_USER');
					$avatarListWhoMakeAction .= count($whoMakesAction)." $label</a>";
					$avatarListWhoMakeAction .= '</span></div>';
				}
				return $avatarListWhoMakeAction;
			}
		}

		// Attachment
		$jxConfig = new JXConfig();
		$files = $stream->getFiles();
		$hasPreview = false;
		$numPreview = 0;
		// Sort the files, photos at the bottom
		usort($files, array('StreamMessage', 'sortAttachment'));
		
		$imgHtml = '<div class="message-content-attachment">';
		
		foreach($files as $file)
		{
			// only show if the file does exist
			// @todo: templatize this ?	
			$dlLink = JRoute::_('index.php?option=com_stream&view=system&task=download&file_id='.$file->id);
			
			// Show file name only if preview doesn't exist
			// Otherwise, just show the preview. People can click on the preview and download it from tehre
			if(!$file->getParam('has_preview') ){
				// Show preview link, of if the filename is doc, docx, pdf, ppt, pptx
				$fext = strtolower(substr($file->filename, -4));

				$html .= '<div data-filename="'.$file->filename.'" '. 'data-message_id=' .$stream->id . ' class="message-content-file ">';
				$html .= '<a  title="Click to download" href="'.$dlLink.'">'.StreamTemplate::escape( JHtmlString::abridge($file->filename, 20, 13)).'</a>';
				$html .= ' <span class="small hint">('.StreamMessage::formatBytes($file->filesize, 1). ')</span>';
				// append to file container only once
				if ($jxConfig->isCrocodocsEnabled() || $jxConfig->isScribdEnabled())
				{
					if( in_array($fext, array('.doc', 'docx', '.pdf', '.ppt', 'pptx'))){
						$html .= ' <a href="#preview" class="meta-preview small" data-filename="'. StreamTemplate::escape($file->filename) .'" data-file_id="'.$file->id.'" onclick="return S.preview.show(this);">'.JText::_('COM_STREAM_LABEL_PREVIEW').'</a>';
					}
				}

				$html .= whoMakesAction($stream, $file->id, 'file');
				$html .= '<div class="clear"></div>';
				$html .= '</div>';				
				/*
				// File can only be remove in 'edit' view
				if( $my->authorise('stream.message.edit', $stream) ){
					$html .= '<a class="meta-edit" href="#removeAttachment" file_id="'.$file->id.'">'. JText::_('COM_STREAM_LABEL_REMOVE').'</a>';			
				}
				*/
			}
			
			if($file->getParam('has_preview') ){
				$randId = 'preview_'.rand ( 1000, 9999 );
				$path = str_replace(DS, '/',$file->getParam('thumb_path'));
				$imgHtml .= '<div class="message-content-preview"><img rel="#'.$randId.'" src="'.JURI::root(). $path .'" /></div>';
				// Attach overlay code
				$width  = $file->getParam('width');
				$height = $file->getParam('height');
				if(!empty($width) && !empty($height)){
					
					if($width > 640){
						$height = (640/$width* $height);
						$width = 640;
					}
					
					if($height >  640){
						$width = (640/$height * $width);
						$height = 640;
					}
					$viewLink = JRoute::_('index.php?option=com_stream&view=system&task=download&file_id='.$file->id .'&display=1');
					$dlLink = JRoute::_('index.php?option=com_stream&view=system&task=download&file_id='.$file->id);
					
					// Replace all <show_next> tag, since the previous one is clearly not the last one
					$imgHtml = str_replace('<show_next>', '<div class="image_next btn btn-large" onclick="$(\'[rel=\\\'#'.$randId.'\\\']\').click();">&rarr;</div>', $imgHtml);
					
					$imgHtml .= '
						<div id="'.$randId.'" class="apple_overlay" style="width:'. ($width) .'px">';
					
					
					// IF this is NOT the first preview, add the 'PREV' button	
					if($numPreview != 0){
						$imgHtml .='<div class="image_prev btn btn-large" onclick="$(\'[rel=\\\'#'.$prevRandId.'\\\']\').click();">&larr;</div>';
					}
					$imgSrc = $dlLink;
					if($file->getParam('preview_path') ){
						$imgSrc = str_replace(DS, '/',$file->getParam('preview_path'));
						$imgSrc = JURI::root() . $imgSrc;
					}
					$imgHtml .='<show_next>
						<a class="close"></a>
						<img width="'.$width.'" height="'.$height.'" src="'.$imgSrc.'" />
						<div>
						<a href="'.$viewLink.'" target="_blank">View full-size image</a> 
						• <a href="'.$dlLink.'">Download</a></div>
						</div>';
					
					$prevRandId = $randId;
					$hasPreview = true;
					$numPreview++;
				}
			}			
		}
		$imgHtml .= '</div>';
		
		// If there is no attachement at all, remove the div
		$imgHtml = str_replace('<div class="message-content-attachment"></div>', '', $imgHtml);
		
		$html .= $imgHtml;
		
		// If we have added the preview, which is left floated, we need to add a clearing div
		// the sorting function above will make sure that preview'ed would be the last attachment
		if($hasPreview)
		{
			// Get rid of all the <show_next> marker
			$html = str_replace('<show_next>', '', $html);
			
			$html.= '<div class="clear"></div>';
		}

		// Videos
		if(!empty($data->video)){
			foreach($data->video as $videoid)
			{
				$video = JTable::getInstance( 'Video' , 'StreamTable' );
				if($video->load($videoid)){
					$html .= '<div class="message-content-video" id="video-'.$videoid.'">
						<img class="message-content-video-thumbnail interactive" src="'. $video->thumb .'" embed_id="'.$videoid.'"  embed_type="videos"/>
						<span class="video-duration">'. StreamMessage::formatDuration($video->duration).'</span>
						<div class="message-content-preview-desc">
							<div class="preview-title">'.JHtmlString::truncate($video->title, 24).'</div>
							<div class="preview-desc">'.JHtmlString::truncate($video->description, 180).'</div> 
						</div><div class="clear"></div>'. whoMakesAction($stream, $video->id, 'video') . '
						<div class="clear"></div>
						</div>';

				}
			}
		}
		
		// Slideshare
		if(!empty($data->slideshare)){
			foreach($data->slideshare as $slideshareid)
			{
				$slideshare = JTable::getInstance( 'Slideshare' , 'StreamTable' );
				if($slideshare->load($slideshareid)){
					$ss = json_decode($slideshare->response);
					$html .= '<div class="message-content-video slideshare" id="video-'.$slideshareid.'">
						<img src="'.$ss->thumbnail.'" embed_id="'.$slideshareid.'"  embed_type="slideshare"/>
						<span class="video-duration"></span>
						<div class="message-content-preview-desc">
							<div class="preview-title">'.JHtmlString::truncate($ss->title, 24).'</div>
							<div class="preview-desc">'.JHtmlString::truncate($ss->author_name, 180).'</div> 
						</div><div class="clear"></div>'. whoMakesAction($stream, $slideshareid, 'slideshare') . '
						<div class="clear"></div>
						</div>';
				}
			}
		}
		
		/* Link service
		 * Certain link will able to store excerpt from the linked page */
		$params = json_decode($stream->params);
		/* refetch if the link is not grab yet */
		$url = self::getLinks($stream->message);
		if (!empty($url[0])) {
			$linkTable = JTable::getInstance( 'Link' , 'StreamTable' );
			if ($linkTable->load(array('link'=>$url[0]))) {
				$linkParam = StreamLinks::format($linkTable->params);
				if (!empty($linkParam) && strlen($linkParam->text) > 0) {

					$html .= '<div class="stream-message-links-in-post">';
					if ($linkParam->media_type && $linkParam->media_link) {
						if (strlen($linkParam->media_type == 'image' && $linkParam->media_link) > 0) {
							$html .= '<div class="stream-links-image-container">
						<span>
						<img class="stream-message-links-image" src="'. StreamLinks::imageAssetThumbnailPath($linkParam->media_link).'" />
						</span>
						</div>';
						}
					}
					$html .= '<div class="stream-links-container">
				<div class="stream-message-links-title">'. $linkParam->title .'</div>
				<div class="stream-message-links-url">'. $linkParam->url .'</div>
				<div class="stream-message-links-content">' .$linkParam->text .'</div>'.
				// since link can only previewed single url in a message, use message id for second 2nd argument to whoMakesAction()
			   '</div><div class="clear"></div>'. whoMakesAction($stream, $stream->id, 'link') . '
				<div class="clear"></div>
				</div>';
				}
			}
		}
		return $html;
	}
	
	/**
	 * Return html tags and entities free and truncated message
	 * NOTE: For display purpose only, DO NOT USE FOR DATABASE Queries
	 */
	static public function formatShortDisplay($str, $options = array(), $length = 180)
	{
		$str = strip_tags(html_entity_decode($str, ENT_COMPAT, 'UTF-8'));
		$str = JHtmlString::truncate($str, $length);
		return self::format($str, $options);
	}
	
	/**
	 * Return nicely formatted filesize
	 */	 	
	static public function formatBytes($size, $precision = 2)
	{
	    if($size == 0)
	    	return '0';
	    	
		$base = log($size) / log(1024);
	    $suffixes = array('', 'k', 'M', 'G', 'T');   
	
	    return round(pow(1024, $base - floor($base)), $precision) . $suffixes[floor($base)];
	}

	
	/**
	 *  Return the number of new message since the last message id
	 */	 	
	public static function countMessageSince( $message_id )
	{
		$my = JXFactory::getUser();
		$model = StreamFactory::getModel('stream');
		return $model->countMessageSince( $message_id, $my->id );	
	}
	
	/**
	 *  Return the number of new message since the last message id
	 */	 	
	public static function lastMessageId( )
	{
		$model = StreamFactory::getModel('stream');
		return $model->lastMessageId();	
	}
	
	/**
	 *  Auto-link the given string
	 */	 	
	public static function autoLink($text)
	{
		/* subdomain must be taken into consideration too */
	   $pattern  = '~(
					  (
					   (?<=([^[:punct:]]{1})|^)			# that must not start with a punctuation (to check not HTML)
					   	https?://[^-][a-zA-Z0-9-]*?[.]	# normal URL lookup
					   )
					   [^\s()<>]+						# characters that satisfy SEF url
					   (?:								# followed by
					   		\([\w\d]+\)					# common character
					   		|							# OR
					   		([^[:punct:]\s]|/)			# any non-punctuation character followed by space OR forward slash
					   )
					 )~x';
	   $callback = create_function('$matches', '
	       $url       = array_shift($matches);
	       $url_parts = parse_url($url);
	
	       $text = parse_url($url, PHP_URL_HOST) . parse_url($url, PHP_URL_PATH);
	       $text = preg_replace("/^www./", "", $text);
	
	       $last = -(strlen(strrchr($text, "/"))) + 1;
	       if ($last < 0) {
	           $text = substr($text, 0, $last) . "&hellip;";
	       }
			$isInternal = JURI::isInternal($url) ? \'\': \'target="_blank" \';
	       return sprintf(\'<a rel="nofollow" \'.$isInternal .\' href="%s">%s</a>\', $url, $text);
	   ');
	   return preg_replace_callback($pattern, $callback, $text);
	}
	
	/**
	 * Return all the links inside the message
	 */	 	
	public static function getLinks($text)
	{
		/* subdomain must be taken into consideration too */
		$pattern  = '#\b(((http|https)+://[^-][a-zA-Z0-9-]*?[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';
	   
		preg_match_all($pattern, $text, $matches);

		$links = array();
		if( $matches )
		{
			foreach($matches[0] as $match){
					$links[]	= $match;
			}
		}
		return $links;
	}
	
	// Function to return URL to the particular stream message
	static public function getMessageUri( $messageId )
	{
		if ( intval( $messageId ) > 0 )
		{
			$uri	= JURI::getInstance();
			$base	= $uri->toString( array('scheme', 'host', 'port'));
			return $base . JRoute::_( 'index.php?option=com_stream&view=message&task=show&message_id='.$messageId );
		}
		
		return JURI::root();
	}
	
	// Function to return URL to the particular stream message
	static public function getGroupUri( $groupId )
	{
		if ( intval( $groupId ) > 0 )
		{
			$uri	= JURI::getInstance();
			$base	= $uri->toString( array('scheme', 'host', 'port'));

			return $base . JRoute::_( 'index.php?option=com_stream&view=groups&task=show&group_id='.$groupId );
		}
		
		return JURI::root();
	}
	
	static public function getMentionUserId( $strMessage )
	{
		$arrUserId		= array();
		if (empty( $strMessage ))
		{
			return $arrUserId;
		}
		
		$str = StreamTemplate::escape($strMessage);
		
		// Autolinked the @username 
		preg_match_all("/@([\w.]+)/", $str, $matches, PREG_SET_ORDER);
		
		if(!empty( $matches )) 
		{
			foreach( $matches as $username )
			{
				// Find out if the user exist
				$userId				= JuserHelper::getUserId($username[1]);
				$username[3]		= $userId;
				$arrUserId[$userId]	= $username;
			}
			
			// reorder to that the longest username gets replaced first
			usort($arrUserId, function($a, $b){
				if (strlen($a[0]) > strlen($b[0]))
					return -1;
				
				if (strlen($a[0]) < strlen($b[0]))
					return 1;
					
				return 0;	
			});
			
		}
		
		return $arrUserId;
	}
	
	static public function formatDuration($duration = 0, $format = 'HH:MM:SS')
	{
		if ($format == 'seconds' || $format == 'sec') {
			$arg = explode(":", $duration);
	
			$hour	= isset($arg[0]) ? intval($arg[0]) : 0;
			$minute	= isset($arg[1]) ? intval($arg[1]) : 0;
			$second	= isset($arg[2]) ? intval($arg[2]) : 0;
	
			$sec = ($hour*3600) + ($minute*60) + ($second);
			return (int) $sec;
		}
	
		if ($format == 'HH:MM:SS' || $format == 'hms') {
			$timeUnits = array
			(
				'HH' => $duration / 3600 % 24,
				'MM' => $duration / 60 % 60,
				'SS' => $duration % 60
			);
	
			$arg = array();
			foreach ($timeUnits as $timeUnit => $value) {
				$arg[$timeUnit] = ($value > 0) ? $value : 0;
			}
	
			$hms = '%02s:%02s:%02s';
			$hms = sprintf($hms, $arg['HH'], $arg['MM'], $arg['SS']);
			
			// trim it so it doesn't show unnecessary 'hour' section
			$hms = ltrim($hms, '0');
			$hms = ltrim($hms, ':');
			
			return $hms;
		}
	}
}