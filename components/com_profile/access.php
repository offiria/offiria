<?php
/**
 * @version     1.0.0
 * @package     com_profile
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */
 
// No direct access
defined('_JEXEC') or die;


/**
 * profile.read
 * 
 */
class ProfileAccess
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
			case 'read':
				// $asset contains user object to be accessed.
				$user = JXFactory::getUser($userid);
				if($user->getParam('groups_member_limited')) {
					return false;
				}
				//@todo : check for the given 
				break;

			case 'edit':
				break;
			default:
				# code...
				break;
		}

		return $permission;
	}
}