<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
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