<?php
$document = JFactory::getDocument();
$docTitle = $document->getTitle();
$groupTitle = StreamTemplate::escape($docTitle);
$task = JRequest::getVar('task');
?>

<div class="group-page-title clearfix">
	<div class="group-page-title-inner pull-left">
		<h1><?php echo $groupTitle; ?></h1>
		
		<?php if($group->access) : ?>
			
			<span class="label label-danger">Private Group</span>

		<?php elseif ($group->archived) : ?>
			
			<span class="label">Archived Group</span>
	
		<?php else : ?>
			
			<span class="label label-info">Public Group</span>
		
		<?php endif; ?>
		
		<div class="clear"></div>
	</div>
	
	<div class="group-module-actions pull-right">
		<form name="group-actions">
			<?php if(!$group->isMember($my->id)) { ?>
				<?php if(!$group->isFollower($my->id)) { ?>
			<a href="#followGroup" class="btn"><?php echo JText::_('COM_STREAM_LABEL_FOLLOW');?></a>
				<?php } else { ?>
			<a href="#unfollowGroup" class="btn"><?php echo JText::_('COM_STREAM_LABEL_UNFOLLOW');?></a>	 
				<?php }  ?>
			<a href="#joinGroup" class="btn btn-info"><?php echo JText::_('COM_STREAM_LABEL_JOIN_GROUP');?></a>
			<?php } else { 
			// if you're already a member, you don't need to follow it right?
			?>
			<a href="#leaveGroup" class="btn btn-warning"><?php echo JText::_('COM_STREAM_LABEL_LEAVE_GROUP');?></a>
			<?php }  ?>
			<input type="hidden" name="group_id" value="<?php echo $group->id; ?>"/>
		</form>
		<div class="clear"></div>
	</div>
</div>

<div class="group-info">
	<!-- <span>←&nbsp;Show all questions</span> -->
	<ul class="group-header-menu clearfix">
		<li <?php if($task == 'show') { echo 'class="active"'; } ?>><a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='.$group->id ); ?>"><?php echo JText::sprintf('COM_STREAM_LABEL_GROUP_NUM_UPDATES',$group->count('update')) ;?></a></li>
		<li <?php if($task == 'show_todos') { echo 'class="active"'; } ?>><a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=show_todos&group_id='.$group->id ); ?>"><?php echo JText::sprintf('COM_STREAM_LABEL_GROUP_NUM_TODOS',$group->count('todo')) ;?></a></li>
		<li <?php if($task == 'show_events') { echo 'class="active"'; } ?>><a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=show_events&group_id='.$group->id ); ?>"><?php echo JText::sprintf('COM_STREAM_LABEL_GROUP_NUM_EVENTS',$group->count('event')) ;?></a></li>
		<li <?php if($task == 'show_milestones') { echo 'class="active"'; } ?>><a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=show_milestones&group_id='.$group->id ); ?>"><?php echo JText::sprintf('COM_STREAM_LABEL_GROUP_NUM_MILESTONES',$group->count('milestone')) ;?></a></li>
		<li <?php if($task == 'show_files') { echo 'class="active"'; } ?>><a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=show_files&group_id='.$group->id ); ?>"><?php echo JText::sprintf('COM_STREAM_LABEL_GROUP_NUM_FILES',$group->count('file')) ;?></a></li>
		
		<?php if($my->authorise('stream.group.edit', $group)) { ?>
		<li class="group-admin-settings">
			<div class="btn-group pull-right">
			<button class="btn dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
			<ul class="dropdown-menu">

				<li class="manage-members"><a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=show_members&group_id='.$group->id);?>" class="add-members-group"><?php echo JText::_('COM_STREAM_LABEL_MANAGE_MEMBERS');?></a></li>

				<li class="edit-group"><a href="#editGroup" onclick="S.groups.create(this);return false;" data-group_id="<?php echo $group->id; ?>"><?php echo JText::_('COM_STREAM_LABEL_EDIT_GROUP');?></a></li>

				<?php if($group->archived){ ?>
				<li class="archive-group"><a href="#archiveGroup" onclick="S.groups.unarchive(this);return false;" data-group_id="<?php echo $group->id; ?>"><?php echo JText::_('COM_STREAM_LABEL_REOPEN_GROUP');?></a></li>
				<?php } else { ?>
				<li class="archive-group"><a href="#archiveGroup" onclick="S.groups.archive(this);return false;" data-group_id="<?php echo $group->id; ?>"><?php echo JText::_('COM_STREAM_LABEL_ARCHIVE_GROUP');?></a></li>
				<?php } ?>

				<li class="delete-group"><a onclick="S.groups.remove(this);return false;"  href="#deleteGroup" data-group_id="<?php echo $group->id; ?>"><?php echo JText::_('COM_STREAM_LABEL_DELETE_GROUP');?></a></li>

			</ul>
			</div>
		</li>
		<?php } ?>
	</ul>
	
	<?php /* if($show_back){ ?>
	
	<div class="prev_next_top">
		<p>
			<a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='.$group->id ); ?>">&larr; <?php echo
			JText::_('COM_STREAM_LABEL_BACK_TO_GROUP'); ?></a>
		</p>
		
		<div class="clear"></div>	
	</div>
	
	<?php } */?>
</div>
