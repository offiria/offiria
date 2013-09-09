<?php
require_once(JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'helpers'.DS.'html.php');

$data = $stream->getData();
$files = $stream->getFiles();
$params = $stream->params;
$params = json_decode($params);
?>
<div class="inline-edit">
	
<form name="message-edit">
	<div id="stream-post-event">
	<div class="stream-post-details" id="stream-post-update">
		<textarea name="message"><?php echo $stream->message; ?></textarea>
	</div>

	</div>
	<div class="stream-post-details">
		<div class="stream-post-time" style="border-right: none;">
			<input autocomplete="off" type="text" class="start-date" name="start_date" placeholder="<?php echo JText::_('COM_STREAM_LABEL_DUE_DATE');?>" value="<?php echo substr($stream->start_date, 0, 10); ?>" />
		</div>
		<div class="clear"></div>
	</div>

	<div class="stream-post-details-select">
		<label><?php echo JText::_('COM_STREAM_LABEL_MESSAGE_GROUP_EDIT'); ?></label>
		<select name="group_id">
			<option value="0">Public</option>
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
					  echo '<option ' . $selected . ' value="' . $table->id . '">' . $table->name . '</option>';
				  }
			  }
			?>
		</select>
	</div>
	
	<div class="clear"></div>
	
	<?php include('stream.item.common.uploader.php'); ?>

	<input type="hidden" name="event_type" value="milestone">
	<input type="hidden" name="type" value="<?php echo $stream->type; ?>" />	
	<input type="hidden" name="edit_type" value="<?php echo $edit_type; ?>" />	
	<input type="hidden" name="message_id" value="<?php echo $stream->id; ?>" />
	
	<div class="form-actions">
      <button class="btn btn-primary" type="submit" name="message-edit-save">Save changes</button>
      <button class="btn" type="reset" name="message-edit-cancel">Cancel</button>
    </div>
    
</form>
</div>