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

jimport('joomla.application.component.model');

JTable::addIncludePath(JPATH_ROOT . '/administrator/components/com_account/tables');

/**
 * Model
 */
class AccountModelUsersInvite extends JModel
{	
	public function getList($filter = null, $limit = 20, $limitstart=0)
	{
		$db			= JFactory::getDbo();
		$condition	= ' WHERE invite_email <> "" ';
		$orderBy	= '';
		
		if (isset($filter['status']) && intval($filter['status']) > 0)
		{
			$condition .= ' AND ' . $db->quoteName('status') .' = '.$db->quote($filter['status']);
		}
		
		if (isset($filter['days_ahead']) && intval($filter['days_ahead']) > 0)
		{
			$condition .= ' AND DATE_SUB(NOW(), INTERVAL '.$filter['days_ahead'].' DAY) >= ' . $db->quoteName('last_invite_date');
		}
		
		if (isset($filter['order_by']) && !empty($filter['order_by']))
		{
			$orderBy = ' ORDER BY '.$filter['order_by'];
		}
		
		$query = 'SELECT * FROM #__users_invite ' . $condition . $orderBy . ' LIMIT '. $limitstart .', '.$limit;
		
		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		
		$rows = array();
		if( !empty($result)) {
			foreach( $result as $row )
			{
				$obj	= JTable::getInstance( 'UsersInvite', 'AccountTable' );
				$obj->bind($row);
				$rows[] = $obj;
			}
		}
		
		return $rows;
	}
	
	
	public function getTotal($filter = null)
	{
		$db = JFactory::getDbo();
		$condition	= ' WHERE invite_email <> "" ';
		
		if (isset($filter['status']) && intval($filter['status']) > 0)
		{
			$condition .= ' AND ' . $db->quoteName('status') .' = '.$db->quote($filter['status']);
		}
		
		if (isset($filter['days_ahead']) && intval($filter['days_ahead']) > 0)
		{
			$condition .= ' AND DATE_SUB(NOW(), INTERVAL '.$filter['days_ahead'].' DAY) >= ' . $db->quoteName('last_invite_date');
		}
		
		if (isset($filter['from']) && !empty($filter['from']))
		{
			$condition .= ' AND ' . $db->quoteName('from_email') .' = '.$db->quote($filter['from']);
		}
		
		$query = 'SELECT count(id) FROM #__users_invite ' . $condition;
		$db->setQuery( $query );		
		$total = $db->loadResult();
		
		return $total;
	}
	
	public function updateFromEmail($oldEmail, $newEmail, $user)
	{
		$db = JFactory::getDbo();
		$query = "UPDATE #__users_invite SET ".$db->quoteName('from_email').' = '.$db->quote($newEmail).', '.
				$db->quoteName('invitor').' = '.$db->quote($user->id). " WHERE ".
				$db->quoteName('from_email').' = '.$db->quote($oldEmail). " OR ".
				$db->quoteName('invitor').' = '.$db->quote($user->id);
		
		$db->setQuery( $query );
		$result = $db->query();
		
		return $result;
	}
}