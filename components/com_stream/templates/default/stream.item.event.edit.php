<?php
require_once(JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'helpers'.DS.'html.php');
$data = $stream->getData();
$files = $stream->getFiles();

$model = StreamFactory::getModel('events');
$eventDate = $model->determinedEventDuration($stream->start_date, $stream->end_date);

$startDate 	= $eventDate['startDate'];
$endDate 	= $eventDate['endDate'];
$startTime 	= $eventDate['startTime'];
$endTime 	= $eventDate['endTime'];

$rdata		= json_decode($stream->raw);
?>

<div class="inline-edit">
<form name="message-edit">
	<div class="stream-post-details">
		<input type="text" autocomplete="off" class="event-title" name="title" placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_TYPE_YOUR_EVENT_TITLE');?>" value="<?php echo $stream->title; ?>" />
		<div class="clear"></div>
	</div>
	
	<div id="stream-post-event" class="stream-post-details">
		<textarea name="message"><?php echo $stream->message; ?></textarea>
	</div>
	<div class="stream-post-details">	
		<div class="stream-post-time location">
			<input type="text" placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_WHERE');?>" name="location" class="location bottom-border" autocomplete="off" value="<?php echo $this->escape($stream->location); ?>"/>
			<label>
				<input type="checkbox" class="hide-map" name="hide_map" placeholder="<?php echo JText::_('COM_STREAM_LABEL_HIDE_MAP');?>" value="1" <?php echo ($stream->getParam('hide_map') == '1') ? 'checked':'';?> /> <?php echo JText::_('COM_STREAM_LABEL_HIDE_MAP');?>
			</label>
		</div>
		
		<div class="stream-post-time bordered">
			<input type="text" placeholder="<?php echo JText::_('COM_STREAM_LABEL_START_DATE');?>" name="start_date" class="start-date" maxlength="10" autocomplete="off" value="<?php echo $startDate->format(JText::_('Y-m-d')); ?>"/>
			<span class="add-time">
				<a href="#selectTime" style="display: none"><?php echo JText::_('COM_STREAM_LABEL_START_TIME'); ?></a>
				<select style="display: block" name="start_time" class="start-time">
					<?php echo StreamHtmlHelper::getTimeOptions($startDate); ?>
				</select>
			</span>
		</div>

		<div class="stream-post-time">
			<input type="text" placeholder="<?php echo JText::_('COM_STREAM_LABEL_END_DATE');?>" name="end_date" class="end-date" maxlength="10" autocomplete="off" value="<?php echo $endDate->format(JText::_('Y-m-d')); ?>"/>
			<span class="add-time">
				<a href="#selectTime" style="display: <?php echo ($endTime == null) ? 'block' : 'none'; ?>"><?php echo JText::_('COM_STREAM_LABEL_END_TIME'); ?></a>
				<select style="display: <?php echo ($endTime == null) ? 'none' : 'block'; ?>" name="end_time" class="end-time">
					<?php echo StreamHtmlHelper::getTimeOptions($endDate); ?>
				</select>
			</span>
		</div>

		<div class="clear"></div>
	</div>

	<div class="stream-post-details-select">
		<label>
			<?php echo JText::_('COM_STREAM_LABEL_MESSAGE_GROUP_EDIT'); ?> 
		</label>
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
	
	<div class="stream-post-details-select">
		<label>
			<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_CATEGORY_PROMPT'); ?> 
		</label>
		<select name="category_id">
			<option value="0"><?php echo JText::_('COM_STREAM_DEFAULT_LABEL_PAGE_DEFAULT_CATEGORY'); ?></option>
			<?php
			$table = new StreamCategory();
			$events = $table->getEvents();
			foreach ($events as $event) {
				// add category that has been added
				$selected = ($event->id == $rdata->category_id) ? 'selected="selected"' : '';
				echo '<option ' . $selected . ' value="' . $event->id . '">' . $this->escape($event->category) . '</option>';
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