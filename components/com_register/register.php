<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');
require_once(JPATH_ROOT.DS.'components'.DS.'com_register'.DS.'factory.php');

// Include tables
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_register' . DS . 'tables' );

// Load language file
$lang = JFactory::getLanguage();
$lang->load('lib_xjoomla');


// Require specific controller if requested
if($controller = JRequest::getVar('view', 'signup')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

// Create the controller
$classname	= 'RegisterController'.ucfirst($controller);

$controller = new $classname();

// Execute the task.
$controller->execute(JRequest::getVar('task', 'display'));

$controller->redirect();
