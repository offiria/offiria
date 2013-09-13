<?php
/**
 * @version     1.0.0
 * @package     com_People
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
require_once(JPATH_ROOT .DS.'libraries'.DS.'joomla'.DS.'xfactory.php');
/**
 * HTML View class for the People component
 */
class PeopleViewMembers extends JView
{
	protected $state;
	protected $item;

	function display($tpl = null)
	{
		$document = JFactory::getDocument();
		$document->addScript(JURI::root().'media/jquery/jquery-1.7.min.js');
		
		/* action form submitted */
		$action = explode('_',JRequest::getVar('action')); //check if there is any user_id to modify
		$lview 	= JRequest::getVar('lview');
		
		if(isset($action[1]) && $action[1] != ''){
			$saved = $this->_save( $action );
//			$session = JFactory::getSession();
//			$session->restart();
			// We have to do 1 redirect to make sure the template is applied
			//$app->redirect($_SERVER['REQUEST_URI']);
			$mainframe = JFactory::getApplication();
			$mainframe->redirect(JRoute::_('index.php?option=com_people&view=members&lview='.$lview), JText::_('COM_PEOPLE_USERGROUP_CHANGED'));
		}

		// alphabet filtering
		$filter = array();
		if( $namefilter = JRequest::getVar('namefilter', '') ){
			$filter['namefilter'] = $namefilter;
		}

		$filterItems = array(
			'all' 	=> JText::_('COM_PEOPLE_FILTER_ALL') ,
			'abc' 	=> JText::_('COM_PEOPLE_FILTER_ABC') ,
			'def'	=> JText::_('COM_PEOPLE_FILTER_DEF') ,
			'ghi'	=> JText::_('COM_PEOPLE_FILTER_GHI') ,
			'jkl'	=> JText::_('COM_PEOPLE_FILTER_JKL') ,
			'mno'	=> JText::_('COM_PEOPLE_FILTER_MNO') ,
			'pqr'	=> JText::_('COM_PEOPLE_FILTER_PQR') ,
			'stu'	=> JText::_('COM_PEOPLE_FILTER_STU') ,
			'vwx'	=> JText::_('COM_PEOPLE_FILTER_VWX') ,
			'yz'	=> JText::_('COM_PEOPLE_FILTER_YZ') ,
			'others'	=> JText::_('COM_PEOPLE_FILTER_OTHERS') ,
		);
		$this->assignRef( 'filterItems', $filterItems );

		// load members
		$userModel = PeopleFactory::getModel('members');
		$members = $userModel->getMembers($filter);
		$this->assignRef( 'members', $members );

		// pagination
		$pagination = $userModel->getPagination();
		$this->assignRef( 'pagination', $pagination );
		$this->addPathway(JText::_('COM_PEOPLE_LABEL_YOUR_MEMBERS'));
        
		parent::display($tpl);
	}
	
	/*
	 * @param : data[array] (0 = actions, 1 = userids) 
	 */
	private function _save($data){
		$action = $data[0];
		$user_arr = explode(',',$data[1]);
		
		$memberModel = PeopleFactory::getModel('members');

		/* only admin are allowed to remove member */
		$my = JXFactory::getUser();
		if (!$my->isAdmin()) {
			$mainframe = JFactory::getApplication();
			$mainframe->enqueueMessage(JText::_('COM_PEOPLE_ACTION_NOT_PERMITTED'), 'error');
			return false;
		}
		
		switch($action){
			case 'activate' :
				$memberModel->activateMembers($user_arr);
				break;
			case 'setadmin' :
				$memberModel->setAdmin($user_arr);
				break;
			case 'unsetadmin' :
				$memberModel->unsetAdmin($user_arr);
				break;
		}
	}

	public function addPathway($text, $link = '')
	{
		// Set pathways
		$mainframe = JFactory::getApplication();
		$pathway = $mainframe->getPathway();

		$pathwayNames = $pathway->getPathwayNames();

		// Test for duplicates before adding the pathway
		if (!in_array($text, $pathwayNames)) {
			$pathway->addItem($text, $link);
		}
	}	
}