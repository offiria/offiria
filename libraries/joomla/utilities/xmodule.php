<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Application
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;
jimport('joomla.utilities.xconfig');

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
	
	public static function addBuffer($position, $html, $module='')
	{
		// convert the module file name to permission ID
		$configHelper	= new JXConfig();
		if ($configHelper->get('module_' . str_replace(".", "_", $module)) === '1') {
			$buff =& JXModule::getBuffer($position);
			$buff[] = $html;
		}
		return true;
	}
}