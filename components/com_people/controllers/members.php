<?php
/**
 * @version     1.0.0
 * @package     com_People
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.controller');

class PeopleControllerMembers extends JController
{
	/**
	 *
	 */	 	
	public function display($cachable = false, $urlparams = false){
		
		JRequest::setVar('view', 'members');
		$my = JXFactory::getUser();
		$username	= JRequest::getVar('user', '');
		$user		= JXFactory::getUser($username);
		
		// People need to be able to read the group
		if( !$my->authorise('profile.read', $user) ){
			$app	= JFactory::getApplication();
		    $app->enqueueMessage( JText::_('JERROR_LOGIN_DENIED' ) , 'error' );
		    return;
		}


		parent::display(true);
	}

	public function listUser() {
		$model = PeopleFactory::getModel('members');
		$results = $model->getMembers();
		// filter needed value only
		$vals = array();
		foreach ($results as $k=>$v) {
			$vals[$k]['name'] = $v->name;
			$vals[$k]['username'] = $v->username;
			$vals[$k]['email'] = $v->email;
			$vals[$k]['date_joined'] = $v->registerDate;
			$vals[$k]['avatar'] = $v->getAvatarURL();
		}
		echo json_encode($vals);
		exit;
	}
}
