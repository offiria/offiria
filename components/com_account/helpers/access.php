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


class AccountAccessHelper
{
	/**
	 * Check user access
	 * 	 	 
	 */	 	
	public function allowEdit($userid)
	{
		// This condition is already available in StreamAccess class
		// added here to eliminate dependency, in case StreamAccess has the condition removed
		$groupIds = JAccess::getGroupsByUser($userid);
		if(in_array(8, $groupIds) || in_array(7, $groupIds)){
			return true;
		}
		
		return false;
	}


	/**
	 * Allow user
	 */
	public static function allowPublicStream($userid) 
	{
		$user = JXFactory::getUser($userid);
		if($user->getParam('groups_member_limited')) {
			return false;
		}
		return true;
	}
}