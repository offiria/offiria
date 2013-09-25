<?php
/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright 	Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */

defined('JPATH_PLATFORM') or die;

jimport('joomla.access.access');
jimport('joomla.registry.registry');
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_profile' . DS . 'tables' );
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_account' . DS . 'tables' );

JLoader::register('Notifications', JPATH_ROOT .DS.'components'.DS.'com_profile'.DS.'libraries'.DS.'notification.php');
JLoader::register('ProfileFactory', JPATH_ROOT .DS.'components'.DS.'com_profile'.DS.'factory.php');

/**
 * User class.  Handles all application interaction with a user
 *
 * @package     Joomla.Platform
 * @subpackage  User
 * @since      11.1
 */
class JXUser extends JUser
{
	protected $_defaultAvatar = array('user.png', 'male_1.png', 'male_2.png', 'male_3.png', 'female_1.png', 'female_2.png', 'female_3.png');
	protected $_anonAvatarThumb = 'anon-thumb.png';
	protected $_defaultAvatarThumb = array('user-thumb.png', 'male_1.png', 'male_2.png', 'male_3.png', 'female_1.png', 'female_2.png', 'female_3.png');
	protected $_isAdmin = null;

	// TODO: Will replace this with something much more simpler (csv)
	// Getting started tasks
	const GSTARTED_COMPLETE_PROFILE = 'COMPLETE_PROFILE';
	const GSTARTED_ADD_AVATAR = 'ADD_AVATAR';
	const GSTARTED_JOIN_GROUP = 'JOIN_GROUP';
	const GSTARTED_INVITE_FRIENDS = 'INVITE_FRIENDS';

	protected $_defaultGetStartedTasks = array(
		self::GSTARTED_COMPLETE_PROFILE => array('link'=> 'index.php?option=com_profile&view=edit&task=details'),
		self::GSTARTED_ADD_AVATAR => array('link'=> 'index.php?option=com_profile&view=edit&task=changeAvatar'),
		self::GSTARTED_JOIN_GROUP => array('link'=> 'index.php?option=com_stream&view=groups'),
		self::GSTARTED_INVITE_FRIENDS => array('link'=> 'index.php?option=com_account&view=invite')
	);

	public function getGettingStartedTask()
	{
		$decodedGetStartedTasks = $this->getParam('get_started_tasks');
		$getStartedTasks = json_decode($decodedGetStartedTasks, true);

		if($decodedGetStartedTasks == null) {

			// If the user doesn't have a 'get_started_tasks' param, create one
			$newGetStartedTask = array();

			foreach($this->_defaultGetStartedTasks as $task=>$detail) {

				// TODO: TEMPORARY IMPORT; REMOVE ON PRODUCTION

				if($task == self::GSTARTED_COMPLETE_PROFILE) {

					// Get the old profile incomplete percentage and store it in the new one
					$oldProfileCompletion = $this->getParam('profile_details_unfilled');
					$newGetStartedTask[$task] = ($oldProfileCompletion != null) ? (100 - $oldProfileCompletion) : 0;

				} else if($task == self::GSTARTED_ADD_AVATAR) {

					// If the avatar is already there, set the task as completed
					$avatarExists = ($this->getAvatarPath()) ? true : false;
					$newGetStartedTask[$task] = ($avatarExists) ? 100 : 0;

				} else if($task == self::GSTARTED_JOIN_GROUP) {

					// If the user has already joined group[s], set the task as completed
					$groupList = $this->getParam('groups_member');
					$newGetStartedTask[$task] = (JXUtility::csvCount($groupList)) ? 100 : 0;

				} else if($task == self::GSTARTED_INVITE_FRIENDS) {
					
					// If the user is admin, he will have additional task to invite users
					if ($this->isAdmin())
					{
						JModel::addIncludePath( JPATH_ROOT . DS . 'components' . DS . 'com_account' . DS . 'tables');
						$inviteModel = JModel::getInstance('UsersInvite', 'AccountModel');
						$total = $inviteModel->getTotal(array('from' => $this->email));
						
						$newGetStartedTask[$task] = ($total >=5 ) ? 100 : 0;
					}
					else
					{
						continue;
					}

				} 
				else {

					$newGetStartedTask[$task] = 0;
				}
			}

			$this->setParam('get_started_tasks', json_encode($newGetStartedTask));
			$this->save();

			return $newGetStartedTask;
		} else {
			return $getStartedTasks;
		}
	}

	public function setGettingStartedCompletion($task, $percentage)
	{
		$task = strtoupper(trim($task));

		$getStartedTasks = $this->getGettingStartedTask();

		$getStartedTasks[$task] = $percentage;
		$this->setParam('get_started_tasks', json_encode($getStartedTasks));

		return $this->save();
	}

	public function getGettingStartedCompletion($task = '')
	{
		$task = strtoupper(trim($task));

		$getStartedTasks = $this->getGettingStartedTask();

		if(empty($task))
		{
			// Return total completion percentage
			$total = 0;

			foreach($getStartedTasks as $task=>$completion) {
				$total += $completion;
			}

			return round(($total / (count($getStartedTasks) * 100)) * 100);
		}
		else
		{
			// Return specific task instead
			return $getStartedTasks[$task];
		}
	}
	
	public function getGettingStartedTaskLink($task)
	{
		if (isset($this->_defaultGetStartedTasks[$task]))
		{
			return JRoute::_($this->_defaultGetStartedTasks[$task]['link']);
		}
		
		return JURI::base();
	}
		
	/*
	 * Get all required fields from XML
	 */
	public function getProfileRequiredFields()
	{
		jimport('joomla.form.form');
		JForm::addFieldPath(JPATH_COMPONENT . DS . 'models' . DS . 'fields');
		
		$requiredFields = array();
		
		$form =& JForm::getInstance('form', JPATH_ROOT.DS.'components'.DS.'com_profile'.DS.'models'.DS.'forms'.DS.'details.xml');
		$fieldSets = $form->getFieldsets('params');
		foreach ($fieldSets as $name => $fieldset) 
		{
			foreach($form->getFieldSet($name) as $field)
			{
				if ($field->required) 
				{
					$requiredFields[] = $field->fieldname;
				}
			}
		}
		
		$form2 =& JForm::getInstance('form2', JPATH_ROOT.DS.'components'.DS.'com_profile'.DS.'models'.DS.'forms'.DS.'edit.xml');			
		foreach($form2->getFieldSet("edit") as $field)
		{
			if ($field->required) 
			{
				$requiredFields[] = $field->fieldname;
			}
		}
		
		return $requiredFields;
	}	
	
	/*
	 * Calculate completion
	 */
	public function getCalculateCompletion()
	{		
		$completedField = 0;
		$requiredFields = $this->getProfileRequiredFields();
		
		foreach ($requiredFields as $field)
		{
			if ($this->getParam($field, '') != '' || $this->get($field, '') != '')
			{
				++$completedField;
			}
		}
		
		$completionPercent = round($completedField/count($requiredFields) * 100);
		return $completionPercent;
	}

	/**
	 *  Clone JUser object into current JXUser obkect
	 */	 	
	public function init( $user )
	{
		$thisVars = get_object_vars($this);
				
		foreach( $thisVars as $key=>$val ) {
			if( isset($user->$key) ) {
				$this->$key = $user->$key ;
			}
		}
		
		// Load user profile information from #_user_details
		if (intval($this->id) > 0)
		{
			$detailModel	= ProfileFactory::getModel('detail');
			$userParams		= $detailModel->getDetails($this->id);
				
			foreach( $userParams as $key => $val ) 
			{
				$this->$key = $userParams[$key];
			}
			
			$this->_enableAllNotification();
		}
	}
	
	/**
	 *
	 */	 	
	public function getURL(){
		return JRoute::_('index.php?option=com_profile&view=display&user='.$this->username);
	}
	
	
	/**
	 * Return full url for the avatar
	 */	 	
	public function getAvatarURL()
	{
		$avatar = $this->getAvatarPath();
		$avatar = (empty($avatar)) ? 'images'.DS.'avatar'.DS.$this->_defaultAvatar[0] : $avatar;
		// Convert filesystem slashes to url backslash
		$avatar = str_replace( DS , '/', $avatar );
		
		return JURI::root().$avatar;
	}
	
	/**
	 * Return full url for avatar thumbnail
	 */	 	
	public function getThumbAvatarURL()
	{
		if(empty($this->id) || is_null($this->username) || $this->username == 'anon'){
			$avatar = 'images'.DS.'avatar'.DS.$this->_anonAvatarThumb;
		} else {
			$avatar = $this->getThumbAvatarPath();
			$avatar = (empty($avatar)) ? 'images'.DS.'avatar'.DS.$this->_defaultAvatarThumb[0] : $avatar;		
		}
		// Convert filesystem slashes to url backslash
		$avatar = str_replace( DS , '/', $avatar );
		
		return JURI::root().$avatar;
	}
	
	/**
	 * Return file path to full-size avatar, relative to the site root
	 */
	public function getAvatarPath()
	{
		return $this->getParam('avatar');
	}
	
	/**
	 * Return file path to thumbnail, relative to the site root
	 */
	public function getThumbAvatarPath()
	{
		return $this->getParam('avatar_thumb');
	}
	
	public function isDefaultAvatar($file = '')
	{
		return (in_array(strtolower(basename($file)), $this->_defaultAvatar));
	}
		
	public function isDefaultAvatarThumb($file = '')
	{
		return (in_array(strtolower(basename($file)), $this->_defaultAvatarThumb));
	}
	
	/**
	 * Check action permission. For some component, this work differently than 
	 * core authorise(...)	 
	 */	 	
	public function authorise($action, $asset = null)
	{
		jimport('joomla.filesystem.file');
		
		// Check if $action is one of those we need to override, 
		// if not, just pass it to parent
		$actions = explode('.', $action);
		// @todo: check and make sure it is exploded properly
		
		$comName = $actions[0];
		$libPath = JPATH_ROOT.DS.'components'.DS.'com_'.$comName.DS.'access.php';

		if(!JFile::exists($libPath))
		{
			return parent::authorise($action, $asset);
		}
		include_once($libPath);
		$className = ucfirst($comName).'Access';
		
		// Shorten the action name and remove the first 'component name' part
		unset($actions[0]);
		$action = implode('.',$actions);
		
		// For some reason, php 5.2 doesn't like the code below
		// $className::check($this->id, $action, $asset)
		// SO, lets just instantiate the object
		$accessObj = new $className();
		
		return $accessObj->check($this->id, $action, $asset);
	}
	
	/**
	 * Check if logined user is Super Admin	 
	 */	 
	public function isAdmin($checkSuperuser=false){
		if($checkSuperuser) {
			return in_array(8, JAccess::getGroupsByUser($this->id)); // we'd want to check if the user's a superadmin
		} else {
			if(is_null($this->_isAdmin))
			{
				$groupIds = JAccess::getGroupsByUser($this->id);
				$this->_isAdmin = (in_array(8, $groupIds) || in_array(7, $groupIds));
			}

			return $this->_isAdmin;
		}
	}
	
	
	/**
	 * Get latest status update for logined user	 
	 */	 
	public function getStatus()
	{
		JModel::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_stream' . DS . 'models' );
		
		$filter			= array('user_id' => $this->id,
								'group_id' => 0,
								'type' => 'update',
								'order_by_desc' => 'created');
		$messageModel	= JModel::getInstance('Stream', 'StreamModel');
		$result = $messageModel->getStream($filter, 1);
		
		if (empty($result))
		{
			$result[0] = new stdClass();
			$result[0]->message = JText::_('COM_PROFILE_LABEL_NO_STATUS_UPDATE');
		}
				
		return $result;
	}
	
	private function _enableAllNotification()
	{
		$enabledSetting		= 1;
		$my					= JFactory::getUser();
		$notification		= new Notifications($my);
		
		if (is_null($my->getParam($notification->getEmailNotificationIndex('profile_post_comment'))))
		{
			$notificationSetting= $notification->getNotification();

			foreach ($notificationSetting as $group => $typeInfo)
			{
				foreach ($typeInfo as $type => $typeSetting)
				{
					$globalNotification = $notification->getGlobalNotificationIndex( $type );
					$emailNotification = $notification->getEmailNotificationIndex( $type );

					$my->setParam($globalNotification, $enabledSetting);
					$my->setParam($emailNotification, $enabledSetting);
				}
			}
			$my->save();
		}
	}
	
	
	// Get registered users in system using com_stream StreamModelUser
	public static function getRegisteredUsers()
	{
		jimport('joomla.application.component.model');
		JModel::addIncludePath( JPATH_ROOT . DS . 'components'.DS.'com_stream'.DS.'models' );
		$userModel	= JModel::getInstance('User', 'StreamModel');
		$arrUsers	= $userModel->getRegisteredUsers();
		
		return $arrUsers;
	}
	
	// get user by email
	public function loadUserByEmail($email, $tableName = 'AccountUser', $tablePrefix = 'AccountTable')
	{
		// Create the user table object
		$table = $this->getTable($tableName, $tablePrefix);

		// Load the JUserModel object based on the user id or throw a warning.
		$load  = $table->load(array('email' => $email));
		if (!$load)
		{
			return false;
		}

		// Set the user parameters using the default XML file.  We might want to
		// extend this in the future to allow for the ability to have custom
		// user parameters, but for right now we'll leave it how it is.

		$this->_params->loadString($table->params);

		// Assuming all is well at this point lets bind the data
		$this->setProperties($table->getProperties());

		return true;
	}
	
	// get user by email
	public function loadUserByUsername($username, $tableName = 'AccountUser', $tablePrefix = 'AccountTable')
	{
		// Create the user table object
		$table = $this->getTable($tableName, $tablePrefix);

		// Load the JUserModel object based on the user id or throw a warning.
		$load  = $table->load(array('username' => $username));
		if (!$load)
		{
			return false;
		}

		// Set the user parameters using the default XML file.  We might want to
		// extend this in the future to allow for the ability to have custom
		// user parameters, but for right now we'll leave it how it is.

		$this->_params->loadString($table->params);

		// Assuming all is well at this point lets bind the data
		$this->setProperties($table->getProperties());

		return true;
	}

	/**
	 * This will return the user timezone
	 * @param int $user_id id of the user
	 * @param String $forDisplay get the display, if true then return value will be formatted to the proper display
	 * @return String depends on the value of format
  	*/
	public static function getUserTime($user_id = null, $forDisplay = false) {
		$config = new JXConfig();
		$user = JFactory::getUser($user_id);

		// First load account setting (if any) timezone to override timezone in language file
		$defaultTz   = ($config->getTimezone() != '') ? $config->getTimezone() : JText::_('JXLIB_DEFAULT_TIMEZONE');
		$my          = (!($user instanceof JUser) && !($user instanceof JXUser)) ? JXFactory::getUser() : $user;
		$timeZoneStr = $my->getParam('timezone');
		
		// Second load user personalize timezone (if any) to override system default timezone
		$timeZoneStr = (empty($timeZoneStr)) ? $defaultTz : $timeZoneStr;
		$tz          = new DateTimeZone($timeZoneStr);		
		
		$date2  = new JDate('now', $tz);
		$offset = $date2->getOffset() / 3600;	
		$date   = new JDate();
		$date->setOffset($offset);

		$xdate = new JXDate();
		/* if the value want to be used as display purposes */
		if ($forDisplay) { 
			return $xdate->formatDate($date);
		}
		else {
			return $xdate->format($date);
		}
	}
	
	public function isSuperAdmin()
	{
		return ($this->id == 42);
	}
	
	public function getDefaultAvatars()
	{
		return $this->_defaultAvatar;
	}

	/**
	 * Get the user joined and followed group IDs
	 * @return string Comma-separated group IDs
	 */
	public function getMergedGroupIDs()
	{
		$groupIJoin   = $this->getParam('groups_member');
		$groupIFollow = $this->getParam('groups_follow');

		return JXUtility::csvMerge($groupIFollow, $groupIJoin);
	}

	/**
	 * Is the user part of a limited group/extranet?
	 * @return boolean
	 */
	public function isExtranetMember()
	{
		// @TODO: use authorise instead
		return $this->getParam('groups_member_limited');
	}

	public function reloadSession() {
		// Force reload from database
		$session = JFactory::getSession();
		$session->set('user', new JUser($this->id));
	}
}
