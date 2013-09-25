<?php
/**
 * @package		Offiria
 * @subpackage	com_register 
 * @copyright 	Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @author      Offiria Team
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.utilities.xconfig');
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_account' . DS . 'tables' );

/**
 * HTML View class for the Account component
 */
class RegisterViewConnect extends RegisterView
{
	function display($tpl = null)
	{		
		$username			= JRequest::getVar('username', '');
		$password			= JRequest::getVar('password', '');
		
		$this->assignRef('username', $username);
		$this->assignRef('password', $password);
		
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_("COM_REGISTER_LABEL_USER_REGISTRATION"));
		parent::display($tpl);
	}
}