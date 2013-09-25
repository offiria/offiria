<?php
/**
 * @version     1.0.0
 * @package     Offiria
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
jimport('joomla.xfactory');

require_once(JPATH_COMPONENT . DS . 'factory.php');

class ProfileControllerDisplay extends JController
{
	/**
	 *
	 */	 	
	public function display($cachable = false, $urlparams = false){
		$my = JXFactory::getUser();
		$username	= JRequest::getVar('user', '');
		$user		= JXFactory::getUser($username);
		
		// People need to be able to read the group
		if( !$my->authorise('profile.read', $user) ){
			$app	= JFactory::getApplication();
		    $app->enqueueMessage( JText::_('JERROR_LOGIN_DENIED' ) , 'error' );
		    return;
		}

		parent::display( null );
	}
		
	public function ajaxGenerateAnalytics()
	{
		$username	= JRequest::getVar('user', '');
		$user		= JXFactory::getUser($username);
		$view		= ProfileFactory::getView('display');
		$html		= $view->generateAnalytics($user);
		
		echo $html;
		exit;
	}
}