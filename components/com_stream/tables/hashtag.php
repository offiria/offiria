<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

/*
CREATE TABLE `#__stream_hashtags` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`hashtag` VARCHAR( 255 ) NOT NULL ,
`frequency` INT NOT NULL ,
`updated` DATETIME NOT NULL ,
PRIMARY KEY ( `id` ) ,
UNIQUE (
`hashtag`
)
) ENGINE = MYISAM ;

*/

class StreamTableHashtag extends JTable
{
	// Tables' fields
	var $id			= null;
	var $hashtag	= null;
	var $frequency 	= 0;
	var $updated 	= null;
	
	
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__stream_hashtags', 'id', $db );
	}
	
	public function load( $keys = null, $reset = true ){
		
		if(isset($keys['hashtag']) ){
			$keys['hashtag'] = strtolower($keys['hashtag']);
		}
		
		if (!is_array($keys))
		{
			// Load by primary key.
			$keys = array($this->_tbl_key => $keys);
		}
		
		$result = parent::load($keys, $reset);
        if(!$result){
			$this->bind($keys);
		}
	}
	
	public function store()
	{
		$now = new JDate();
		// Always update the stream last updated time
		$this->updated =  $now->toMySQL();		
		return parent::store();
	}
	
	public function hit(){
		$this->frequency++;
	}
	
}