<?php
/**
 * @package		Offiria
 * @subpackage	com_register 
 * @copyright 	Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @author      Offiria Team
 */

// No direct access.
defined('_JEXEC') or die;

require_once(JPATH_ROOT.DS.'components'.DS.'com_register'.DS.'factory.php');
jimport('joomla.user.xuser');
jimport('joomla.utilities.integration.activedirectory');
				
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_account' . DS . 'tables' );

//jimport('joomla.utilities.xconfig');
//$jxConfig		= new JXConfig();
//if (!$jxConfig->allowUsersRegister())
//{
//	$mainframe	= JFactory::getApplication();
//	$mainframe->redirect(JURI::base(), JText::_('COM_REGISTER_MSG_NOT_ALLOW_REGISTRATION'), 'error');
//}

class RegisterControllerConnect extends JController
{
	
	public function display($cachable = false, $urlparams = false) 
	{		
		// Only admin can use this function to invite guests
		$mainframe	= JFactory::getApplication();
		$my			= JXFactory::getUser();
		$dummy		= new JXUser();
				
		if ($_POST)
		{
			// Check for request forgeries.
			JRequest::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
			
			$activeDirectory	= new JActiveDirectory();
			if ($activeDirectory->connect())
			{
				$username = JRequest::getVar('username', '', 'method', 'username');
				$password = JRequest::getString('password', '', 'post', JREQUEST_ALLOWRAW);
				
				// select user from database
				$checkUser = $dummy->loadUserByUsername($username);
				
				$addMsg	= false;
				
				// if user exists, login user
				if ( $checkUser === false )
				{
					$displayName = $activeDirectory->getExchangeInfo('displayname');
					if (empty($displayName))
					{
						$displayName = $username;
						$addMsg = true;
					}
					
					$email = $activeDirectory->getExchangeInfo('mail');
					if (empty($email))
					{
						$email = $username.$activeDirectory->getDomain();
						$addMsg = true;
					}
					
					// register user automatically
					$data	= array('name' => $displayName,
									'username' => $username,
									'password' => $password,
									'conf_pass' => $password,
									'email'		=> $email);

					$model	= RegisterFactory::getModel('registration');
					if (!$model->registerUser($data))
					{
						$mainframe->enqueueMessage($model->getError(), 'error');
					}
					$dummy->loadUserByUsername($username);
				}
				
				// Set the user to have the integration parameter; can be used to avoid user from editing password
				$chgPwd['password'] = $password;
				$chgPwd['password2'] = $password;
				$dummy->bind($chgPwd);
				$dummy->setParam('integration', 'activedirectory');
				$dummy->save();
				
				// login user
				// Get the log in options.
				$options = array();
				$options['remember'] = false;

				// Get the log in credentials.
				$credentials = array();
				$credentials['username'] = $username;
				$credentials['password'] = $password;
				$mainframe->login($credentials, $options);
				
				echo '<script type="text/javascript"> ';
				if ($addMsg)
				{
					echo "alert('".JText::_('COM_ACCOUNT_CONNECT_LABEL_CHANGE_DEFAULT_NAME_EMAIL')."'); ";
					echo 'window.opener.location.href="'.JRoute::_('index.php?option=com_profile&view=edit').'"; window.close();';
				}
				else
				{
					echo 'window.opener.location.reload(); window.close();';
				}
				echo '</script>';
				exit;
			}
			else
			{
				$mainframe->redirect(JRoute::_('index.php?option=com_register&view=connect&type=ad'), 'Failed to login Active Directory. Account might not exists. Please contact your server/site administrator!', 'error');
				exit;
			}
		}
		
		JRequest::setVar('view', 'connect');
		parent::display();
	}	
	
	public function login()
	{		
		$app->redirect(JURI::base());
		exit;
		$app = JFactory::getApplication();
		$data['return'] = base64_decode(JRequest::getVar('return', '', 'POST', 'BASE64'));
		$data['username'] = JRequest::getVar('username', '', 'method', 'username');
		$data['password'] = JRequest::getString('password', '', 'POST', JREQUEST_ALLOWRAW);

		
		// Get the log in credentials.
		$credentials = array();
		$credentials['username'] = $data['username'];
		$credentials['password'] = $data['password'];

		// Perform the log in.
		if (true === $app->login($credentials, array())) {
			// Success
			$app->redirect(JRoute::_($data['return'], false));
		} else {
			$app->redirect(JURI::base());
		}
	}
}