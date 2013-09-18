<?php
/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');

/**
 * Templating system for JomSocial
 */
class StreamType
{
	protected $data;
	
	/**
	 * Basic message read permission
	 */	 	
	public function allowRead($userid)
	{
		// If private, only the group member can access
		if($this->data->access){
			$my = JXFactory::getUser();
			$userGroups = $my->getParam('groups_member');
			// added condition to allow also siteadmin and superuser to read the private group content
			return (JXUtility::csvExist($userGroups, $this->data->group_id) || $my->isAdmin());
		}
		
		return true;
	}
	
}

interface Streamable {
	function getItemHTML( $format = null);
}