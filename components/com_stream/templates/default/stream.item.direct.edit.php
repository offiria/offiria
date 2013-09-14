<?php
$data = $stream->getData();
$files = $stream->getFiles();
?>

<div class="inline-edit">

	<form name="message-edit">	

		<div id="stream-post-update" class="stream-post-details">
			<textarea name="message"><?php echo $stream->message; ?></textarea>
		</div>

		<input type="hidden" name="type" value="<?php echo $stream->type; ?>" />
		<input type="hidden" name="message_id" value="<?php echo $stream->id; ?>" />
		<?php include('stream.item.common.uploader.php'); ?>
		<div class="form-actions">
			<button class="btn" type="reset" name="message-edit-cancel"><?php echo JText::_('COM_STREAM_LABEL_CANCEL'); ?></button>
			<button class="btn btn-info submit" type="submit" name="message-edit-save"><?php echo JText::_('COM_STREAM_LABEL_SAVE_CHANGES'); ?></button>
	    </div>
    
	</form>

</div>