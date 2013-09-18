<?php

/**
 * @package		Offiria
 * @subpackage		Core 
 * @copyright		(C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');
JLoader::register('Notifications', JPATH_ROOT .DS.'components'.DS.'com_profile'.DS.'libraries'.DS.'notification.php');

abstract class StreamNotification 
{
	// Available type:
	// Profile: 'profile_post_comment', 'profile_like_post', 'profile_mention_todo', 'profile_mention_event', 'profile_mention_update', 'profile_mention_milestone'
	// Group: 'group_add_update', 'group_follow', 'group_join', 'group_add_todo', 'group_add_event', 'group_add_milestone'
	// Event: 'event_join'
	// ToDo: 'todo_complete_item'
	// File: 'file_replace_new'
	// Direct Messages: 'direct_message_new'
	static public function trigger()
	{
		$notificationSetting	= new Notifications();

		if (func_num_args() > 0 && $notificationSetting->validNotificationType( func_get_arg(0) ) )
		{
			$funcArgs			= func_get_args();
			$notificationType	= $funcArgs[0];

			if ($notificationSetting->validNotificationType( $notificationType ))
			{
				switch( $notificationType )
				{
					case 'profile_post_comment':
						// funcArgs[1] is JTable $comment (#__stream_comment)
						self::sendCommentAdd( $notificationType, $funcArgs[1] );
						break;

					case 'profile_like_post':
						// funcArgs[1] is JTable $message (#__stream)
						self::sendMessageLike( $notificationType, $funcArgs[1] );
						break;

					case 'profile_mention_update':
					case 'profile_mention_todo':
					case 'profile_mention_event':
					case 'profile_mention_milestone':
						// funcArgs[1] is JTable $message (#__stream)
						// funcArgs[2] is group_id 
						$streamTable	= $funcArgs[1];
						$groupId		= $funcArgs[2];
						// Perform Name Mention Notification Trigger
						$mentionedUserId= StreamMessage::getMentionUserId($streamTable->message);	
						
						if(!empty( $mentionedUserId )) 
						{
							self::sendMentionName( $notificationType, $mentionedUserId, $streamTable, $groupId );
						}
						break;
					
					/*************************************************************************************************
					 * 
					 *  GROUP NOTIFICATION SECTION
					 * 
					 ************************************************************************************************/
					case 'group_join':
					case 'group_follow':
						// funcArgs[1] is JTable $group (#__groups)
						// funcArgs[1] is JTable $actor (#__users)
						$action = explode('_', $notificationType);
						self::sendGroupJoinFollow( $notificationType, $funcArgs[1], $funcArgs[2], $action[1] );
						break;
					case 'group_add_update':
					case 'group_add_todo':
					case 'group_add_event':
					case 'group_add_milestone':
						// funcArgs[1] is JTable $group (#__groups)
						// funcArgs[2] is JTable $stream (#__stream)
						self::sendGroupAddContent( $notificationType, $funcArgs[1], $funcArgs[2] );
						break;

					/*************************************************************************************************
					 * 
					 *  EVENT NOTIFICATION SECTION
					 * 
					 ************************************************************************************************/
					case 'event_join':
						// funcArgs[1] is JTable $todo (#__stream)
						self::sendEventJoin( $notificationType, $funcArgs[1] );
						break;
					/*************************************************************************************************
					 * 
					 *  TODO NOTIFICATION SECTION
					 * 
					 ************************************************************************************************/
					case 'todo_complete_item':
						// funcArgs[1] is JTable $todo (#__stream)
						// funcArgs[2] is the actor (#__users)
						// funcArgs[3] is the index of the item in todo 
						self::sendTodoCompleteItem( $notificationType, $funcArgs[1], $funcArgs[2], $funcArgs[3] );
						break;
					/*************************************************************************************************
					 * 
					 *  FILE NOTIFICATION SECTION
					 * 
					 ************************************************************************************************/
					case 'file_replace_new':
						// funcArgs[1] is JTable $file (#__stream_files)
						self::sendFileReplaceNew( $notificationType, $funcArgs[1], $funcArgs[2] );
						break;

					/*************************************************************************************************
					 *
					 *  DIRECT/PRIVATE MESSAGING
					 *
					 ************************************************************************************************/
					case 'direct_message_new':
						// funcArgs[1] is JTable $group (#__groups)
						// funcArgs[2] is JTable $stream (#__stream)
						self::sendDirectMessageNew( $notificationType, $funcArgs[1], $funcArgs[2] );
						break;
				}
			}
		}
		
		return false;
	}
	
	static public function sendCommentAdd()
	{
		$funcArgs			= func_get_args();
		
		// Get notification type from caller
		$notificationType	= $funcArgs[0];		
		// Get the comment table object from passed in parameter
		$commentTable		= $funcArgs[1];
		$userList			= array();
		$emailList			= array();
		$jConfig			= new JConfig();

		if ($commentTable instanceof JTable)
		{		
			if (self::sentNotificationCheck($notificationType, $commentTable->user_id, $commentTable->id) === false)
			{
				// Get commentor
				$commentor				= JXFactory::getUser( $commentTable->user_id );

				// Get comment's main Stream Message
				$streamTable			= JTable::getInstance( 'Stream' , 'StreamTable' );
				$streamTable->load( $commentTable->stream_id );

				// Get Stream Message Owner
				$streamMessageOwner		= JXFactory::getUser( $streamTable->user_id );
				// Get Wall Post like users
				$likeUser				= $streamTable->likes;
				// Get Wall Post followers users
				$followers				= $streamTable->followers;
				// Get Wall Post all comments to fetch participated users
				$streamModel			= StreamFactory::getModel( 'Stream' );
				$commentRows			= $streamModel->getComments( array('message_id' =>$commentTable->stream_id) );
				foreach ($commentRows as $comment)
				{
					$userList[]			= $comment->user_id;
				}

				$userList[]				= $streamTable->user_id;
				
				// Add Like Users into list
				$userList				= JXUtility::csvMerge(implode(',',$userList), $likeUser);				
				// Add Followers into list
				$userList				= explode(',',JXUtility::csvMerge($userList, $followers));
				
				$userList				= self::groupUserByLanguage($userList, $commentor->id, $notificationType);

				$messageUrl				= StreamMessage::getMessageUri( $commentTable->stream_id );
				$arrParam				= array(
												'streamMessage' => $streamTable,
												'messageComment' => $commentTable,
												'sender' => $commentor,
												'postOwner' => $streamMessageOwner);
				foreach ($userList as $lang => $bccEmail)
				{
					self::loadLanguage($lang);

					// subject and content based on message type (direct messages or comments)
					if($streamTable->type == 'direct') {
						$subject		= JText::sprintf( 'COM_STREAM_NOTIFICATION_PROFILE_POST_DIRECT_SUBJECT', $commentor->name );
						$bodyContent	= self::getHeaderContent( JText::_('COM_STREAM_NOTIFICATION_DIRECT_POST_TITLE'), $messageUrl );
					} else {
						$subject		= JText::sprintf( 'COM_STREAM_NOTIFICATION_PROFILE_POST_COMMENT_SUBJECT', $commentor->name );
						$bodyContent	= self::getHeaderContent( JText::_('COM_STREAM_NOTIFICATION_COMMENT_POST_TITLE'), $messageUrl );
					}

					$bodyContent	.= self::getBodyContent( 'notification.post.comment', $arrParam );
					$bodyContent	.= self::getFooterContent();

					self::sendMail($jConfig->mailfrom, $jConfig->fromname, $jConfig->mailfrom, $subject, $bodyContent, $bccEmail);
				}
			}
		}
		
	}
	
	static public function sendMessageLike()
	{		
		$funcArgs			= func_get_args();
		
		// Get notification type from caller
		$notificationType	= $funcArgs[0];		
		// Get the message table object from passed in parameter
		$messageTable		= $funcArgs[1];
		$jConfig			= new JConfig();
		
		$onlineUser = self::getCurrentOnlineUser();
		if ($messageTable instanceof JTable)
		{	
			// Get actor (current login user)
			$actor					= JXFactory::getUser();
			
			if (self::sentNotificationCheck($notificationType, $actor->id, $messageTable->id) === false)
			{
				// exclude actor from notification list
				if ($actor->id == $messageTable->user_id)
				{
					return false;
				}
				else if (in_array($messageTable->user_id, $onlineUser))
				{
					// excludes user who are currently online from receiving email notificaion
					return false;
				}

				// Get Recipient (Stream Message Owner) details and notification settings
				$recipient				= JXFactory::getUser( $messageTable->user_id );
				$recipientNotification	= new Notifications( $recipient );

				$likeType = JString::strtoupper($messageTable->type);
				$type = JText::_('COM_STREAM_LABEL_'.$likeType);
				
				if ($messageTable->type == 'update' && intval($messageTable->group_id) > 0 )
				{
					// Check for home wall post or group wall post
					$type =  JText::_('COM_STREAM_LABEL_GROUP_STATUS_UPDATE');
				}

				// if the receipient enables the Email Notification on post comment
				if ($recipientNotification->getEmailNotificationSetting( $notificationType ))
				{			
					self::loadLanguage($recipient->getParam('language'));
					$messageUrl		= StreamMessage::getMessageUri( $messageTable->id );
					$arrParam		= array('streamMessage' => $messageTable,
											'jsConfig' => $jConfig,
											'sender' => $actor,
											'postOwner' => $recipient,
											'messageUrl' => $messageUrl);
					$bodyContent	= self::getHeaderContent( JText::_('COM_STREAM_NOTIFICATION_LABEL_LIKE_NOTIFICATION'), $messageUrl );
					$bodyContent	.= self::getBodyContent( 'notification.like.post', $arrParam );
					$bodyContent	.= self::getFooterContent();
					$subject		= JText::sprintf( 'COM_STREAM_NOTIFICATION_PROFILE_LIKE_POST_SUBJECT', $actor->name, $type );

					self::sendMail($jConfig->mailfrom, $jConfig->fromname, $recipient->email, $subject, $bodyContent);
				}
			}
		}
	}

	static public function sendMentionName()
	{
		$funcArgs			= func_get_args();
		
		// Set the notification type
		$notification		= $funcArgs[0];
		// Get the array of usernames from passed in parameter
		$userNames			= $funcArgs[1];
		// Get the stream message 
		$messageTable		= $funcArgs[2];
		// Get the group id
		$groupId			= $funcArgs[3];
		// Get the actor
		$actor				= JXFactory::getUser();
		$emailList			= array();
		$jConfig			= new JConfig();
		
		$onlineUser = self::getCurrentOnlineUser();
		if (self::sentNotificationCheck($notification, $actor->id, $messageTable->id) === false)
		{
			$mentionType	= JString::strtolower($messageTable->type);
			$type			= JText::_('COM_STREAM_LABEL_'.$mentionType);
			if ($messageTable->type == 'update' && intval($groupId) > 0 )
			{
				// Check for home wall post or group wall post
				$type =  JText::_('COM_STREAM_LABEL_GROUP_STATUS_UPDATE');
			}
			
			if (is_array( $userNames ))
			{

				foreach( $userNames as $username)
				{
					$mentionedUserId = $username[3];
					// exclude actor from notification list
					if ($actor->id == $mentionedUserId || empty($mentionedUserId))
					{
						continue;
					}
					else if (in_array($mentionedUserId, $onlineUser))
					{
						// excludes user who are currently online from receiving email notificaion
						continue;
					}

					// Get mentioned users
					$mentionedUser			= JXFactory::getUser( $mentionedUserId );

					// Get Recipient (Stream Message Owner) details and notification settings
					$recipientNotification	= new Notifications( $mentionedUser );

					// if the receipient enables the Email Notification on post comment
					if ($recipientNotification->getEmailNotificationSetting( $notification ))
					{
						self::loadLanguage($mentionedUser->getParam('language'));
						$messageUrl		= StreamMessage::getMessageUri( $messageTable->id );
						$arrParam		= array('streamMessage' => $messageTable,
												'jsConfig' => $jConfig,
												'sender' => $actor,
												'postOwner' => $actor,
												'messageUrl' => $messageUrl,
												'recipient' => $mentionedUser,
												'type' => $type);

						$bodyContent	= self::getHeaderContent( JText::_('COM_STREAM_NOTIFICATION_LABEL_MENTIONED_NAME_NOTIFICATION'), $messageUrl );
						$bodyContent	.= self::getBodyContent( 'notification.mention.name', $arrParam );
						$bodyContent	.= self::getFooterContent();
						$subject		= JText::sprintf( 'COM_STREAM_NOTIFICATION_PROFILE_MENTIONED_NAME_SUBJECT', $actor->name, $type );					
						self::sendMail($jConfig->mailfrom, $jConfig->fromname, $mentionedUser->email, $subject, $bodyContent);				
					}
				}
			}
		}
	}
	
	static public function sendGroupJoinFollow()
	{	
		$funcArgs			= func_get_args();
		
		// Get notification type from caller
		$notificationType	= $funcArgs[0];
		// Get the group table object from passed in parameter
		$groupTable	= $funcArgs[1];
		// Get actor (current login user)
		$actor		= $funcArgs[2];
		
		$action		= (isset($funcArgs[3]) && JString::strtoupper($funcArgs[3]) == 'JOIN') ? 'JOIN' : 'FOLLOW';

		if ($groupTable instanceof JTable)
		{							
			// exclude actor from notification list
			if ($actor->id == $groupTable->creator)
			{
				return false;
			}			
			
			if (self::sentNotificationCheck($notificationType, $actor->id, $groupTable->id) === false)
			{			
				$groupMembers			= $groupTable->get('members');
				$groupFollowers			= $groupTable->get('followers');

				$processUserList		= explode(',', JXUtility::csvMerge($groupMembers, $groupFollowers));
				
				$userList = self::groupUserByLanguage($processUserList, $actor->id, $notificationType);
				$jConfig		= new JConfig();
				$messageUrl		= StreamMessage::getGroupUri( $groupTable->id );
				$arrParam		= array('actor' => $actor, 
										'contentName' => JText::sprintf('COM_STREAM_NOTIFICATION_GROUP_'.$action.'_CONTENT', $groupTable->name),
										'date' => date('Y-m-d h:i:s'));
				
				foreach ($userList as $lang => $bccEmail)
				{
					self::loadLanguage($lang);
					$bodyContent	= self::getHeaderContent( JText::_('COM_STREAM_NOTIFICATION_GROUP_'.$action.'_TITLE'), $messageUrl );
					$bodyContent	.= self::getBodyContent( 'notification.group.join', $arrParam );
					$bodyContent	.= self::getFooterContent();
					$subject		= JText::sprintf( 'COM_STREAM_NOTIFICATION_GROUP_'.$action.'_SUBJECT', $actor->name, $groupTable->name );
					self::sendMail($jConfig->mailfrom, $jConfig->fromname, $jConfig->mailfrom, $subject, $bodyContent, $bccEmail);
				}
			}
		}
	}

	static public function sendGroupAddContent()
	{
		$funcArgs			= func_get_args();
		
		// Get notification type from caller
		$notificationType	= $funcArgs[0];
		// Get the group table object from passed in parameter
		$groupTable	= $funcArgs[1];
		// Get the stream object from passed in parameter
		$streamTable	= $funcArgs[2];
		// Get actor (current login user)
		$actor		= JXFactory::getUser();		// e.g: milestone, todo, event
		
		$contentType = JString::strtoupper($streamTable->type);
		$template	= ($contentType == 'UPDATE') ? 'notification.group.wall.post' : 'notification.group.join';

		if ($groupTable instanceof JTable)
		{				
			if (self::sentNotificationCheck($notificationType, $actor->id, $streamTable->id) === false)
			{									
				// Process Members and Followers to merged into UserList 
				$groupMembers			= $groupTable->get('members');
				$groupFollowers			= $groupTable->get('followers');

				$processUserList		= explode(',', JXUtility::csvMerge($groupMembers, $groupFollowers));
				
				$userList = self::groupUserByLanguage($processUserList, $actor->id, $notificationType);

				$jConfig		= new JConfig();
				$messageUrl		= StreamMessage::getMessageUri( $streamTable->id );
				$arrParam		= array('actor' => $actor, 
										'contentName' => JText::sprintf('COM_STREAM_NOTIFICATION_GROUP_ADD_'. $contentType .'_CONTENT', $streamTable->message, $groupTable->name),
										'date' => $streamTable->created,
										'messageTable' => $streamTable);
				
				foreach ($userList as $lang => $bccEmail)
				{
					self::loadLanguage($lang);
					$bodyContent	= self::getHeaderContent( JText::_('COM_STREAM_NOTIFICATION_GROUP_ADD_'. $contentType .'_TITLE'), $messageUrl );
					
					$bodyContent	.= self::getBodyContent( $template, $arrParam );
					$bodyContent	.= self::getFooterContent();
					$subject		= JText::sprintf( 'COM_STREAM_NOTIFICATION_GROUP_ADD_'. $contentType .'_SUBJECT', $actor->name, $groupTable->name );
					self::sendMail($jConfig->mailfrom, $jConfig->fromname, $jConfig->mailfrom, $subject, $bodyContent, $bccEmail);
				}
			}
		}
	}

	static public function sendEventJoin()
	{
		$funcArgs			= func_get_args();
		
		// Get notification type from caller
		$notificationType	= $funcArgs[0];
		// Get the event table object from passed in parameter
		$eventTable	= $funcArgs[1];
		// Get actor (current login user)
		$actor		= JXFactory::getUser();

		if ($eventTable instanceof JTable)
		{			
			if (true)//(self::sentNotificationCheck($notificationType, $actor->id, $eventTable->id) === false)
			{						
				$toSendList		= explode(',', $eventTable->get('followers'));
				// Add Event creator to toSendList
				$toSendList[]	= $eventTable->user_id;
				$toSendList		= array_unique($toSendList);
			
				$userList = self::groupUserByLanguage($toSendList, $actor->id, $notificationType);

				$jConfig		= new JConfig();
				$messageUrl		= StreamMessage::getMessageUri( $eventTable->id );
				$arrParam		= array('actor' => $actor, 
										'contentName' => JText::sprintf('COM_STREAM_NOTIFICATION_JOIN_EVENT_CONTENT', $eventTable->message),
										'eventTable' => $eventTable);
				
				foreach($userList as $lang => $bccEmail)
				{
					self::loadLanguage($lang);
					$bodyContent	= self::getHeaderContent( JText::_('COM_STREAM_NOTIFICATION_EVENT_JOIN_TITLE'), $messageUrl );
					$bodyContent	.= self::getBodyContent( 'notification.event.join', $arrParam );
					$bodyContent	.= self::getFooterContent();
					$subject		= JText::sprintf( 'COM_STREAM_NOTIFICATION_EVENT_JOIN_SUBJECT', $actor->name );
					self::sendMail($jConfig->mailfrom, $jConfig->fromname, $jConfig->mailfrom, $subject, $bodyContent, $bccEmail);
				}
			}
		}
	}
		
	static public function sendTodoCompleteItem()
	{
		$funcArgs			= func_get_args();
		
		// Get notification type from caller
		$notificationType	= $funcArgs[0];
		// Get the todo table object from passed in parameter
		$todoTable			= $funcArgs[1];
		// Get actor (current login user)
		$actor				= $funcArgs[2];
		// Get index of the item completed in todo
		$itemIndex			= $funcArgs[3];

		if ($todoTable instanceof JTable)
		{												
			// exclude actor from notification list
			if ($actor->id == $todoTable->user_id)
			{
				return false;
			}
					
			if (self::sentNotificationCheck($notificationType, $actor->id, $todoTable->id) === false)
			{	
				// Get Recipient (Todo Owner) details and notification settings
				$recipient				= JXFactory::getUser( $todoTable->user_id );
				$recipientNotification	= new Notifications( $recipient );

				// if the receipient enables the Email Notification on post comment
				if ($recipientNotification->getEmailNotificationSetting( $notificationType ))
				{
					self::loadLanguage($recipient->getParam('language'));
					$jConfig		= new JConfig();
					$messageUrl		= StreamMessage::getMessageUri( $todoTable->id );
					$todoList		= json_decode($todoTable->raw);
					$itemCompleted	= $todoList->todo[$itemIndex];
					$arrParam		= array('actor' => $actor, 
											'todoName' => $todoTable->message,
											'todoItem' => $itemCompleted,
											'todoTable' => $todoTable,
											'recipient' => $recipient);
					$bodyContent	= self::getHeaderContent( JText::_('COM_STREAM_NOTIFICATION_TODO_COMPLETE_TITLE'), $messageUrl );
					$bodyContent	.= self::getBodyContent( 'notification.todo.complete', $arrParam );
					$bodyContent	.= self::getFooterContent();
					$subject		= JText::sprintf( 'COM_STREAM_NOTIFICATION_TODO_COMPLETE_SUBJECT', $actor->name );

					self::sendMail($jConfig->mailfrom, $jConfig->fromname, $recipient->email, $subject, $bodyContent);
				}
			}
		}
	}
	
	// This notification does not require sentNotificationCheck 
	// to avoid same file being replace multiple times but only the first time is sent
	static public function sendFileReplaceNew()
	{
		$funcArgs			= func_get_args();
		
		// Get notification type from caller
		$notificationType	= $funcArgs[0];		
		// Get the file table object from passed in parameter
		$fileTable			= $funcArgs[1];	
		// Get the previous filename
		$previousFilename	= $funcArgs[2];

		if ($fileTable instanceof JTable)
		{		
			// Get commentor
			$owner		= JXFactory::getUser( $fileTable->user_id );

			// Get Wall Post followers users
			$followers	= $fileTable->followers;
			$userList	= explode(',', $followers);
			
			$userList = self::groupUserByLanguage($userList, $fileTable->user_id, $notificationType);
			
			$messageUrl		= StreamMessage::getMessageUri( $fileTable->stream_id );
			$arrParam		= array(
								'fileTable' => $fileTable,
								'previousFilename' => $previousFilename,
								'owner' => $owner);
			$jConfig		= new JConfig();
			
			foreach ($userList as $language => $bccEmail)
			{
				self::loadLanguage($language);
				$bodyContent	= self::getHeaderContent( JText::_('COM_STREAM_NOTIFICATION_FILE_REPLACE_NEW'), $messageUrl );
				$bodyContent	.= self::getBodyContent( 'notification.file.replace', $arrParam );
				$bodyContent	.= self::getFooterContent();
				$subject		= JText::sprintf( 'COM_STREAM_NOTIFICATION_FILE_REPLACE_NEW_SUBJECT', $owner->name );
				self::sendMail($jConfig->mailfrom, $jConfig->fromname, $jConfig->mailfrom, $subject, $bodyContent, $bccEmail);
			}
			
		}
		
	}

	// Direct/private message
	static public function sendDirectMessageNew()
	{
		$funcArgs = func_get_args();

		// Get notification type from caller
		$notificationType = $funcArgs[0];
		// Get the group table object from passed in parameter
		$groupTable = $funcArgs[1];
		// Get the stream object from passed in parameter
		$streamTable = $funcArgs[2];
		// Get actor (current login user)
		$actor = JXFactory::getUser();

		if ($groupTable instanceof JTable) 
		{
			if (self::sentNotificationCheck($notificationType, $actor->id, $streamTable->id) === false) 
			{
				// Process Members and Followers to merged into UserList
				$groupMembers = $groupTable->get('members');
				$groupFollowers = $groupTable->get('followers');

				$processUserList = explode(',', JXUtility::csvMerge($groupMembers, $groupFollowers));
				
				$userList = self::groupUserByLanguage($processUserList, $actor->id, $notificationType);

				$messageUrl		= '#';
				$arrParam		= array(
					'streamMessage' => $streamTable,
					'sender' => $actor);
				$jConfig		= new JConfig();
				
				foreach ($userList as $lang => $bccEmail) 
				{
					self::loadLanguage($lang);
					$subject		= JText::sprintf( 'COM_STREAM_NOTIFICATION_PROFILE_POST_DIRECT_SUBJECT', $actor->name );
					$bodyContent	= self::getHeaderContent( JText::_('COM_STREAM_NOTIFICATION_DIRECT_POST_TITLE'), $messageUrl );
					$bodyContent	.= self::getBodyContent( 'notification.direct', $arrParam );
					$bodyContent	.= self::getFooterContent();
					self::sendMail($jConfig->mailfrom, $jConfig->fromname, $jConfig->mailfrom, $subject, $bodyContent, $bccEmail);
				}
			}
		}
	}
	
	static public function sendMail($mailFrom, $fromName, $mailTo, $subject, $bodyContent, $bccList='')
	{
		$my = JXFactory::getUser();
		self::loadLanguage($my);
		
		$status = true;
		$mailer	= JFactory::getMailer();
		
		ob_start();
		// the true argument indicates content is in HTML format
		$mailer->sendMail($mailFrom, $fromName, $mailTo, $subject, $bodyContent, true, '', $bccList);
		$errorMsg = ob_get_contents();					
		ob_end_clean();
		
		if (!empty($errorMsg))
		{			
			JLog::add($errorMsg.' Fail sending to '.$mailTo, JLog::ERROR, 'com_stream');
			$status = false;
		}
		
		return $status;
	}
	
	static public function getHeaderContent( $notificationTitle = 'Notification', $messageUrl = '' )
	{                    
		$messageUrl = (empty($messageUrl)) ? JURI::base() : $messageUrl;
		
		$tmpl = new StreamTemplate();
		$tmpl->set('notificationTitle', $notificationTitle);
		$tmpl->set('messageUrl', $messageUrl);
		$html = $tmpl->fetch( 'notification.header' );			
		
		return $html;
	}
	
	static public function getBodyContent( $templateName, $arrayParam = array() )
	{
		if ( !empty($templateName) )
		{                              
			$tmpl = new StreamTemplate();
			foreach($arrayParam as $index => $value)
			{
				$tmpl->set($index, $value);
			}
			$tmpl->set('linkOption', array('fullUri' => true));
			$tmpl->set('formatDateLong', JXDate::LONG_DATE_FORMAT);
			$tmpl->set('formatDateShort', JXDate::SHORT_DATE_FORMAT);
			$html = $tmpl->fetch( $templateName );			
			return $html;
		}
		
		return '';
	}
	
	static public function getFooterContent()
	{                    
		$jsConfig = new JConfig();
		$tmpl = new StreamTemplate();
		$tmpl->set('jsConfig', $jsConfig);
		$html = $tmpl->fetch( 'notification.footer' );			
		return $html;
	}
	
	/*
	 * string: notificationType - profile_mention_update, group_add_update, event_join, etc
	 * int: userid - actor's id
	 * int:	subjectid - #__stream table id, #__group table id
	 */
	static private function sentNotificationCheck($notificationType, $userid, $subjectid)
	{
		$prefix		= 'email_noti_';
		$name		= $prefix.$notificationType.'.'.intval($userid).'.'.intval($subjectid);
		$session	= JFactory::getSession();
		$exists		= $session->get($name, false);
		
		if ( $exists === false )
		{
			$session->set($name, true);
		}
		
		// return false if no such notification sent, else return true
		return $exists;
	}

	/**
	 * This is used to send the user inactivity mail if the user havent been active
	 * @param int user_id id of the targeted user 
	 * @param String $template template used to send the reminder
	 * 		(options): Reminder
	 * 				   Fresh // is for the new user
	 */
	static public function sendReminder($user_id, $template) {
		$jConfig = new JConfig();
		$recipient = JXFactory::getUser( $user_id );
		/* fresh is template for user who have not posted anything */
		if ($template == 'fresh') {
			$subject = JText::_('COM_STREAM_NOTIFICATION_REMINDER_FRESH_TITLE');
			$content = JText::sprintf('COM_STREAM_NOTIFICATION_REMINDER_FRESH_CONTENT', $recipient->name, $jConfig->sitename);
		}
		else {
			$subject = JText::_('COM_STREAM_NOTIFICATION_REMINDER_TITLE');
			$content = JText::sprintf('COM_STREAM_NOTIFICATION_REMINDER_CONTENT', $recipient->name,$jConfig->sitename);
		}

		$html = self::getHeaderContent($subject);

		$arrParam = array('content'=>$content);
		$html .= self::getBodyContent( 'notification.reminder', $arrParam);
		$html .= self::getFooterContent();

		self::sendMail($jConfig->mailfrom, $jConfig->fromname, $recipient->email, $subject, $html);
	}

	static function loadLanguage($language = 'default')
	{
		// Load language file
		$lang			= JFactory::getLanguage();
		$config			= new JXConfig();
		// First load account setting language (if any) to override joomla! language
		$defLanguage	= ($config->getLanguage() != '') ? $config->getLanguage() : $lang->get('default');
		/*
		// Second load user personal language (if any) to override default language
		$siteLanguage	= (intval($user->id) > 0 && $user->getParam('language') != '') ? $user->getParam('language') : $defLanguage;
		*/
		$siteLanguage	= (empty($language) || $language == 'default') ? $defLanguage : $language;
		$lang->setLanguage($siteLanguage);
		$result = $lang->load('lib_xjoomla', JPATH_BASE, $siteLanguage, true);
	}
	
	static function groupUserByLanguage($userList = array(), $userToExlude = 0, $notificationType = '')
	{
		$groupedUserList = array();
		$onlineUser = self::getCurrentOnlineUser();
				
		foreach ($userList as $index => $userId)
		{
			if (empty($userId))
			{
				continue;
			}
			
			// exclude explicitly named user ids
			if (intval($userId) == intval($userToExlude))
			{
				continue;
			}
			
			// If user is currently online, exclude him/her from receiving email notification
			if (in_array($userId, $onlineUser))
			{
				continue;
			}
			
			$user = JXFactory::getUser($userId);
			
			// Filter user off if he does not have notification preference checked
			if (!empty($notificationType))
			{
				$notification = new Notifications($user);
				if (!$notification->getEmailNotificationSetting( $notificationType ))
				{
					continue;
				}
			}
			
			$lang = $user->getParam('language', 'default');
			$groupedUserList[$lang][] = $user->email;
		}
		
		/*foreach($groupedUserList as $lang => $arrUsers)
		{
			$groupedUserList[$lang] = implode(',', $arrUsers);
		}*/
		
		return $groupedUserList;
	}
	
	static function getCurrentOnlineUser()
	{
		$db		= JFactory::getDbo();
		$query	= $db->getQuery(true);
		$query->select('a.userid');
		$query->from('#__session AS a');
		$query->where('a.userid != 0');
		$query->where('a.client_id = 0');
		$query->group('a.userid');
		$db->setQuery($query);
		$online = $db->loadResultArray();
		
		/* online user must not consider block user as login */
		$q = "SELECT id FROM ". $db->nameQuote('#__users') ." WHERE block=0";
		$db->setQuery($q);
		/* contains id of active user */
		$active = $db->loadResultArray();
		$online = array_intersect($online, $active);

		return (array) $online;
	}
}