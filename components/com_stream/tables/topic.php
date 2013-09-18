<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

/*

CREATE TABLE IF NOT EXISTS `dev_e20`.`fwhz7_stream_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topic` varchar(255) NOT NULL,
  `stream_id` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = MYISAM ;
 */
class StreamTableTopic extends JTable
{
	// Tables' fields
	var $id		=   null;
	var $topic	=   null;	// word
	var $streamids	=   null;	// string, csv of stream ids

	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__stream_topic', 'id', $db );
	}
	
	/**
	 * For topic table, we load it based on the 'topic' word
	 * instead of normal 'id'
	 */
	public function load( $topic )
	{
		//@todo: make sure $topic is a string
		// Search via keyword
		$db		= JFactory::getDBO();

		$query	= 'SELECT * FROM '
			. $db->nameQuote( '#__stream_topic' ) . ' '
			. ' WHERE ' . $db->nameQuote( 'topic' ) . ' LIKE ' . $db->Quote( $topic );
		$db->setQuery( $query );
		$result = $db->loadObject();
		if(!empty($result))
		{
			$this->bind($result);

		} 
		else 
		{
			$this->topic  = $topic;
			$this->store();
		}
	}
	
	/**
	 *
	 * @param type $streamid 
	 */
	public function addStreamId( $streamid )
	{
		
	}
	
	public function removeStreamId( $streamid )
	{
		
	}
}
