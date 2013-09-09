<div class="moduletable">
	<h3><?php echo JText::_('COM_STREAM_LABEL_ATTENDEE');?></h3>
	<?php if (!empty($attendees)) { ?>
	<ul class="user-info-compact-sidebar">
		<?php foreach($attendees as $user) { ?>
		<li>
			<div class="user-avatar">
				<a href="<?php echo $user->getURL(); ?>"><img class="tips" title="<?php echo $this->escape($user->name); ?>" src="<?php echo $user->getThumbAvatarURL(); ?>"></a>
			</div>
			<div class="clear"></div>
		</li>
		<?php } ?>
	</ul>
	<?php } else { ?>	
	<div class="alert-message block-message info">
		<p><?php echo JText::_('COM_STREAM_LABEL_NO_ATTENDEES_YET');?></p>
	</div>
	<?php } ?>
</div>