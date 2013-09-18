<?php
/**
 * @version     1.0.0
 * @package     com_oauth
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access.
defined('_JEXEC') or die;

// define header code
define('AUTH_SUCCESS', 'HTTP/1.1 200 OK');
define('AUTH_VALID', 'HTTP/1.1 302 Found');
define('AUTH_FAILED', 'HTTP/1.1 400 Bad Request');

// Includes
require_once(JPATH_ROOT .DS.'components'.DS.'com_oauth'.DS.'libraries'.DS.'PDOOAuth2.inc');

class OauthControllerOauth extends JController
{
	public function display() {
		/* if (!JRequest::getVar('appId')) { */
		/* 	$mainframe = JFactory::getApplication(); */
		/* 	$mainframe->redirect(JRoute::_('index.php?option=com_profile&view=edit&task=applications', false)); */
		/* } */

		parent::display(null);
	}

	/**
	 * This is a callback for authorized token
	 * @method requesting authorize_token
	 * If the device has been approved, we can read the authorize_token necessarily
	 * Callback:
	 * [offiria]/index.php/component/oauth/?view=oauth&task=authenticate&response_type=code&client_id=[clientId]&client_secret=[clientSecret]
	 * 
	 * @method requesting access_token with authorize token
	 * If the device have the authorize_token, exchange for access_token to use access
	 * Callback:
	 * [offiria]/index.php/component/oauth/?view=oauth&task=authenticate&grant_type=authorization_code&client_id=[clientId]&client_secret=[clientSecret]&code=[code]
	 *
	 * @method requesting access_token with password (skipping authorization)
	 * Callback:
	 * [offiria]/index.php/component/oauth/?view=oauth&task=authenticate&grant_type=password
	 				&client_id=[clientId]&client_secret=[clientSecret]&username=[username]&pass=[pass]&redirect_uri=[redirect_uri]
	 */
	public function authenticate() {
		$oauth = new PDOOAuth2();
		$responseType = JRequest::getVar('response_type');
		$clientId = JRequest::getVar('client_id');
		$clientSecret = JRequest::getVar('client_secret');
		$table = JTable::getInstance('Token', 'OAuthTable');
		if ($responseType == 'code') {
			header("Content-Type: application/json");
			header("Cache-Control: no-store");
			$code = $table->getParam('code', array(
												  'client_id' => $clientId,
												  'client_secret' => $clientSecret
												  ));
			echo json_encode(array('code'=>$code));
			exit;
			break;
		}
		else {
			switch(JRequest::getVar('grant_type')) {
			case 'password':
				$app = JFactory::getApplication();

				// Get the log in credentials.
				$credentials = array();
				$credentials['username'] = JRequest::getVar('username');
				$credentials['password'] = JRequest::getVar('pass');

				// Get the log in options.
				$options = array();
				$options['remember'] = false;
				$options['return'] = '';

				header("Content-Type: application/json");
				header("Cache-Control: no-store");

				// error handler
				if (!JRequest::getVar('redirect_uri')) {
					echo json_encode(array('error'=>'invalid_redirect_uri'));
					exit;
				}

				// Perform the log in.
				if (true === $app->login($credentials, $options)) {
					// Success
					$url = JRoute::_('/index.php/component/profile/?view=edit&task=applications&client_id='.JRequest::getVar('client_id').
									 '&client_secret='.JRequest::getVar('client_secret').'&redirect_uri='.JRequest::getVar('redirect_uri').'&silent=true');
					$mainframe = JFactory::getApplication();
					$mainframe->redirect($url);
				}
				break;
			default:
				$oauth->grantAccessToken();
			}
			exit;
		}
		exit;
	}
}