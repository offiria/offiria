<?php

/**
 * @package	JomSocial
 * @subpackage Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

jimport('joomla.xfactory');
jimport('joomla.html.parameter');

class Notifications
{
	protected $_defaultNotifications;
	protected $_user;
	protected $_notification;
	const LABEL = 'label';
	const EMAIL_PREFIX = 'e_';
	const GLOBAL_PREFIX = 'g_';
	const LANGUAGE_PREFIX = 'COM_PROFILE_NOTIFICATION_LABEL_';
	
	public function __construct( $user = null )
	{
		if (is_null( $user ) || !($user instanceof JUser))
		{
			$this->_user = JXFactory::getUser();
		}
		else
		{
			$this->_user = $user;
		}
		$this->_initializeDefault();
		$this->_prepareDefault( $this->_user->getParameters() );
	}
	
	public function getNotification()
	{
		return $this->_notification;
	}
	
	public function setNotification( $arrParameters )
	{		
		if (is_object( $arrParameters ))
		{
			$arrParameters = JArrayHelper::toObject( $arrParameters );
		}
		
		$jParam = new JParameter(json_encode( $arrParameters ));
		
		$this->_prepareDefault( $jParam );
	}
	
	public function getGlobalNotificationIndex( $notificationType )
	{
		return self::GLOBAL_PREFIX.$notificationType;
	}
	
	public function getEmailNotificationIndex( $notificationType )
	{
		return self::EMAIL_PREFIX.$notificationType;
	}
	
	public function getNotificationTypeLabel( $notificationType )
	{
		$notificationGroup = $this->getNotificationTypeGroup( $notificationType );
		
		if ($this->validNotificationType( $notificationType ))
		{
			return $this->_notification->$notificationGroup->$notificationType->get( self::LABEL );
		}
		
		return JText::_(self::LANGUAGE_PREFIX.strtoupper( $notificationType ));	
	}
	
	public function getGlobalNotificationSetting( $notificationType )			
	{
		$notificationGroup = $this->getNotificationTypeGroup( $notificationType );
		$notification = $this->getGlobalNotificationIndex( $notificationType );
		
		if ($this->validNotificationType( $notificationType ))
		{
			return $this->_notification->$notificationGroup->$notificationType->$notification;
		}
		
		return 0;
	}
	
	public function getEmailNotificationSetting( $notificationType )			
	{
		$notificationGroup = $this->getNotificationTypeGroup( $notificationType );
		$notification = $this->getEmailNotificationIndex( $notificationType );
		
		if ($this->validNotificationType( $notificationType ))
		{
			return $this->_notification->$notificationGroup->$notificationType->$notification;
		}
		
		return 0;
	}
	
	public function validNotificationType( $notificationType )
	{
		$notificationGroup = $this->getNotificationTypeGroup( $notificationType );
		
		if (isset( $this->_notification->$notificationGroup ) && isset( $this->_notification->$notificationGroup->$notificationType ))
		{
			return true;
		}
		
		return false;
	}
	
	private function _prepareDefault( $parameters )
	{
		$this->_notification = new stdClass();
		
		foreach ($this->_defaultNotifications as $notificationGroup => $notificationInfo)
		{
			$arrangedInfo = array();
			foreach ($notificationInfo as $notificationType => $notificationSetting)
			{
				$globalNotification			= $this->getGlobalNotificationIndex( $notificationType );
				$emailNotification			= $this->getEmailNotificationIndex( $notificationType );
				$globalNotificationValue	= $parameters->get( $globalNotification, $notificationSetting[$globalNotification] );
				$emailNotificationValue		= $parameters->get( $emailNotification, $notificationSetting[$emailNotification] );				
				
				$arrangedInfo[$notificationType] = $this->_generateNotificationSetting( $notificationGroup, $notificationType, $globalNotificationValue, $emailNotificationValue );
			}
			$this->_notification->$notificationGroup = JArrayHelper::toObject($arrangedInfo);
		}
	}
	
	private function _initializeDefault()
	{
		// Notification items should have the same prefix eg. 'direct_notification' => array('direct_message_new')
		$notifications = array(	'profile_notification' => array('profile_post_comment', 'profile_like_post', 'profile_mention_update', 
																'profile_mention_todo', 'profile_mention_milestone', 'profile_mention_event'),
								'group_notification' => array('group_add_update', 'group_follow', 'group_join', 'group_add_todo', 'group_add_event', 'group_add_milestone'),
								'event_notification' => array('event_join'),
								'todo_notification' => array('todo_complete_item'),
								'file_notification' => array('file_replace_new'),
								'direct_notification' => array('direct_message_new')
								);
		foreach ($notifications as $notificationGroup => $notificationType)
		{
			$this->_defaultNotifications[$notificationGroup] = array();
			for ($i = 0; $i < count($notificationType); $i++)
			{
				$this->_defaultNotifications[$notificationGroup][$notificationType[$i]] = $this->_generateNotificationSetting( $notificationGroup, $notificationType[$i] );
			}
		}
	}
	
	private function _generateNotificationSetting( $notificationGroup, $notificationType, $notificationGlobalType = '', $notificationEmailType = '' )
	{
		$globalNotification		= $this->getGlobalNotificationIndex( $notificationType );
		$emailNotification		= $this->getEmailNotificationIndex( $notificationType );
		$notificationGlobalType = ($notificationGlobalType === '') ? 0 : $notificationGlobalType;
		$notificationEmailType	= ($notificationEmailType === '') ? 0 : $notificationEmailType;
		$label = $this->getNotificationTypeLabel( $notificationType );
		
		$settingValue = array(
							$globalNotification => $notificationGlobalType, 
							$emailNotification => $notificationEmailType,
							self::LABEL => $label
						);
		
		return $settingValue;
	}
	
	public function getNotificationTypeGroup( $notificationType )
	{
		$postFix	= '_notification';
		$typeArr	= explode( '_', $notificationType );
		$type		= strtolower( $typeArr[0] );
		
		return $type.$postFix;
	}
}
?>
