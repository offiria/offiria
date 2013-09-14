<?php
$data = $stream->getData();
$files = $stream->getFiles();
?>

<div class="inline-edit">
	
<form name="message-edit">
	<div class="stream-post-details">
		<textarea name="message"><?php echo $stream->message; ?></textarea>
	</div>
	
	<div class="stream-post-todo-list">
		<ul>
		<?php
	  	$data = json_decode($stream->raw);
		$date = new JDate($stream->end_date);
	  	$todoIndex = 0;
	  	$states = array();
	  	$doneBy = array();
	  	$doneOn = array();
		foreach( $data->todo as $todo )
		{
			// @todo: move checking to input filtering
			if(!empty($todo)) {
				$s = $stream->getState($todoIndex);
				$db = $stream->getDoneBy($todoIndex);
				$do = $stream->getDoneOn($todoIndex);
				
				$states[] = $s;
				$doneBy[] = $db;
				$doneOn[] = $do;
				?>
				<!--
				<li class="todo-item">
					<a href="#done"></a><span><?php echo $todo; ?></span>
				</li>
				-->
				<li>
					<input done_by="<?php echo $db; ?>" done_on="<?php echo $do; ?>" class="<?php echo $s ? ' todo-done':''; ?>" type="text" value="<?php echo $this->escape($todo); ?>" placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_TYPE_TODO_AND_ENTER');?>" name="todo[]" autocomplete="off">
					<span><a href="#deleteTodo"><?php echo JText::_('COM_STREAM_LABEL_DELETE');?></a></span>
				</li>
				<!-- <img src="<?php echo JURI::root(); ?>components/com_stream/assets/check.png"/> -->
			<?php
				$todoIndex++;
			}
			
		}
		?>
	  	<li>
			<input class="" type="text" value="" placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_TYPE_TODO_AND_ENTER');?>" name="todo[]" autocomplete="off">
			<span><a href="#deleteTodo"><?php echo JText::_('COM_STREAM_LABEL_DELETE');?></a></span>
		</li>
		</ul>
	</div>
	
	<div class="stream-post-details">
		<!--input type="text" autocomplete="off" class="person" name="location" placeholder="<?php //echo JText::_('COM_STREAM_DEFAULT_LABEL_WHO_INCHARGE');?>"-->
		<input type="text" autocomplete="off" class="start-date" name="end_date" placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_DUE');?>" value="<?php echo $date->format(JText::_('JXLIB_DATE_FORMAT_EVENT')); ?>">
		<div class="clear"></div>
	</div>
	
	<div class="stream-post-details-select">
		<?php
		$group_id = $stream->group_id;
		$streamModel = StreamFactory::getModel('stream');
		$milestones = $streamModel->getStream(array('type' => 'milestone', 'group_id'=> $group_id ));
		?>
		<label><?php echo JText::_('COM_STREAM_LABEL_MILESTONE');?>:</label>
		<select name="milestone">
			<option value=""><?php echo JText::_('COM_STREAM_LABEL_TODO_NONE');?></option>
			<?php
			foreach($milestones as $mstone){
				$selected = ($mstone->id == $data->milestone) ? ' selected="selected" ' : ''; 
			?> 
			<option <?php echo $selected; ?> value="<?php echo $mstone->id; ?>"><?php echo $mstone->message; ?></option>
			<?php } ?>
		</select>
	</div>
	<div class="clear"></div>
	
	<?php include('stream.item.common.uploader.php'); ?>
	
	<input type="hidden" name="type" value="<?php echo $stream->type; ?>" />
	<input type="hidden" name="states" value="<?php echo implode(',', $states); ?>" />
	<input type="hidden" name="done_by" value="<?php echo implode(',', $doneBy); ?>" />
	<input type="hidden" name="done_on" value="<?php echo implode(',', $doneOn); ?>" />		
	<input type="hidden" name="message_id" value="<?php echo $stream->id; ?>" />
	
	<div class="form-actions">
		<div class="pinned-message-action">
		<label>
			<?php echo JText::_('COM_STREAM_LABEL_MESSAGE_GROUP_EDIT'); ?> 
		</label>
		<select name="group_id">
			<option value="0"><? echo JText::_('COM_STREAM_LABEL_PUBLIC'); ?></option>
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