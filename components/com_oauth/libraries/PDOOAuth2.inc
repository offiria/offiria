<?php

/**
 * IMPORTANT
 * This file works as a delegate to current Joomla model without modification to core library files
 */

include "oauth2/OAuth2.inc";

/**
 * OAuth2 Library PDO DB Implementation.
 */
class PDOOAuth2 extends OAuth2 {

	private $_model;

	/**
	 * Overrides OAuth2::__construct().
	 */
	public function __construct() {
		parent::__construct();
		return $this->_model = OAuthFactory::getModel('Application');
	}

	/**
	 * Little helper function to add a new client to the database.
	 *
	 * Do NOT use this in production! This sample code stores the secret
	 * in plaintext!
	 *
	 * @param $client_id
	 *   Client identifier to be stored.
	 * @param $client_secret
	 *   Client secret to be stored.
	 * @param $redirect_uri
	 *   Redirect URI to be stored.
	 */
	public function addClient($client_id, $client_secret, $redirect_uri) {
		return $this->_model->addClient($client_id, $client_secret, $redirect_uri);
	}

	/**
	 * Implements OAuth2::checkClientCredentials().
	 *
	 * Do NOT use this in production! This sample code stores the secret
	 * in plaintext!
	 */
	protected function checkClientCredentials($client_id, $client_secret = NULL) {
		return $this->_model->checkClientCredentials($client_id, $client_secret = NULL);
	}

	/**
	 * Implements OAuth2::getRedirectUri().
	 */
	protected function getRedirectUri($client_id) {
		return $this->_model->getRedirectUri($clientId);
	}

	/**
	 * Implements OAuth2::getAccessToken().
	 */
	protected function getAccessToken($oauth_token) {
		return $this->_model->getAccessToken($oauth_token);
	}

	/**
	 * Implements OAuth2::setAccessToken().
	 */
	protected function setAccessToken($oauth_token, $client_id, $expires, $scope = NULL) {
		return $this->_model->setAccessToken($oauth_token, $client_id, $expires, $scope = NULL);
	}

	/**
	 * Overrides OAuth2::getSupportedGrantTypes().
	 */
	protected function getSupportedGrantTypes() {
		return array(
					 OAUTH2_GRANT_TYPE_AUTH_CODE,
					 );
	}

	/**
	 * Overrides OAuth2::getAuthCode().
	 */
	protected function getAuthCode($code) {
		return $this->_model->getAuthCode($code);
	}

	/**
	 * Overrides OAuth2::setAuthCode().
	 */
	protected function setAuthCode($code, $client_id, $redirect_uri, $expires, $scope = NULL) {
		return $this->_model->setAuthCode($code, $client_id, $redirect_uri, $expires, $scope = NULL);
	}

	/**
	 * Overrides OAuth2::finishClientAuthorization().
	 * Removes redirect uri
	 */
	public function finishClientAuthorization($is_authorized, $params = array()) {
		$params += array(
						 'scope' => NULL,
						 'state' => NULL,
						 );
		extract($params);

		if ($state !== NULL)
			$result["query"]["state"] = $state;

		if ($is_authorized === FALSE) {
			$result["query"]["error"] = OAUTH2_ERROR_USER_DENIED;
		}
		else {
			if ($response_type == OAUTH2_AUTH_RESPONSE_TYPE_AUTH_CODE || $response_type == OAUTH2_AUTH_RESPONSE_TYPE_CODE_AND_TOKEN)
				$result["query"]["code"] = $this->createAuthCode($client_id, $redirect_uri, $scope);

			if ($response_type == OAUTH2_AUTH_RESPONSE_TYPE_ACCESS_TOKEN || $response_type == OAUTH2_AUTH_RESPONSE_TYPE_CODE_AND_TOKEN)
				$result["fragment"] = $this->createAccessToken($client_id, $scope);
		}
	}

	/**
	 * Overrides OAuth2::createAuthCode().
	 * Make class accessible to this
	 */
	protected function createAuthCode($client_id, $redirect_uri, $scope = NULL) {
		$code = $this->genAuthCode();
		$this->setAuthCode($code, $client_id, $redirect_uri, time() + $this->getVariable('auth_code_lifetime', OAUTH2_DEFAULT_AUTH_CODE_LIFETIME), $scope);
		return $code;
	}
}
