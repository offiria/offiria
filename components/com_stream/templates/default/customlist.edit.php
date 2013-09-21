<?php
if ($customList->id)
	$title = JText::sprintf('COM_STREAM_LABEL_CUSTOMLIST_EDIT');
else {
	$title = JText::sprintf('COM_STREAM_LABEL_CUSTOMLIST_NEW');
}
?>
<div class="modal customlist-modal" id="customlist-edit-message-modal" style="display: none;">
	<div class="modal-header">
		<a class="close" data-dismiss="modal">×</a>
		<h3><?php echo $title; ?></h3>
	</div>
	<form method="post" id="customlist-form" action="<?php echo JRoute::_('index.php?option=com_stream&view=customlist&task=save'); ?>">
		<div class="modal-body">
			<div class="control-group last">
				<label class="control-label" for="title"><?php echo JText::_('COM_STREAM_LABEL_LISTNAME'); ?>:</label>
				
				<div class="controls">
					<input type="text" class="input-xlarge" id="title" name="title" value="<?php echo $this->escape($customList->title); ?>">
				</div>
			</div>
		</div>
		<div class="modal-footer">
			<a class="btn" data-dismiss="modal"><?php echo JText::_('COM_STREAM_LABEL_CANCEL'); ?></a>
			<input type="submit" class="btn btn-info " name="btnSubmit" value="<?php echo JText::_('COM_STREAM_LABEL_SAVE'); ?>">
			<input name="customlist_id" type="hidden" value="<?php echo $customList->id; ?>">
		</div>
	</form>
</div>