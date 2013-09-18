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

/**
 * Model
 */
class OauthModelApplication extends JModel
{
	private $_table;

	public function __construct() {
		JTable::addIncludePath(JPATH_ROOT.DS.'components'.DS.'com_oauth'.DS.'tables');
		$this->_table = JTable::getInstance('Token', 'OAuthTable');
	}

	/**
	 *
	 * Install new client 
	 * 
	 * @param $client_id
	 *   Client identifier to be stored.
	 * @param $client_secret
	 *   Client secret to be stored.
	 * @param $redirect_uri
	 *   Redirect URI to be stored.
	 */
	public function addClient($clientId, $clientSecret, $redirectUri) {
		$my = JXFactory::getUser();
		// update table if already exist
		$id = $this->_table->getParam('id', array(
												 'user_id'=>$my->id,
												 'client_id'=>$clientId));
		$this->_table->load($id);
		$this->_table->client_id = $clientId;
		$this->_table->client_secret = $clientSecret;
		$this->_table->redirect_uri = $redirectUri;
		$this->_table->user_id = $my->id;
		return $this->_table->store();
	}

	/**
	 * Implements OAuth2::checkClientCredentials().
	 */
	public function checkClientCredentials($clientId, $clientSecret = NULL) {
		return $this->_table->getParam('client_secret', array('client_id'=>$clientId));
	}

	/**
	 * Implements OAuth2::getRedirectUri().
	 */
	public function getRedirectUri($client_id) {
		return $this->_table->getParam('redirect_uri', array('client_id'=>$clientId));
	}

	/**
	 * Implements OAuth2::getAccessToken().
	 */
	public function getAccessToken($oauth_token) {
		$db = JFactory::getDbo();
		$query = "SELECT redirect_uri, expires, scope FROM " . $db->nameQuote('#__oauth_tokens') . " WHERE oauth_token='$oauth_token'";
		$db->setQuery($query);
		$result = $db->loadAssoc();
		return $result;
	}

	/**
	 * Implements OAuth2::setAccessToken().
	 */
	public function setAccessToken($oauthToken, $clientId, $expires, $scope = NULL) {
		$my = JXFactory::getUser();
		$id = $this->_table->getParam('id', array(
												 'user_id'=>$my->id,
												 'client_id'=>$clientId));
		$this->_table->load($id);
		$this->_table->oauth_token = $oauthToken;
		$this->_table->client_id = $clientId;
		$this->_table->expires = $expires;
		$this->_table->scope = $scope;
		return $this->_table->store();
	}

	/**
	 * Overrides OAuth2::getSupportedGrantTypes().
	 */
	public function getSupportedGrantTypes() {
		return array(
					 OAUTH2_GRANT_TYPE_AUTH_CODE,
					 );
	}

	/**
	 * Overrides OAuth2::getAuthCode().
	 */
	public function getAuthCode($code) {
		$db = JFactory::getDbo();
		$query = "SELECT code, client_id, redirect_uri, expires, scope FROM " . $db->nameQuote('#__oauth_tokens') . " WHERE code='$code'";
		$db->setQuery($query);
		$result = $db->loadAssoc();
		/* just return the first result */
		return $result;
	}

	/**
	 * Overrides OAuth2::setAuthCode().
	 */
	public function setAuthCode($code, $clientId, $redirectUri, $expires, $scope = NULL) {
		$this->_table->load();
		$this->_table->code = $code;
		$this->_table->client_id = $clientId;
		$this->_table->redirect_uri = $redirectUri;
		$this->_table->expires = $expires;
		$this->_table->scope = $scope;
		return $this->_table->store();
	}

	public function removeDevice($ownerId, $clientId) {
		$id = $this->_table->getParam('id', array(
												  'user_id'=>$ownerId,
												  'client_id'=>$clientId));
		$this->_table->load($id);
		return $this->_table->delete();
	}

	public function retrieveAccessToken($clientId, $clientSecret) {
		return $this->_table->getParam('oauth_token', array(
															'client_id'=>$clientId,
															'client_secret'=>$clientSecret));
	}

	/**
	 * To check if the access token is valid
	 */
	public function isValidAccess($accessToken) {
		return ($this->_table->getParam('user_id', array('oauth_token'=>$accessToken)) > 0);
	}

	/**
	 * To retrieve the bearer of the token
	 */
	public function getTokenBearer($accessToken) {
		return $this->_table->getParam('user_id', array('oauth_token'=>$accessToken));
	}
}
