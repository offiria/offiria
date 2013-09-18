<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class StreamModelLinks {
	
	/**
	 * Return list of groups
	 */	 	
	public function getLinks($filter = null, $limit = 20, $limitstart = 0)
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
		$countRow = 'SELECT count(id) AS num_row FROM #__stream_links WHERE '. $sql;
		$query = 'SELECT * FROM #__stream_links WHERE '. $sql . $order_by .$limit;
				
		$db->setQuery( $countRow );
		$records	= $db->loadResult();
				
		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		
		$rows = array();
		foreach( $result as $row )
		{
			$obj	= JTable::getInstance( 'Link', 'StreamTable' );
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
		
		$countRow = 'SELECT count(id) AS num_row FROM #__stream_links WHERE '. $sql;
				
		$db->setQuery( $countRow );
		$records	= $db->loadResult();
		
		return $records;
	}
	
	
	/**
	 * Filter
	 */	 	
	private function _buildFilterConditions($filter)
	{
		$where = array();
		$db = JFactory::getDbo();
		
		// link id
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
		
		// Exclude link id
		if( isset( $filter['!id']) && !empty($filter['!id']))
		{
			// Id can be a single number or list of ids, such as
			// 1,2,3,4,5
			$filter['!id'] = trim($filter['!id'], ',');
			$where[] = $db->nameQuote('id') ." NOT IN (".$filter['!id'].") ";
		}
		
		// Exclude empty link
		if( isset( $filter['!link']))
		{
			$where[] = $db->nameQuote('link') ." <> ".$db->quote($filter['!link'])." ";
		}
		
		if(empty($where)){
			$where[] = ' 1 ';
		}
		
		$sql = implode(' AND ',$where); 
		
		return $sql;	
	}
}
	
