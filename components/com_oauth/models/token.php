<?php
/**
 * @version     1.0.0
 * @package     com_oauth
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.application.component.helper');
jimport('joomla.user.helper');

JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_oauth' . DS . 'tables' );
define('APP_REQUEST_SECRET', $_SERVER['HTTP_HOST']);

/**
 * Model
 */
class OauthModelToken extends JModel
{
	protected $_item;
	protected $_token = null;
	private $userId;

	/**
	 * Generate token here to standardize the token generation
	 * @condition if the userId is provided it will be assign to the user directly
	 * @see assignToken()
	 * @return String generated token
	 */
	public function generateToken() {
		$salt = JUserHelper::genRandomPassword(20);
		$crypt = JUserHelper::getCryptedPassword(rand(), $salt);
		$token = $crypt . ':' . $salt;
		return $token;
	}

	/**
	 * Retrieve user id for the designated token
	 * Available after token is authenticated
	 */
	public function getUserId() {
		return $this->userId;
	}

	/**
	 * @example
	 * authenticate() flow is like following:
	 * 1) retrieve $request_token
	 * 2) retrieve the $app_id
	 * 3) generate the $app_secret
	 * 4) store the $app_secret
	 * 5) DO NOT APPROVE the application unless the user agree to approve the device
	 * @param String $app_id public identification of the application
	 * @param String $request_token token that has been requested previously 
	 * @return Boolean true on success
	 */
	public function authenticate($request_token, $app_id, $nonce) {
		$tToken =& JTable::getInstance('Token', 'OauthTable');
		$tNonce =& JTable::getInstance('Nonce', 'OauthTable');
		if ($tNonce->validCall($nonce)) {
			return $tToken->exchangeRequestWithAccess($request_token, $app_id, $this->generateToken());
		}
		else {
			return false;
		}
	}
	
	/**
	 * Compare user token with token inside database
	 * @param String token to compare
	 * @return BOOL if aunthenticated
	 */
	public function authenticateToken($appSecret, $appId) {
		/* validate the entries */
		$table =& JTable::getInstance('Token', 'OauthTable');
				
		/*
		if ( $table->load( array('token_id' => $tokenId, 'app_id' => $appId) )) {
			$this->userId = $table->get('user_id');
			return true;
		}
		return false;
		*/
		return $this->getParam('user_id', array(
				'app_secret'=> $appSecret,
				'app_id'=>$appId
			));
	}

	/**
	 * Check if token already exist and assign to the user
	 * @param userId int id of the current user
	 * @return BOOL if the token exist
	 */
	public function tokenExist($userId) {
		$db = & $this->getDbo();
		//try to get associated id from usergroups
		$query = "SELECT * FROM " . $db->quoteName( '#__users_token' ) . " WHERE `user_id` = '$userId'";
		$db->setQuery($query);
		if (count($db->loadObjectList()) > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Assign token to user
	 * @param userId int id of the user
	 * @return BOOL on success
	 * @deprecated method is not required
	 * @see OauthModelApplication->addDevice();
	 */
	public function assignToken($userId, $token) {
		JError::raiseError(500, 'Method assignToken() is deprecated');
		return false;

		$db =& $this->getDbo();
		// create new values if the user does now own a token
		if (!$this->tokenExist($userId)) {
			$q = "INSERT INTO " . $db->quoteName( '#__users_token' ) . "(user_id, token_id, expires) VALUES('$userId', '$token', NOW())";
			$db->setQuery($q);
			if ($db->query()) {
				return true;
			}
			/* failed to insert into database */
			else {
				return false;
			}
		}
		/* token already exist */
		else {
			return false;
		}
	}

	/**
	 * Method to remove authorization by token
	 * @return BOOL on success
	 */
	public function removeToken($token) {
		$db =& $this->getDbo();
		// create new values if the user does now own a token
		$q = "DELETE FROM " . $db->quoteName( '#__users_token' ) . " WHERE " . $db->quoteName('token_id') . "='$token' LIMIT 1";
		$db->setQuery($q);
		if ($db->query()) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * Check if the device id exist on database
	 * @param String $token device id to compare
	 * @return Boolean true on success
	 */
	public function isExist($token) {
		$table = JTable::getInstance('Token', 'OauthTable');
		return $table->checkExistenceByToken($token);
	}
	
	/**
	 * Method to check whether the token already expires
	 * @param String $token is the token id
	 * @return Boolean true if expired
	 */
	public function isExpires($token) {
		$table = JTable::getInstance('Token', 'OauthTable');
		$expires = $table->getExpires($token);
		$today = date('Y-m-d H:i:s');
		if ($today > $expires) {
			return true;
		}
		else {
			return false;
		}
	}
	
	/**
	 * Method to check whether is token is authorized or not
	 * @param String $token token string  
	 * @return Boolean true on authorized
	 */
	public function isAuthorized($token) {
		$table = JTable::getInstance('Token', 'OauthTable');
		return $table->checkAuthorization($token);
	}
	
	/**
	 * Generate request token
	 * @return String hashed token
	 */
	public function getRequestToken() {
		// use Joomla browser detection
		jimport('joomla.environment.browser');
		$browser =& JBrowser::getInstance();
		
		// format of the request token is reverse-dns prepend to device type
		$host = $_SERVER['HTTP_HOST'];
		
		// remove any port 
		$domain = substr($host, 0, strpos($host, ':'));
		
		// reverse the dns name
		$token = explode('.', $domain);
		$token = array_reverse($token);
		// combine and append with dot (.) at the end for category 
		$token = implode('.', $token) . '.';
		
		// append category to the token based on USER AGENT
		// use if's for readability
		if (preg_match('/mac|win|linux/i', $browser->getPlatform()) > 0) { 
			$token .= 'Desktop';
		}
		if ($browser->isMobile()) {
			$token .= 'Mobile';
		}	
		
		// apppend with random salt
		$token = $token . rand();
		$token = hash_hmac('sha1', $token, APP_REQUEST_SECRET);
		$table = JTable::getInstance('Token', 'OauthTable');
		if ($table->onRequest($token)) {
			return $token;
		}
		return null;
	}
	
	/**
	 * Provide access token when
	 * @param String $app_key Application identifier
	 * @param String $app_secret Application secret
	 * @return Boolean true on success 
	 */
	public function createAccessToken($app_key, $app_secret) 
	{
		$table = JTable::getInstance('Token', 'OauthTable');
		$token = $this->generateToken();
		return $table->makeAccess($app_key, $app_secret, $token);
	}
	
	/**
	 * Check request_token validity
	 * Validity as in it has been requested before and the rows should exist
	 * @return Boolean true on success
	 */
	public function isValidRequest($request_token) 
	{
		$table = JTable::getInstance('Token', 'OauthTable');
		return $table->isValidRequest($request_token);
	}
	
	/**
	 * Check access_token validity
	 * Validity as in the access token is not expired and is a valid access_token
	 * @return Boolean true on success
	 */
	public function isValidAccess($access_token) 
	{
		$table = JTable::getInstance('Token', 'OauthTable');
		return $table->isValidAccess($access_token);
	}
	

	/**
	 * Track verifier
	 * @param String $verifier verifier is a temporary value that will be removed once the request is exchange with access token
	 * @return Boolean true on success
	 */
	public function addVerifier($verifier, $request_token) 
	{
		$table =& JTable::getInstance('Token', 'OauthTable');
		return $table->addVerifierToRequest($verifier, $request_token);
	}
}

