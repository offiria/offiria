<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');
JLoader::register('StreamNotification', JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'notification.php');

class StreamActivity    
{   
	/**
	 * This method is run by cron and will be scanning for inactive users based on their activity
	 * This method is configured for 1 week interval
	 */
   	public function scan() {   
		$dbo = JFactory::getDbo();
		// this will return the id of inactive user based on interval 1 week
		$q = "SELECT user_id, GREATEST(message, blog, groups, events, todo) as last_update " .
			"FROM " . $dbo->quoteName('#__users_activity') . " " .
			"WHERE GREATEST(message, blog, groups, events, todo) " .
			"< NOW() - INTERVAL 1 WEEK";
		$dbo->setQuery($q);

		/* results contains id for the user who has not been active for the past 1 week */
		$results = $dbo->loadObjectList();

		foreach ($results as $inactive) {
			/* if the users havent post anything, treat the user as a fresh user */
			$template = null;
			if ($inactive->last_update == '0000-00-00 00:00:00') {
				$template = 'fresh';
			}
			/* notify the user for inactivity */
			$this->notify($inactive->user_id, $template);
		}
		return true;
	}

	/**
	 * Send the notification template
	 * @param String $user_id id of the notified user
	 * @param $tmpl template to use to remind the user
	 * @return void
	 */
	public function notify($user_id, $tmpl = 'reminder') {
		StreamNotification::sendReminder($user_id, $tmpl);
	}

	/**
	 * This is to keep track of last user activity
	 */
	public function update($user_id, $type) {
		$activity = JTable::getInstance('Activity', 'StreamTable');
		switch ($type) {
			/* change the type value to be writable on activity */
		case 'update': 
			$type = 'message';
			break;
		case 'event':
			$type = 'events';
			break;
		case 'page':
			$type = 'blog';
			break;
		case 'todo':
			$type = 'todo';
			break;
		case 'groups':
			$type = 'groups';
			break;
			/* to avoid the system from throwing errors */
		default: 
			$type = 'message';
		}
		return $activity->update($user_id, $type);
	}
}	
