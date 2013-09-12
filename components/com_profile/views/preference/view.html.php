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

jimport('joomla.application.component.view');
jimport('joomla.user.helper');
jimport('joomla.xfactory');

/**
 * HTML View class for the Administrator component
 */
class ProfileViewPreference extends ProfileView
{

	function display($tpl = null)
	{	     
		$document = JFactory::getDocument();
		$document->addScript(JURI::root()."/media/jquery/jquery-1.7.min.js");
		$document->addScript(JURI::root().'components/com_profile/assets/javascript/script.js');
		$user = JXFactory::getUser();

		$this->assignRef('user', $user);
		$this->addPathway( JText::_('NAVIGATOR_LABEL_PROFILE'), JRoute::_('index.php?option=com_profile&view=edit'));
		$this->addPathway(JText::_('COM_PROFILE_LABEL_PREFERENCE'));
		
		parent::display($tpl);
	}
}