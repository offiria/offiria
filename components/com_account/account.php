<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include dependancies
jimport('joomla.application.component.controller');
require_once(JPATH_ROOT.DS.'components'.DS.'com_account'.DS.'factory.php');

// Include tables
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_account' . DS . 'tables' );

// Load language file
$lang = JFactory::getLanguage();
$lang->load('lib_xjoomla');

// Check for the logined user for Account Setting access permission
$my = JXFactory::getUser();
$accessHelper = new AccountAccessHelper();

if (!$my->authorise('stream.setting.edit', $accessHelper) && JRequest::getVar('view', 'account') != 'invite')
{
	$mainframe = JFactory::getApplication();
	$mainframe->redirect(JURI::base(), JText::_('COM_ACCOUNT_ERRMSG_ACCESS_DENIED'), 'error');
}

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
$classname	= 'AccountController'.ucfirst($controller);
$controller = new $classname();

// Execute the task.
$controller->execute(JRequest::getVar('task', 'display'));
$controller->redirect();

