<?php
/**
 * @version     1.0.0
 * @package     com_administrator
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

// No direct access
defined('_JEXEC') or die;

/**
 * View class for Applications in com_profile
 */
class ProfileViewApplications extends ProfileView
{

	function display($tpl = null)
	{	     
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_PROFILE_LABEL_APPLICATIONS'));
		$this->addPathway( JText::_('NAVIGATOR_LABEL_PROFILE'), JRoute::_('index.php?option=com_profile&view=edit'));
		$this->addPathway(JText::_('COM_PROFILE_LABEL_APPLICATIONS'));
		
		$apps = new Applications();
		// contains the user object
		$rows = $apps->getUserDeviceList();
		foreach ($rows as $row) {
			$row->isAuthorized = $apps->isAuthorized($row->authorized);
		}

		$this->assignRef('userDevices', $rows);
		
		parent::display($tpl);
	}
}