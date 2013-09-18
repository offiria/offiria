<?php
/**
 * @category	Tables
 * @package		Offiria
 * @subpackage	Activities 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

/*
CREATE TABLE IF NOT EXISTS `prefix_stream` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NOT NULL,
  `type` varchar(64) NOT NULL,
  `message` text NOT NULL,
  `raw` text NOT NULL,
  `likes` text NOT NULL,
  `topics` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `source_id` tinyint(4) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `followers` text NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `message` (`message`,`raw`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

 */
 
class StreamTableLink extends JTable
{
	var $id 		= null;
	var $link 		= null;
	var $title 		= null;
	var $mime 		= null;
	var $type 		= null;
	var $params 	= null;
	var $user_ids 	= null;

	//var $params 	= null;
  	
  	private $_params	= null;
  	
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__stream_links', 'id', $db );
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
		else 
		{
			// Return false for videos link
			StreamFactory::load('libraries.video');	
			$videoLib = new StreamVideo();
			$videos = $videoLib->getURL($keys['link']);
			if(in_array($keys['link'], $videos)){
				return false;
			}
			
			// Return false for slidehshare links
			StreamFactory::load('libraries.slideshare');
			$ssLib = new StreamSlideshare();
			$slideShares = $ssLib->getURL($keys['link']);
			
			if(in_array($keys['link'], $slideShares)){
				return false;
			}
		}
		
		$result = parent::load($keys, $reset);
		$this->_params = new JParameter($this->params);

		if(!$result && isset($keys['link'])){
			$this->link = $keys['link'];
		}

		if (empty($this->params)) {
			$this->params = StreamLinks::grab($this->link);
			if ($this->params) {
				$date = new JDate();
				$this->timestamp = $date->format('Y-m-d h:i:s');
				$this->store(true);
			}
		}

		return $result;
	}
	
	public function addUser($userid){
		$this->user_ids = JXUtility::csvInsert($this->user_ids, $userid);
	}
	
	public function removeUser($userid){
		$this->user_ids = JXUtility::csvRemove($this->user_ids, $userid);
	}

	/**
	 * Return the param requested
	 * @param String $field field to retrieve 
	 * @param Array $condition condition for the array to load
	 * @return String as requested on success and false on empty
	 */
	public function getParam($field, $condition=null) {
		return $this->_getParam($field, $condition);
	}

	/**
	 * Return the param requested
	 * @param String $field field to retrieve 
	 * @param Array $condition condition for the array to load
	 * @return String as requested on success and false on empty
	 */
	private function _getParam($field, $condition=null) {
		if (is_array($condition)) {
			$this->load($condition);
		}
		else {
			$this->load();
		}
		if ($this->{$field} == null) {
			return false;
		}
		return $this->{$field};
	}
}
