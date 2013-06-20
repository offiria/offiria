<?php
/**
 * @version     1.0.0
 * @package     com_People
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.controller');

class PeopleControllerMembers extends JController
{
	/**
	 *
	 */	 	
	public function display(){
		
		JRequest::setVar('view', 'members');
		parent::display(true);
	}

	public function listUser() {
		$model = AFactory::getModel('members');
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
