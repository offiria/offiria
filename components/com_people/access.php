<?php
/**
 * @version     1.0.0
 * @package     Offiria
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */
 
// No direct access
defined('_JEXEC') or die;


/**
 * people.profiles.list
 * 
 */
class PeopleAccess
{
	/**
	 * Check user access
	 * 
	 * @action string should be in a form of 'objectName.actionName'
	 * @asset mixed	typical an object which is needed to resolve permission	 	 	 
	 */	 	
	static public function check($userid, $action, $asset)
	{
		$permission = true;
		
		switch ($action) {
			case 'profiles.list':
				// Disallow profile listing access for invited user with lmited access
				$user = JXFactory::getUser($userid);
				if($user->getParam('groups_member_limited')) {
					return false;
				}

				break;
			case 'profile.read':
				// $asset contains user object to be accessed.
				$user = JXFactory::getUser($userid);
				if($user->getParam('groups_member_limited')) {
					return false;
				}
				//@todo : check for the given 
				break;

			case 'profile.edit':
				break;
			default:
				# code...
				break;
		}

		return $permission;
	}
}