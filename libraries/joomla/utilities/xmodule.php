<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
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