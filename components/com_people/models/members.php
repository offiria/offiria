<?php
/**
 * @version     1.0.0
 * @package     com_People
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.application.component.helper');



/**
 * Model
 */
class PeopleModelMembers extends JModel
{
	var $_pagination = null;
	var $_total = null;
	var $_data = null;

	/**
	 *   Return list of all site members
	 */	 

	function __construct()
	{
		parent::__construct();

		$app	= JFactory::getApplication();
		$config = JFactory::getConfig();

		$this->setState('limit', $app->getUserStateFromRequest('com_search.limit', 'limit', $config->get('list_limit'), 'int'));
		$this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));
	}

	/* 
	public function getMembers( $filters = null )
	{
		$db		=& $this->getDBO();
	    $query	= 'SELECT id FROM ' . $db->nameQuote( '#__users' ) . ' '
	    		 .'ORDER BY `id`';
						
	      
	    $db->setQuery( $query );
	    $result = $db->loadResultArray();

	    if($db->getErrorNum())
	    {
			JError::raiseError( 500, $db->stderr());
	    }
		
		// Load individual users to get user objects 
	    $users = array();
	    foreach($result as $row)
	    {
	    	$users[] = JFactory::getUser($row);
		}
		
	    return $users;
	}
	*/

	function getMembers($filter = null)
	{
		// Lets load the content if it doesn't already exist
		if (empty($this->_data))
		{
			$db	   = $this->getDBO();

			/*
			 * this will contain an array of user id to be filtered to, which filter position/department
			 * it is optional and may be empty
			 */
			$userIdAfterFilter = array();

			/**
			 * The department and position are stored inside details table which explains the hassle to compare them
			 */
			// this only runs if both argument is supplied as inner join is needed
			if (JRequest::getVar('department_id', false) && JRequest::getVar('position_id', false)) {
				$positionId = JRequest::getVar('position_id');
				$departmentId = JRequest::getVar('department_id');
				$q = "SELECT t1.user_id " .
					"FROM #__user_details as t1 " .
					"JOIN #__user_details as t2 " .
					"ON t1.user_id = t2.user_id " .
					"WHERE t1.field='work_department' " .
					"AND t1.value='$departmentId' " .
					"AND t2.field='work_position' " .
					"AND t2.value='$positionId'; ";
				// be safe and run from within condition
				$db->setQuery( $q );
				$userIdAfterFilter = $db->loadColumn();
			}
			// whereas only normal query is needed to accomplish either one
			else {
				try {
					if (JRequest::getVar('position_id') != NULL) {
						$q = "SELECT user_id FROM #__user_details WHERE " .
							"field='work_position' AND value='".JRequest::getVar('position_id')."';";
					}
					if (JRequest::getVar('department_id', NULL) != NULL) { 
						$q = "SELECT user_id FROM #__user_details WHERE " . 
							"field='work_department' AND value='".JRequest::getVar('department_id')."';";
					}
					if (!empty($q)) { 
						$db->setQuery( $q );
						$userIdAfterFilter = $db->loadColumn();
					}
				}
				catch (Exception $e) {
					/* log this */
					$error = $e->getMessage();
				}
			}

			$where = $this->_buildFilterConditions($filter);
			$query	=  'SELECT u.id
						FROM ' . $db->nameQuote( '#__users' ) . ' AS u
						WHERE '. $where;
			$db->setQuery( $query );
			$results = $db->loadAssocList();
			
			$users = array();
			
			//group of admins
			$admingroups = array (	0 => 'Super People',
									1 => 'People',	
									2 => 'Manager',
									3 => 'Super Users'
								 );

			foreach ($results as $result) {
				if (JRequest::getVar('position_id') || JRequest::getVar('department_id')) {
					if (in_array($result['id'], $userIdAfterFilter)) {
						$users[] = JXFactory::getUser($result['id']);
					}
				}
				else {
					$users[] = JXFactory::getUser($result['id']);
				}
			}

			$this->_total	= count($users);
			if ($this->getState('limit') > 0) {
				$this->_data	= array_splice($users, $this->getState('limitstart'), $this->getState('limit'));
			} else {
				$this->_data = $users;
			}
		}

		return $this->_data;
	}

	function getTotal()
	{
		return $this->_total;
	}

	function getPagination()
	{
		if (empty($this->_pagination))
		{
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
		}

		return $this->_pagination;
	}
	
	/*
	 * To block members for logging in
	 * The user are not allowed to login but the data will still be intact
	 * This is a toggle function if the user is active it will be deactive and vice versa
	 * @params : id (Array)
	 */
	public function activateMembers($ids){
		/* instead of deleting the user. disable the member */
		foreach ($ids as $id) {
			$my = JXFactory::getUser($id);
			/* for Joomla block is 1 */
			if ($my->block == 0) {
				/* block 1 is UNBLOCKED */
				$my->block = 1;
				$str = JText::sprintf('COM_PEOPLE_LABEL_USER_DEACTIVATE', $my->name);
			}
			else {
				/* block 0 is BLOCKED */
				$my->block = 0;
				$str = JText::sprintf('COM_PEOPLE_LABEL_USER_ACTIVATE', $my->name);
			}
			if ($my->save()) {
				JFactory::getApplication()->enqueueMessage($str);
			}
		}
	}
	
	/*
	 * To set members as admin
	 * @params : id (Array)
	 */
	public function setAdmin($id)
	{
		$adminUsr	= 'Administrator';
		$db			=& $this->getDBO();
		
		//try to get associated id from usergroups 
		$query = "SELECT id FROM " . $db->nameQuote( '#__usergroups' ) . " WHERE title = ".$db->quote($adminUsr);
		$db->setQuery($query);
		$level_id = $db->loadResult();
		
		//update the mapping groups to the permission
		foreach($id as $uid){
			$data = new stdClass();
			$data->user_id = $uid;
			$data->group_id = $level_id;
			$db->updateObject( '#__user_usergroup_map' , $data , 'user_id' );
			
			// Logout the user that usergroup has been changed 
			$query = "DELETE FROM " . $db->nameQuote( '#__session' ) . " WHERE userid = ".$db->quote($uid);
			$db->setQuery($query);
			$db->query();
		}
		
		if(!$db->getErrorNum()){
			return true;
		}else{
			JError::raiseError( 500, $db->stderr());
		}
	}
	
	/*
	 * To set members as normal user
	 * @params : id (Array)
	 */
	public function unsetAdmin($id)
	{
		$normalUsr	= 'Registered';
		$db			=& $this->getDBO();
		
		//cannot unset super user : 42
		foreach($id as $key => &$i){
			if($i == '42'){
				unset($id[$key]);
			}
		}
		
		//try to get associated id from usergroups 
		$query = "SELECT id FROM " . $db->nameQuote( '#__usergroups' ) . " WHERE title = ".$db->quote($normalUsr);
		$db->setQuery($query);
		$level_id = $db->loadResult();
		
		//update the mapping groups to the permission
		foreach($id as $uid){
			$data = new stdClass();
			$data -> user_id = $uid;
			$data -> group_id = $level_id;
			$db->updateObject( '#__user_usergroup_map' , $data , 'user_id' );
			
			// Logout the user that usergroup has been changed 
			$query = "DELETE FROM " . $db->nameQuote( '#__session' ) . " WHERE userid = ".$db->quote($uid);
			$db->setQuery($query);
			$db->query();
		}
		
		if(!$db->getErrorNum()){
			return true;
		}else{
			JError::raiseError( 500, $db->stderr());
		}
	}

	private function _buildFilterConditions($filter)
	{
		$db = JFactory::getDbo();

		$where = array();
		if( isset($filter['namefilter']) && !empty( $filter['namefilter'] ) )
		{
			$namefilter = strtolower($filter['namefilter']);

			switch( $namefilter )
			{
				case 'others':
					$where[] = ' u.name REGEXP "^[^a-zA-Z]."';
					break;
				case 'all':
					break;
				default:
					$filterCount	= JString::strlen( $namefilter );
					$allowedFilters	= array('abc','def','ghi','jkl','mno','pqr','stu','vwx' , 'yz' );
					$filterQuery = '';

					if( in_array( $namefilter , $allowedFilters ) )
					{
						$filterQuery	.= ' (';
						for( $i = 0; $i < $filterCount; $i++ )
						{
							$char			= $namefilter{$i};
							$filterQuery	.= $i != 0 ? ' OR ' : ' ';
							$filterQuery	.= 'u.name LIKE '.$db->Quote(JString::strtoupper($char).'%').' OR u.name LIKE '.$db->Quote(JString::strtolower($char) . '%');
						}
						$filterQuery	.= ')';
					}
					$where[] = $filterQuery;
					break;
			}
		}
		
		// Hide 'admin' account
		$where[] = $db->nameQuote('username') . ' != ' .$db->Quote('admin');
		
		if(empty($where)){
			$where[] = ' 1 ';
		}

		return  implode(' AND ',$where);
	}

}
