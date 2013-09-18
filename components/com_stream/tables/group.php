<?php

/*
CREATE TABLE IF NOT EXISTS `prefix_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  `creator` int(11) NOT NULL,
  `params` text NOT NULL,
  `members` text NOT NULL,
  `followers` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;
*/

class StreamTableGroup extends JTable
{
	var $id 	 	= null;
	var $access		= null; // 0 = public, 1 = private
	var $name 	 	= null;
	var $description = null;
	var $creator = null;
	var $created = null;
	var $members = null;
	var $followers = null;
	var $archived  = null;
	var $category_id  = null;
	
	protected $_params = null;
	
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		$this->_params = new JRegistry;
		parent::__construct( '#__groups', 'id', $db );
	}
	
	public function load($keys = NULL, $reset = true)
	{
		$ret = parent::load($keys);
		$this->_params->loadString($this->params);
		return $ret;
	}
	
	public function bind($src, $ignore = Array())
	{
		$ret = parent::bind($src);
		$this->_params->loadString($this->params);
		return $ret;
		
	}

	public function store($preventUpdate = false)
	{
		// Set date if none exist
		if($preventUpdate != true) {
			$now = new JDate();
			$this->created = $now->toMySQL();
		}

		return parent::store();
	}

	public function isMember($user_id)
	{
		return JXUtility::csvExist($this->members, $user_id);
	}
	
	public function isFollower($user_id)
	{
		return JXUtility::csvExist($this->followers, $user_id);
	}
	
	public function allowJoin($user_id)
	{
		return ($this->access != 1);
	}
	
	/**
	 * Return true if the user have permission to edit the group
	 */	 	
	public function allowEdit($userid)
	{
		// Allow editing new group
		if($this->id == 0)
			return true;
			
		// Allow if user is the group creator
		if($userid == $this->creator)
			return true;
			
		// Allow is user id group admin (which is none right now)
		return false;
	}
	
	
	public function allowArchive($userid){
		// Ifyou can delete, you can archive it
		return $this->allowDelete($userid);
	}
	
	/**
	 * If it is private group, you can read, if you're a member
	 */	 	
	public function allowRead($userid)
	{
		// If the user is limited by group, do not allow them to access group
		// beyond their assigned group
		$user = JXFactory::getUser($userid);
		$limitGroup = $user->getParam('groups_member_limited');
		if($limitGroup) {
			return JXUtility::csvExist($limitGroup, $this->id);	
		}

		if(!$this->access)
			return true;
			
		$userGroups = $user->getParam('groups_member');
		return JXUtility::csvExist($userGroups, $this->id);	
	}
	
	/**
	 * Return true if the user have permission to delete the group
	 */	 	
	public function allowDelete($userid)
	{
		// if you can edit, you can delete the group
		return (!empty($this->id) && $this->allowEdit($userid));	
	}
	
	public function getParam($key, $default = null)
	{
		return $this->_params->get($key, $default);
	}

	/**
	 * Method to set a parameter
	 */
	public function setParam($key, $value)
	{
		$this->_params->set($key, $value);
		$this->params = $this->_params->toString();
		return true;
	}
	
	public function count($type)
	{
		$streamModel = StreamFactory::getModel('stream');
		$fileModel = StreamFactory::getModel('files');
		switch($type)
		{
			case 'update':
				return $streamModel->countStream(array('group_id' => $this->id, 'type' => 'update'));
				break;
				
			case 'file':
				return $fileModel->countFiles(array('group_id' => $this->id));
				break;
				
			case 'event':
				return $streamModel->countStream(array('group_id' => $this->id, 'type' => 'event'));
				break;
			
			case 'milestone':
				return $streamModel->countStream(array('group_id' => $this->id, 'type' => 'milestone'));
				break;
				
			case 'todo':
				return $streamModel->countStream(array('group_id' => $this->id, 'type' => 'todo'));
				break;
		}
	}
	
	/**
	 * Method to list all groups on database
	 * @param int $userid id of the user
	 * @return array id(s) of groups name
	 */
	public function listGroupsByUser($userid) {
		$db = JFactory::getDbo();
		$q = "SELECT " . $db->nameQuote('id') . " FROM " . $db->nameQuote('#__groups') .
			" WHERE " . $db->nameQuote('members') . " LIKE '%" . $userid . "%'";
		$db->setQuery($q);
		$db->query();
		$results = $db->loadObjectList();
		$ids = array();
		foreach($results as $result) {
			$ids[] = $result->id;
		}
		return $ids;
	}
	
	
	
	/**
	 * Return the link to the specific group
	 */	 	
	public function getUri()
	{
		$uri	= JURI::getInstance();
		$base	= $uri->toString( array('scheme', 'host', 'port'));
		return $base . JRoute::_( 'index.php?option=com_stream&view=groups&task=show&group_id='.$this->id);
	}
}

