<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access.
defined('_JEXEC') or die;

require_once(JPATH_ROOT.DS.'components'.DS.'com_account'.DS.'factory.php');
jimport('joomla.utilities.xconfig');


class AccountControllerInvite extends JController
{
	const ALREADY_REGISTERED_FLAG = 4;
	const SENT_FLAG = 2;
	
	public function display($cachable = false, $urlparams = false) 
	{		
		// Only admin can use this function to invite guests
		$jxConfig = new JXConfig();
		$my = JXFactory::getUser();
		$accessHelper = new AccountAccessHelper();
		if (!$my->authorise('stream.setting.edit', $accessHelper))
		{
			$mainframe = JFactory::getApplication();
			$mainframe->redirect(JURI::base(), JText::_('COM_ACCOUNT_ERRMSG_INVITATION_DENIED'), 'error');
		}
		
		$inviteView = AccountFactory::getView('invite');
		parent::display();
	}
	
	public function invite()
	{	
		// Only admin can use this function to invite guests
		$jxConfig = new JXConfig();
		$my = JXFactory::getUser();
		$accessHelper = new AccountAccessHelper();
		if (!$my->authorise('stream.setting.edit', $accessHelper))
		{
			$mainframe = JFactory::getApplication();
			$mainframe->redirect(JURI::base(), JText::_('COM_ACCOUNT_ERRMSG_INVITATION_DENIED'), 'error');
		}
		
		if ($_POST)
		{
			$postvar = JRequest::getVar('params');
			$postvar['invitation'] = $jxConfig->cleanEmailList(explode(',',$postvar['invitation']));

			$mainframe = JFactory::getApplication();
			if (!is_array($postvar['invitation']))
			{
				$mainframe->enqueueMessage($postvar['invitation'], 'error');
				parent::display();
			}
			else
			{
				$currentUserCount = $jxConfig->getRegisteredAndInvitedCount();
				$maxUserAllowed = $jxConfig->getMaxAllowUsers();
				$toHaveUserCount = $currentUserCount + count($postvar['invitation']);
				if ( $toHaveUserCount > $jxConfig->getMaxAllowUsers())
				{
					$mainframe->enqueueMessage(JText::sprintf('COM_ACCOUNT_MSG_ALLOWED_NUMBERS_OF_INVITATION', $currentUserCount, ($jxConfig->getMaxAllowUsers() - $currentUserCount)), 'error');
					parent::display();
				}
				else
				{
					JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_account' . DS . 'tables' );
					$my				= JXFactory::getUser();
					$alreadyUser	= array();
					$invitedEmail	= array();

					foreach ($postvar['invitation'] as $email)
					{
						$processResult = $this->processInvitation($my, $email, $postvar['group_limited']);
						if ($processResult['flag'] == self::SENT_FLAG)
						{
							$invitedEmail[] = $email;
						}
						else
						{
							$alreadyUser[] = $email;
						}
					}

					if (!empty($alreadyUser))
					{
						$mainframe->enqueueMessage(JText::sprintf('COM_ACCOUNT_MSG_INVITATION_EMAILS_ALREADY_REGISTERED', implode(',', $alreadyUser)));
					}

					$inviteModel = JModel::getInstance('UsersInvite', 'AccountModel');
					$total = $inviteModel->getTotal(array('from' => $my->email));
					$completion = ($total >=5 ) ? 100 : 0;
					// Set avatar getting started helper task as completed
					$my->setGettingStartedCompletion(JXUser::GSTARTED_INVITE_FRIENDS, $completion);

					$successMsg = (empty($invitedEmail)) ? JText::_('COM_ACCOUNT_MSG_INVITATION_PROCESS_COMPLETE') : JText::sprintf('COM_ACCOUNT_MSG_INVITATION_SENT', implode(',', $invitedEmail));

					$mainframe->redirect(JRoute::_('index.php?option=com_account&view=invite'), $successMsg);
				}
			}
		}
	}	
	
	public function processInvitation($sender, $receiverEmail, $limitedGroupIDs = null)
	{
		$dummy			= new JXUser();
		$jConfig		= JFactory::getConfig();
		$now			= new JDate();
		$result			= array('email' => $receiverEmail);
		$usersInvite	= JTable::getInstance('usersInvite', 'AccountTable' );
		
		// Check if email is already registered
		$loadEmail = $dummy->loadUserByEmail($receiverEmail);
		if ( $loadEmail === true && intval($dummy->id) > 0)
		{
			while($usersInvite->load(array('invite_email' => $receiverEmail, 'status' => AccountTableUsersInvite::PENDING)))
			{
				$usersInvite->status = AccountTableUsersInvite::REGISTERED;
				$usersInvite->store();
			}
			$result['flag'] = self::ALREADY_REGISTERED_FLAG;			
		}
		else
		{
			$result['flag'] = self::SENT_FLAG;
			$token			= md5(time()+rand(0,10000));
			
			// prepare invitation email
			$subject		= $sender->get('name').' invites you to join '.$jConfig->get('sitename');
			$bodyContent	= $this->generateInvitationContent($sender, $receiverEmail, $token);

			$usersInvite->load(array('from_email' => $sender->email, 'invite_email' => $receiverEmail));
			if (!$usersInvite->id)
			{
				$usersInvite->reset();
				$usersInvite->invitor = $sender->id;
				$usersInvite->from_email = $sender->email;
				$usersInvite->invite_email = $receiverEmail;
				$usersInvite->status = AccountTableUsersInvite::PENDING;
				$usersInvite->token = $token;					
				$usersInvite->last_invite_date = $now->toMySQL();										
				$usersInvite->created = $now->toMySQL();

				if($limitedGroupIDs) {
					$usersInvite->group_limited = implode(',', $limitedGroupIDs);
				}

				$usersInvite->store();

				// only send if db stored
				$this->sendInvitationEmail($sender->email, $sender->get('name'), $receiverEmail, $subject, $bodyContent);
			}
			else
			{
				if ($usersInvite->status == AccountTableUsersInvite::PENDING)
				{
					$usersInvite->last_invite_date = $now->toMySQL();
					$usersInvite->token = $token;					
					$usersInvite->store();

					// only send if db stored
					$this->sendInvitationEmail($sender->email, $sender->get('name'), $receiverEmail, $subject, $bodyContent);
				}
			}
		}
		
		return $result;
	}
	
	public function ajaxResendInvitation()
	{	
		// Only admin can use this function to invite guests
		$jxConfig = new JXConfig();
		$my = JXFactory::getUser();
		$accessHelper = new AccountAccessHelper();
		if (!$my->authorise('stream.setting.edit', $accessHelper))
		{
			echo '{"error":"1","info":""';
			exit;
		}
		
		$invitation = JRequest::getVar('invitation_id', 0);
		
		$returnData = array();
		
		//if ($email !== false)
		if ($invitation)
		{
			$usersInvite	= JTable::getInstance('usersInvite', 'AccountTable' );
			$usersInvite->load(array('id' => $invitation));
			if ($usersInvite->id)
			{				
				$emailToInvite = $usersInvite->invite_email;
				$dummy	= new JXUser();
				$loadUser = $dummy->loadUserByEmail($usersInvite->from_email);
				if (!$loadUser)
				{
					// Delete invitations which invitor email cannot be found from registered users
					$dummy		= JXFactory::getUser();
					$usersInvite->delete();
				}
				$result = $this->processInvitation($dummy, $emailToInvite);				
				$now	= new JDate();
				if( $result['flag'] == self::SENT_FLAG)
				{
					$usersInvite->load(array('from_email' => $dummy->email, 'invite_email' => $emailToInvite));
					$returnData["info"] = JXDate::formatDate($now->format('Y-m-d h:i:s'));
					$returnData["html"] = $usersInvite->getRowHtml();
				}
				elseif( $result['flag'] == self::ALREADY_REGISTERED_FLAG)
				{
					$returnData["msg"] = JText::sprintf('COM_ACCOUNT_MSG_INVITATION_EMAIL_ALREADY_REGISTERED', $result['email']);
				}
				$returnData["error"] = '0';
			}
			else
			{
				$returnData["error"] = '1';
			}
		}
		else
		{
				$returnData["error"] = '1';
		}
		echo json_encode($returnData);
		exit;
	}
	
	public function sendInvitationEmail($mailFrom, $fromName, $mailTo, $subject, $bodyContent, $bccList='')
	{
		$status = true;
		$mailer	= JFactory::getMailer();
		
		ob_start();
		// last parameter set to true to indicate content is in HTML format
		$mailer->sendMail($mailFrom, trim($fromName), $mailTo, $subject, $bodyContent, true, '', $bccList);
		$errorMsg = ob_get_contents();					
		ob_end_clean();
		
		if (!empty($errorMsg))
		{			
			JLog::add($errorMsg.' Fail sending to '.$mailTo, JLog::ERROR, 'com_account');
			$status = false;
		}
		
		return $status;
	}
	
	public function generateInvitationContent($sender, $receiverEmail, $token)
	{		
		$currentTemplate = JText::_('CUSTOM_TEMPLATE');
		
		$jConfig = new JXConfig();
		// Sadly, JRoute will always replace + with %20 , hence we need to hardcode this
		$receiverEmailPlaceholder = str_replace('+', 'PLUS_SIGN_HOLDER', $receiverEmail);
		$invitationUrl = JRoute::_('index.php?option=com_register&view=register&code='.md5($receiverEmail).'&token='.$token.'&email='.$receiverEmailPlaceholder, true, 2);		
		// Restore + sign
		$invitationUrl = str_replace('PLUS_SIGN_HOLDER', '%2B', $invitationUrl);

		ob_start();
		require_once(JPATH_ROOT .DS.'components'.DS.'com_account'.DS.'templates'.DS.'emailInvite.php');
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}
	
	public function ajaxDeleteInvitation()
	{
		$invitationId	= JRequest::getInt('invitation', 0);
		$usersInvite	= JTable::getInstance('usersInvite', 'AccountTable' );
		
		if ($usersInvite->load($invitationId))
		{
			$usersInvite->delete();
			echo '{"error":"0"}';
			exit;
		}
				
		echo '{"error":"1"}';
		exit;
	}
	
	/*
	 * Right bar Module for Member to invite guests
	 */	
	public function ajaxMemberInvite()
	{
		$configHelper = new JXConfig();
		
		if ($configHelper->allowInvite())
		{
			$postvar		= JRequest::getVar('invitation');
			$inviteType		= JRequest::getVar('inviteType', '');
			$arrEmails		= explode(',',$postvar);
			$emailtoInvite	= $configHelper->cleanEmailList( $arrEmails );
			
			if (strtolower($inviteType) == 'welcome')
			{
				if ( (count($arrEmails) < 3) || (empty($arrEmails[0]) || empty($arrEmails[1]) || empty($arrEmails[2])))
				{
					echo '{"error":"1", "msg":"'.JText::sprintf('COM_ACCOUNT_ERRMSG_PLEASE_INVITE_EMAILS', '3').'"}';
					exit;

				}
				elseif ( $arrEmails[0] == $arrEmails[1] || $arrEmails[1] == $arrEmails[2] || $arrEmails[0] == $arrEmails[2])
				{
					echo '{"error":"1", "msg":"'.JText::sprintf('COM_ACCOUNT_ERRMSG_PLEASE_INVITE_EMAILS', '3').'"}';
					exit;
				}
			}
			
			if (!is_array($emailtoInvite))
			{
				echo '{"error":"1", "msg":"'.$emailtoInvite.'"}';
			}
			else
			{
				
				$currentUserCount = $configHelper->getRegisteredAndInvitedCount();
				$maxUserAllowed = $configHelper->getMaxAllowUsers();
				$toHaveUserCount = $currentUserCount + count($emailtoInvite);
				if ( $toHaveUserCount > $configHelper->getMaxAllowUsers())
				{
					echo '{"error":"1", "msg":"'.JText::sprintf('COM_ACCOUNT_MSG_ALLOWED_NUMBERS_OF_INVITATION', $currentUserCount, ($configHelper->getMaxAllowUsers() - $currentUserCount)).'"}';
				}
				else
				{
					JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_account' . DS . 'tables' );
					$my				= JXFactory::getUser();
					$alreadyUser	= array();
					$invitedEmail	= array();

					foreach ($emailtoInvite as $email)
					{
						$processResult = $this->processInvitation($my, $email);
						if ($processResult['flag'] == self::SENT_FLAG)
						{
							$invitedEmail[] = $email;
						}
						else
						{
							$alreadyUser[] = $email;
						}
					}
					$msg = '';

					if (!empty($alreadyUser))
					{
						$msg .= (count($alreadyUser) == 1) ? JText::sprintf('COM_ACCOUNT_MSG_INVITATION_EMAIL_ALREADY_REGISTERED', implode(',', $alreadyUser)).'\n' : JText::sprintf('COM_ACCOUNT_MSG_INVITATION_EMAILS_ALREADY_REGISTERED', implode(',', $alreadyUser)).'\n';
					}
					$msg .= (empty($invitedEmail)) ? '' : JText::sprintf('COM_ACCOUNT_MSG_INVITATION_SENT', implode(',', $invitedEmail));

					echo '{"error":"0", "msg":"'.$msg.'"}';
				}
			}	
		}
		else
		{
			echo '{"error":"1", "msg":"'.JText::_('COM_ACCOUNT_ERRMSG_INVITATION_DENIED').'"}';
		}
		exit;
	}
	
	public function resendPending($all = false)
	{
		$accessHelper = new AccountAccessHelper();
		$mainframe = JFactory::getApplication();
		$my = JXFactory::getUser();
		if (!$my->authorise('stream.setting.edit', $accessHelper))
		{
			$mainframe = JFactory::getApplication();
			$mainframe->redirect(JURI::base(), JText::_('COM_ACCOUNT_ERRMSG_INVITATION_DENIED'), 'error');
		}
		
		$inviteModel = AccountFactory::getModel( 'usersinvite' );
		$userTbl= JTable::getInstance('AccountUser', 'AccountTable');
		$inviteTbl= JTable::getInstance('usersinvite', 'AccountTable');
		if ($all)
		{
			$filter = array('status' => AccountTableUsersInvite::PENDING);
		}
		else
		{
			$filter = array('status' => AccountTableUsersInvite::PENDING,
							'days_ahead' => '3');
		}
		$filter['order_by'] = 'from_email';
		$rows	= $inviteModel->getList( $filter, 1000, 0 );
		
		foreach ($rows as $row)
		{
			$emailToInvite = $row->invite_email;
			if ($userTbl->email != $row->from_email)
			{
				$loadUser = $userTbl->load(array('email' => $row->from_email));
				if (!$loadUser)
				{					
					$row->delete();
					$user = JXFactory::getUser();
				}
				else
				{
					$user = JXFactory::getUser($userTbl->id);
				}
			}
			//echo $row->invite_email.'    :    '.$row->from_email.'    :    '.$user->email.'<br/>';
			$processResult = $this->processInvitation($user, $emailToInvite);
		}
		
		$mainframe->redirect(JRoute::_('index.php?option=com_account&view=invite', false), JText::_('COM_ACCOUNT_MSG_INVITATION_PROCESS_COMPLETE'));
	}
	
	public function getPendingEmailCount($all = false)
	{
		$inviteModel = AccountFactory::getModel( 'usersinvite' );
		$userTbl= JTable::getInstance('AccountUser', 'AccountTable');
		$inviteTbl= JTable::getInstance('usersinvite', 'AccountTable');
		if ($all)
		{
			$filter = array('status' => AccountTableUsersInvite::PENDING);
		}
		else
		{
			$filter = array('status' => AccountTableUsersInvite::PENDING,
							'days_ahead' => '3');
		}
		
		$num	= $inviteModel->getTotal( $filter );
		
		return $num;
	}
}