<?php
require_once(JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'helpers'.DS.'html.php');
$data = $stream->getData();
$files = $stream->getFiles();

$model = StreamFactory::getModel('events');
$eventDate = $model->determinedEventDuration($stream->start_date, $stream->end_date);

$startDate = $eventDate['startDate'];
$endDate = $eventDate['endDate'];
$startTime = $eventDate['startTime'];
$endTime = $eventDate['endTime'];
?>

<div class="inline-edit">
<form name="message-edit">
	<div id="stream-post-event" class="stream-post-details">
		<textarea name="message"><?php echo $stream->message; ?></textarea>
	</div>
	<div class="stream-post-details">	
		<div class="stream-post-time location">
			<input type="text" placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_WHERE');?>" name="location" class="location bottom-border" autocomplete="off" value="<?php echo $this->escape($stream->location); ?>"/>
			<label>
				<input type="checkbox" class="hide-map" name="hide_map" placeholder="<?php echo JText::_('Hide Map');?>" value="1" <?php echo ($stream->getParam('hide_map') == '1') ? 'checked':'';?> /> <?php echo JText::_('Hide Map');?>
			</label>
		</div>
		
		<div class="stream-post-time bordered">
			<input type="text" placeholder="<?php echo JText::_('COM_STREAM_LABEL_START_DATE');?>" name="start_date" class="start-date" maxlength="10" autocomplete="off" value="<?php echo $startDate->format(JText::_('JXLIB_DATE_FORMAT_EVENT')); ?>"/>
			<span class="add-time">
				<a href="#selectTime" style="display: none"><?php echo JText::_('COM_STREAM_LABEL_START_TIME'); ?></a>
				<select style="display: block" name="start_time" class="start-time">
					<?php echo StreamHtmlHelper::getTimeOptions($startDate); ?>
				</select>
			</span>
		</div>

		<div class="stream-post-time">
			<input autocomplete="off" type="text" class="end-date" name="end_date" placeholder="<?php echo JText::_('COM_STREAM_LABEL_END_DATE');?>" maxlength="10" value="<?php echo $endDate->format(JText::_('JXLIB_DATE_FORMAT_EVENT')); ?>"/>
			<span class="add-time">
				<a href="#selectTime" style="display: <?php echo ($endTime == null) ? 'block' : 'none'; ?>"><?php echo JText::_('COM_STREAM_LABEL_END_TIME'); ?></a>
				<select style="display: <?php echo ($endTime == null) ? 'none' : 'block'; ?>" name="end_time" class="end-time">
					<?php echo StreamHtmlHelper::getTimeOptions($endDate); ?>
				</select>
			</span>
		</div>

		<!-- <input type="text" placeholder="<?php echo JText::_('COM_STREAM_LABEL_START_DATE');?>" name="start_date" class="start-date" autocomplete="off" value="<?php echo $startDate; ?>"/>
		<input autocomplete="off" type="text" class="start-date" name="end_date" placeholder="<?php echo JText::_('COM_STREAM_LABEL_END_DATE');?>" value=""  value="<?php echo $stream->end_date; ?>"/> -->
		<div class="clear"></div>
	</div>
	
	<div class="stream-post-details-select">
		<label>
			<?php echo JText::_('COM_STREAM_LABEL_MESSAGE_GROUP_EDIT'); ?> 
		</label>
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
					echo '<option ' . $selected . ' value="' . $table->id . '">' . $this->escape($table->name) . '</option>';
				}
			}
			?>
		</select>
	</div>
	
	<div class="clear"></div>
	
	<?php include('stream.item.common.uploader.php'); ?>
	
	<input type="hidden" name="type" value="<?php echo $stream->type; ?>" />	
	<input type="hidden" name="message_id" value="<?php echo $stream->id; ?>" />
	
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
			
      <button class="btn btn-primary" type="submit" name="message-edit-save">Save changes</button>
      <button class="btn" type="reset" name="message-edit-cancel">Cancel</button>
    </div>
    
</form>
</div>