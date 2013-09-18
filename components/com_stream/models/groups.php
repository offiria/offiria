<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class StreamModelGroups {
	
	/**
	 * Return list of groups
	 */	 	
	public function getGroups($filter = null, $limit = 20, $limitstart = 0)
	{
		$where = array();
		$db = JFactory::getDbo();
				
		if(!is_null($limit)){
			//$limit = ' LIMIT '. $limit;
			$limit = ' LIMIT '.$limitstart .', '.$limit;
		} else {
			$limit = '';
		}
		
		// Ordering cannot be used along with ['limitstart']
		$order_by = ' ORDER BY id DESC ';
		if(!empty($filter['order_by_asc'])){			
			$order_by =' ORDER BY '.$db->nameQuote($filter['order_by_asc']).' ASC ';
		}
		
		if(!empty($filter['order_by_desc'])){
			$order_by =' ORDER BY '.$db->nameQuote($filter['order_by_desc']).' DESC ';
		}

		$sql = $this->_buildFilterConditions($filter);
		
		// Run the query
		$countRow = 'SELECT count(id) AS num_row FROM #__groups WHERE '. $sql;
		$query = 'SELECT * FROM #__groups WHERE '. $sql . $order_by .$limit;
				
		$db->setQuery( $countRow );
		$records	= $db->loadResult();
				
		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		
		$rows = array();
		foreach( $result as $row )
		{
			$obj	= JTable::getInstance( 'Group', 'StreamTable' );
			$obj->bind($row);
			$rows[] = $obj;
		}
		
		return $rows;
	}
	
	/**
	 * Return list of groups
	 */	 	
	public function getTotal($filter = null)
	{
		$db = JFactory::getDbo();
		
		$sql = $this->_buildFilterConditions($filter);
		
		$countRow = 'SELECT count(id) AS num_row FROM #__groups WHERE '. $sql;
				
		$db->setQuery( $countRow );
		$records	= $db->loadResult();
		
		return $records;
	}
	
	/**
	 *
	 */	 	
	public function updateAccess($group_id, $access )
	{
		$db = JFactory::getDbo();
		
		// Update stream access
		$query = 'UPDATE #__stream SET '. $db->nameQuote('access') .'=' . $db->Quote($access) 
				. '  WHERE '. $db->nameQuote('group_id') .'=' . $db->Quote($group_id);
		$db->setQuery( $query );
		$db->query();
		
		// Update files access
		$query = 'UPDATE #__stream_files SET '. $db->nameQuote('access') .'=' . $db->Quote($access) 
				. '  WHERE '. $db->nameQuote('group_id') .'=' . $db->Quote($group_id);
		$db->setQuery( $query );
		$db->query();
	}
	
	/**
	 * Return list of groups this user is active in the last 30-days
	 */	 	
	public function getActiveGroups($userid, $limit = 20)
	{
		$db = JFactory::getDbo();
		
				
		
		$sql = $db->nameQuote('user_id').'='.$db->Quote($userid).' AND '.$db->nameQuote('group_id').' != 0'
			   .' AND '.$db->nameQuote('updated').' > NOW() - INTERVAL 30 DAY';
		
		// Now, if the show only public group, or group visible to the user
		$my = JXFactory::getUser();
		$userGroups = $my->getParam('groups_member');
		
		$sql =  '( '. $sql . ') AND ('. $db->nameQuote('access') ."=". $db->Quote(0) ;
		if(!empty($userGroups))
		 	$sql .=' OR '. $db->nameQuote('id') .'IN ('. $userGroups .') )';
		else
			$sql .= ')';
			
			
		$query = 'SELECT group_id, count(*) as frequency FROM #__stream '
				.' WHERE ' . $sql
				.' GROUP BY '.$db->nameQuote('group_id').' ORDER BY frequency DESC';
		$db->setQuery( $query );
		$data = $db->loadColumn(0);
		
		// Now that we have group_ids, load up the groups
		$ids = implode(',' , $data);
		$filter = array();
		$filter['id'] = $ids;
		$groups = $this->getGroups($filter, $limit);

		// reorder the group lisy properly
		foreach($groups as $row){
			$key = array_search($row->id, $data);
			if($key !== FALSE){
				$data[$key] = $row;
			}
		}
		
		// Make sure it is an object, otherwise, remove it
		foreach($data as $i => $row){
			if(!is_object($data[$i])){
				unset($data[$i]);
			}
		}
		
		return $data;
	}
	
	/**
	 * Filter
	 */	 	
	private function _buildFilterConditions($filter)
	{
		$where = array();
		$db = JFactory::getDbo();
		
		// Group id
		if( isset( $filter['id']))
		{ 	
			// If the id specified is empty, return and empty array
			// the caller logic might result in empty id list
			if( empty($filter['id']) ){
				$filter['id'] = '-1';
			}
				 
			// Id can be a single number or list of ids, such as
			// 1,2,3,4,5
			$filter['id'] = trim($filter['id'], ',');
			$where[] = $db->nameQuote('id') ." IN (".$filter['id'].") ";
			
		}
		
		// Exclude Group id
		if( isset( $filter['!id']) && !empty($filter['!id']))
		{
			// Id can be a single number or list of ids, such as
			// 1,2,3,4,5
			$filter['!id'] = trim($filter['!id'], ',');
			$where[] = $db->nameQuote('id') ." NOT IN (".$filter['!id'].") ";
		}
		
		// Access
		if( isset( $filter['access']))
		{ 
			$where[] = $db->nameQuote('access') ."=". $db->Quote($filter['access']);
			
		}
		
		// Archived
		if( isset( $filter['archived']))
		{ 
			$where[] = $db->nameQuote('archived') ."=". $db->Quote($filter['archived']);
			
		}
		
		// Category
		if( isset( $filter['category_id']))
		{ 
			$where[] = $db->nameQuote('category_id') ."=". $db->Quote($filter['category_id']);
			
		}
		// Name cannot be empty
		$where[] = $db->nameQuote('name') ."!=". $db->Quote('');
		
		if(empty($where)){
			$where[] = ' 1 ';
		}
		
		$sql = implode(' AND ',$where); 
		
		// Now, if the show only public group, or group visible to the user		
		$my = JXFactory::getUser();
		$userGroups = $my->getParam('groups_member');

		$sql = implode(' AND ',$where);
		$sql =  '( '. $sql . ') AND ('. $db->nameQuote('access') ."=". $db->Quote(0) ;
		if(!empty($userGroups))
		 	$sql .=' OR '. $db->nameQuote('id') .'IN ('. $userGroups .') )';
		else
			$sql .= ')';
		
		return $sql;	
	}
}
	
