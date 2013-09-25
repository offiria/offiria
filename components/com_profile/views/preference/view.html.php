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
class ProfileViewPreference extends ProfileView
{

	function display($tpl = null)
	{	     
		$document = JFactory::getDocument();
		$document->addScript(JURI::root()."/media/jquery/jquery-1.7.min.js");
		$document->addScript(JURI::root().'components/com_profile/assets/javascript/script.js');
		$user = JXFactory::getUser();

		$this->assignRef('user', $user);

		parent::display($tpl);
	}
}