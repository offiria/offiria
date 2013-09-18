<?php
/**
 * @version     1.0.0
 * @package     com_Stream
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.html.parameter' );
jimport('joomla.application.component.controller');
jimport('joomla.xfactory');

include_once(JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'html'.DS.'string.php');

require_once(JPATH_ROOT .DS.'libraries'.DS.'joomla'.DS.'xfactory.php');

// Include tables
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_stream' . DS . 'tables' );
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_analytics' . DS . 'tables' );

// Includes
require_once(JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'factory.php');
require_once(JPATH_ROOT .DS.'components'.DS.'com_analytics'.DS.'factory.php');



// During ajax calls, the following constant might not be called
if(!defined('JPATH_COMPONENT'))
{
	define('JPATH_COMPONENT', dirname(__FILE__));
}

$view	= JRequest::getCmd('view', 'company');
$task 	= JRequest::getCmd('task', '');
$tmpl	= JRequest::getCmd('tmpl', '' ,'GET' );

// If the task is 'azrul_ajax', it would be an ajax call and core file
// should not be processing it.
if($task != 'azrul_ajax')
{
	// Require specific controller if requested
	if($controller = JRequest::getWord('view', 'dashboard')) {
		$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
		if (file_exists($path)) {
			require_once $path;
		} else {
			$controller = '';
		}
	}
	
	// Create the controller
	$classname	= 'AnalyticsController'.ucfirst($controller);
	$controller = new $classname( );

	// Perform the Request task
	$controller->execute(JRequest::getCmd('task'));
	
	// Redirect if set by the controller
	$controller->redirect();
}

/**
 * Entry poitn for all ajax call
 */
function analyticsAjaxEntry($func, $args = null)
{
	// For AJAX calls, we need to load the language file manually.
	$lang = JFactory::getLanguage();
	$lang->load( 'com_analytics' );
	
	$response = new JAXResponse();
	$output = '';
	
	$triggerArgs = array();
	$triggerArgs[] = $func;
 	$triggerArgs[] = $args;
	$triggerArgs[] = $response;
	
	//print_r($args);
	$calls		= explode( ',' , $func );

	// Built-in ajax calls go here         	
	$func		= $_REQUEST['func'];
	$callArray	= explode(',', $func);

	$controller = JString::strtolower($callArray[0]);

	// Require specific controller if requested
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
	
	// Create the controller
	$classname	= 'AnalyticsController'.ucfirst($controller);
	$controller = new $classname( );
	
	// Perform the Request task
	$output = call_user_func_array(array(&$controller, $callArray[1]), $args);

	return $output;
}
