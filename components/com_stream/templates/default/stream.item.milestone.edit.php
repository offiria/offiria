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
		<?php
		// TODO: place these in a template once we have a consistent html structure/styling between the post box and edit box
		if($my->isAdmin()) :
			$pinOptions = array(JText::_('COM_STREAM_LABEL_UNPINNED') => '0', JText::_('COM_STREAM_LABEL_FORADAY') => '1 day', JText::_('COM_STREAM_LABEL_FORAWEEK') => '1 week', JText::_('COM_STREAM_LABEL_FORAMONTH') => '1 month');
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
		<button class="btn" type="reset" name="message-edit-cancel"><?php echo JText::_('COM_STREAM_LABEL_CANCEL'); ?></button>
		<button class="btn btn-info submit" type="submit" name="message-edit-save"><?php echo JText::_('COM_STREAM_LABEL_SAVE_CHANGES'); ?></button>
    </div>
    
</form>
</div>