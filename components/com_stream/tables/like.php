<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');
/*

CREATE TABLE `fwhz7_stream_likes` (
`id` INT( 11 ) NOT NULL AUTO_INCREMENT ,
`stream_id` INT( 11 ) NOT NULL ,
`likes` TEXT NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = MYISAM ;
 
 */
class StreamTableLike extends JTable
{
	// Tables' fields
	var $id			=   null;
	var $stream_id	=   null;
	var $likes		=   null;

	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__stream_likes', 'id', $db );
	}
}
