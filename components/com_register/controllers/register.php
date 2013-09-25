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
				
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_account' . DS . 'tables' );

jimport('joomla.utilities.xconfig');
$jxConfig		= new JXConfig();
if (!$jxConfig->allowUsersRegister())
{
	$mainframe	= JFactory::getApplication();
	$mainframe->redirect(JURI::base(), JText::_('COM_REGISTER_USER_LIMIT_REACHED'), 'error');
}

class RegisterControllerRegister extends JController
{
	public function display($cachable = false, $urlparams = false) 
	{		
		// Only admin can use this function to invite guests
		$jxConfig	= new JXConfig();
		$mainframe	= JFactory::getApplication();
		$my			= JXFactory::getUser();
		
		$email		= JRequest::getVar('email', '');
		$token		= JRequest::getString('token', '');
		$code		= JRequest::getString('code', '');
		
		$userInviteTable = JTable::getInstance('usersInvite', 'AccountTable' );
		$userInviteTable->load(array('invite_email' => $email, 'status' => AccountTableUsersInvite::PENDING, 'token' => $token));
		
		if ($userInviteTable->id) // md5 code from url matches with md5(email) from db
		{			
			
			if ($_POST)
			{
				$name			= JRequest::getVar('name', '');
				$username		= JRequest::getVar('username', '');
				$password1		= JRequest::getString('password', '');
				$password2		= JRequest::getString('confirm_password', '');
				$data			= array('name' => $name,
										'username' => $username,
										'password' => $password1,
										'conf_pass' => $password2,
										'email'		=> $email,
										'group_limited' => $userInviteTable->group_limited);

				$model = RegisterFactory::getModel('registration');

				if ($userid = $model->registerUser($data))
				{
					$userInviteTable->status = AccountTableUsersInvite::REGISTERED;
					$userInviteTable->store();

					// If user is invited to a certain group only, add the user into the group
					if( $userInviteTable->group_limited ){
						// Include tables
						JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_stream' . DS . 'tables' );
						$groups = explode(',', $userInviteTable->group_limited);
						$user = JXFactory::getUser($userid);

						foreach ($groups as $group_id) {
							
							$group = $group	= JTable::getInstance( 'Group' , 'StreamTable' );
							$group->load($group_id);

							// If you join, you'd also follow it
							$group->members = JXUtility::csvInsert($group->members, $user->id);
							$group->followers = JXUtility::csvInsert($group->followers, $user->id);
							$group->store();

							// Store user cache
							$groupList = $user->getParam('groups_member');
							$groupList = JXUtility::csvInsert($groupList, $group->id);
							$user->setParam('groups_member', $groupList);
							$user->save();
						}
					}
					
					$mainframe->redirect(JURI::base(), JText::_('COM_REGISTER_MSG_REGISTRATION_SUCCESSFUL'));
				}
				else
				{
					$mainframe->enqueueMessage($model->getError(), 'error');
				}
			}
			
			parent::display();
		}
		else // redirect to index page with error msg
		{
			$mainframe->redirect(JURI::base(), JText::_('COM_REGISTER_ERRMSG_NO_VALID_INVITATION'), 'error');
		}
	}
}	