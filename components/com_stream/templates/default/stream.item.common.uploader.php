<!-- uploader start -->
	
	
	<div class="edit-file-button clearfix">
		<span>Attach:</span> 
		<span id="edit-file-uploader-<?php echo $stream->id; ?>" class="qq-uploader-edit"></span>
		<!--<a href="#" onclick="return S.uploader.selectFile('edit-file-uploader-<?php echo $stream->id; ?>');"><?php echo JText::_('COM_STREAM_LABEL_UPLOAD'); ?></a> -->	
	</div>
	
	<div class="clear"></div>
	
	<ul class="edit-attachment" id="edit-attachment-list-<?php echo $stream->id; ?>">
	<!-- show attachement to allow file deletion -->
	<?php 
	if(!empty($files)) { 
		foreach($files as $file ){ ?>
			<li class="qq-upload-success">
				<div data-filename="<?php echo $file->filename; ?>" class="message-content-file" file_id="<?php echo $file->id; ?>">
					<?php echo StreamTemplate::escape( JHtmlString::abridge($file->filename, 24)); ?>
						<span class="small hint">(<?php echo StreamMessage::formatBytes($file->filesize, 1); ?>)</span>
						<a file_id="<?php echo $file->id; ?>" href="#unlinkAttachment" class="meta-edit">Remove</a>
					<input type="hidden" value="<?php echo $file->id; ?>" name="attachment[]">
				</div>
			</li>
	<?php 
		}
	} ?>
	</ul>
<!-- uploader end -->