<div>
	<div class="user-avatar">
		<img class="tips" title="<?php echo $this->escape($user->name); ?>" src="<?php echo $user->getThumbAvatarURL(); ?>">
	</div>
	<span class="user-name">
		<?php echo StreamTemplate::escape($user->name); ?>
	</span>
</div>
	<?php if($my->authorise('stream.group.edit', $group) && $user->id != $my->id): ?>
	<a class="btn btn-danger pull-right" data-group_id="<?php echo $group->id; ?>" data-user_id="<?php echo $user->id; ?>" onclick="S.groups.memberRemove(this);return false;"><i class="icon-remove icon-white"></i><?php echo JText::_('Remove');?></a>
	<?php endif; ?>
<div class="clear"></div>