<?php

class Applications {
	
	// instance of JUser
	private $_user;
	
	// table from OauthTableToken
	private $_table;
	
	public function __construct( $user = null )	{
		if (is_null( $user ) || !($user instanceof JUser)) {
			$this->_user = JXFactory::getUser();
		}
		else {
			$this->_user = $user;
		}
		// Include tables
		JTable::addIncludePath(JPATH_ROOT.DS.'components'.DS.'com_oauth'.DS.'tables');
		$this->_table = JTable::getInstance('token', 'OauthTable');
	}	
	
	/*
	 * This will run query on com_oauth table and return list all devices
	 * @return stdClass object containing all user entries
	 */
	public function getDeviceList() {
		$rows = $this->_table->listAll();
		return $rows;
	}

	/*
	 * This will run query on com_oauth table and return list all devices approved by user
	 * @return stdClass object containing entries by users
	 */
	public function getUserDeviceList($userId = null) {
		if ($userId == null) {
			$userId = $this->_user->id;
		}		
		$rows = $this->_table->listByUser($userId);
		return $rows;
	}

	/*
	 * Revoke access to device
	 * @param String $deviceId id of the device that need to be revoke 
	 */
	public function revokeAccess($deviceId) {
		$this->_table->revokeAccess($deviceId);
	}
	
	/*
	 * Restore access to device
	 * @param String $deviceId id of the device that need to be revoke 
	 */
	public function grantAccess($deviceId) {
		$this->_table->grantAccess($deviceId);		
	}
	
	/*
	 * More descriptive method
	 * @param int $code value of the code to check
	 * @return true when its an authorized value
	 */
	public function isAuthorized($code) {
		if ($code == APP_AUTHORIZED) {
			return true;
		}
		else if ($code == APP_DEAUTHORIZED) {
			return false;
		}
		// should be impossible to happen
		// better throw an error instead on debugging for null reference
		else {
			JError::raiseError(500, 'Device Authorizing: Uncaught exception');
		}
	}
	
	/*
	 * Method to remove device
	 * @param String $appId is the appId to be removed 
	 * @return Boolean true on success
	 */
	public function removeDevice($appId) {
		return $this->_table->removeDevice($appId);
	}
	
	/*
	 * return constant for authorized code for centralizing purposes
	 * @return int value of authorized code
	 */
	public function getAuthorizedCode() {
		return APP_AUTHORIZED;
	}

	/*
	 * Return constant for deauthorized code for centralizing purposes
	 * @return int value of deauthorized code
	 */
	public function getDeauthorizedCode() {
		return APP_DEAUTHORIZED;
	}
	
	/**
	 * Check if the name of the application exist
	 * @param String $name name of the application
	 * @return Boolean true on success
	 */
	public function nameExist($name) {
		$appList = $this->getUserDeviceList();
		foreach ($appList as $apps) {
			if ($apps->client_id == $name) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Check if the name of the application exist
	 * @param String $name authorization code
	 * @return Boolean true on success
	 */
	public function codeExist($code) {
		$appList = $this->getUserDeviceList();
		foreach ($appList as $apps) {
			if ($apps->code == $code) {
				return true;
			}
		}
		return false;
	}

}