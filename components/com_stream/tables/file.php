<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

/*
CREATE TABLE `dev_e20`.`fwhz7_stream_files` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`filename` VARCHAR( 1024 ) NOT NULL ,
`mimetype` VARCHAR( 255 ) NOT NULL ,
`filesize` INT NOT NULL ,
`path` VARCHAR( 1024 ) NOT NULL ,
`user_id` INT( 11 ) NOT NULL ,
`group_id` INT( 11 ) NOT NULL ,
`params` TEXT NOT NULL
) ENGINE = MYISAM ;
*/

class StreamTableFile extends JTable
{
	// Tables' fields
	var $id		=   null;
	var $status		= null;
	var $access		= null;
	var $stream_id 	= null;
	var $created 	= null;
	var $filename	= null;
	var $mimetype	= null;
	var $filesize	= null;
	var $path		= null;
	var $user_id	= null;
	var $group_id	= null;
	var $param		= null;
	var $followers	= null;
	
	private $_params	= null;
	
	const PHOTO_THUMB_WIDTH = 160;
	const PHOTO_THUMB_HEIGHT = 120;
	
	const PHOTO_PREVIEW_WIDTH = 640;
	
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__stream_files', 'id', $db );
		$this->_params = new JParameter($this->params);
	}
	
	public function load( $keys = NULL, $reset = true )
	{
		$ret = parent::load($keys);
		$this->_params = new JParameter($this->params);
		return $ret;
	}
	
	public function bind( $src, $ignore = array() )
	{
    	if(empty($src)){
    		return;
		}
		
    	$ret = parent::bind($src, $ignore);
		
		$this->_params = new JParameter($this->params);
		return $ret;
	}
	
	public function store($updateNulls = false){
		
		$now = new JDate();
		if( $this->created == null)
			$this->created =  $now->toMySQL();
		
		$this->_generatePreview();
		$this->params = $this->_params->toString();
		
		return parent::store();
	}
	
	/**
	 * Delete the files as well
	 */	 	
	public function delete($pk = NULL){
		jimport('joomla.filesystem.file');
		JFile::delete(JPATH_ROOT.DS.$this->path);
		
		if($this->getParam('has_preview')){
			$pathinfo = pathinfo($this->path);
			$thumbPath = JPATH_ROOT .DS . $pathinfo['dirname'] .DS. $pathinfo['filename'].'_thumb.jpg';			
			JFile::delete($thumbPath);
			
			$thumbPath = JPATH_ROOT .DS . $pathinfo['dirname'] .DS. $pathinfo['filename'].'_preview.jpg';			
			JFile::delete($thumbPath);
		}
		
		parent::delete();
	}
	
	
	/**
	 * Prep crocodocs preview
	 */	 	
	public function preparePreview()
	{	
		$jxConfig	= new JXConfig();
		$my = JXFactory::getUser();
		
		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER , false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST , 0 );
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    curl_setopt($ch, CURLOPT_POST, true);
		
		// copy file to tmp path
		$tmpPath = $jxConfig->get('tmp_path').DS. $this->filename;
		JFile::copy(JPATH_ROOT.DS.$this->path, $tmpPath);
		
		// if Crocodocs is enabled
		if ($jxConfig->isCrocodocsEnabled())
		{
			curl_setopt($ch, CURLOPT_URL, 'https://crocodoc.com/api/v2/document/upload');
			$post = array(
				"token" => $jxConfig->get("crocodocs"),
				"file"=>"@".$tmpPath,
			);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
			$response = curl_exec($ch);
			// @todo: validate json
			$responseObj = json_decode($response);
			if (!isset($responseObj->uuid))
			{
				return false;
			}
			$uuid = $responseObj->uuid;
			$this->setParam('uuid', $uuid);
			$this->setParam('preview-ready', 0);
		}
		elseif ($jxConfig->isScribdEnabled()) // try using scribd
		{
			curl_setopt($ch, CURLOPT_URL, "http://api.scribd.com/api?api_key=".$jxConfig->get(JXConfig::SCRIBD_API));
			$post = array(
				"method" => "docs.upload",
				"api_key" => $jxConfig->get(JXConfig::SCRIBD_API),
				"api_sig" => $jxConfig->get(JXConfig::SCRIBD_SECRET),
				"my_user_id" => $my->id,
				"paid_content" => 1,
				"file"=>"@".$tmpPath,
			);
			
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post); 
			$response = curl_exec($ch);
			$result = simplexml_load_string($response); 
			if (!isset($result->doc_id) && !isset($result->access_key))
			{
				return false;
			}
			
			$this->setParam('doc_id', (string)$result->doc_id);
			$this->setParam('access_key', (string)$result->access_key);
			$this->setParam('preview-ready', 0);
		}
	    
	    JFile::delete($tmpPath);


	    $this->store();
	    
	    return true;
	}
	
	/**
	 * The user has downloaded the file
	 */	 	
	public function downloaded($userid){
		$this->followers = JXUtility::csvInsert($this->followers, $userid);
	}
	
	/**
	 * Return true if the user is allowed to download it
	 */	 	
	public function allowDownload($userid){		
				
		// If this is a group, and the group is private, only members can download it
		$my = JXFactory::getUser($userid);
		$limitGroup = $my->getParam('groups_member_limited');
		if($limitGroup) {
			return JXUtility::csvExist($limitGroup, $this->group_id);	
		}

		if( $this->group_id ){
		
			$group	= JTable::getInstance( 'Group' , 'StreamTable' );
			$group->load($this->group_id);
			
			
			// People need to be able to read the group
			if( !$my->authorise('stream.group.read', $group) ){
			    return false;
			}
		}
		
		return !empty($userid);
	}
	
	/**
	 * @todo: check for edit permission
	 */	 	
	public function allowEdit($userid){
		// Only those who uploaded the file can delete it
		return ($this->user_id == $userid);
	}
	
	/**
	 * Guess the mimetype from the filename
	 */	 	
	function getMIMETypeFromFilename()
    {
    	$filename = $this->filename;
    	
        preg_match("|\.([a-z0-9]{2,4})$|i", $filename, $fileSuffix);

        switch(strtolower($fileSuffix[1]))
        {
            case "js" :
                return "application/x-javascript";

            case "json" :
                return "application/json";

            case "jpg" :
            case "jpeg" :
            case "jpe" :
                return "image/jpg";

            case "png" :
            case "gif" :
            case "bmp" :
            case "tiff" :
                return "image/".strtolower($fileSuffix[1]);

            case "css" :
                return "text/css";

            case "xml" :
                return "application/xml";

            case "doc" :
            case "docx" :
                return "application/msword";

            case "xls" :
            case "xlt" :
            case "xlm" :
            case "xld" :
            case "xla" :
            case "xlc" :
            case "xlw" :
            case "xll" :
                return "application/vnd.ms-excel";

            case "ppt" :
            case "pps" :
                return "application/vnd.ms-powerpoint";

            case "rtf" :
                return "application/rtf";

            case "pdf" :
                return "application/pdf";

            case "html" :
            case "htm" :
            case "php" :
                return "text/html";

            case "txt" :
                return "text/plain";

            case "mpeg" :
            case "mpg" :
            case "mpe" :
                return "video/mpeg";

            case "mp3" :
                return "audio/mpeg3";

            case "wav" :
                return "audio/wav";

            case "aiff" :
            case "aif" :
                return "audio/aiff";

            case "avi" :
                return "video/msvideo";

            case "wmv" :
                return "video/x-ms-wmv";

            case "mov" :
                return "video/quicktime";

            case "zip" :
                return "application/zip";

            case "tar" :
                return "application/x-tar";

            case "swf" :
                return "application/x-shockwave-flash";

            default :
            if(function_exists("mime_content_type"))
            {
                $fileSuffix = mime_content_type($filename);
            }

            return "unknown/" . trim($fileSuffix[0], ".");
        }
    }
    
    /**
     *
     */	     
    public function getPreviewHTML(){
	}
	
	/**
	 * Generate the file preview. U
	 */	 	
	private function _generatePreview(){
		// Create thumbnail (for jpeg) if there is none
		$supportedImageMine = array('image/png', 'image/jpeg');
		if( in_array($this->mimetype, $supportedImageMine) ){	
			jimport('joomla.image');
			require_once(JPATH_ROOT .DS.'libraries'.DS.'joomla'.DS.'image'.DS.'filters'.DS.'sharpen.php');
			
			$image = new JImage(JPATH_ROOT . DS . $this->path );
			
			if(!$image->isLoaded()){
				return false;
			}
			
			$pathinfo = pathinfo($this->path);
			$width  = $image->getWidth();
			$height = $image->getHeight();
			
			// Generate preview		
			if($width > 640){
				$height = (640/$width* $height);
				$width = 640;
			}
			
			if($height >  640){
				$width = (640/$height * $width);
				$height = 640;
			}
			// Resize for preview
			$image = $image->resize($width, $height);
			$previewPath = JPATH_ROOT .DS . $pathinfo['dirname'] .DS. $pathinfo['filename'].'_preview.jpg';
			
			$image->filter('sharpen');
			$image->toFile($previewPath, IMAGETYPE_JPEG, array('quality' => 90));
			
			// crop them to predefined aspect ratio if necessary
			// and the resize them
			if( ($width/$height) > 1.3 ){
				$image = $image->crop( ($height*1.3), $height, ($width -$height*1.3)/2, 0 );
				$image = $image->resize(StreamTableFile::PHOTO_THUMB_WIDTH, StreamTableFile::PHOTO_THUMB_HEIGHT);
			} elseif( ($height/$width) > 1.3 ){
				$image = $image->crop( $width, ($width*1.3), 0, ($height - $width*1.3) );
				$image = $image->resize(StreamTableFile::PHOTO_THUMB_HEIGHT, StreamTableFile::PHOTO_THUMB_WIDTH);
			} else{
				$image = $image->resize(StreamTableFile::PHOTO_THUMB_WIDTH, StreamTableFile::PHOTO_THUMB_WIDTH);
			}
			
			
			$thumbPath = JPATH_ROOT .DS . $pathinfo['dirname'] .DS. $pathinfo['filename'].'_thumb.jpg';
			
			$image->toFile($thumbPath, IMAGETYPE_JPEG, array('quality' => 100));
			
			if(JFile::exists($thumbPath)){
				$this->setParam('has_preview', true);
				$this->setParam('thumb_path', $pathinfo['dirname'] .DS. $pathinfo['filename'].'_thumb.jpg');
				$this->setParam('preview_path', $pathinfo['dirname'] .DS. $pathinfo['filename'].'_preview.jpg');
				$this->setParam('width', $width);
				$this->setParam('height', $height);
			}
		}
		else
		{
			$this->setParam('has_preview', false);
			$this->setParam('thumb_path', '');
			$this->setParam('preview_path', '');
			$this->setParam('width', '');
			$this->setParam('height', '');
		}
	}
	
	public function getParam($key, $default = null)
	{
		return $this->_params->get($key, $default);
	}

	/**
	 * Method to set a parameter
	 *
	 * @param   string  $key    Parameter key
	 * @param   mixed   $value  Parameter value
	 */
	public function setParam($key, $value)
	{
		return $this->_params->set($key, $value);
	}
}
