<?php
/**
 * @version     1.0.0
 * @package     com_Stream
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

defined('_JEXEC') or die;


require_once(JPATH_ROOT .DS.'libraries'.DS.'joomla'.DS.'xfactory.php');

/**
 *  Output system related javascript which we cannot add to '.js' files
 */	
$my = JXFactory::getUser();
		
// Add expiry header
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600)); // 1 hour
?>

/* Server generated path */
S.path['message.add'] 		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=add'); ?>";
S.path['message.edit'] 		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=edit'); ?>";
S.path['message.save'] 		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=save'); ?>";
S.path['message.delete'] 	= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=delete'); ?>";
S.path['message.filter'] 	= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=filter'); ?>";
S.path['message.load'] 		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=load'); ?>";
S.path['message.fetch'] 	= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=fetch'); ?>";
S.path['message.grab.links']= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=grabLinks'); ?>";
S.path['message.viewed']= "<?php echo JRoute::_('index.php?option=com_stream&view=system&task=messageViewed'); ?>";

S.path['tag.add'] 		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=tagAdd'); ?>";
S.path['tag.delete'] 		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=tagDelete'); ?>";

S.path['tag.autocomplete'] 		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=tagAutocomplete'); ?>";

S.path['people.autocomplete'] 		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=peopleAutocomplete'); ?>";

// START DEV
S.path['people.xautocomplete'] 		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=peoplexAutocomplete'); ?>";
S.path['group.xautocomplete'] 		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=groupxAutocomplete'); ?>";
S.path['tag.xautocomplete'] 		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=tagxAutocomplete'); ?>";
// END DEV

S.path['todo.done'] 		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=action&action=done'); ?>";

S.path['direct.inbox'] 		= "<?php echo JRoute::_('index.php?option=com_stream&view=direct'); ?>";
S.path['direct.compose'] 		= "<?php echo JRoute::_('index.php?option=com_stream&view=direct&task=compose'); ?>";

S.path['comment.add']		= "<?php echo JRoute::_('index.php?option=com_stream&view=comment&task=add'); ?>";
S.path['comment.delete']	= "<?php echo JRoute::_('index.php?option=com_stream&view=comment&task=delete'); ?>";
S.path['comment.showall']	= "<?php echo JRoute::_('index.php?option=com_stream&view=comment&task=showall'); ?>";

S.path['like.like']			= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=like'); ?>";
S.path['like.unlike']		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=unlike'); ?>";
S.path['like.showall']		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=showlikes'); ?>";
S.path['like.comment.like'] = "<?php echo JRoute::_('index.php?option=com_stream&view=comment&task=like'); ?>";
S.path['like.comment.unlike'] = "<?php echo JRoute::_('index.php?option=com_stream&view=comment&task=unlike'); ?>";
S.path['like.comment.showall'] = "<?php echo JRoute::_('index.php?option=com_stream&view=comment&task=showlikes'); ?>";

S.path['event.follow']		= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=follow'); ?>";
S.path['event.unfollow']	= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=unfollow'); ?>";
S.path['event.updateCalendar'] = "<?php echo JRoute::_('index.php?option=com_stream&view=events&task=updateCalendar'); ?>";

S.path['milestone.completed']	= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=setStatus'); ?>";
S.path['milestone.uncompleted']	= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=setStatus'); ?>";
S.path['milestone.updateSelect']= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=updateSelect'); ?>";
S.path['milestone.completeAll']	= "<?php echo JRoute::_('index.php?option=com_stream&view=message&task=action&action=completeTodo'); ?>";

S.path['video.play']		= "<?php echo JRoute::_('index.php?option=com_stream&view=videos&task=play'); ?>";

S.path['group.edit']		= "<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=create'); ?>";
S.path['group.save']		= "<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=save'); ?>";
S.path['group.follow']		= "<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=follow'); ?>";
S.path['group.unfollow']	= "<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=unfollow'); ?>";
S.path['group.join']		= "<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=join'); ?>";
S.path['group.leave']		= "<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=leave'); ?>";
S.path['group.followers']	= "<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=followers'); ?>";
S.path['group.delete']		= "<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=delete'); ?>";
S.path['group.archive']		= "<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=archive'); ?>";
S.path['group.unarchive']		= "<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=unarchive'); ?>";
S.path['group.member.add'] = "<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=memberAdd'); ?>";
S.path['group.member.remove'] = "<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=memberRemove'); ?>";

S.path['customlist.create']		= "<?php echo JRoute::_('index.php?option=com_stream&view=customlist&task=create'); ?>";
S.path['customlist.delete']		= "<?php echo JRoute::_('index.php?option=com_stream&view=customlist&task=delete'); ?>";

S.path['notification.update'] = "<?php echo JRoute::_('index.php?option=com_stream&view=system&task=notification'); ?>";

S.path['system.root']		= "<?php echo JURI::root();?>";
S.path['system.upload']		= "<?php echo JRoute::_('index.php?option=com_stream&view=system&task=upload');?>";
S.path['system.preview']		= "<?php echo JRoute::_('index.php?option=com_stream&view=system&task=preview');?>";                                                                                
S.path['system.search']		= "<?php echo JRoute::_('index.php?option=com_stream&view=system&task=search');?>";
S.path['system.suggest']	= "<?php echo JRoute::_('index.php?option=com_stream&view=system&task=suggest');?>";
S.path['system.query']		= "<?php echo JRoute::_('index.php?option=com_stream&view=system&task=query');?>";
S.path['system.hidewelcome'] = "<?php echo JRoute::_('index.php?option=com_stream&view=system&task=hidewelcome');?>";
S.path['system.hidealert'] = "<?php echo JRoute::_('index.php?option=com_stream&view=system&task=hidealert');?>";

S.path['files.delete']		= "<?php echo JRoute::_('index.php?option=com_stream&view=files&task=delete');?>";
S.path['files.update.file']		= "<?php echo JRoute::_('index.php?option=com_stream&view=files&task=updateFile');?>";
S.path['files.replace.new']		= "<?php echo JRoute::_('index.php?option=com_stream&view=files&task=replaceNew');?>";

/* Private messaging */
S.path['messaging.inbox'] 		= "<?php echo JRoute::_('index.php?option=com_messaging&view=inbox'); ?>";
S.path['messaging.people.autocomplete']	= "<?php echo JRoute::_('index.php?option=com_messaging&task=peopleAutocomplete'); ?>";
S.path['messaging.compose'] 		= "<?php echo JRoute::_('index.php?option=com_messaging&task=addMessage'); ?>";
S.path['messaging.reply'] 		= "<?php echo JRoute::_('index.php?option=com_messaging&task=addReply'); ?>";
S.path['messaging.upload'] 		= "<?php echo JRoute::_('index.php?option=com_messaging&task=upload'); ?>";
S.path['messaging.message.markAsRead'] 		= "<?php echo JRoute::_('index.php?option=com_messaging&task=markAsRead'); ?>";
S.path['messaging.message.markAsUnread'] 		= "<?php echo JRoute::_('index.php?option=com_messaging&task=markAsUnread'); ?>";
S.path['messaging.message.removeFullMessages'] 		= "<?php echo JRoute::_('index.php?option=com_messaging&task=removeFullMessages'); ?>";
S.path['messaging.message.remove'] 		= "<?php echo JRoute::_('index.php?option=com_messaging&task=removeMessage'); ?>";
S.path['messaging.participant.add']	= "<?php echo JRoute::_('index.php?option=com_messaging&task=addParticipant'); ?>";
S.path['messaging.participant.remove']	= "<?php echo JRoute::_('index.php?option=com_messaging&task=removeParticipant'); ?>";

/* Server generated language string */
S.text['test']	= "<?php echo JText::_('test'); ?>";
S.text['edit'] 	= "<?php echo JText::_('COM_STREAM_LABEL_EDIT');?>";
S.text['upload']= "<?php echo JText::_('COM_STREAM_LABEL_UPLOAD'); ?>";

S.text['error.message.add.empty'] 	= "<?php echo JText::_('COM_STREAM_ERRMSG_CANT_POST_EMPTY_MSG');?>";
S.text['error.todo.add.empty'] 		= "<?php echo JText::_('COM_STREAM_ERRMSG_CANT_CREATE_EMPTY_TODO');?>";
S.text['confirm.file.delete'] 	= "<?php echo JText::_('COM_STREAM_MSG_PERMANENTLY_DELETE_FILE');?>";
S.text['confirm.message.delete'] 	= "<?php echo JText::_('COM_STREAM_MSG_PERMANENTLY_DELETE_POST');?>";
S.text['confirm.group.archive'] 	= "<?php echo JText::_('COM_STREAM_MSG_ARCHIVE_GROUP');?>";
S.text['confirm.group.delete'] 		= "<?php echo JText::_('COM_STREAM_MSG_DELETE_GROUP');?>";
S.text['confirm.group.member.remove'] = "<?php echo JText::_('COM_STREAM_MSG_GROUP_MEMBER_REMOVE');?>";
S.text['error.tag.assign.empty'] 		= "<?php echo JText::_('COM_STREAM_ERRMSG_CANT_ASSIGN_EMPTY_TAG');?>";
S.text['error.tag.unsupported.characters'] 		= "<?php echo JText::_('COM_STREAM_ERRMSG_CHARACTERS_UNSUPPORTED');?>";

/* Server generated language string for COM_PROFILE */
S.text['error.avatar.save.fail']	= "<?php echo JText::_('COM_PROFILE_AVATAR_MSG_FAILED_SAVE_THUMBNAIL');?>";
S.text['error.avatar.upload.fail']	= "<?php echo JText::_('COM_PROFILE_AVATAR_MSG_NO_AVATAR_UPLOADED');?>";
S.text['error.avatar.remove.fail']	= "<?php echo JText::_('COM_PROFILE_AVATAR_MSG_FAILED_REMOVE_AVATAR');?>";
S.text['error.upload.empty.file']	= "<?php echo JText::_('COM_PROFILE_AVATAR_MSG_UPLOAD_EMPTY_FILE');?>";

S.text['error.validate.is.empty']	= "<?php echo JText::_('COM_STREAM_VALIDATE_EMPTY_FIELD');?>";
S.text['error.validate.invalid.email']	= "<?php echo JText::_('COM_STREAM_VALIDATE_INVALID_EMAIL');?>";
S.text['error.validate.character.limit']	= "<?php echo JText::_('COM_STREAM_VALIDATE_CHARACTER_LIMIT');?>";
S.text['error.validate.invalid.date']	= "<?php echo JText::_('COM_STREAM_VALIDATE_INVALID_DATE');?>";

S.text['label.new.notification']	= "<?php echo JText::_('COM_STREAM_NOTIFICATION_LABEL_NEW_NOTIFICATION');?>";
S.text['label.no.new.notification']	= "<?php echo JText::_('COM_STREAM_NOTIFICATION_LABEL_NO_NEW_NOTIFICATION');?>";

S.text['label.file.dragdrop'] = "<?php echo JText::_('COM_STREAM_LABEL_FILE_DRAG_AND_DROP');?>";

// Trigger system notification
$(function(){
	// Notification
	S.notification.update();
	S.notification.intervals = setInterval('S.notification.update();', 10000);
});

<?php
exit;