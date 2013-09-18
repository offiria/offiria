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
class AccountViewAdvance extends AccountView
{
	function display($tpl=null)
	{		
		$defaultAdmin	= JXFactory::getUser(42);
		
		$configHelper	= new JXConfig();		
		$crocodocs		= $configHelper->get('crocodocs');
		$crocodocsenable= $configHelper->get('crocodocsenable');
		$scribd_api		= $configHelper->get('scribd_api');
		$scribd_secret	= $configHelper->get('scribd_secret');
		$scribdenable	= $configHelper->get('scribdenable');
		$diffbot		= $configHelper->get('diffbot');
		
		$mailer			= $configHelper->get('mailer');
		$mailfrom		= $configHelper->get('mailfrom'); // admin email ? 
		$fromname		= $configHelper->get('fromname'); // admin name ?
		$sendmail		= $configHelper->get('sendmail'); // /usr/sbin/sendmail
		$smtpauth		= $configHelper->get('smtpauth');
		$smtpuser		= $configHelper->get('smtpuser');
		$smtppass		= $configHelper->get('smtppass');
		$smtphost		= $configHelper->get('smtphost');
		$smtpsecure		= $configHelper->get('smtpsecure');
		$smtpport		= $configHelper->get('smtpport'); // 25
		//overwrite value with postParam when save error
		$error = array();
		if ($_POST)
		{
			$postParam		= JRequest::getVar('jform');		
			$crocodocs		= $postParam['crocodocs'];
			$crocodocsenable= $postParam['crocodocsenable'];	
			$scribd_api		= $postParam['scribd_api'];
			$scribd_secret	= $postParam['scribd_secret'];
			$scribdenable	= $postParam['scribdenable'];
			$diffbot		= $postParam['diffbot'];		
		
			$mailer			= $postParam['mailer'];
			$mailfrom		= $postParam['mailfrom'];
			$fromname		= $postParam['fromname'];
			$sendmail		= $postParam['sendmail'];
			$smtpauth		= $postParam['smtpauth'];
			$smtpuser		= $postParam['smtpuser'];
			$smtppass		= $postParam['smtppass'];
			$smtphost		= $postParam['smtphost'];
			$smtpsecure		= $postParam['smtpsecure'];
			$smtpport		= $postParam['smtpport'];
		}
		

		$this->assignRef('crocodocs', $crocodocs);
		$this->assignRef('crocodocsenable', $crocodocsenable);
		$this->assignRef('scribd_api', $scribd_api);
		$this->assignRef('scribd_secret', $scribd_secret);
		$this->assignRef('scribdenable', $scribdenable);
		$this->assignRef('diffbot', $diffbot);
		
		$this->assignRef('mailer', $mailer);
		$this->assignRef('mailfrom', $mailfrom);
		$this->assignRef('fromname', $fromname);
		$this->assignRef('sendmail', $sendmail);
		$this->assignRef('smtpauth', $smtpauth);
		$this->assignRef('smtpuser', $smtpuser);
		$this->assignRef('smtppass', $smtppass);
		$this->assignRef('smtphost', $smtphost);
		$this->assignRef('smtpsecure', $smtpsecure);
		$this->assignRef('smtpport', $smtpport);
		
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_("COM_ACCOUNT_LABEL_ACCOUNT_ADVANCE_SETTING"));
		parent::display($tpl);
	}
}