<div class="stream-post-message-share tab-content" id="stream-post-todo" style="display:none">
	<textarea id="message-box" name="message" class="stream-post limit-length" style="resize:vertical;" cols="63" maxlength="1000" placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_WHAT_TODO_LIST_ABOUT');?>"></textarea>
	<div class="stream-post-todo-list">
		<ul>
			<li>
				<input autocomplete="off" type="text" name="todo[]" placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_TYPE_TODO_AND_ENTER');?>" value="" />
				<span><a href="#deleteTodo"><?php echo JText::_('COM_STREAM_LABEL_DELETE');?></a></span>
			</li>
		</ul>
	</div>
	
	<div class="stream-post-details">
		<!--
		<input type="text" placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_WHO_INCHARGE');?>" name="person" class="person" autocomplete="off">
		-->
		<input type="text" value="" placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_DUE');?>" name="end_date" class="end-date" autocomplete="off">
		<div class="clear"></div>
	</div>
	
	<div class="stream-post-details-milestone">
		<label><?php echo JText::_('COM_STREAM_LABEL_MILESTONE');?>:</label>
		<?php echo StreamMilestonesHelper::getSelectList($group_id); ?>
	</div>
	
	<input type="hidden" name="type" value="todo"/>
</div>