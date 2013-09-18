<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class StreamModelUser {
	
	public function searchUsername(){
	}
	
	
	public function search($username, $filter = null){

		if(empty($username)){
			return array();
		}

		$where = array();
		$db = JFactory::getDbo();

		if(isset( $filter['!id']) && !empty($filter['!id']))
		{
			// Id can be a single number or list of ids, such as 1,2,3,4,5
			$filter['!id'] = trim((string)$filter['!id'], ',');
			$where[] = $db->nameQuote('t.id') ." NOT IN (".$filter['!id'].") ";
		}

		if(empty($where)){
			$where[] = ' 1 ';
		}
		
		// Select those with username start with the given name
		// and later search those with the given username in the middle
		$query = " SELECT * FROM (SELECT id FROM #__users WHERE `username` LIKE " . $db->Quote($username.'%')
				." UNION"
				." SELECT id FROM #__users WHERE `username` LIKE " . $db->Quote('%'.$username.'%') ." ) as t"
				." WHERE " . implode(' AND ',$where);

		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		$users = array();
		
		foreach($result as $row){
			$users[] = JXFactory::getUser($row->id);
		}

		return $users;
	}
	
	
	// Function used to get all regisered users
	// Currently use in JXConfig for registered user count
	public function getRegisteredUsers()
	{
		$db = JFactory::getDbo();
		
		// get registered users
		$query = "SELECT * FROM #__users WHERE ".$db->nameQuote('block')." = 0 ";
		
		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		if($db->getErrorNum())
		{
			JError::raiseError( 500, $db->stderr());
		}

		return $result;
	}
}
