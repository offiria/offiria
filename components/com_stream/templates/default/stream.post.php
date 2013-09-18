<?php
$document = JFactory::getDocument();
require_once(JPATH_ROOT .DS.'components'.DS.'com_account'.DS.'helpers'.DS.'access.php');
require_once(JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'helpers'.DS.'html.php');
require_once(JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'helpers'.DS.'milestones.php');
/* Global javascripts */

// Check permission
// @todo: use standadized offiria authorise control, check for actual group permission as well
if (!AccountAccessHelper::allowPublicStream($my->id))
{
	return;
}
?>

<div id="stream-post">

	<div class="stream-post-tabs">
		<ul class="clearfix">
			<li class="li-text" id=""><a href="javascript:void(0)"><?php echo JText::_('COM_STREAM_LABEL_UPDATE');?><span></span>
			</a></li>
			<li class="tab active" id=""><a href="#stream-post-update"><?php echo JText::_('COM_STREAM_LABEL_STATUS');?><span></span>
			</a></li>
			<li class="tab" id=""><a href="#stream-post-event"><?php echo JText::_('COM_STREAM_LABEL_EVENT');?><span></span>
			</a></li>
			<li class="tab" id=""><a href="#stream-post-milestone"><?php echo JText::_('COM_STREAM_LABEL_MILESTONE');?><span></span>
			</a></li>
			<li class="tab" id=""><a href="#stream-post-todo"><?php echo JText::_('COM_STREAM_LABEL_TODO');?><span></span>
			</a></li>
			<?php if( empty($group_id)) { ?>
			<li class="tab" id=""><a href="#stream-post-page"><?php echo JText::_('COM_STREAM_LABEL_BLOG');?><span></span>
			</a></li>
			<?php } ?>
			<!-- <li class="tab" id=""><a href="#stream-post-milestone"><?php echo JText::_('COM_STREAM_LABEL_MILESTONE');?><span></span></a></li> -->
		</ul>
	</div>

	<div class="stream-post-message minimized" id="stream-post-message">

		<form id="stream-form">
			<!-- <input type="input" name="link[]" value=""> -->

			<!-- Updates -->
			<div class="stream-post-message-share tab-content"
				id="stream-post-update">
				<textarea id="message-box" name="message" class="stream-post limit-length"
					style="resize: vertical;" maxlength="1000"
					placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_TYPE_YOUR_MESSAGE_HERE');?>"></textarea>
				<input type="hidden" name="type" value="update">
			</div>

			<!-- Start Events -->
			<?php $this->load('stream.post.event'); ?>
			<!-- End Events -->

			<!-- Start Milestone -->
			<?php $this->load('stream.post.milestone'); ?>
			<!-- End Milestone -->

			<!-- Start Todos -->
			<?php $this->load('stream.post.todo'); ?>
			<!-- End Todos -->

			<div class="stream-message-links">
				<a class="stream-message-links-remove btn">&times</a>
				<div class="stream-links-image-container">
					<span><img class="stream-message-links-image"/></span>
				</div>
				<div class="stream-links-container">
					<div class="stream-message-links-title"></div>
					<div class="stream-message-links-url"></div>
					<div class="stream-message-links-content"></div>
					<input type="hidden" class="stream-message-linkable-url" name="linkable_link" />
				</div>
				<div class="clear"></div>
			</div><!--end stream-message-links-->
		
		<script type="text/javascript">
		$(function() {
	
		 // Add new input when the last input box get focus
		$('#stream-post-todo input[name="todo\\[\\]"]:last').live('focus', function(e) {
			// If we're the last input that get focus, add a new one at the bottom
			//if ( e.which == 13)
			{
				// Make sure input is not empty
				//if( $(this).val().length > 0 )
				{
					
					// Only add if the first one is not empty
					//if( $('#stream-post-todo input[name="todo\\[\\]"]:first').val().length != 0 )
					{
						$(this)
							.clone().parents('li')
							.insertBefore('#stream-post-todo input[name="todo\\[\\]"]:last')
							.val('')
							.focus();
					}
				}
				return false;
			} 
		});
	});
	</script>

	<?php if( empty($group_id)) { ?>
	<!-- Blog -->
	<div class="stream-post-message-share tab-content"
		 id="stream-post-page" style="display: none">
		<div class="stream-post-details">
			<input type="text" autocomplete="off" class="page-title"
				   name="title"
				   placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_PAGE_TITLE');?>">
			<div class="clear"></div>
		</div>

		<textarea id="message-box-page" name="message"
				  class="stream-post message-page-editor" style="resize: vertical;"
				  cols="63" 
				  placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_LONG_PAGE_HERE');?>"></textarea>
		
		<div class="stream-post-details-milestone">
			<label><?php echo JText::_('COM_STREAM_DEFAULT_LABEL_CATEGORY_PROMPT'); ?></label>
			<select name="blog_category">
				<option><?php echo JText::_('COM_STREAM_DEFAULT_LABEL_PAGE_DEFAULT_CATEGORY'); ?></option>
				<?php
				  $table = new StreamCategory();
				  $blogs = $table->getBlogs();
				  foreach ($blogs as $blog) {
					  // add category that has been added
				?>
				<option value="<?php echo $blog->id; ?>"><?php echo $blog->category; ?></option>
				<?php } ?>
			</select>
		</div>
		
		<input type="hidden" name="type" value="page">
		</div>					<!--end stream-post-message-share tab-content-->
		<?php } ?>


			<!-- @mention -->
			<div class="stream-post-suggest" style="display: none"></div>

			<div class="stream-post-message-attach">
				<ul id="post-attachment-list"></ul>
				<!-- Link -->
				<div class="stream-post-link"></div>

				<!-- Todo -->
				<div class="stream-post-todo"></div>

				<!-- Topic -->
				<!--
	  <div id="stream-post-topic" class="stream-post-topic topic-container-parent">
	    <ul id="stream-topics" class="topic-container"></ul>
	    <input type="text" class="topic-input" placeholder="type message topics, each saperate by comma or space" style="width:90%;border:none"/>
		<br/>
	    <input type="button" class="topic-add stream-form-topic-add" name="" value="Add Topic" />
	    <input type="hidden" class="stream-form-topic-value" name="topics" />
	    <a class="topic-done-change" href="#">Done</a>	  
	  </div>
	  -->
				<!--end stream-post-topic-->
			</div>

			<div class="stream-post-message-tabs">

				<ul>
					<li class="li-text">
						<?php echo JText::_('COM_STREAM_LABEL_ATTACH');?>
					</li>
					<li class="stream-post-link-add">
						<div id="post-file-uploader" >
							<noscript>
								<p>Please enable JavaScript to use file uploader.</p>
								<!-- or put a simple form for upload here -->
							</noscript>
						</div>
					</li>
					<?php /*
					<li class="stream-post-link-add"><a href="#"
						onclick="return S.uploader.selectFile('post-file-uploader');"><?php echo JText::_('COM_STREAM_LABEL_UPLOAD');?>
						</a>
					</li>
					<li class="stream-post-topic-add active"><a href="#"><?php echo JText::_('COM_STREAM_LABEL_TOPIC');?></a></li> */?>
				</ul>
				<input type="hidden" name="group_id"
					value="<?php echo isset($group_id)? $group_id: ''; ?>" /> <input
					type="submit" class="btn btn-info submit"
					value="<?php echo JText::_('COM_STREAM_LABEL_SHARE');?>" />
				<div id="stream-post-loading" class="stream-loading"></div>

				<?php
				// TODO: place these in a template once we have a consistent html structure/styling between the post box and edit box
				//if($my->authorise('core.admin')) :
				if($my->isAdmin()) :
					$pinOptions = array(JText::_('COM_STREAM_LABEL_UNPINNED') => '0', JText::_('COM_STREAM_LABEL_FORADAY') => '1 day', JText::_('COM_STREAM_LABEL_FORAWEEK') => '1 week', JText::_('COM_STREAM_LABEL_FORAWEEK') => '1 month');
				?>
				<div class="pinned-message-action">
					<label><?php echo JText::_('COM_STREAM_LABEL_PINTOTOP'); ?>:</label>
					<select name="pinned">
						<?php foreach($pinOptions as $optionKey => $optionValue) : ?>
						<option value="<?php echo $optionValue; ?>"><?php echo $optionKey; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endif; ?>

				<div class="clear"></div>
			</div>

		</form>

	</div>

</div>
<!-- stream-post -->

<script type="text/javascript">

    $(function() {

    	$('#stream-form').submit(function() {
        	// clear system message everytime
        	S.enqueueMessage();
        	// get the current stream box type
   			var type = $('#stream-form input[name="type"][disabled!="disabled"]').val();

   			switch (type) {
				   case 'page':
					   // page have multiple input type to check in custom method. grab the error manually
					   var errors = [];
					   // validate title
					   errors.push(S.validate.element('notEmpty', $('.page-title'), 'reset'));
					   // validate content manually
					   var content = tinyMCE.activeEditor.getContent();
					   if (S.validate.isNotEmpty(content)) {
						   errors.push(true);
					   }
					   else {
						   S.enqueueMessage('Field <b>Blog content</b> must not be blank', 'error');
						   errors.push(false);
					   }
					   // check for falsy value in array
					   if ($.inArray(false, errors) >= 0) {
						   return false;
					   }
					   break;
				   case 'milestone':
						$dueDate = $('#stream-post-milestone').find('.start-date');
						if (!S.validate.element('notEmpty', $('#stream-post-milestone textarea'), 'reset', '<?php echo
					   JText::_('COM_STREAM_LABEL_MILESTONE'); ?>') || 
					   !S.validate.element('notEmpty', $dueDate, 'reset', '<?php echo JText::_('COM_STREAM_LABEL_DUE_DATE'); ?>')) {
						   return false;
					   }
					   break;
				   case 'event':
					   if (!(S.validate.element('notEmpty', $('#stream-post-event textarea'), 'reset', '<?php echo JText::_('COM_STREAM_LABEL_EVENT'); ?>')
						   && S.validate.element('notEmpty', $('.start-date'), 'reset', '<?php echo JText::_('COM_STREAM_LABEL_START_DATE'); ?>'))) {
						   return false;
					   }

					   if (!$('select[name="start_time"]').is(":visible")) {
						   S.enqueueMessage('Field <b>Start Time</b> must not be blank', 'error');
						   return false;
					   }

					   // when the start date is filled, the start time is required
					   if ($('input[name="end_date"]').val().length !== 0) {
						   if (!$('select[name="end_time"]').is(":visible")) {
							   S.enqueueMessage('Field <b>End Time</b> must not be blank', 'error');
							   return false;
						   }
					   }

					   break;
				   case 'todo':
					   if (!S.validate.element('notEmpty', $('#stream-post-todo textarea'), 'reset', '<?php echo JText::_('COM_STREAM_LABEL_TODO'); ?>')) {
						   return false;
					   }
					   break;
   			}
	    	S.stream.post("<?php echo JRoute::_('index.php?option=com_stream&view=message&task=add'); ?>");
	    	return false;
		});

    	$('li.tab a').click(function() {
        	// clear existing system message if exist | the error might be for another tab
        	S.enqueueMessage();
    	});
    	
		// textarea autogrow
		//$('textarea[name="message"]').autoGrow();
		
		// Show the correct tab
		var hash = document.location.hash;
		switch( hash ){
			case '#newTodo':
				$('a[href="#stream-post-todo"]').click();
				break;
			case '#newBlog':
				$('a[href="#stream-post-page"]').click();
				break;
			case '#newEvent':
				$('a[href="#stream-post-event"]').click();
				break; 
			
		}
		
	});
    
  </script>


<link
	href="<?php echo JURI::root(); ?>media/uploader/fileuploader.css"
	rel="stylesheet" type="text/css">
<script
	src="<?php echo JURI::root(); ?>media/uploader/fileuploader.js"
	type="text/javascript"></script>

<script type="text/javascript">
  	
        $(function() {
    		S.uploader.init('post-file-uploader', 'post-attachment-list');  
			
			$("#stream-post textarea.stream-post").keypress( function(event) {
			
				if(!S.suggest.active){
				 	if (event.charCode == 64 || event.keyCode == 64) {
		    			S.suggest.start('mention');
		   			}
		   			
		   			if (event.charCode == 35 || event.keyCode == 35) {
		    			S.suggest.start('hashtag');
		   			}
	   			}
	   			
				if(S.suggest.active){
	   				//if (event.charCode == 32 || event.keyCode == 13) {
	    			//	S.suggest.stop();
	   				//} else 
					   {				
	   					// If char is alphanumeric, update auto-suggest
	   					S.suggest.update(event);
					}
	   			}
	   			
			});  
			S.validate.element('limitChar', $(".limit-length"));
    	});
    	

    
  </script>
