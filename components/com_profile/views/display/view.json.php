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

jimport('joomla.application.component.view');
jimport('joomla.user.helper');
jimport('joomla.xfactory');

/**
 * HTML View class for the Administrator component
 */
class ProfileViewDisplay extends ProfileView
{

	function display($tpl = null)
	{

		$name	= JRequest::getString( 'user' );
		$userId	= JUserHelper::getUserId( $name );
		$user	= JXFactory::getUser( $userId );
		$lastStatus	= $user->getStatus();
		
		$vals['name'] = $user->get('name');
		$vals['username'] = $user->get('username');
		$vals['designation'] = $user->get('designation');
		$vals['about_me'] = $user->getParam('about_me');
		$vals['skills'] = $user->get('skills');
		$vals['avatar'] = $user->getAvatarURL();
		
		// wrapper
		$results['profile'] = $vals;
		echo json_encode($results);
		exit;
	}
     

     
}