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
jimport('joomla.form.form');
jimport('joomla.utilities.xintegration');

/**
 * HTML View class for the Administrator component
 */
class ProfileViewEdit extends ProfileView
{

	function display($tpl = null)
	{
		$user = JXFactory::getUser();

		$this->assignRef('user', $user);
		// use JForm to create standardize 
		$form = JForm::getInstance('profileForm', JPATH_ROOT.DS.'components'.DS.'com_profile'.DS.'models'.DS.'forms'.DS.'edit.xml');

		$this->assignRef('profileForm', $form);
		$this->assign('userEmail', $user->get('email'));
		$this->assign('userTimezone', $user->getParam('timezone'));
		$this->assign('userLanguage', $user->getParam('language'));
		$this->assign('userAboutMe', $user->getParam('about_me'));
		$this->assign('isIntegration', JXIntegration::isActiveDirectory($user->getParam('integration')));


		/* $this->assignRef('timezoneList', $this->getTimezoneList()); */
		/* /\* defaulted to +8 *\/ */
		/* $jLang = JFactory::getLanguage(); */
		/* $this->assignRef('languageList', $jLang->getKnownLanguages()); */

		$document = JFactory::getDocument();
		$document->addScript(JURI::root().'media/jquery/jquery-1.7.min.js');
		$document->setTitle(JText::_('COM_PROFILE_LABEL_EDIT_PROFILE'));

	    parent::display($tpl);
	}

}