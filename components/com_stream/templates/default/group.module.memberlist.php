<div class="moduletable">
	<h3><?php echo $title; ?></h3>
	<?php if (!empty($members)) { ?>
	<ul class="user-info-compact-sidebar clearfix">
		<?php foreach($members as $user) { ?>
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
		<p><?php echo JText::_('COM_STREAM_LABEL_NO_JOINED_MEMBER');?></p>
	</div>
	<?php }

		if(isset($total)) {
	?>
	<span class="bottom-link">
		<?php
		$allLink = JRoute::_('index.php?option=com_stream&view=groups&task=show_members&group_id='.$group->id );
		?>
		<a href="<?php echo $allLink; ?>">
			<?php echo JText::sprintf('Show all %1d members', $total);?>
		</a>
	</span>
		<?php } ?>
</div>