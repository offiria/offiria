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
class AccountModelPayments extends JModel
{	
	public function getList($filter = null, $limit = 20, $limitstart=0)
	{
		$db			= JFactory::getDbo();
		$condition	= array();
		$orderBy	= '';
		
		if (isset($filter['status']) && !empty($filter['status']))
		{
			$condition[] = $db->quoteName('status').' = '.$db->quote($filter['status']);
		}
		
		if (isset($filter['type']) && !empty($filter['type']))
		{
			$condition[] = $db->quoteName('type').' = '.$db->quote($filter['type']);
		}
		$condition = ' WHERE '.implode(' AND ', $condition);
		
		$query = 'SELECT * FROM #__payments ' . $condition . $orderBy . ' LIMIT '. $limitstart .', '.$limit;
		
		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		
		$rows = array();
		if( !empty($result)) {
			foreach( $result as $row )
			{
				$obj	= JTable::getInstance( 'Payment', 'AccountTable' );
				$obj->bind($row);
				$rows[] = $obj;
			}
		}
		
		return $rows;
	}
	
	
	public function getTotal($filter = null)
	{
		$db = JFactory::getDbo();
		$db			= JFactory::getDbo();
		$condition	= array();
		$orderBy	= '';
		
		if (isset($filter['status']) && !empty($filter['status']))
		{
			$condition[] = $db->quoteName('status').' = '.$db->quote($filter['status']);
		}
		
		if (isset($filter['type']) && !empty($filter['type']))
		{
			$condition[] = $db->quoteName('type').' = '.$db->quote($filter['type']);
		}
		$condition = ' WHERE '.implode(' AND ', $condition);
		
		$query = 'SELECT count(id) FROM #__payments ' . $condition;
		$db->setQuery( $query );		
		$total = $db->loadResult();
		
		return $total;
	}
}