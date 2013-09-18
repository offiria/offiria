<?php
/**
 * @category	Tables
 * @package		Offiria
 * @subpackage	Activities
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

/*

CREATE TABLE IF NOT EXISTS [prefix]_oauth_tokens(
id int(11) NOT NULL AUTO_INCREMENT,
user_id int NOT NULL,
client_id varchar(20) NOT NULL,
client_secret varchar(20) NOT NULL,
redirect_uri varchar(200) NOT NULL, 
code varchar(40) NOT NULL,
expires int(11) NOT NULL,
scope varchar(250) NOT NULL,
oauth_token varchar(40) NOT NULL,
authorized tinyint(4) DEFAULT '1',
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;
*/

define('APP_AUTHORIZED', 1);
define('APP_DEAUTHORIZED', 0);
define('APP_VALIDITY', '+1 MONTH');
define('REQUEST_VALIDITY', '+5 MIN');

class OauthTableToken extends JTable
{
	private $_handler	= null;
	private $_params	= null;
	 
	var $id = null;
	var $user_id = null;
	var $client_id = null;
	var $client_secret = null;
	var $redirect_uri = null;
	var $code = null;
	var $expires = null;
	var $scope = null;
	var $oauth_token = null;
	var $authorized = null;
	
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__oauth_tokens', 'id', $db );
	}
	 
	/**
	 * List all device installed
 	 * @return stdClass object containing entries
	 */
	public function listAll() {
		$db = JFactory::getDbo();
		$query = 'SELECT * FROM ' . $db->nameQuote('#__users_token');
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}
	
	/**
	 * List all device approved by user
	 * @param $userId userId the id of the user
	 * @return stdClass object containing entries
	 */
	public function listByUser($userId) {
		$db = JFactory::getDbo();
		$query = 'SELECT * FROM ' . $db->nameQuote('#__oauth_tokens') .
			'WHERE ' . $db->nameQuote('user_id') . "='". $userId . "'";
		$db->setQuery($query);
		return $db->loadObjectList();
	}
	
	/**
	 * Revoking is changing entries 'authorized' to APP_DEAUTHORIZED
	 * @return Boolean true on success
	 */
	public function revokeAccess($deviceId) {
		$this->load(array('id'=>$deviceId));
		$this->authorized = APP_DEAUTHORIZED;
		if ($this->store()) {
			return true;
		}
		return false;
	}
	
	/**
	 * Granting is changing entries 'authorized' to APP_AUTHORIZED
	 * @return Boolean true on success
	 */
	public function grantAccess($deviceId) {
		$this->load(array('id'=>$deviceId));
		$this->authorized = APP_AUTHORIZED;
		if ($this->store()) {
			return true;
		}
		return false;
	}
	
	/**
	 * Method to remove device entry base on its consumer_key
	 * @param String $clientId is the client_id
	 */
	public function removeDevice($clientId) {
		$curr = $this->load(array('id'=>$clientId));
		return $this->delete($curr->id); 
	}
	
	/**
	 * Accessor to the token expiry
	 * @param String $token of the token id
	 * @return String unformatted string of the expiry date
	 */
	public function getExpires($token) {
		return $this->_getParam('expires', array('token_id'=>$token));
	}
	
	/**
	 * Access for the token
	 * @param String $appId application id to be retrieve
	 * @return String token id
	 */
	public function getTokenByAppId($appId) {
		return $this->_getParam('app_secret', 
			array('consumer_key'=>$appId
			));
	}
	
	
	/**
	 * Accessor for the token authorization value
	 * @return Boolean true if authorized
	 */
	public function checkAuthorization($token) {
		$this->_getParam('authorized', array('app_secret'=>$token));
		if ($this->authorized == APP_AUTHORIZED) {
			return true;
		}
		return false;
	}
	
	/**
	 * Check for existence of entries by app id
	 * @param String $appId application identifier for check
	 * @return Boolean true on success
	 */
	public function checkExistenceByAppId($appId) {
		return $this->_getParam('consumer_key', 
			array('consumer_key' => $appId
			));
	}
	
	/**
	 * Check for existence of entries by token
	 * @param String $token application identifier for check
	 * @return Boolean true on success
	 */
	public function checkExistenceByToken($token) {
		return $this->_getParam('app_secret', 
			array('app_secret' => $token
			));
	}
	
	/**
	 * Check whether the app belongs to user
	 * @return Boolean true if the app belongs to the user
	 */
	public function isAppBelongToUser($appId, $userId) {
		return $this->_getParam('user_id', 
			array(
				'user_id'=>$userId,
				'consumer_key'=>$appId
			));
	}
	
	/**
	 * Check request_token validity
	 * Validity as in it has been requested before and the rows should exist
	 * @return id of the user
	 */
	public function isValidRequest($request_token) 
	{
		return $this->_getParam('id', 
			array(
				'request_token'=>$request_token
			));
	}
	
	/**
	 * Check request_token validity
	 * Validity as in it has been requested before and the rows should exist
	 * @return Boolean true on success
	 */
	public function isValidAccess($access_token) 
	{
		return $this->load(array(
				'access_token'=>$access_token
			));
	}
	
	/**
	 * Return the param requested
	 * @param String $field field to retrieve 
	 * @param Array $condition condition for the array to load
	 * @return String as requested on success and false on empty
	 */
	private function _getParam($field, $condition=null) {
		if (is_array($condition)) {
			$this->load($condition);
		}
		else {
			$this->load();
		}
		if ($this->{$field} == null) {
			return false;
		}
		return $this->{$field};
	}

	/**
	 * Return the param requested
	 * @param String $field field to retrieve 
	 * @param Array $condition condition for the array to load
	 * @return String as requested on success and false on empty
	 */
	public function getParam($field, $condition=null) {
		return $this->_getParam($field, $condition);
	}
}
