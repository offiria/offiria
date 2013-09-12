<?php
/**
 * @package		JomSocial
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.user.helper');
jimport('joomla.xfactory');

/**
 * HTML View class for the Administrator component
 */
class ProfileViewAvatar extends ProfileView
{

	function display($tpl = null)
	{	     
		$document = JFactory::getDocument();
		$document->addScript(JURI::root()."/media/jquery/jquery-1.7.min.js");
		$document->addScript(JURI::root().'components/com_profile/assets/javascript/script.js');
		$user = JXFactory::getUser();

		$this->assignRef('user', $user);

		$document->setTitle(JText::_('COM_PROFILE_LABEL_PROFILE_AVATAR'));
		$this->addPathway( JText::_('NAVIGATOR_LABEL_PROFILE'), JRoute::_('index.php?option=com_profile&view=edit'));
		$this->addPathway(JText::_('COM_PROFILE_LABEL_PROFILE_AVATAR'));

		parent::display($tpl);
	}
}