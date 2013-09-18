<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access
defined('_JEXEC') or die;
jimport('joomla.application.component.view');
jimport('joomla.user.helper');
jimport('joomla.form.form');
jimport('joomla.utilities.xconfig');
jimport('joomla.xfactory');


/**
 * HTML View class for the Account component
 */
class AccountViewAccount extends AccountView
{
	function display($tpl=null)
	{		
		$configHelper		= new JXConfig();
		$my					= JFactory::getUser();
		$defaultLang		= JText::_('JXLIB_DEFAULT_LANGUAGE');
		$defaultTz			= JText::_('JXLIB_DEFAULT_TIMEZONE');
		$defaultInvite		= JXConfig::DISALLOW;
		$defaultAnon		= JXConfig::ALLOW;
		
		// Additional Configuration which are not Joomla! native variables
		$siteName			= ($configHelper->get(JXConfig::SITENAME) == '') ? '' : $configHelper->get(JXConfig::SITENAME);
		$timeZone			= $configHelper->getTimezone();
		$language			= $configHelper->getLanguage();
		$allowAnon			= ($configHelper->get(JXConfig::ALLOW_ANON) == '') ? $defaultAnon : $configHelper->get(JXConfig::ALLOW_ANON);
		$allowInvite		= ($configHelper->get(JXConfig::ALLOW_INVITE) == '') ? $defaultInvite : $configHelper->get(JXConfig::ALLOW_INVITE);
		$limitEmailDomain	= ($configHelper->get(JXConfig::LIMIT_EMAIL_DOMAIN) == '') ? '' : $configHelper->get(JXConfig::LIMIT_EMAIL_DOMAIN);
		$domainName			= ($configHelper->getDomainName(true) == '') ? '' : $configHelper->getDomainName(true);
		
		//overwrite value with postParam when save error
		$error = array();
		if ($_POST)
		{
			$postParam		= JRequest::getVar('params');
			$timeZone		= (isset($postParam['timezone'])) ? $postParam['timezone'] : $timeZone;
			$language		= (isset($postParam['language'])) ? $postParam['language'] : $language;
			$siteName		= (isset($postParam['sitename'])) ? $postParam['sitename'] : $configHelper->get('sitename');
			$allowInvite	= (isset($postParam['allow_invite'])) ? $postParam['allow_invite'] : $allowInvite;
			$limitEmailDomain= (isset($postParam['limit_email_domain'])) ? $postParam['limit_email_domain'] : $limitEmailDomain;
			$domainName		= (isset($postParam['domain_name'])) ? $postParam['domain_name'] : $domainName;
		}
		
		$form = JForm::getInstance('profileForm', JPATH_ROOT.DS.'components'.DS.'com_profile'.DS.'models'.DS.'forms'.DS.'edit.xml');

		$this->assignRef('profileForm', $form);
		$this->assignRef('my', $my);
		$this->assignRef('sitename', $siteName);
		$this->assignRef('default_timezone', $timeZone);
		$this->assignRef('default_language', $language);
		$this->assignRef('domain_name', $domainName);
		
		$this->assignRef('limit_email_domain', $limitEmailDomain);
		$this->assignRef('allow_invite', $allowInvite);
		$this->assignRef('allow_anon', $allowAnon);
		
		$this->assign('domain_editable', $configHelper->allowChangeDomain());
		
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_("COM_ACCOUNT_LABEL_ACCOUNT_SETTING"));
		parent::display($tpl);
	}
}