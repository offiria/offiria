<?php
/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright 	Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */

defined('JPATH_PLATFORM') or die;

class JXModule {
	public static $buffer = array();
	
	public static function &getBuffer($position)
	{
		static $instance = null;
		if($instance == null){
			$instance = array();
		}
		
		if(empty($instance[$position]))
		{
			$instance[$position] = array();
		}
		return $instance[$position];
	}
	
	public static function addBuffer($position, $html)
	{
		$buff =& JXModule::getBuffer($position);
		$buff[] = $html;
		return true;
	}
}