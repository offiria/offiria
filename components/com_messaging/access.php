<?php
// No direct access
defined('_JEXEC') or die;

class MessagingAccess
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
		return $permission;
	}
}