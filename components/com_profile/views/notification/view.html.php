<?php
/**
 * @package		Offiria
 * @subpackage	com_profile
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.user.helper');
jimport('joomla.xfactory');
JLoader::register('Notifications', JPATH_ROOT .DS.'components'.DS.'com_profile'.DS.'libraries'.DS.'notification.php');

/**
 * HTML View class for the Administrator component
 */
class ProfileViewNotification extends ProfileView
{

	function display($tpl = null)
	{	     
		$document = JFactory::getDocument();
		$document->addScript(JURI::root()."/media/jquery/jquery-1.7.min.js");
		$document->addScript(JURI::root().'components/com_profile/assets/javascript/script.js');
		
		$notification = new Notifications();
		
		$this->assignRef('notification', $notification);
		$document->setTitle(JText::_('COM_PROFILE_LABEL_NOTIFICATION'));

		parent::display($tpl);
	}
}