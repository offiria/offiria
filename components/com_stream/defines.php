<?php

define('STREAM_EDIT_INTERVAL', 5 );
define('GROUP_NAME_LENGTH', 26 ); // Characters

/* Alerts IDs */
define('ALERT_BLOG_INTRO', 			'alt1');
define('ALERT_FILE_INTRO', 			'alt2');
define('ALERT_CALENDAR_INTRO', 		'alt3');
define('ALERT_PRIVATE_MESSAGING',	'alt4');
define('ALERT_INVITE_INTRO',		'alt5');

define("OVERDUE", 1);
define("INCOMPLETE", 2);
define("COMPLETED", 3);

/* Module config options ID => Title */
/* ID has to reflect the file name, dots replaced with underscore */
$GLOBALS['MODULES'] = array(
	"module_invite_guest" 		=> JText::_('COM_ACCOUNT_LABEL_INVITE_FRIENDS_HERE'),
	"module_members_birthday" 	=> JText::_('COM_ACCOUNT_LABEL_MEMBERS_BIRTHDAY_TITLE'),
	"event_module_attendee" 	=> JText::_('COM_STREAM_LABEL_ATTENDEES'),
	"file_module_list" 			=> JText::_('COM_STREAM_LABEL_RELATED_FILES'),
	"file_module_storagestats" 	=> JText::_('COM_STREAM_LABEL_STORAGE_USAGE'),
	"group_module_eventslist" 	=> JText::_('COM_STREAM_LABEL_UPCOMING_EVENTS'),
	"group_module_groups" 		=> JText::_('COM_STREAM_LABEL_NEW_GROUPS'),
	"group_module_info" 		=> JText::_('COM_STREAM_LABEL_GROUP_INFO'),
	"group_module_memberlist" 	=> JText::_('COM_STREAM_LABEL_GROUP_MEMBERS'),
	"group_module_milestones" 	=> JText::_('COM_STREAM_LABEL_MILESTONES'),
	"group_module_archive" 		=> JText::_('COM_STREAM_BLOG_ARCHIVE'),
	"stream_tag_trending" 		=> JText::_('COM_STREAM_LABEL_TRENDING_TAGS'),
	"todo_module_pending" 		=> JText::_('COM_STREAM_LABEL_PENDING_TASKS')
	);
