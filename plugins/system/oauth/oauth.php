<?php

// no direct access
defined('_JEXEC') or die;

jimport('joomla.xfactory');

require_once( JPATH_ROOT . DS . 'components' . DS . 'com_oauth' . DS . 'factory.php' );

/**
 * System OAuth Plugin
 */
if(!class_exists('plgSystemOAuth'))
{
	class plgSystemOAuth extends JPlugin
	{
		function plgSystemOAuth(& $subject, $config)
		{
			parent::__construct($subject, $config);

			$access_token = JRequest::getVar('access_token');

			// Load language file
			$lang = JFactory::getLanguage();
			$lang->load('lib_xjoomla');

			// if this is repeating access use access token			
			if (JRequest::getVar('access_token')) {
				$apps = OauthFactory::getModel('application');
				if ($apps->isValidAccess($access_token)) {
					$userId = $apps->getTokenBearer($access_token);
					$user = JFactory::getUser($userId);
					$this->userLogin($user);
				}	

			}
		}

		/**
		 * Validate request by checking with access token
		 * If the access token is valid and alive the user assign to the access_token is logged in
		 */
		private function validateAccessToken($access_token) 
		{
		}
		
		/**
		 * This event will be trigger if the session is not set but the token is provided
		 * ----------
		 * User can authenticate via the token and registers the session for Device to retrieve data
		 * This is needed since the browser store the cookie to mark the state as login otherwise reading data from external apps will be directed to login
		 * @param String $token token stored per user
		 */
		private function verify($token, $appId) {
			if (isset($token) && isset($appId)) {
				$model = OauthFactory::getModel('token');
				$apps = OauthFactory::getModel('application');
				/* login to session */
				if ($model->authenticateToken($token, $appId)) {
					$userId = $model->getUserId();
					$user = JFactory::getUser($userId);
					$mainframe = JFactory::getApplication();
						
					// check if the token is expired
					if ($model->isExpires($token)) {
						$apps->removeDevice($appId);
						$mainframe->redirect(JRoute::_('index.php/?option=com_oauth&view=oauth&task=authenticate&appId=' . $appId, false),
						JText::_('PLG_SYSTEM_OAUTH_TOKEN_EXPIRED'), 'Error');
					}
					// check if the token is authorized
					else if (!$model->isAuthorized($token)) {
						$mainframe->redirect(JRoute::_('index.php/?option=com_profile&view=edit&task=applications', false),
						JText::_('PLG_SYSTEM_OAUTH_TOKEN_DEAUTHORIZED'), 'Error');
					}
					// all conditions are met; proceed to login
					else {
						$this->userLogin($user);
					}
				}
				/* re-authenticate device */
				else {
					$mainframe = JFactory::getApplication();
					$mainframe->redirect(JRoute::_('index.php/?option=com_oauth&view=oauth&task=authenticate&appId=' . $appId, false),
					JText::_('PLG_SYSTEM_OAUTH_TOKEN_INVALID'), 'Error');
				}
			}
		}

		/**
		 * This method should handle any logout logic and report back to the subject
		 *
		 * @param	array	$user		Holds the user data.
		 * @param	array	$options	Array holding options (client, ...).
		 *
		 * @return	object	True on success
		 * @since	1.5
		 */
		private function userLogin($user, $options = array()) {
			jimport('joomla.user.helper');
			$instance = $user;
				
			// If _getUser returned an error, then pass it back.
			if (JError::isError($instance)) {
				return false;
			}

			// If the user is blocked, redirect with an error
			if ($instance->get('block') == 1) {
				JError::raiseWarning('SOME_ERROR_CODE', JText::_('JERROR_NOLOGIN_BLOCKED'));
				return false;
			}

			// Authorise the user based on the group information
			if (!isset($options['group'])) {
				$options['group'] = 'USERS';
			}

			// Chek the user can login.
			//$result	= $instance->authorise($options['action']);

			/**
			 * @todo: Make sure the purpose of this check
			 */
			/* if (!$result) { */
			/* 	    JError::raiseWarning(401, JText::_('JERROR_LOGIN_DENIED')); */
			/* 	    return false; */
			/* } */

			// Mark the user as logged in
			$instance->set('guest', 0);

			// Register the needed session variables
			$session = JFactory::getSession();
			$session->set('user', $instance);

			$db = JFactory::getDBO();

			// Check to see the the session already exists.
			$app = JFactory::getApplication();
			$app->checkSession();

			// Update the user related fields for the Joomla sessions table.
			$db->setQuery(
		    'UPDATE `#__session`' .
		    ' SET `guest` = '.$db->quote($instance->get('guest')).',' .
		    '	`username` = '.$db->quote($instance->get('username')).',' .
		    '	`userid` = '.(int) $instance->get('id') .
		    ' WHERE `session_id` = '.$db->quote($session->getId())
			);
			$db->query();

			// Hit the user last visit field
			$instance->setLastVisit();

			return true;
		}
	}
}