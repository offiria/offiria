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

/**
 * View class for Applications in com_profile
 */
class ProfileViewApplications extends ProfileView
{

	function display($tpl = null)
	{	     
		$document = JFactory::getDocument();
		$document->setTitle(JText::_('COM_PROFILE_LABEL_APPLICATIONS'));
		
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