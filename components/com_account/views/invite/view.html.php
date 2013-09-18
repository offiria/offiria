<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.utilities.xconfig');

require_once( JPATH_ROOT.DS.'components'.DS.'com_account'.DS.'tables'.DS. 'usersinvite.php' );
require_once( JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'factory.php');

/**
 * HTML View class for the Account component
 */
class AccountViewInvite extends AccountView
{
	function display($tpl = null)
	{
		jimport('joomla.html.pagination');
		
		$configHelper	= new JXConfig();	
		$usersInvite	= AccountFactory::getModel('usersInvite');
		$my				= JXFactory::getUser();
		
		$total			= $usersInvite->getTotal(array('from_email' => $my->email));
		$result			= $usersInvite->getList(array('from_email' => $my->email, 'order_by' => 'status, last_invite_date'), $configHelper->get('list_limit'), JRequest::getVar('limitstart', 0));
		$inviteEmail	= '';
		if ($_POST)
		{
			$postVar	= JRequest::getVar('params');
			$inviteEmail	= $postVar['invitation'];
		}

		// Extranet/Limited groups
		$groupModel 	= StreamFactory::getModel('groups');
		$myJoinedGroups = $groupModel->getGroups(array('id' => $my->getParam('groups_member')), 100);
		
		// Pagination
		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $configHelper->get('list_limit'));
		$pendingStat= AccountTableUsersInvite::PENDING;
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_("COM_ACCOUNT_LABEL_INVITE_USERS"));
		$this->assignRef('inviteEmail', $inviteEmail);
		$this->assignRef('pagination', $pagination);
		$this->assignRef('pendingStat', $pendingStat);
		$this->assign('allowInvite', $configHelper->allowUsersRegister());
		$this->assignRef('results', $result);
		$this->assignRef('myJoinedGroups', $myJoinedGroups);

		parent::display($tpl);
	}
	
	public function modInvitationList()
	{
		$usersInvite = AccountFactory::getModel('usersInvite');
		$result		 = $usersInvite->getList(array('status' => AccountTableUsersInvite::PENDING), '10');
		$html		 = '<ul>';
		if (!empty($result))
		{
			foreach ($result as $record)
			{
				$date = JXDate::formatDate($record->last_invite_date);
				$html .= '<li>'.$record->invite_email.' invited on '.$date.'</li>';
			}
		}
		$html		.= '</ul>';
		return $html;
	}
	
	public function modMemberInvite()
	{
		$html = '';
		$configHelper = new JXConfig();
		
		if ($configHelper->allowInvite() && $configHelper->allowUsersRegister())
		{
			ob_start();
			require_once(JPATH_ROOT .DS.'components'.DS.'com_account'.DS.'templates'.DS.'modInviteGuest.php');
			$html = ob_get_contents();
			ob_end_clean();
		}
		
		return $html;
	}
}