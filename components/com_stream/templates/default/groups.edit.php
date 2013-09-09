<?php
	// get groups be available for selection
	$Category = new StreamCategory();
	// variable cannot be named to groups, will contaminate current $this->group
	$categories = $Category->getGroups();

	if ($group->id)
		$title = JText::sprintf('COM_STREAM_LABEL_EDIT_GROUP');
	else {
		$title = JText::sprintf('COM_STREAM_LABEL_ADD_NEW_GROUP');
		$group->access = 0;
	}
?>

<div class="modal group-modal" id="group-create-message-modal" style="display: none;">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">×</a>
		<h3><?php echo $title; ?></h3>
	</div>
	<div class="modal-body">
		<form name="groups-edit">
			<div class="control-group">
				<label class="control-label" for="title"><?php echo JText::_('COM_STREAM_LABEL_GROUP_NAME');?>:</label>
				
				<div class="controls">
					<input type="text" value="<?php echo $this->escape($group->name); ?>" name="name" class="page-title" autocomplete="off">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="title">Group Description:</label>
				
				<div class="controls">
					<textarea  style="resize:vertical;" name="description" ><?php echo $group->description; ?></textarea>
				</div>
			</div>
			
			<?php if (count($categories) > 0): ?>

			<div class="control-group">
				<label class="control-label"><?php echo JText::_('COM_STREAM_LABEL_GROUP_IN_CATEGORY'); ?>:</label>
				
				<div class="controls">
					<select name="category_id" style="width:120px;">
						<?php
						foreach ($categories as $list) {
							// get current group category
							$isSelected = ($list->id == $group->category_id) ? ' selected="selected" ' : '';
							echo '<option '. $isSelected. 'value="' .$list->id. '">' . $list->category . '</option>';
						}
						?>
					</select>
				</div>
			</div>
			
			<?php endif; ?>
			<?php if ($group->id && !$group->access): ?>
			<!-- <div class="control-group error" style="margin-bottom:0"> -->
			<!-- 	<span class="help-inline"><?php echo JText::_('COM_STREAM_LABEL_CONVERT_TO_PRIVATE_GROUP_WARNING'); ?></span><br /> -->
			<!-- </div> -->
			<?php endif; ?>
			<div class="control-group last">
				<div class="controls">
					<input type="checkbox" name="access" value="1" <?php if($group->access == 1){echo ' checked="checked" '; } ?>>&nbsp;&nbsp;<?php echo JText::_('COM_STREAM_LABEL_PRIVATE_GROUP'); ?>
				</div>
			</div>
			<!--
				<input type="hidden" name="access" value="sfsadf0"> Private
				<div class="btn-group">
					<a href="#" data-toggle="dropdown" class="btn dropdown-toggle">Public <span class="caret"></span></a>
					<ul class="dropdown-menu">
						<li><a href="#" onclick="alert($(this).parents('form').find('input[name=\'access\']').value());return true;"><i class="icon-eye-open"></i> Public</a></li>
						<li><a href="#" onclick="$(this).parents('form').find('input[name=\'access\']').val(1);return true;"><i class="icon-lock"></i> Private</a></li>
					</ul>
				</div>
			-->
			<input name="group_id" type="hidden" value="<?php echo $group->id;?>">
		</form>
	</div>
	<div class="modal-footer">
		<a class="btn" data-dismiss="modal">Cancel</a>
		<a href="#" id="groups-edit-save" class="btn btn-primary" name="groups-edit-save"><?php echo JText::_('COM_STREAM_LABEL_SAVE'); ?></a>
	</div>
</div>