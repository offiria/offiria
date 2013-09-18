<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class StreamModelStream {
	
	/**
	 *  $filter is an array, possible key
	 *  [user_id] = search by user id
	 *  [group_id] = search by user id	 
	 *  [mention] = search by @mention
	 *  [type] = search by type	 
	 *  [hashtag] = search by hashtag
	 *  [keyword] = generic full-text search
	 *  [category_id] = filter by category_id
	 */	 	
	public function getStream($filter = null, $limit = 20, $limitstart=0)
	{
		$db    = JFactory::getDbo();
		$where = $this->_buildFilterConditions($filter);

		// Ordering cannot be used along with ['limitstart']
		$order_by = ' ORDER BY id DESC ';
		if(!empty($filter['order_by_asc'])){			
			$order_by =' ORDER BY '.$db->nameQuote($filter['order_by_asc']).' ASC ';
		}
		
		if(!empty($filter['order_by_desc'])){
			$order_by =' ORDER BY '.$db->nameQuote($filter['order_by_desc']).' DESC ';
		}
		
		// Order by without filter
		if(!empty($filter['order_by'])){
			$order_by =' ORDER BY '. $filter['order_by'];
		}
		
		$query = 'SELECT * FROM #__stream WHERE '. $where. $order_by. ' LIMIT '. $limitstart .', '.$limit;

		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		$rows = array();
		if( !empty($result)) {
			foreach( $result as $row )
			{
				$obj = JTable::getInstance( 'Stream', 'StreamTable' );
				$obj->bind($row);
				$rows[] = $obj;
			}
		}
		
		return $rows;
	}
	
	// @TODO: move into its own model
	public function getCustomlist($userid = null)
	{
		$db = JFactory::getDbo();

		$query = 'SELECT * FROM #__stream_customlist WHERE ' . $db->nameQuote('user_id') . ' = ' . $db->Quote($userid);

		$db->setQuery($query);
		$result = $db->loadObjectList();

		$rows = array();
		if (!empty($result)) {
			foreach ($result as $row) {
				$obj = JTable::getInstance('Customlist', 'StreamTable');
				$obj->bind($row);
				$rows[] = $obj;
			}
		}

		return $rows;
	}
	
	/**
	 * Return total number of record ignoring any limit
	 *
	 */	 	 	
	public function countStream($filter = null)
	{
		$db = JFactory::getDbo();
		$where = $this->_buildFilterConditions($filter);
		
		$query = 'SELECT count(*) FROM #__stream WHERE '. $where;
		$db->setQuery( $query );
		$result	= $db->loadResult();
		
		return $result;
	}
	
	/**
	 * Return ids of record ignoring any limit
	 *
	 */	 	 	
	public function getStreamIds($filter = null)
	{
		$db = JFactory::getDbo();
		$where = $this->_buildFilterConditions($filter);
		
		$query = 'SELECT id FROM #__stream WHERE '. $where;
		$db->setQuery( $query );
		$result	= $db->loadResultArray();
		
		return $result;
	}
	
	/**
	 * Return 'next' message based on message id and 'type' 
	 */	 	
	public function getNextMessage($message)
	{
		$db = JFactory::getDbo();
		$where = $db->nameQuote('id') . ' > ' . $db->Quote($message->id)
				.' AND '
				. $db->nameQuote('type') . ' = ' . $db->Quote($message->type);
		
		$query = 'SELECT * FROM #__stream WHERE '. $where . ' ORDER BY `id` ASC LIMIT 1';
		$db->setQuery( $query );
		$result	= $db->loadObject();
		
		$obj	= JTable::getInstance( 'Stream', 'StreamTable' );
		$obj->bind($result);
		return $obj;
	}
	
	/**
	 * Return 'next' message based on message id and 'type' 
	 */	 	
	public function getPrevMessage($message)
	{
		$db = JFactory::getDbo();
		$where = $db->nameQuote('id') . ' < ' . $db->Quote($message->id)
				.' AND '
				. $db->nameQuote('type') . ' = ' . $db->Quote($message->type);
		
		$query = 'SELECT * FROM #__stream WHERE '. $where . ' ORDER BY `id` DESC LIMIT 1';
		$db->setQuery( $query );
		$result	= $db->loadObject();
		
		$obj	= JTable::getInstance( 'Stream', 'StreamTable' );
		$obj->bind($result);
		return $obj;
	}
	
	/**
	 * Return an array of data with month-count information
	 * Sample data
	 * MONTH(`created`) 	YEAR(`created`) 	count(*)
		10 					2011 				1
		11 					2011 				2
		12 					2011 				8
		1 					2012 				3	 	 
	 */	 	
	public function getMessageStats($filter = null){

		$db = JFactory::getDbo();
		$query = 'SELECT MONTH('.$db->nameQuote('created').') as month, YEAR('.$db->nameQuote('created').') as year, count(*) as count FROM #__stream WHERE '.$db->nameQuote('type').'='.$db->Quote('page').' '
				.' GROUP BY  YEAR('.$db->nameQuote('created').'), MONTH('.$db->nameQuote('created').') '
				.' ORDER BY year DESC, month DESC ';
				
		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		return $result;
	}
	
	/**
	 * Return StreamTableComment objects
	 * @param type $stream_id
	 * @param type $limit 
	 */
	public function getComments( $filter, $limit = null )
	{
		$db = JFactory::getDbo();
		$stream_id = $filter['message_id'];
		
		// Ordering cannot be used along with ['limitstart']
		$order_by = ' ORDER BY id ASC ';
		if(!empty($filter['order_by_asc'])){			
			$order_by =' ORDER BY '.$db->nameQuote($filter['order_by_asc']).' ASC ';
		}
		
		if(!empty($filter['order_by_desc'])){			
			$order_by =' ORDER BY '.$db->nameQuote($filter['order_by_desc']).' DESC ';
		}
		
		if(!empty($limit)){
			$limit = ' LIMIT '. $limit;
		}
		

		$query = "SELECT * FROM #__stream_comments WHERE "
			. $db->nameQuote('stream_id') .' = ' . $db->Quote($stream_id)
			. $order_by . $limit;
		
		$db->setQuery( $query );

		$result	= $db->loadObjectList();
		
		$rows = array();
		if(!empty($result)){
			foreach( $result as $row )
			{
				$obj	= JTable::getInstance( 'Comment', 'StreamTable' );
				$obj->bind($row);
				$rows[] = $obj;
			}
		}
		return $rows;
	}
	
	public function countComments($filter)
	{
		$where = array();
		$db = JFactory::getDbo();
		
		// Id more
		if( isset( $filter['id_more'])){ 
			// @todo: optimize this further!
			$where[] = $db->nameQuote("id")." > ". $db->Quote($filter['id_more']);
		}
		
		// Group id
		if( isset( $filter['group_id'])){
			// @todo: optimize this further!
			$where[] = $db->nameQuote("group_id")." = ". $db->Quote($filter['group_id']);
		}
		
		// Stream id
		if( isset( $filter['stream_id'])){
			// @todo: optimize this further!
			$where[] = $db->nameQuote("stream_id")." = ". $db->Quote($filter['stream_id']);
		}
		
		$where = implode(' AND ',$where);	
		
		
		$query = "SELECT count(*) FROM #__stream_comments WHERE " . $where;
		
		$db->setQuery( $query );
		$result	= $db->loadResult();
		
		return $result;
	}
	
	public function deleteComments( $stream_id )
	{
	    $db = JFactory::getDbo();
		$query = "DELETE FROM #__stream_comments WHERE "
			. $db->nameQuote('stream_id') .' = ' . $db->Quote($stream_id);
		
		$db->setQuery( $query );
		$db->query();
	}
	
	           
	public function countMessageSince( $message_id, $excludeUserId )
	{
		$db = JFactory::getDbo();
		
		$filter = array();
		$filter['id_more']  = $message_id;
		$filter['!user_id'] = $excludeUserId;
		
		$where = $this->_buildFilterConditions($filter);
		
		$query = "SELECT count(*) FROM #__stream WHERE " . $where;

		$db->setQuery( $query );

		return $db->loadREsult();
	}
	
	
	public function lastMessageId( $type = null )
	{
		$db = JFactory::getDbo();
		$query = "SELECT id FROM #__stream ORDER BY `id`  DESC LIMIT 1";
		
		$db->setQuery( $query );
		return $db->loadREsult();
	}
	
	/**
	 * Delete messages with the given filters
	 */	 	
	public function delete($filter)
	{
		$db = JFactory::getDbo();
		$where = $this->_buildFilterConditions($filter);
		
		$query = 'DELETE FROM #__stream WHERE '. $where;
		$db->setQuery( $query );
		$result	= $db->query();
	}
	
	private function _buildFilterConditions($filter)
	{
		$where = array();
		$db = JFactory::getDbo();
		
		if( isset( $filter['id']))
		{ 
			// If the id specified is empty, return and empty array
			// the caller logic might result in empty id list
			if( empty($filter['id']) ){
				return array();
			}
			 
			// Id can be a single number or list of ids, such as
			// 1,2,3,4,5
			$filter['id'] = trim($filter['id'], ',');
			$where[] = $db->nameQuote('id') ."IN (".$filter['id'].") ";
		}
		
		// Mention
		if( isset( $filter['mention'])){
			// @todo: optimize this further!
			$where[] = "message LIKE '%".$filter['mention']."%' ";
			//$where[] = ' match(message) against( "@joe" IN BOOLEAN MODE)';
		}
		
		// Search
		if( isset( $filter['search'])){
			// the following will look for word boundry (POSIX style for \b)
			// REPLACE is needed since the dot(.) character is not consider as word boundary
			$where[] = "REPLACE(message, '.', '_') REGEXP '.*".$filter['search']."[[:>:]].*$' ";

			// @todo: optimize this further!
			/* $where[] = "message LIKE '%".$filter['search']."%' "; */
			//$where[] = ' match(message) against( "@joe" IN BOOLEAN MODE)';
		}
		
		// User id
		if( isset( $filter['user_id'])){
			// @todo: optimize this further!
			$where[] = "user_id = ". $db->Quote($filter['user_id']);
		}
		
		// Not user id
		if( isset( $filter['!user_id'])){
			// @todo: optimize this further!
			$where[] = $db->nameQuote("user_id") . " != ". $db->Quote($filter['!user_id']);
		}
		
		// Group id
		if( isset( $filter['group_id'])){
			// @todo: optimize this further!
			$where[] = $db->nameQuote("group_id")." = ". $db->Quote($filter['group_id']);
		}

		// Id more
		if( isset( $filter['id_more'])){ 
			// @todo: optimize this further!
			$where[] = $db->nameQuote("id")." > ". $db->Quote($filter['id_more']);
		}
		
		// Type
		if( isset( $filter['type'])){
			// @todo: optimize this further!
			$where[] = $db->nameQuote("type"). " = ".$db->Quote($filter['type']) ;
		}
		
		// Status
		if( isset( $filter['status'])){
			// @todo: optimize this further!
			$where[] = $db->nameQuote("status"). " = ". $db->Quote($filter['status']);
		}
		
		// Month created
		if( isset( $filter['month'])){
			// @todo: optimize this further!
			$where[] = ' MONTH('.$db->nameQuote("created"). ") = '".$filter['month']."' ";
		}
		
		// Year created
		if( isset( $filter['year'])){
			// @todo: optimize this further!
			$where[] = ' YEAR ('.$db->nameQuote("created"). ") = '".$filter['year']."' ";
		}
		
		// Due date
		if( !empty( $filter['start_date'] )){
			// @todo: optimize this further!
			$where[] = "start_date > '".$filter['start_date']."' ";
		}
		
		// use for overdue
		if( !empty( $filter['end_date'] )){
			// @todo: optimize this further!
			$where[] = "end_date < '".$filter['end_date']."' ";
			$where[] = "end_date != '0000-00-00 00:00:00' ";
		}		
		
		// Due date
		if( !empty( $filter['create_start'] )){
			// @todo: optimize this further!
			$where[] = "created > '".$filter['create_start']."' ";
		}
		
		// use for overdue
		if( !empty( $filter['create_end'] )){
			// @todo: optimize this further!
			$where[] = "created < '".$filter['create_end']." 23:59:59' ";
			$where[] = "created != '0000-00-00 00:00:00' ";
		}	
		
		// use for check current occurring event 
		if( !empty( $filter['event_occurring_date'] )){
			// @todo: optimize this further!
			$where[] = "(( start_date > '".$filter['event_occurring_date']."' ) OR ( end_date > '".$filter['event_occurring_date']."' AND end_date != '0000-00-00 00:00:00' )) ";
		}
		
		// use for check event
		if( !empty( $filter['event_past_date'] )){
			// @todo: optimize this further!
			$where[] = "(( end_date < '".$filter['event_past_date']."' ) OR ( start_date < '".$filter['event_past_date']."' AND end_date = '0000-00-00 00:00:00' )) ";
		}
		
		// Must have end date
		if( isset( $filter['has_end_date'] )){
			// @todo: optimize this further!
			$where[] = $db->nameQuote("end_date")." != '0000-00-00 00:00:00' ";
		}
		
		// Must NOT have end date
		if( isset( $filter['!has_end_date'] )){
			// @todo: optimize this further!
			$where[] = $db->nameQuote("end_date")." = '0000-00-00 00:00:00' ";
		}
		
		// categorize
		if( isset ( $filter['category_id'] )) {
			$where[] = $db->nameQuote("category_id") . " = " .  $filter['category_id'];
		}

		// Tags
		if( isset( $filter['tag'])){
			//TODO: Full text indexing if appropriate
			$where[] = "raw LIKE '%#".$filter['tag']."#%' ";
		}

		// Tags
		if( isset( $filter['custom'])){
			// @TODO: Full text indexing if appropriate
			$where[] = $filter['custom'];
		}
		
		// START @TODO: multiple ID support to current filters
		if (isset($filter['group_ids'])) {
			$where[] = $db->nameQuote('group_id') . ' IN (' . $filter['group_ids'] . ')';
		}
		if (isset($filter['user_ids'])) {
			$where[] = $db->nameQuote('user_id') . ' IN (' . $filter['user_ids'] . ')';
		}
		if (isset($filter['tags'])) {
			$tagWhere = array();
			foreach (explode(',', $filter['tags']) as $tag) {
				if($tag !== '') {
					$tagWhere[] = $db->nameQuote('raw') . ' LIKE \'%#' . $tag . '#%\''; // TODO: consistent with search
				}
			}
			if(count($tagWhere)) {
				$where[] = '(' . implode(' OR ', $tagWhere) . ')';
			}
		}
		// END

		if(empty($where)){
			$where[] = ' 1 ';
		}
		
		// Now, if the stream is a private stream, the group MUST match user's group
		// added condition to check if viewing user is a siteadmin/superuser, he can sees the content
		$my = JXFactory::getUser();

		if (!$my->isAdmin(true))
		{
			$sql = implode(' AND ',$where);

			if($my->getParam('groups_member_limited')) {
				// If the users have limited groups (extranet), then filter those groups only
				$sql .=' AND '. $db->nameQuote('group_id') .'IN ('. $my->getParam('groups_member_limited') .')';
			} else {
				// Get both the joined and followed group IDs
				$userGroups = $my->getMergedGroupIDs();

				$sql =  '( '. $sql . ') AND ('. $db->nameQuote('access') ."=". $db->Quote(0) ;
				if(!empty($userGroups))
					$sql .=' OR '. $db->nameQuote('group_id') .'IN ('. $userGroups .') )';
				else
					$sql .= ')';
			}
		}
		else
		{			
			$sql = implode(' AND ',$where);
		}

		return $sql;
	}
}