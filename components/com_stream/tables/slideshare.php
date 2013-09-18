<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

/*
CREATE TABLE `dev_e20`.`fwhz7_stream_videos` (
`id` INT NOT NULL AUTO_INCREMENT ,
`source` VARCHAR( 1024 ) NOT NULL ,
`type` VARCHAR( 64 ) NOT NULL ,
`thumb` VARCHAR( 512 ) NOT NULL ,
`title` VARCHAR( 512 ) NOT NULL ,
`description` TEXT NOT NULL ,
`duration` VARCHAR( 64 ) NOT NULL ,
`params` TEXT NOT NULL ,
PRIMARY KEY ( `id` ) 
*/

class StreamTableSlideshare extends JTable
{
	// Tables' fields
	var $id				= null;
	var $message_ids	= null; // csv of stream ids
	var $source			= null;
	var $slideshow_id	= null;
	var $params			= null;
	var $response		= null;
	
	private $_params	= null;
	
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__stream_slideshare', 'id', $db );
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
			$this->_init();
		}
		
		return $result;
	}

	
	/**
	 *
	 */	 	
	public function store(){
		$this->params = $this->_params->toString();
		
		return parent::store();
	}
	
	/**
	 * Initialize the video
	 */	 	
	private function _init(){
	
		StreamFactory::load('libraries.slideshare');

		jimport('joomla.http.http');
		
		$feedURL = 'http://www.slideshare.net/api/oembed/2?url='. $this->source . '&format=json';
		
		$options = new JRegistry();
		$transport = new JHttpTransportCurl($options);
		$http = new JHttp($options, $transport);
		
		$response =  $http->get( $feedURL );
		$this->response = $response->body;
		
		return true;
		
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
	public function getEmbedHTML(){
		$response = json_decode($this->response);
		$html = $response->html;
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
