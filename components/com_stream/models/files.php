<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class StreamModelFiles {
	
	/**
	 * Return list of groups
	 */	 	
	public function getFiles($filter = null, $limit = 10, $limitstart = 0)
	{
		$db = JFactory::getDbo();	
		$where = $this->_buildFilterConditions($filter);
		
		$query = 'SELECT * FROM #__stream_files WHERE '. $where.' ORDER BY id DESC LIMIT '.$limitstart .', '.$limit;
		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		$rows = $this->_getTables($result);
		return $rows;
	}
	
	/**
	 * Return total count(*) given the same filter as getFiles func
	 */
	public function countFiles($filter = array())
	{
		$db = JFactory::getDbo();		
		$where = $this->_buildFilterConditions($filter);
		
		$query = 'SELECT count(*) FROM #__stream_files WHERE '. $where;
		$db->setQuery( $query );
		$result	= $db->loadResult();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}
		
		return $result;
	}	 	
	
	/**
	 * Return all unused files
	 */	 	
	public function getUnusedFiles(){
		$db = JFactory::getDbo();
		$where = array();
		$where[] = $db->nameQuote('stream_id') . ' = 0 ';
		$where[] = $db->nameQuote('created') . '< NOW() - INTERVAL 2 DAY';
		
		$query = 'SELECT * FROM #__stream_files WHERE '. implode(' AND ',$where);
		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		$rows = $this->_getTables($result);
		return $rows;
	}
	
	/**
	 * Return total number of files
	 */	 	
	public function getTotal( $filter ){
		$db = JFactory::getDbo();
		$where =  $this->_buildFilterConditions($filter);
		
		$query = 'SELECT count(*) FROM #__stream_files WHERE '. $where;
		$db->setQuery( $query );
		$result	= $db->loadResult();
		return $result;
	}
	
	/**
	 * Delete files with the given filters
	 */	 	
	public function delete($filter)
	{
		$db = JFactory::getDbo();
		$where = $this->_buildFilterConditions($filter);
		
		$query = 'UPDATE #__stream_files SET `stream_id`=0 WHERE '. $where;
		$db->setQuery( $query );
		$result	= $db->query();
	}
	
	private function _getTables($result){
		$rows = array();
		if(!empty($result)){
			foreach( $result as $row )
			{
				$obj	= JTable::getInstance( 'File', 'StreamTable' );
				$obj->bind($row);
				$rows[] = $obj;
			}
		}
		
		return $rows;
	}
	
	/**
	 *
	 */	 	
	public function getTotalStorage( $userid = null){
		
		$where = array();
		$db = JFactory::getDbo();
		
		// Only select files attached to a stream
		$where[] = $where[] = $db->nameQuote('stream_id') . ' != 0 ';
		if(empty($where)){
			$where[] = ' 1 ';
		}
		
		// If userid specified, filter by user
		if(!is_null($userid)){
			$where[] = $db->nameQuote('user_id') . '=' . $db->Quote($userid); 
		}
		
		
		$query = 'SELECT SUM(filesize) FROM #__stream_files WHERE '. implode(' AND ',$where);
		$db->setQuery( $query );
		$result = $db->loadResult();
		return $result;
	}
	
	public function updateOwner($message){
		$rawData = json_decode($message->raw);
		
		// If there is file uploaded, update the file
		if(!empty($rawData->attachment)){
			foreach($rawData->attachment as $fileid)
			{
				$file = JTable::getInstance( 'File' , 'StreamTable' );
				if(!empty($fileid) && $file->load($fileid) ){
					$file->status = 1;
					$file->stream_id = $message->id;
					$file->group_id = $message->group_id;
					$file->access	= $message->access;
					$file->store();
				}
			} 
		}
	}
	
	/**
	 * Build WHERE condition
	 */	 	
	private function _buildFilterConditions($filter)
	{
		$db = JFactory::getDbo();
		$user = JXFactory::getUser();
		
		// User files
		if( isset( $filter['user_id']) && !empty($filter['user_id']))
		{
			// Id can be a single number or list of ids, such as
			// 1,2,3,4,5
			$filter['user_id'] = trim($filter['user_id'], ',');
			$where[] = $db->nameQuote('user_id') ."IN (".$filter['user_id'].") ";
		}
		
		/**
		 * By (everyone / mine / mygroups)
		 */
		if( isset( $filter['by']) && !empty( $filter['by'] ) )
		{
			$mimetype =  array();
			switch($filter['by'])
			{
				case 'mine':
					$where[] = $db->nameQuote('user_id') ."=". $db->Quote($user->id);
					break;
					
				case 'mygroups':
					if(!$user->id) continue; // No user data loaded.
					$group_ids = $user->getParam('groups_member');
					$where[] = $db->nameQuote('group_id') ."IN (". $group_ids .") ";
					break;
				
				case 'everyone':
					// No filter really
					break;
			}
		}
		
		// Filetype
		/**
		 * Filetype is loosely related to the mimetype. Each filetype might actually
		 * filter to a few known mimetype		 
		 */		 		
		if( isset( $filter['filetype']) && !empty( $filter['filetype'] ) )
		{
			$mimetype =  array();
			switch($filter['filetype'])
			{
				case 'images':
					$mimetype[] = $db->Quote('image/jpeg');
					$mimetype[] = $db->Quote('image/png');
					break;
					
				case 'documents':
					$mimetype[] = $db->Quote('application/msword');
					$mimetype[] = $db->Quote('application/pdf');
					break;
				
				case 'spreadsheet':
					$mimetype[] = $db->Quote('application/pdf');
					break;
					
				case 'archive':
					break;
			}
			if(!empty($mimetype))
			{
				$where[] = $db->nameQuote('mimetype') ."IN (". implode(',', $mimetype) .") ";
			}
		}
		
		// Group files
		if( isset( $filter['group_id']) && !empty($filter['group_id']))
		{
			// Id can be a single number or list of ids, such as
			// 1,2,3,4,5
			$filter['group_id'] = trim($filter['group_id'], ',');
			$where[] = $db->nameQuote('group_id') ."IN (".$filter['group_id'].") ";
		}
		
		// Only select files attached to a stream
		$where[] = $where[] = $db->nameQuote('stream_id') . ' != 0 ';		
		
		if(empty($where)){
			$where[] = ' 1 ';
		}
		
		$my = JXFactory::getUser();
		$userGroups = $my->getParam('groups_member');

		$sql = implode(' AND ',$where);
		$sql =  '( '. $sql . ') AND ('. $db->nameQuote('access') ."=". $db->Quote(0) ;
		if(!empty($userGroups))
		 	$sql .=' OR '. $db->nameQuote('group_id') .'IN ('. $userGroups .') )';
		else
			$sql .= ')';
		
		return $sql;
		
	}
}
	
