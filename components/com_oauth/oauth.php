<?php
/**
 * @version     1.0.0
 * @package     com_oauth
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');
require_once(JPATH_ROOT.DS.'components'.DS.'com_oauth'.DS.'factory.php');
require_once(JPATH_ROOT.DS.'components'.DS.'com_oauth'.DS.'views'.DS.'view.php');
require_once(JPATH_ROOT.DS.'components'.DS.'com_oauth'.DS.'controller.php');

// Include tables
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_oauth' . DS . 'tables' );

// Load language file
$lang = JFactory::getLanguage();
$lang->load('lib_xjoomla');

// Require specific controller if requested
if($controller = JRequest::getVar('view', 'display')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

// Create the controller
$classname = 'OauthController'.ucfirst($controller);
$controller = new $classname();
$model = OAuthFactory::getModel('application');

// Execute the task.
$controller->execute(JRequest::getVar('task', 'display'));
$controller->redirect();

