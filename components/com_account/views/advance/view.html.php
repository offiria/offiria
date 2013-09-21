<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
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
	protected $Modules = array();
	
	function display($tpl=null)
	{		
		$this->Modules = array(
			"module_invite_guest" 		=> JText::_('COM_ACCOUNT_LABEL_INVITE_FRIENDS_HERE_DESC'),
			"module_members_birthday" 	=> JText::_('COM_ACCOUNT_LABEL_MEMBERS_BIRTHDAY_DESC'),
			"event_module_attendee" 	=> JText::_('COM_STREAM_LABEL_ATTENDEES_DESC'),
			"file_module_list" 			=> JText::_('COM_STREAM_LABEL_RELATED_FILES_DESC'),
			"file_module_storagestats" 	=> JText::_('COM_STREAM_LABEL_STORAGE_USAGE_DESC'),
			"group_module_eventslist" 	=> JText::_('COM_STREAM_LABEL_UPCOMING_EVENTS_DESC'),
			"group_module_groups" 		=> JText::_('COM_STREAM_LABEL_NEW_GROUP_DESC'),
			"group_module_info" 		=> JText::_('COM_STREAM_LABEL_GROUP_INFO_DESC'),
			"group_module_memberlist" 	=> JText::_('COM_STREAM_LABEL_GROUP_MEMBERS_DESC'),
			"group_module_milestones" 	=> JText::_('COM_STREAM_LABEL_MILESTONES_DESC'),
			"group_module_archive" 		=> JText::_('COM_STREAM_BLOG_ARCHIVE_DESC'),
			"stream_tag_trending" 		=> JText::_('COM_STREAM_LABEL_TRENDING_TAGS_DESC'),
			"todo_module_pending" 		=> JText::_('COM_STREAM_LABEL_PENDING_TASKS_DESC')
			);

		$defaultAdmin	= JXFactory::getUser(42);
		
		$configHelper	= new JXConfig();		
		$crocodocs		= $configHelper->get('crocodocs');
		$crocodocsenable= $configHelper->get('crocodocsenable');
		$scribd_api		= $configHelper->get('scribd_api');
		$scribd_secret	= $configHelper->get('scribd_secret');
		$scribdenable	= $configHelper->get('scribdenable');
		$diffbotenable	= $configHelper->get('diffbotenable');
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
		
		// weather module
		$module_weatherenable = $configHelper->get('module_weatherenable');
		$weather_showcity	= $configHelper->get('weather_showcity');	// default: 1
		$weather_condition	= $configHelper->get('weather_condition');	// default: 1
		$weather_humidity	= $configHelper->get('weather_humidity');	// default: 1
		$weather_wind		= $configHelper->get('weather_wind');		// default: 1
		$weather_forecast	= $configHelper->get('weather_forecast');	// default: 4
		$weather_layout		= $configHelper->get('weather_layout');		// default: block
		$weather_separator	= $configHelper->get('weather_separator');	// default: /
		$weather_tempUnit	= $configHelper->get('weather_tempUnit');  	// default: c
		$weather_useCache	= $configHelper->get('weather_useCache');	// default: 1 enabled
		$weather_cacheTime	= $configHelper->get('weather_cacheTime');	// default: 900
		
	
		// process all enabled/disabled modules
		foreach ($this->Modules as $key => $value) {
			${'module_' . $key} = $configHelper->get('module_' . $key);
		}

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
			$diffbotenable	= $postParam['diffbotenable'];
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

			// weather module
			$module_weatherenable = $postParam['module_weatherenable'];
			$weather_showcity	= $postParam['weather_showcity'];
			$weather_condition	= $postParam['weather_condition'];
			$weather_humidity	= $postParam['weather_humidity'];
			$weather_wind		= $postParam['weather_wind'];
			$weather_forecast	= $postParam['weather_forecast'];
			$weather_layout		= $postParam['weather_layout'];
			$weather_separator	= $postParam['weather_separator'];
			$weather_tempUnit	= $postParam['weather_tempUnit'];
			$weather_useCache	= $postParam['weather_useCache'];
			$weather_cacheTime	= $postParam['weather_cacheTime'];
			
			// process all enabled/disabled modules
			foreach ($this->Modules as $key => $value) {
				${'module_' . $key} = $postParam['module_' . $key];
			}
		}
		
		$this->assignRef('crocodocs', $crocodocs);
		$this->assignRef('crocodocsenable', $crocodocsenable);
		$this->assignRef('scribd_api', $scribd_api);
		$this->assignRef('scribd_secret', $scribd_secret);
		$this->assignRef('scribdenable', $scribdenable);
		$this->assignRef('diffbotenable', $diffbotenable);
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

		// weather module
		$this->assignRef('module_weatherenable', $module_weatherenable);
		$this->assignRef('weather_showcity', $weather_showcity);
		$this->assignRef('weather_condition', $weather_condition);
		$this->assignRef('weather_humidity', $weather_humidity);
		$this->assignRef('weather_wind', $weather_wind);
		$this->assignRef('weather_forecast', $weather_forecast);
		$this->assignRef('weather_layout', $weather_layout);
		$this->assignRef('weather_separator', $weather_separator);
		$this->assignRef('weather_tempUnit', $weather_tempUnit);
		$this->assignRef('weather_useCache', $weather_useCache);
		$this->assignRef('weather_cacheTime', $weather_cacheTime);

		// process all enabled/disabled modules
		foreach ($this->Modules as $key => $value) {
			$this->assignRef('module_' . $key, ${'module_' . $key});
		}
		
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_("COM_ACCOUNT_LABEL_ACCOUNT_ADVANCE_SETTING"));
		$this->addPathway( JText::_('JXLIB_SETTINGS'), JRoute::_('index.php?option=com_account&view=account'));
		$this->addPathway(JText::_('COM_ACCOUNT_LABEL_ACCOUNT_ADVANCE_SETTING'));
		parent::display($tpl);
	}
}