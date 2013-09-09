<?php
$data = $stream->getData();
$files = $stream->getFiles();
$params = $stream->params;
$params = json_decode($params);
?>

<div class="inline-edit">

	<form name="message-edit">	

		<div id="stream-post-update" class="stream-post-details">
			<textarea name="message"><?php echo $stream->message; ?></textarea>
		</div>

		<input type="hidden" name="type" value="<?php echo $stream->type; ?>" />
		<input type="hidden" name="message_id" value="<?php echo $stream->id; ?>" />
		
		<div class="stream-post-details-select">
			 <label><?php echo JText::_('COM_STREAM_LABEL_MESSAGE_GROUP_EDIT'); ?></label>
			<select name="group_id">
			
			<option value="0"><?php echo JText::_('COM_STREAM_LABEL_PUBLIC'); ?></option>
			<?php
			  $my = JXFactory::getUser();
			  $groups = $my->getParam('groups_member');
			  $groups = explode(',', $groups);
			  $table = JTable::getInstance('Group', 'StreamTable');
			  foreach($groups as $group) {
			  	$table->load($group);
				// Skip direct message
			  	if( !empty($table->name)) {
				  	$selected = ($table->id == $stream->group_id) ? 'selected="selected"' : '';
				  	echo '<option ' . $selected . ' value="' . $table->id . '">' . $this->escape($table->name) . '</option>';
			  	}
			  }
			  ?>
			</select>
		</div>
		
		<div class="clear"></div>
		
		<?php include('stream.item.common.uploader.php'); ?>
		
		<div class="form-actions">
			<?php
			// TODO: place these in a template once we have a consistent html structure/styling between the post box and edit box
			if($my->isAdmin()) :
				$pinOptions = array(JText::_('COM_STREAM_LABEL_UNPINNED') => '0', JText::_('COM_STREAM_LABEL_FORADAY') => '1 day', JText::_('COM_STREAM_LABEL_FORAWEEK') => '1 week', JText::_('COM_STREAM_LABEL_FORAWEEK') => '1 month');
			?>
			<div class="pinned-message-action">
				<label><?php echo JText::_('COM_STREAM_LABEL_PINTOTOP'); ?>:</label>
				<select name="pinned">
					<?php foreach($pinOptions as $optionKey => $optionValue) : ?>
					<option value="<?php echo $optionValue; ?>" <?php if(!empty($data->pinned) && $data->pinned == $optionValue) echo 'selected="selected"'; ?>><?php echo $optionKey; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<?php endif; ?>
			<button class="btn btn-primary" type="submit" name="message-edit-save"><?php echo JText::_('COM_STREAM_LABEL_SAVE_CHANGES'); ?></button>
			<button class="btn" type="reset" name="message-edit-cancel"><?php echo JText::_('COM_STREAM_LABEL_CANCEL'); ?></button>
	    </div>
    
	</form>

</div>