<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

function getStreamMessage($streamTbl, &$streamMsg, $stream_id)
{
	if (!array_key_exists($stream_id, $streamMsg))
	{
		$streamTbl->load($stream_id);
		$streamMsg[$stream_id] = $streamTbl;
	}
	
	return $streamMsg[$stream_id];
}
?>

<link rel="stylesheet" href="<?php echo JURI::root();?>media/uploader/fileuploader.css" type="text/css" />
<script src="<?php echo JURI::root();?>media/uploader/fileuploader.js" type="text/javascript"></script>
<script type="text/javascript">
	var fileReplace = {
		init: function() {
			/* bind enter key to "save" button action */
			$('#new_name').keydown( function(e) {
		        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
		        if(key == 13) {
		            e.preventDefault();
		            $('div.modal-footer input.btn').click();
		        }
		    });

			/* bind action to delete action to remove attachment */
			$('div.file-action a[href="#deleteFile"]').live("click",function()
			{
				if (!confirm(S.text['confirm.file.delete']))
				{
					return false;
				}

				var file_id = $(this).parent('li').parent('ul').data('file_id');

				if(file_id != 'undefined'){		    	
					$.ajax({
						type: "POST",
						url: S.path['files.delete'],
						data: {file_id: file_id},
						cache: false,
						dataType: 'json',
						success: function(data){
							$('li#file_item_' + file_id ).remove();
						}
					});
				}

				return false;	
			});

			/* bind action to download action */
			$('div.file-action button.download-file').live("click",function()
			{		
				document.location.href = $(this).data("dl_loc");
			});

			/* bind action to edit action to rename/replace attachment */
			$('div.file-action a[href="#editFile"]').live('click', function (e) {	
				e.preventDefault();
				$('.edit-file-control').show();
				$('.replace-file-control').hide();
				
				var file_id = $(this).parent('li').parent('ul').data('file_id');

				if (file_id != undefined)
				{
					var container = $('div#file-upload-container');
					container.modal({ backdrop:true, show:true });

					var originalFilename = $('#file_item_'+file_id+' a.file-title').data("filename");

					$('#file-replace-form #file_id').val(file_id);
					$('#file-replace-form #new_name').val(originalFilename);

					$('div.modal-footer input.btn').click(function() {
						$.ajax({
							url: S.path['files.update.file'],
							dataType:'json',
							type:'POST',
							data:$('#file-replace-form').serialize(),
							success:function (data) {
								if (!data.result)
								{
									$('div.modal-header div.error_msg').html(data.error);
								}
								else
								{
									$('#file_item_'+data.fileid+' a.file-title').html(data.filename);
									$('#file_item_'+data.fileid+' a.file-title').data("filename", data.full_filename);
									container.modal('hide');
									$('div.modal-header div.error_msg').html("");
									$('div.modal-footer input.btn').unbind("click");
									$('#file-replace-form input[type="text"]').val('');
								}
							}
						});
					})
				}
			});
			
			

			/* bind action to edit action to rename/replace attachment */
			$('div.file-action a[href="#replaceFile"]').live('click', function (e) {	
				e.preventDefault();
				$('.edit-file-control').hide();
				$('.replace-file-control').show();
				
				var file_id = $(this).parent('li').parent('ul').data('file_id');

				if (file_id != undefined)
				{
					var container = $('div#file-upload-container');
					container.modal({ backdrop:false, show:true });

					$('#file-replace-form #file_id').val(file_id);

					fileReplace.createReplaceUploader();
				}
			});
			
			$('input.replace-file-control').live('click', function() {
				var container = $('div#file-upload-container');
				container.modal('hide');
			});
		},
		
		createReplaceUploader: function() {
			var uploader = new qq.FileUploader({
				element: document.getElementById('file-replace-item'),
				action: S.path['files.replace.new']+'&file_id='+$('#file-replace-form #file_id').val(),
				showMessage:function(message) {},
				onComplete:function(id, fileName, response){
					setTimeout("$('.modal-body .qq-upload-success').fadeOut('slow')", 5000);
					$('#file_item_'+$('#file-replace-form #file_id').val()+' a.file-title').html(response.filename);
					$('#file_item_'+$('#file-replace-form #file_id').val()+' div.file-list-type').removeClass(response.oldext);
					$('#file_item_'+$('#file-replace-form #file_id').val()+' div.file-list-type').addClass(response.newext);					
					$('#file_item_'+$('#file-replace-form #file_id').val()+' div.file-list-type img').attr('src', response.preview);					
					$('#file_item_'+$('#file-replace-form #file_id').val()+' div.file-list-meta span.small').html(response.filesize);
				}
			});
		}		
	}

// Hide n Show Container (stream) for grid view
$(document).ready(function() {		
	fileReplace.init();
});
</script>
<ul id="file-listing" class="list clearfix">
	<?php 
	$jxConfig = new JXConfig();
	$streamMessage = array();
	foreach($files as $row){
		$user = JXFactory::getUser($row->user_id);
		$date = new JDate($row->created); 
		$streamObj = getStreamMessage($streamTbl, $streamMessage, $row->stream_id);
		
		// formulate the filename for data binding in html5
		$arrFilename = explode('.', $row->filename);
		$extension = strtolower(array_pop($arrFilename));
		$fullFilename = implode('.', $arrFilename);
	?>
		<li id="file_item_<?php echo $row->id;?>">
			<div class="file-list-avatar">
				<img src="<?php echo $user->getThumbAvatarURL(); ?>" title="<?php echo $user->name;?>" />
			</div>
			
			<div class="file-list-container">
				
				<div class="file-list-content">
					
					<!--  strip the content/ -->
					<div class="file-list-type <?php echo $extension; ?>">
						<?php if($row->getParam('thumb_path')): ?>
						<img src="<?php echo JURI::root() . str_replace(DS, '/', $row->getParam('thumb_path'));?>" />
						<?php endif; ?>
					</div>
					
					<div class="file-list-meta">
						<a class="file-title" data-filename="<?php echo StreamTemplate::escape($fullFilename); ?>" href="<?php echo JRoute::_('index.php?option=com_stream&view=system&task=download&file_id='.$row->id); ?>"><?php echo StreamTemplate::escape(JHtmlString::abridge($row->filename, 32, 25)); ?></a>
						<span class="small">
							<?php
							$fext = strtolower(substr($row->filename, -4));
							if ($jxConfig->isCrocodocsEnabled() || $jxConfig->isScribdEnabled())
							{
								if( in_array($fext, array('.doc', 'docx', '.pdf', '.ppt', 'pptx'))){
									echo ' <a href="javascript:void(0);" class="meta-preview small" data-message_id="'.$row->stream_id.'" data-filename="'. StreamTemplate::escape($row->filename) .'" data-file_id="'.$row->id.'" onclick="S.preview.show(this);">'.JText::_('COM_STREAM_LABEL_PREVIEW').'</a>';
								}
							}
							?>
							(<?php echo StreamMessage::formatBytes($row->filesize); ?>)
						</span>
						<div class="file-list-meta-content">
							<div class="file-list-meta-inner">
								<span>Attached in:</span>
								<a href="<?php echo $streamObj->getUri();?>"><?php echo StreamTemplate::escape(JHtmlString::truncate($streamObj->message, 200));?></a>
							</div>
							<span class="uploader"><a href="<?php echo $user->getURL();?>" class="actor-link"><?php echo $this->escape($user->name); ?></a></span>
						</div>
					</div>
					
					<?php /*
					<div class="file-count">
						<?php echo JXUtility::csvCount($row->followers); ?>
						<?php echo $date->format(JText::_('JXLIB_DATE_SHORT_FORMAT')); ?>
					</div>
					*/ ?>
					
					<div class="clear"></div>
				</div>
				
				<div class="file-action">
					<div class="btn-group">
						<button class="btn btn-mini download-file" data-dl_loc="<?php echo JRoute::_('index.php?option=com_stream&view=system&task=download&file_id='.$row->id); ?>">Download</button>
						<?php if ($my->authorise('stream.file.edit', $row)) {?>
						<button data-toggle="dropdown" class="btn btn-mini dropdown-toggle"><span class="caret"></span></button>
						<ul class="dropdown-menu" data-file_id="<?php echo $row->id;?>">
							<li><a href="#editFile">Edit</a></li>
							<li><a href="#replaceFile">Replace</a></li>
							<li><a href="#deleteFile">Delete</a></li>
						</ul>
						<?php } ?>
					</div>
				</div>
				
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
		</li>
	<?php } ?>
</ul>

<?php 
if (count($files) == 0)
{?>
<div class="alert-message block-message info alert-empty-stream">   
	<p><?php echo JText::_('COM_STREAM_NO_FILE');?></p>        
</div>
<?php }	?>

<div class="modal" id="file-upload-container" style="display:none;">	
	<div class="modal-header">
		<a class="close" data-dismiss="modal">×</a>
		<h3>Edit File</h3>
		<div class="error_msg"></div>
	</div>
	<div class="modal-body">	
		<form method="post" class="edit" id="file-replace-form" action="<?php echo JRoute::_("index.php?option=com_stream&view=files");?>" enctype="multipart/form-data">
			<ul>
				<li class="edit-file-control">
					<label for="new_name" id="new_name-lbl">Rename to:</label>
					<input type="text" name="new_name" id="new_name" />
					<span class="file-extension"></span>
				</li>
				<li class="replace-file-control">
					<label for="upload-file" id="upload-file-lbl">Replace with:</label>
					<div id="file-replace-item">
						<noscript>
							<p>Please enable JavaScript to use file uploader.</p>
							<!-- or put a simple form for upload here -->
						</noscript>
					</div>
				</li>
				<input type="hidden" name="task" value="updateFile" />	
				<input type="hidden" name="file_id" id="file_id" value="" data-uploaded_file=""/>		
			</ul>
		</form>
	</div>
	<div class="modal-footer">
		<input type="button" class="btn btn-primary edit-file-control" value="<?php echo JText::_('COM_STREAM_LABEL_SAVE');?>" />
		<input type="button" class="btn btn-primary replace-file-control" value="<?php echo JText::_('COM_STREAM_LABEL_DONE');?>" />
	</div>
</div>

<div id="uploadedList"></div>
<div class="pagination">
<?php echo $pagination->getPagesLinks(); ?>
</div>