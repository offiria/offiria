<?php
$data = $stream->getData();
$files = $stream->getFiles();
$date = new JDate( $stream->created );
?>
<form method="POST" action="" name="blog-edit">
<div class="inline-edit">
	<div class="stream-post-message-share tab-content" id="stream-post-page">
		<div class="stream-post-details">
			<input type="text" autocomplete="off" class="page-title" name="title" placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_PAGE_TITLE');?>" value="<?php echo StreamTemplate::escape($data->title); ?>" />
			<div class="clear"></div>
		</div>
		
		<div class="blog-header">
			<span class="small">
			<?php echo JText::_('COM_STREAM_BLOG_LABEL_POSTED_ON'); ?> <?php echo $date->format(JText::_('JXLIB_DATE_SHORT_FORMAT')); ?>	
			</span><br />
			<span class="small">
				<?php echo JText::_('COM_STREAM_BLOG_LABEL_IN_CATEGORY'); ?>: 
				<?php 
				  $categoryTable = JTable::getInstance('Category', 'StreamTable');
				  $category = $categoryTable->getCategoryNameById($stream->category_id);
				  if (!empty($category)) {
					  echo '<a href="' . JRoute::_('index.php?option=com_stream&view=blog&category=' . $category) . '">' . $category . '</a>';
				  }
				  else {
					  echo JText::_('COM_STREAM_DEFAULT_LABEL_PAGE_DEFAULT_CATEGORY');
				  }
				?>
			</span>
		</div>
		
		<textarea id="message-box-page" name="message" class="stream-post message-page-editor" style="width:660px;resize:vertical;" cols="80" placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_LONG_PAGE_HERE');?>">
			<?php echo $stream->message; ?>
		</textarea>
		<input type="hidden" name="type" value="page">
		<input type="hidden" name="action" value="save" />				
	</div>
	
<?php 	
			$table = new StreamCategory();
			 $categories = $table->getBlogs();
?>	
	<div class="stream-post-details-select">

		<label><?php echo JText::_('COM_STREAM_DEFAULT_LABEL_PAGE_EDIT_CATEGORY_PROMPT'); ?></label>
		<select name="category_id">
			<option><?php echo JText::_('COM_STREAM_DEFAULT_LABEL_PAGE_DEFAULT_CATEGORY'); ?></option>
			<?php
				$blogs = $table->getBlogs();
				foreach ($blogs as $blog) {

					// mark for selected if its already categorized
					$selected = ($stream->category_id == $blog->id) ? 'selected="selected"' : '';
				// add category that has been added
			?>
			<option <?php echo $selected;?>  value="<?php echo $blog->id; ?>"><?php echo $blog->category; ?></option>
			<?php } ?>
			</select>
	
	</div>
		
	<div class="clear"></div>
		
	<div class="edit-file-button">
		<span>Attach:</span>
		<a onclick="return S.uploader.selectFile('edit-file-uploader');" href="#"><?php echo JText::_('COM_STREAM_BLOG_LABEL_UPLOAD'); ?></a>

		<span id="edit-file-uploader" style="float:left;visibility:hidden;width:1px;height:1px"></span>

	</div>

	<ul id="edit-attachment-list" class="edit-attachment">
	<!-- show attachement to allow file deletion -->
	<?php 
	if(!empty($files)) { 
		foreach($files as $file ){ ?>
			<li style="list-style: none outside none;margin-left:0px;padding-left: 8px;" class="qq-upload-success">
				<div  data-filename="<?php echo $file->filename; ?>" class="message-content-file" file_id="<?php echo $file->id; ?>">
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
		
		<div class="form-actions">
			<button class="btn btn-primary" type="submit" name="message-edit-save">Save changes</button>

			<span class="more-button">
				<a href="<?php echo $stream->getUri(); ?>">&larr; View post</a>
			</span>  
		</div>
</div>
</form>
<script type="text/javascript">
$(function() {
	tinyMCE.execCommand("mceAddControl", true, "message-box-page");
	
	$("form[name='blog-edit']").submit(function(event){
		//event.preventDefault();
	});	
	
	// Initialize the file uploader
	S.uploader.init('edit-file-uploader', 'edit-attachment-list');
});
</script>