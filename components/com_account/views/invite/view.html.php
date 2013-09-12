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
		$this->addPathway( JText::_('JXLIB_SETTINGS'), JRoute::_('index.php?option=com_account&view=account'));
		$this->addPathway(JText::_('COM_ACCOUNT_LABEL_INVITE_USERS'));
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
	
	public function modMembersBirthday() {
		$html = '';
		$members = ''; 
		$i = 0;
		$birthdaymember = array();
		$today = getdate();

		$db = JFactory::getDbo();
		$query = 'SELECT u.username, u.name, ud.value FROM ' . $db->nameQuote('#__users') . ' u' .
			' LEFT JOIN ' . $db->nameQuote('#__user_details') . ' ud ON ud.user_id = u.id' .
			' WHERE u.block = 0 AND ud.field = "personal_birthday"';	
		$db->setQuery($query);
		$result = $db->loadObjectList();
		
		foreach ($result as $key => $value) {
			// [{"personal_birthday_date":"26.11.1978","personal_birthday_age_public":"Public"}]
			$data = json_decode($value->value, true);
			
			foreach($data as $elementKey=>$elementValue) {
				// To-do: after the proper format has been applied in profile edit field;
				$mday = substr($elementValue["personal_birthday_date"],0,2);
				$month = substr($elementValue["personal_birthday_date"],3,2);
				if ($elementValue["personal_birthday_age_public"] == 'Public' && $mday == $today['mday'] && $month == $today['mon']) {
					$birthdaymember[$value->username] = $value->name;
				}
			}
		}

		$numOfMembers = count($birthdaymember);
		if ($numOfMembers > 0) {
			foreach ($birthdaymember as $key => $value) {
				$i++;
				$members .= '<a href="' . JRoute::_('index.php?option=com_messaging&view=inbox&to=' . $key) . '"><b>' . $value . '</b></a>';
				if ($i == $numOfMembers) {	// last member, don't add separator
				} elseif ($i == $numOfMembers - 1) { // last but one member, add 'and' separator	
					$members .= ' ' . JText::_('COM_ACCOUNT_LABEL_MEMBERS_BIRTHDAY_AND') . ' ';		
				} else {
					$members .= ', ';		
				}
			}

			ob_start();
			require_once(JPATH_ROOT .DS.'components'.DS.'com_account'.DS.'templates'.DS.'modMembersBirthday.php');
			$html = ob_get_contents();
			ob_end_clean();
		}
		return $html;;
	}
}