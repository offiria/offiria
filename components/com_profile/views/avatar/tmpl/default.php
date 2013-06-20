<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<link rel="stylesheet" type="text/css" href="<?php echo JURI::root(); ?>components/com_profile/assets/imgareaselect/css/imgareaselect-default.css" />
<script type="text/javascript" src="<?php echo JURI::root(); ?>components/com_profile/assets/imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>

<div class="profile-navbar">
<?php echo $this->showNavBar(); ?>
</div>

<div id="profile-avatar">
<?php $avatarExists = ($this->user->getAvatarPath()) ? true : false; ?>
<form method="post" id="avatar-form" action="<?php echo JRoute::_("index.php?option=com_profile&view=edit");?>" enctype="multipart/form-data">
<input type="file" name="avatar" id="avatar-file"/>
<input type="hidden" name="task" value="changeAvatar" />
<input type="submit" class="btn btn-info" value="<?php echo JText::_('COM_PROFILE_LABEL_UPLOAD');?>" />
</form>
</div>
<div class="large-avatar">
	<?php if ($avatarExists) { ?>
	<div class="large-profile-pic">
		<img id="large-profile-pic" src="<?php echo $this->user->getAvatarURL(); ?>"/>
	</div>
	
	<div class="profile-avatar-btn clearfix">
		<div id="edit-thumbnail"><a class="btn" href="javascript:void(0);" onclick="jxProfile.avatar.initImageSelect();"><?php echo JText::_('COM_PROFILE_LABEL_EDIT_THUMBNAIL');?></a></div>
		<div id="stop-thumbnail" style="display:none"><a class="btn btn-warning" href="javascript:void(0);" onclick="jxProfile.avatar.exitImageSelect();"><?php echo JText::_('COM_PROFILE_LABEL_DONE');?></a></div>
		<div id="save-thumbnail" style="display:none"><a class="btn btn-success" href="javascript:void(0);" onclick="jxProfile.avatar.saveThumbnail('<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=ajaxSaveThumbnail');?>');"><?php echo JText::_('COM_PROFILE_LABEL_SAVE_THUMBNAIL');?></a></div>
		<div id="remove-thumbnail"><a class="btn btn-danger" href="javascript:void(0);" onclick="jxProfile.avatar.removeAvatar('<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=ajaxRemoveAvatar');?>');"><?php echo JText::_('COM_PROFILE_LABEL_REMOVE_AVATAR');?></a></div>
		<?php } else { ?>	
		<div class="notification"><?php echo JText::_('COM_PROFILE_AVATAR_MSG_NO_AVATAR_UPLOADED');?></div>	
		<?php } ?>
	</div>
	
	<div class="clear"></div>
</div>

<?php if ($avatarExists) { ?>
<div class="thumbnail-avatar">
	<span><?php echo JText::_('COM_PROFILE_LABEL_AVATAR_THUMBNAIL');?></span>
	<div>
		<img id="thumbnail-profile-pic" src="<?php echo $this->user->getThumbAvatarURL(); ?>" />
	</div>	
</div>
<?php } ?>