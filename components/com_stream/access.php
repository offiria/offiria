<?php
/**
 * @version     1.0.0
 * @package     com_administrator
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */
 
// No direct access
defined('_JEXEC') or die;


class StreamAccess
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
		
		$action = explode('.', $action);
		
		// Ultimately, this is resolved in each individual object, which should 
		// be part of the library
		$socialObjects = array('message', 'direct', 'update', 'todo', 'event', 'milestone', 'setting');
		if(in_array($action[0], $socialObjects))
		{
			// Guest pretty much can't do anything
			if(!$userid){
				return false;
			}
			
			// If current user is an admin, you can pretty much do everything
			$groupIds = JAccess::getGroupsByUser($userid);
			if(in_array(8, $groupIds)){
				return true;
			}
			
			// We will basically call up ->allowXXX function on the $asset itself
			// which, in social-objects, is basically a StreamTable object.
			// StreamTable object in return, will refer to each individual libraries
			// if the allow function doesn't exist in the body
			$func = 'allow'.ucfirst($action[1]);
			$permission = $asset->$func($userid);
		}
		else
		{
			
			// Guest pretty much can't do anything
			if(!$userid){
				return false;
			}
			
			// Anon user can only add comment
			//if($userid == JXUser::ANON_ID && !empty($jconfig->allow_anon)){
			//	return ($action[0] == 'comment' && $action[1] == 'add');
			//s}
			
			// If current user is an admin, you can pretty much do everything
			$groupIds = JAccess::getGroupsByUser($userid);
			if(in_array(8, $groupIds)){
				return true;
			}
			
			// Other system permission. Handle Manually
			switch($action[0])
			{
				case 'comment':
					switch($action[1])
					{
						case 'add': // comment.add
							// anyone can comment
							$permission = true;
							break;
							
						case 'delete': // comment.add
							// only owner can delete
							// @todo: admin can delete too!
							$permission = ($asset->user_id == $userid);
							break;		
					}
					break;
					
				case 'group':
					// Asset would the StreamGroupTable object
					// re-route to object function
					$func = 'allow'.ucfirst($action[1]);
					$permission = $asset->$func($userid);	
					break;
					
				case 'file':
					$func = 'allow'.ucfirst($action[1]);
					$permission = $asset->$func($userid);
					break;
					
			}
		}
		
		return $permission;
	}
}