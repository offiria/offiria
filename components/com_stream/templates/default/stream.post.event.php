<div class="stream-post-message-share tab-content" id="stream-post-event" style="display:none">
	<textarea id="message-box" name="message" class="stream-post limit-length" style="resize:vertical;" maxlength="1000" cols="63" placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_TYPE_YOUR_EVENT_HERE');?>"></textarea>
	        
	<div class="stream-post-details">		
		<div class="stream-post-time location">
			<input autocomplete="off" type="text" class="location bottom-border" name="location" placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_WHERE');?>" value="" />
			<label>
				<input type="checkbox" class="hide-map" name="hide_map" placeholder="<?php echo JText::_('Hide Map');?>" value="1" /> <?php echo JText::_('Hide Map');?>
			</label>
		</div>
		<div class="clear"></div>
		<div class="stream-post-time">
	    	<input autocomplete="off" type="text" class="start-date" name="start_date" placeholder="<?php echo JText::_('COM_STREAM_LABEL_START_DATE');?>" value="" />
	        <span class="add-time">
	          
<a href="#selectTime"><?php echo JText::_('COM_STREAM_LABEL_START_TIME'); ?></a>
	          <select style="display:none" name="start_time">
	            <?php echo StreamHtmlHelper::getTimeOptions(); ?>
	          </select>
	        </span>
	    </div>
	    <div class="stream-post-time">
	        <input autocomplete="off" type="text" class="end-date" name="end_date" placeholder="<?php echo JText::_('COM_STREAM_LABEL_END_DATE');?>" value="" />
	        <span class="add-time">
	          <a href="#selectTime"><?php echo JText::_('COM_STREAM_LABEL_END_TIME'); ?></a>
	          <select style="display:none"  name="end_time">
	            <?php echo StreamHtmlHelper::getTimeOptions(); ?>
	          </select>
	        </span>
	    </div>
		<div class="clear"></div>
	</div>   
	
	<div class="stream-post-details-milestone" style="display: none;">
	<label><?php echo JText::_('COM_STREAM_DEFAULT_LABEL_CATEGORY_PROMPT'); ?></label>
	<select name="event_category">
		<option><?php echo JText::_('COM_STREAM_DEFAULT_LABEL_PAGE_DEFAULT_CATEGORY'); ?></option>
		<?php
			$table = new StreamCategory();
			$events = $table->getEvents();
			foreach ($events as $event) {
				// add category that has been added
		?>
		<option value="<?php echo $event->id; ?>"><?php echo $event->category; ?></option>
		<?php } ?>
	</select>
	</div>

	<input type="hidden" name="type" value="event">				
</div>