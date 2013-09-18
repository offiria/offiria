<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.utilities.xconfig');
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_account' . DS . 'tables' );

/**
 * HTML View class for the Account component
 */
class RegisterViewRegister extends RegisterView
{
	function display($tpl = null)
	{
		$email = JRequest::getVar('email', '');
		$token = JRequest::getString('token', '');
		$code = JRequest::getString('code', '');
		
		$userInviteTable = JTable::getInstance('usersInvite', 'AccountTable' );
		$userInviteTable->load(array('invite_email' => $email, 'status' => AccountTableUsersInvite::PENDING, 'token' => $token));
		
		$username			= JRequest::getVar('username', '');
		$name				= JRequest::getVar('name', '');
		$password			= JRequest::getVar('password', '');
		$confirmPassword	= JRequest::getVar('confirm_password', '');
		
		$this->assignRef('name', $name);
		$this->assignRef('username', $username);
		$this->assignRef('password', $password);
		$this->assignRef('confirm_password', $confirmPassword);
		$this->assignRef('email', $email);
		$this->assignRef('token', $token);
		$this->assignRef('code', $code);
		
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_("COM_REGISTER_LABEL_USER_REGISTRATION"));
		parent::display($tpl);
	}
}