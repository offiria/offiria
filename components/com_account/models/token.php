<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.model');
jimport('joomla.application.component.helper');
jimport('joomla.user.helper');

JTable::addIncludePath(JPATH_ROOT . '/administrator/components/com_account/tables');

/**
 * Model
 */
class AccountModelToken extends JModel
{
	protected $_item;
	protected $_token = null;

     /**
      * Generate token here to standardize the token generation
      * @condition if the user_id is provided it will be assign to the user directly
      * @see assignToken()
      * @return String generated token
      */
     public function generateToken($user_id=null) {
     	$salt = JUserHelper::genRandomPassword(50);
		$crypt = JUserHelper::getCryptedPassword($user_id, $salt);
		$token = $crypt . ':' . $salt;	
		if ($user_id != NULL) {
			if ($this->assignToken($user_id, $token)) {
				return true;
			}
			else {
				return false;
			}
		}
		else {
			return $token;
		}
     }

     /**
      * Compare user token with token inside database
      * @param String token to compare
      * @return BOOL if aunthenticated
      */
     public function authenticateToken($token_id) {
     }

     /**
      * Check if token already exist and assign to the user
      * @param user_id int id of the current user
      * @return BOOL if the token exist
      */
     public function tokenExist($user_id) {
     	$db = & $this->getDbo();
		//try to get associated id from usergroups 
		$query = "SELECT * FROM " . $db->quoteName( '#__users_token' ) . " WHERE `user_id` = '$user_id'";
		$db->setQuery($query);
		if (count($db->loadObjectList()) > 0) {
			return true;			
		}
		return false;
     }

     /**
      * Assign token to user
      * @param user_id int id of the user
      * @return BOOL on success
      */
     public function assignToken($user_id, $token) {
     	$db = & $this->getDbo();
		// create new values if the user does now own a token  
		if (!$this->tokenExist($user_id)) {
     		$q = "INSERT INTO " . $db->quoteName( '#__users_token' ) . " VALUES('$user_id', '$token', NOW())";
     		$db->setQuery($q);
     		return $db->query();
		}
		else {
			return false;
		}
     }	

	/**
	 * Get the data for a banner
	 */
	function &getItem()
	{
		if (!isset($this->_item))
		{
			$cache = JFactory::getCache('com_account', '');

			$id = $this->getState('account.id');

			$this->_item =  $cache->get($id);

			if ($this->_item === false) {
				
                // redirect to banner url
				$db		= $this->getDbo();
				$query	= $db->getQuery(true);
				$query->select(
					'a.*'
					);
				$query->from('#__users as a');
				$query->where('a.id = ' . (int) $id);

				$db->setQuery((string)$query);
				if (!$db->query()) {
					JError::raiseError(500, $db->getErrorMsg());
				}

				$this->_item = $db->loadObject();
				$cache->store($this->_item, $id);
			}
		}
		return $this->_item;
	}

}

