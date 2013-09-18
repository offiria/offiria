<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

/*
CREATE TABLE IF NOT EXISTS `e20_stream_videos` (
`id` INT NOT NULL AUTO_INCREMENT,
`message_ids` text NOT NULL,
`source` VARCHAR( 1024 ) NOT NULL,
`video_id` VARCHAR(255) NOT NULL,
`type` VARCHAR( 64 ) NOT NULL,
`thumb` VARCHAR( 512 ) NOT NULL,
`title` VARCHAR( 512 ) NOT NULL,
`description` TEXT NOT NULL,
`duration` VARCHAR( 64 ) NOT NULL,
`params` TEXT NOT NULL,
PRIMARY KEY ( `id` ); 
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
*/

class StreamTableVideo extends JTable
{
	// Tables' fields
	var $id				= null;
	var $message_ids	= null; // csv of stream ids
	var $source			= null;
	var $video_id		= null;
	var $type 			= null;
	var $thumb 			= null;
	var $title			= null;
	var $description	= null;
	var $duration		= null;
	var $params			= null;
	
	private $_params	= null;
	
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__stream_videos', 'id', $db );
		$this->_params = new JParameter($this->params);
	}
	
	/**
	 *
	 */	 	
	public function load($keys = null, $reset = true)
	{
		if (!is_array($keys))
		{
			// Load by primary key.
			$keys = array($this->_tbl_key => $keys);
		}
		
		$result = parent::load($keys, $reset);
		$this->_params = new JParameter($this->params);
		
		if(!$result && isset($keys['source'])){
			$this->source = $keys['source'];
			$this->_initVideo();
		}
		
		return $result;
	}

	
	/**
	 *
	 */	 	
	public function store($updateNulls = false){
		$this->params = $this->_params->toString();
		
		return parent::store();
	}
	
	/**
	 * Initialize the video
	 */	 	
	private function _initVideo(){
		$videoType = StreamVideo::getType($this->source);
		$video = StreamVideo::getVideo($videoType);
		if( $video->init($this->source) )
		{
			$this->title 		= $video->getTitle();
			$this->description 	= $video->getDescription();
			$this->duration 	= $video->getDuration();
			$this->type			= $video->getType();
			$this->thumb 		= $video->getThumbnail();
			$this->video_id		= $video->getId();
		}
		
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
		}
		
		parent::delete();
	}
	
	public function attachMessage($message_id){
		$this->message_ids = JXUtility::csvInsert($this->message_ids, $message_id);
	}

	/**
	 * Return true if the user is allowed to download it
	 */	 	
	public function allowView($userid){
		
		return !empty($userid);
	}
	
	/**
	 * Html embed code
	 */	 	
	public function getEmbedHTML($width = NULL, $height = NULL) {
		$width = (!$width) ? 420 : $width;
		$height = (!$height) ? 260 : $height;
		switch($this->type){
			case 'youtube':
				$html = '<iframe width="'.$width.'" height="'.$height.'" src="http://www.youtube.com/embed/'.$this->video_id.'?rel=0&autoplay=1" frameborder="0" allowfullscreen></iframe>';
				break;
			case 'vimeo':
				$html = '<iframe src="http://player.vimeo.com/video/'.$this->video_id.'" width="'.$width.'" height="'.$height.'" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe';
				break;
		}

		return $html;
	}
	
	/**
	 * Get File params
	 */	 	
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
