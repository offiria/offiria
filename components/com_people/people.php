<?php
/**
 * @version     1.0.0
 * @package     com_People
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');
include_once('factory.php');

// During ajax calls, the following constant might not be called
if(!defined('JPATH_COMPONENT'))
{
	define('JPATH_COMPONENT', dirname(__FILE__));
}


// Load language file
$lang = JFactory::getLanguage();
$lang->load('lib_xjoomla');


$view	= JRequest::getCmd('view', 'members');
$task 	= JRequest::getCmd('task', 'display');
$tmpl	= JRequest::getCmd('tmpl', '' ,'GET' );

// If the task is 'azrul_ajax', it would be an ajax call and core file
// should not be processing it.
if($task != 'azrul_ajax')
{
	// Require specific controller if requested
	if($controller = $view) {
		$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
		if (file_exists($path)) {
			require_once $path;
		} else {
			$controller = '';
		}
	}
	
	// Create the controller
	$classname	= 'PeopleController'.ucfirst($controller);
	$controller = new $classname( );
	
	// Perform the Request task
	$controller->execute(JRequest::getCmd('task'));
	
	// Redirect if set by the controller
	$controller->redirect();
}

