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
	      <button class="btn btn-primary" type="submit" name="message-edit-save">Save changes</button>
	      <button class="btn" type="reset" name="message-edit-cancel">Cancel</button>
	    </div>
    
	</form>

</div>