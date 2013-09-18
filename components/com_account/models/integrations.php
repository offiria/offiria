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
class AccountModelIntegrations extends JModel
{	
	public function getList()
	{
		$db			= JFactory::getDbo();
		$condition	= ' WHERE 1=1 '; // KIV this, might want to implement enable/disable option
		$orderBy	= '';
				
		$query = 'SELECT * FROM #__integrations ' . $condition;
		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		
		$rows = array();
		if( !empty($result)) 
		{
			foreach( $result as $row )
			{
				$obj	= JTable::getInstance( 'integration', 'AccountTable' );
				$obj->bind($row);
				$rows[] = $obj;
			}
		}
		
		return $rows;
	}
	
	
	public function getTotal($filter = null)
	{
		$db = JFactory::getDbo();
		$condition = ' WHERE 1=1 '; // KIV this, might want to implement enable/disable option
		
		$query = 'SELECT count(id) FROM #__integrations ' . $condition;
		$db->setQuery( $query );		
		$total = $db->loadResult();
		
		return $total;
	}
}