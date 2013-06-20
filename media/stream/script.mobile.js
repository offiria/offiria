S = {
    jQuery: window.jQuery,
    extend: function(obj){
		this.jQuery.extend(this, obj);
    }
};

S.extend({
	text: [], /* server generated text definitions */
	path: [], /* Server generated ajax path */
	
	utils: {
		/* Return the message id for any given child element within the message */
		getMessageId: function(e){
			// check if the element itself carry the message_id data
			var stream_id = $(e).attr('message_id');
			
			if(stream_id != undefined){
				return stream_id;
			}
			
			stream_id = $(e).parents('li.message-item').attr("id");
	    	stream_id = stream_id.match(/^\w+_([0-9]+)$/)[1];
	    	return stream_id;
		},
		
		/* Return group id */
		getGroupId: function(e){
			// check if the element itself carry the message_id data
			var g_id = $(e).attr('group_id');
			
			if(g_id != undefined){
				return g_id;
			}
			
			return 0;
		},
		
		/* Return the comment id for any given child element within the message */
		getCommentId: function(e){
			var comment_id = $(e).parents('div.comment-item').attr("id");
	    	comment_id = comment_id.match('([0-9]+)')[0];
	    	return comment_id;
		},
		
		setupOptionSelect: function(e){
			var val = $(e).attr('value');
			var target = $(e).attr('target-input');
			var name = $(e).html();
			console.log(name);
			
			$('form input[name="'+target+'"]').val(val);
			$('a.dropdown-toggle[href="#'+target+'"]').html( name + ' <b class="caret">');
			return false;
		}
	},
	uploader:{
		init: function(uploaderElement, uploadedList){
			var uploader = new qq.FileUploader({
            	multiple: false,
                element: document.getElementById(uploaderElement),
                action: S.path['system.upload'],
                debug: true,
                listElement: document.getElementById(uploadedList),
                fileTemplate: '<li style="list-style: none outside none;margin-left:0px;padding-left: 8px;">' +
		                '<div class="message-content-file"><span class="qq-upload-file"></span>' +
		                '<span class="qq-upload-spinner"></span>' +
		                '<span class="qq-upload-size"></span>' +
		                '<a class="qq-upload-cancel" href="#">Cancel</a>' +
		                '<span class="qq-upload-failed-text">Failed</span>' +
		                '<input type="hidden" name="attachment[]" value="" />' +
		                '</div>' +
		            '</li>',
		        onComplete: function(id, fileName, response){
		        	// get the element,
		        	var item = uploader._getItemByFileId(id); 
		        	
		        	// attach file uploader
					$(item).find('input').val(response.file_id);
					
					// add 'remove' button
					$(item).find('div.message-content-file').append('<span class="qq-upload-remove"><a file_id="'+response.file_id+'" href="#removeAttachment">Remove</a></span>');
					
					
		        	// id is the index of the attachment
					/*    			    
                    same(id, expectedId, 'progress event fired with correct id param');
                    same(fileName, expectedName, 'progress event fired with correct fileName param')
                                    				
    				data.fileName = fileName;
                    data.qqfile = fileName;

    				same(response, data, 'server received passed params, filenames match');
    				
    				same(fileName, uploadHandler.getName(id), 'getName method');   
					*/ 				 
    			}
            }); 
		},
		
		selectFile: function(uploader){
			$('#'+ uploader +' input=[name=\'file\']').click(); 
			return false;
		},
		getHandler: function(){				
	        if(qq.UploadHandlerXhr.isSupported()){           
	            return qq.UploadHandlerXhr;                        
	        } else {
	            return qq.UploadHandlerForm;
	        }
		}
	},
	
	preview : {
		modal: false, 
		show: function(e) {
			// get file id
			var file_id = $(e).attr("data-file_id");
			var file_name = $(e).attr("data-filename");
			// Load waiting message
			// Destroy any previous modal
			/*
			if ($("#preview-modal").length == 0){
				$('body').append('<div id="preview-modal"class="modal hide modal-wide" style="max-height:900px;width: 750px; margin: -250px 0 0 -375px;bottom:30px;top:280px"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">×</button><h3>'+file_name+'</h3></div><div class="modal-body" style="bottom:0px;max-height:900px">Loading</div></div>');
			}			
			$("#preview-modal div.modal-body").html('<img style="width: 220px;   height: 20px;   position: absolute;   left: 50%;   top: 50%;    margin-left: -140px;   margin-top: -10px;" src="/templates/offiria/images/hloading.gif">');
			$("#preview-modal").modal({ backdrop:true, show:true });
			*/
			S.preview.load(file_id);
			
		},
		
		load:function( file_id){
			// Load preview
			$.ajax({
				type: "POST",
				 url: S.path['system.preview'],
				 data: {file_id: file_id},
				 dataType: 'json',
				 cache: false,
				 success: function(data){
				 	// if reload request is sent, queue it for another 5 seconds
				 	//console.log(typeof data.reload);
				 	if( data.reload ){
				 		// Only reload if the modal is still open
				 		//if($('div#preview-modal').css('display') == 'block'){
				 			S.preview.load(data.file_id);
				 		//	console.log(data.file_id);
				 		//}
					}
				 	// set proper height
				 	//$('div#preview-modal div.modal-body').css('height', $('#preview-modal').height() - 90 );
				 	//$('div#preview-modal div.modal-body').html(data.html);
				 	// open in new window
				 	var previewLink = $(data.html).attr('src');
				 	window.open(previewLink);
				 }
			});
		}
	},
	
	notification: {
		intervals: null,
		pHash : null, // public data hash
		gHash : null, // group data hash
		update: function()
		{ 
			$.ajax({
				type: "POST",
				url: S.path['notification.update'],
				dataType: 'json',
				data: {pHash: S.notification.pHash},
				cache: false,
				success: function(data){
					if(data.logout != undefined){
						// @todo: put a cool session expired alert here
						// and stop all future updates
						alert("Session expired. Please login again");
						clearInterval(S.notification.intervals);
						
						// Redirect to login page
						window.location = S.path['system.root'];
					} 
					else if(data.pHash != S.notification.pHash) 
					{
						S.notification.pHash = data.pHash;
						//S.notification.gHash = data.gHash;
						// Update notification bubble
						if (typeof data.notification != undefined ){
							$.each(data.notification, function(key, value) {
								if(value != 0)
								{ 
									$('span#'+key).html(value); 
									$('span#'+key).show();
									$('ol#stream div.'+key +' a').html( value + ' new updates');
									$('ol#stream div.'+key +' a').attr('limit', value);
									$('ol#stream div.'+key).show();
								}
								else
								{
									$('span#'+key).hide();
									$('ol#stream div.'+key).hide();
								}
							});
						}
						
						// Update notification data content
						if (typeof data.data_content != undefined ){
							$.each(data.data_content, function(key, value) {
								$(key).attr('data-content', value);
							});
						}
					} 
				}
			});
		}
	},
	
	search: {
		init: function(){
			/*
			$.widget( "custom.catcomplete", $.ui.autocomplete, {
				_renderMenu: function( ul, items ) {
					var self = this,
						currentCategory = "";
					$.each( items, function( index, item ) {
						if ( item.category != currentCategory ) {
							ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
							currentCategory = item.category;
						}
						self._renderItem( ul, item );
					});
				}
			});
			
			$('ul.ui-autocomplete a').live('click', function(){
				alert($(this).html());
			});
		
			$( '#searchform input[name="searchword"]' ).catcomplete({
				source: S.path['system.search'],
				minLength: 2,
				select: function( event, ui ) {
					
					//log( ui.item ?
					//	"Selected: " + ui.item.value + " aka " + ui.item.id :
					//	"Nothing selected, input was " + this.value );
					
				}
			});
			*/
		}
	},
    stream: {
    	init: function() {
    		$('#stream a[href="#showNew"]').live('click', function(){
    			var group_id = S.utils.getGroupId(this);
				var limit = $(this).attr('limit');
					
				$.ajax({
					type: "POST",
					url: S.path['message.load'],
					data: {group_id :group_id, limit:limit},
					dataType: 'json',
					cache: false,
					success: function(data){
						$(data.html).each(function(index) {
						
							var newHTML = $(data.html[index]).css('display', 'none');
							var currentLi = $('ol#stream li:first');
							
							$("ol#stream li.older-stream-saperator").show();
							$('ol#stream li:first').before(newHTML);
							$('ol#stream li:first').slideDown("slow");
							//$(currentLi).prev().slideDown('slow');
							//$("ol#stream li:first").before(data.html[index]);
							//$("ol#stream li:first").slideDown("slow");
						});
						
						
						//$('ol#stream li.message-item').before(data.html);
						$('ol#stream div.morebox').slideUp("slow");						
					}
				});
				
				return false;	
			});
		},
		update: function( data ){
		    // Update the stream with the given data
	
		    //prepend
		},
		
		// Post updates 
		post: function ( posturl ) {
			// get the active message type
			var type = $('#stream-form input[name="type"][disabled!="disabled"]').val();
			
			// Call onSubmit trigger. If it return a false, do not proceed with the submit
			// The todo/etc is responsible for displaying its one errror message
			// @todo: check make sure function exist first
			var trigger = 'S.'+type+'.onSubmit("form#stream-form");';
			if( eval(trigger) == false ){
				return false;
			}
			
			// Need to grab the data before we disable the form elements
			var postData = $('#stream-form').serialize();
			
			// Disable the form elements and submit form
			var activeType = $('#stream-post-tabs li a').attr('href');
			$( activeType + ' :input, #stream-form input[type="submit"]').attr("disabled", true);
			
			// Loading indicator
			$('#stream-post-loading').show();
			
		    $.ajax({
			type: "POST",
				url: posturl,
				data: postData,
				dataType: 'json',
				success: function(data){
					// Show the older stream saperator, in case we're currently viewing much older stream
					$("ol#stream li.older-stream-saperator").show();
					$("ol#stream li:first").before(data.html);
					$("ol#stream li:first").slideDown("slow");
					$(".stream-post").val('');
					$("#message-box").focus();
					$("#flash").hide();
					
					// Remove attachment list
					$('ul#post-attachment-list li').remove();
					
					// Hide "no message" notice
					$('div.alert-empty-stream').fadeOut();
					
					// Hide the 'updated' saperator
					$('#updated-stream-separator').hide();
				}
		    }).done(function( msg ) {
		    	$('#stream-post-loading').hide();
		    	
		    	// Re-enabled form elements and submit form
				$(activeType + ' :input, #stream-form input[type="submit"]').attr("disabled", false);
				
				
				
				// Trigger onAfterSubmit
				// @todo: check make sure function exist first
				trigger = 'S.'+type+'.onAfterSubmit();';
				eval(trigger);
		    });
		    
		},
		
		/* Update user params data at a delayed interval */
		userstats: function(updateData){
			$.ajax({
			type: "POST",
				url: S.path['stream.update'],
				data:updateData
		    }).done(function( msg ) {
				
		    });	
		}
    },
    
    postbox: {
		// Init the tab status by populating them with jQuery data (loop)
		init: function(){
		    
		    $('.stream-post-tabs li[class!="li-text"]').click(function() {
				// Grab content from old textarea
				var text = $('#stream-form textarea[name="message"][value!=""]').val();
				$('#stream-form textarea[name="message"][value!=""]').val('');
				
				//Remove any "active" class
				$(".stream-post-tabs li").removeClass("active");
				 
				$(this).addClass("active"); //Add "active" class to selected tab
				$(".tab-content").hide(); //Hide all tab content
				// Disable all form element inside the content area
				$(".tab-content").find('textarea,input').attr('disabled', true);
		
				//Find the href attribute value to identify the active tab + content
				var activeTab = $(this).find("a").attr("href"); 
				//Fade in the active ID content
				$(activeTab).show(); 
				
				$(this).find('a').blur();
				
				// Reassign the active textarea with the text
				$(activeTab).find('textarea[name="message"]').val(text);
				
				// Enable all form element inside the content area
				$(activeTab).find('textarea,input').attr('disabled', false);
				
				// activate ckeditor box if necessary
				if(activeTab == '#stream-post-page')
					tinyMCE.execCommand("mceAddControl", true, "message-box-page");
				//	CKEDITOR.replace($('textarea.message-page-editor').get());
					
				return false;
			});
			
			// Initialize it with a click
			$(".stream-post-tabs li.active").click();
			
			// Initizlize date picker
			$( "input.start-date, input.end-date" ).datepicker({ dateFormat: 'yy-mm-dd' });
			//$( "input.start-date, input.end-date" ).dateinput({format: 'yyyy-mm-dd'});
	
			// empty out the message box
			$('#message-box').val('');
		},
		// toggle will alternate between displaying/hide
		toggle: function(/* element that trigger the event */sender, 
						 /* this will force the preview */ force) {
			// set default value of force
			var force = force || false;
			
			// check to make sure visibility
			var isDisplayed = ($('#stream-post').css('display') == 'block') ? true : false;
			if (isDisplayed && !force) {
				S.postbox.hide();
				// add styling to the toggle button
				$(sender).removeClass('active');
			}
			else {
				S.postbox.show();
				// add styling to the toggle button
				$(sender).addClass('active');
			}
			// whenever a message is post hide the postbox
			$('#stream-post').find('input[type=submit]').click(function() {
				// rebind to trigger the same event to avoid duplicate code
				S.postbox.toggle(sender);
				// move the container to top of the page
				 $('html, body').animate({ scrollTop:0 }, 'fast');
			});
		},
    	show: function() {
    		// make animation here
    		$('#stream-post').css('display', 'block');
    	},
    	hide: function() {
    		// make animation here
    		$('#stream-post').css('display', 'none');
    	}
    },

    topic: {
		init: function(sender) {
		    S.topic.done(sender.find('.topic-edit-change'));
		},
	
		// Update hidden form's input element with topic ins csv format
		update: function(sender) {
		    var topics = $(sender).parents('.topic-container-parent').find('.stream-topics-element');
		    var hiddenForm = $(sender).parents('.topic-container-parent').find('.stream-form-topic-value');
		    var values = [];
		    topics.each(function(idx, el) {
				// minus 1 to remove close button text
				var text = $(el).text();
				values.push(text.substr(0, text.length-1));
		    });
		    hiddenForm.val(values.join(','));
		},
	
		// @param sender is needed to monitor the call is from stream of sharebox
		add: function(sender) {
		    var input = $(sender).parents('.topic-container-parent').find('.topic-input');
		    var container = $(sender).parents('.topic-container-parent').find('.topic-container');
		    var link = '#';
		    var topicClass = 'stream-topics-element';
		    var closeButtonClass = 'stream-topics-close';
		    var value = input.val().split(' ');
		    input.val('');
			
		    value.each(function(el, idx) {
			if (el.length > 0) {
			    var item = $('<li />', {
					'class': topicClass,
					'html': '<a href="' + link + '">' + el + '</a>'
			    }).appendTo(container);
				
			    var closeButton = $('<span />', {
					'class': closeButtonClass,
					'html': 'x'
			    }).appendTo(item);
			}
		    });
		    S.topic.update(sender);
		},
		close: function(sender) {
		    $(sender).parent().remove();
		},
		
		edit: function(sender) {
		    var close = $(sender).parents('.topic-container-parent').find('.stream-topics-close');
		    var input = $(sender).parents('.topic-container-parent').find('.topic-input');
		    var addButton = $(sender).parents('.topic-container-parent').find('.topic-add');
		    addButton.show();
		    input.show();
		    close.show();
		    $(sender).html('Done');
		    $(sender).click(function() {
				S.topic.done(this);
		    })
		},
		
		done: function(sender) {
		    var close = $(sender).parents('.topic-container-parent').find('.stream-topics-close');
		    var input = $(sender).parents('.topic-container-parent').find('.topic-input');
		    var addButton = $(sender).parents('.topic-container-parent').find('.topic-add');
		    addButton.hide();
		    input.hide();
		    close.hide();
		    $(sender).html(S.text['edit']);
		    $(sender).click(function() {
				S.topic.edit(this);
		    })
		}
    },
    
    /* Generic messages script */
    message: {
    	init: function(){
    		/* Hide/show delete button */
			$('.message-item').live("mouseenter", function(){
				$(this).find('.message-remove').show();
				$(this).find('.hover-show').show();
			});
			$('.message-item').live("mouseleave", function(){
				$(this).find('.message-remove').hide();
				$(this).find('.hover-show').hide();
			});
			
    		/* Filtering */
    		$('a[href="#filterMentions"], a[href="#filterAll"], a[href="#filterMine"], , a[href="#filterDue"]').live("click",function(){
    			$(this).closest('ul').find('li').removeClass('active');
				$(this).closest('li').addClass('active');
				var group_id = S.utils.getGroupId(this);
				
				var filter = {group_id:0,user_id:0, search:'', overdue:''};						
    			var user_id = $(this).attr('user_id');
    			var search = $(this).attr('search');
    			var overdue = $(this).attr('overdue');
    			
    			if(group_id != undefined){
    				filter.group_id = group_id;
    			}
    			if(search != undefined){
    				filter.search = search;
				}
				
				if(user_id != undefined){
					filter.user_id = user_id;
				}
				
				if(overdue != undefined){
					filter.overdue = overdue;
				}
				
				// Passed filter type info
				filter.type = $('#stream-tab li.active a').attr('href');
				
				// Show loading
				$('div.stream-loading').remove();
				$('#update').before('<div class="stream-loading"></div>');
				
    			$.ajax({
					type: "POST",
					 url: S.path['message.filter'],
					 data: filter,
					 dataType: 'json',
					 cache: true,
					 success: function(data){
						// Filter the data first
						// Only update the #update content if the tab selection
						// is still valid, if not, just ignore it
						if( $('#stream-tab li.active a').attr('href') == data.type ){					
							var html = $(data.html).find('#update').html();
							
							// Change the content
							$('#update').html(html);
							
							// Change the pagination
							html = $(data.html).find('.pagination').html();
							$('.pagination').html(html);
							
							$('.stream-loading').remove();
						}
					 }
				});
    			return false;
			});
			
			/* Filtering */
    		$('a[href="#showMore"]').live("click",function(){
    
								
				var filter = {group_id:0,user_id:0, search:'', limitstart:0};
				var group_id = S.utils.getGroupId(this);						
    			var user_id = $(this).attr('user_id');
    			var search = $(this).attr('search');
    			var limitstart = $(this).attr('limitstart');
    			
    			if(group_id != undefined){
    				filter.group_id = group_id;
    			}
    			if(search != undefined){
    				filter.search = search;
				}
				
				if(user_id != undefined){
					filter.user_id = user_id;
				}
				
				if(limitstart != undefined){
					filter.limitstart = limitstart;
				}
				
    			$.ajax({
					type: "POST",
					 url: S.path['message.filter'],
					 data: filter,
					 dataType: 'html',
					 cache: true,
					 success: function(data){
						// Filter the data firls
						var html = $(data).find('#stream').html();
						
						$('#stream').append(html);
						
					 }
				});
    			return false;
			});
			
    		/* Message delete button */
		    $('div.message-remove a.remove, div.message-meta a[href="#remove"]').live("click",function()
		    {
			    var stream_id = S.utils.getMessageId(this);
		
			    if(confirm(S.text['confirm.message.delete']))
			    {
					$.ajax({
					type: "POST",
					 url: S.path['message.delete'],
					 data: {message_id : stream_id},
					 dataType: 'json',
					 cache: false,
					 success: function(data){
						$('li#message_'+data.id).slideUp('slow', function() {$(this).remove();});
					 }
					});
		
			    }
		
		
				return false;
		    });
		    
			/* Edit message */
			$('.message-meta a[href="#edit"]').live("click",function()
		    {
		    	var stream_id = S.utils.getMessageId(this);
		    	
				$().modal('show', S.text['edit'], function(){
		    		$.ajax({
						type: "POST",
						url: S.path['message.edit'],
						data: {message_id: stream_id},
						cache: false,
						dataType: 'json',
						success: function(data){
							// Clear the message box
							$().modal('content', data.html);
							$().modal('title', data.title);
							$().modal('actions', data.actions);
							
							
							// Autogrow textarea
							//$('#stream-post-update textarea').autoGrow({minHeight:60});
							//$().modal('resize');
							
							// set up date picker if necessary
							$( "#cWindow input.start-date, #cWindow input.end-date" ).datepicker({ dateFormat: 'yy-mm-dd' });
							
							// If there is any js, excute them
							eval(data.script);
						}
					});
				});
				
				return false;	
		    });
		    
		    /* Click on message edit modal box */
		    $('input[name="message-edit-save"]').live("click", function()
		    {
		    	var type = $('form[name="message-edit"]').find('input[name="type"]').val();
		    	// Call onSubmit trigger. If it return a false, do not proceed with the submit
				// The todo/etc is responsible for displaying its one errror message
				// @todo: check make sure function exist first
				var trigger = 'S.'+type+'.onSubmit(\'form[name="message-edit"]\');';
				if( eval(trigger) == false ){
					return false;
				}
				
				$.ajax({
					type: "POST",
					url: S.path['message.save'],
					data: $('form[name="message-edit"]').serialize(),
					cache: false,
					dataType: 'json',
					success: function(data){
						// Close the box
						$().modal('hide');
						
						// Update the message in the current stream
						$('li#message_' + data.id ).replaceWith(data.html);
					}
				});
				
				return false;
			});
			
			
			/* Remove attachment */
			$('.message-content-file a[href="#removeAttachment"]').live("click",function()
		    {
		    	//var message_id = S.utils.getMessageId(this);
				var file_id = $(this).attr('file_id');		    	
	    		$.ajax({
					type: "POST",
					url: S.path['files.delete'],
					data: {file_id: file_id},
					cache: false,
					dataType: 'json',
					success: function(data){
						
						// If there is no id, it might not have a stream
						if( typeof data.id === 'undefined'){
							// find the closest wrapper and delete it
							$('a[file_id="'+data.file_id+'"]').closest('li').remove();
						} else {
							$('li#message_' + data.id ).replaceWith(data.html);
						}
					}
				});
				
				return false;	
		    });
		    
		    /* Unline attachment. Doesn't get deleted, used during message editing */
		    $('.message-content-file a[href="#unlinkAttachment"]').live("click",function()
		    {
		    	$(this).parents('li.qq-upload-success').remove();
		    	return false;
			});
			
		}
    	
	},
	
	/* Comments */
	comment: {
	
		init: function(){
			/* Comment Save button */
			$('.comment-action button').live("click",function()
		    {
				// Make sure comment is not empty
				if($(this).parents('form').find('textarea[name="comment"]').val().length == 0)
				{
					return false;
				}
				var data = $(this).parents('form').serialize();
				var stream_id = S.utils.getMessageId(this);
				
				$.ajax({
					type: "POST",
					url: S.path['comment.add'],
					data: data,
					cache: false,
					success: function(html){
						// Clear the message box
						$('#message_' + stream_id + ' .stream-comment textarea[name="comment"]').val('');
						
						// Append the response to the last item above the form
						$('#message_' + stream_id + ' .stream-comment .comment-form').before(html);
					}
				});
				
				return false;
			});
			
			/* Delete comment */
		    $('div.comment-item a[href="#delete"]').live("click",function()
		    {
				var comment_id = S.utils.getCommentId(this);
				
				$.ajax({
					type: "POST",
					url: S.path['comment.delete'],
					data: { comment_id: S.utils.getCommentId(this)},
					cache: false,
					success: function(html){
						// Remove the comment
						$('#comment-'+comment_id).remove();
						//$('#message_' + stream_id + ' .stream-comment textarea[name="comment"]').val('');
						
						// Append the response to the last item above the form
						//$('#message_' + stream_id + ' .stream-comment .comment-form').before(html);
					}
				});
				
				return false;
			});
			
			/* Load all comments */
		    $('.comment-more a[href="#showallcomments"]').live("click",function()
		    {
				var stream_id = S.utils.getMessageId(this);
				
				$.ajax({
					type: "POST",
					url: S.path['comment.showall'],
					data: 'message_id='+stream_id,
					cache: false,
					success: function(html){
						// Clear the 'Show all x comment and last comment
						// to give space to load all other comments
						$('#message_' + stream_id + ' .stream-comment .comment-item').remove();
						$('#message_' + stream_id + ' .stream-comment .comment-more').remove();
						
						// If like bar is there, add it after like bar
						if($('#message_' + stream_id + ' .stream-comment .stream-like').size() > 0){
							$('#message_' + stream_id + ' .stream-comment .stream-like').after(html);
						}else {
							$('#message_' + stream_id + ' .stream-comment').prepend(html);
						}
						
						//
						
						$('#message_' + stream_id + ' .stream-comment textarea[name="comment"]').val('');
					}
				});
				
				return false;
			});
			
			/* Reply link click to show reply form */
			$('.comment-form a[href="#reply"],.message-item a[href="#comment"]').live("click",function()
		    {
		    	var stream_id = S.utils.getMessageId(this);
			    
			    $('#message_' + stream_id + ' .stream-comment .comment-form form').show().find('textarea').focus();
			    $('#message_' + stream_id + ' .stream-comment .comment-form > span').hide();
			    	
		    	return false;
			});
			
			/* Reply cancel link */
		    $('.comment-form a[href="#cancelPostinComment"]').live("click",function()
		    {
		    	var stream_id = S.utils.getMessageId(this);
			    
			    $('#message_' + stream_id + ' .stream-comment .comment-form form').hide();
			    
			    // Only show reply if there are actually comment already
			    if( $('#message_' + stream_id + ' .stream-comment .comment-item').size() > 0) {
			    	$('#message_' + stream_id + ' .stream-comment .comment-form > span').show();
			    }
			    	
		    	return false;
			});
		},
		
		add: function( data ){
			// Update the stream with the given data
			//prepend
			
			$.ajax({
			type: "POST",
				url: posturl,
				data: $('#stream-form').serialize()
			}).done(function( msg ) {
			//alert( "Data Processed: " + msg );
				$("ol#stream").prepend(msg);
				$("ol#stream li:first").slideDown("slow");
				$(".stream-post").val('');
				$("#message-box").focus();
				$("#flash").hide();
			});

		},
		
		/* Delete the comment */
		remove: function (e){
			$.ajax({
			type: "POST",
				url: S.path['comment.delete'],
				data: { comment_id: S.utils.getCommentId(this)}
			}).done(function( msg ) {
				
			});
			                    comment-item
		}
	},
	
	/* Likes */
	like: {
		init: function() {
			/* Like link */
			$('.message-item a[href="#like"]').live("click",function()
		    {
				var stream_id = S.utils.getMessageId(this);
				
				$.ajax({
					type: "POST",
					url: S.path['like.like'],
					data: 'message_id='+stream_id,
					dataType: 'json',
					cache: false,
					success: function(data){
						// div.stream like is there, but might be hidden when there are no data
						// show it.
						$('#message_' + stream_id + ' .stream-comment .stream-like').show();
						$('#message_' + stream_id + ' .stream-comment .stream-like').html(data.html);
						$('#message_' + stream_id + ' a[href="#like"]').html(data.label).attr('href', '#unlike');
					}
				});
				
				return false;
			});
			
			/* UnLike link */
			$('.message-meta a[href="#unlike"]').live("click",function()
		    {
				var stream_id = S.utils.getMessageId(this);
				
				$.ajax({
					type: "POST",
					url: S.path['like.unlike'],
					data: 'message_id='+stream_id,
					dataType: 'json',
					cache: false,
					success: function(data){
						// div.stream like is there, but might be hidden when there are no data
						// show it.
						$('#message_' + stream_id + ' .stream-comment .stream-like').show();
						$('#message_' + stream_id + ' .stream-comment .stream-like').html(data.html);
						$('#message_' + stream_id + ' a[href="#unlike"]').html(data.label).attr('href', '#like');
					}
				});
				
				return false;
			});
			
			/* show all likes */
			$('.stream-like a[href="#showLikes"]').live("click",function()
		    {
				var stream_id = S.utils.getMessageId(this);
				
				$.ajax({
					type: "POST",
					url: S.path['like.showall'],
					data: 'message_id='+stream_id,
					cache: false,
					dataType: 'json',
					success: function(data){
						// Clear the message box
						$('#message_' + stream_id + ' .stream-like').html(data.html);
					}
				});
				
				return false;
			});
		}
	},
	
	/* Update message type */
	update: {
		onSubmit: function(form){
			// make sure it is not empty
			if( $(form).find('#stream-post-update textarea[name="message"][value!=""]').length == 0 )
			{
				alert(S.text['error.message.add.empty']);
				return false;
			}
			return true;
		},
		
		onAfterSubmit: function(){
			// clear form 
		}	
	},
	
	/* Todo Message Type*/
    todo: {

		getIndex: function(e){
			// check for the todo_index in the element itself
			var i = $(e).attr('todo_index');
			if(i != undefined){
				return i;
			}
			
			// Otherwise return the todo list index
			return $(e).parents('li.todo-item').prevAll().length;
		},
    	init: function(){
    	
    		/* add new todo input after [enter] key*/
    		$('input[name*="todo\\["]').live('keypress', function(e) {
				if ( e.which == 13){
					// Make sure input is not empty
					if( $(this).val().length > 0 ){
						
						// Only add if the first one is not empty
						if( $(this).last().val().length != 0 )
						{
							var inputContainer = $(this).closest('li').clone().get();
							$(this).closest('ul').append( $(inputContainer));
							$(inputContainer).find('input').val('').focus();
						} else{
							$(this).last().focus();
						}
					}
					return false;
				} 
			});
			
			/** remove todo item */
			$('a[href="#deleteTodo"]').live('click', function() {
				// Only delete if there are more than 1 todo item
				if($(this).closest('li').siblings().length > 0){
					$(this).closest('li').remove();
				}
				return false; 
			});
			
    		/* Bind #done click */
			$('li.todo-item a[href="#done"][class!="readonly"]').live('click', function(){
    			$(this).parents('li.todo-item').toggleClass('todo-done');
    			
				// Get current state and send the current state as the user
				// sees. We don't care what it is in the server
				var todoState = $(this).parents('li.todo-item').hasClass('todo-done') ? 1 : 0;
				var currentIndex = S.todo.getIndex(this);
                var stream_id = S.utils.getMessageId(this);
	    
				// Send ajax call
				$.ajax({
					type: "POST",
						url: S.path['todo.done'],
						dataType: 'json',
						data: {'message_id':stream_id,
								'state': todoState,
								'item': currentIndex
								}
					}).done(function( data ) {
						// All done, refresh the stream
						$('li#message_' + data.id ).replaceWith(data.html);
						
						// Execute scripts
						eval(data.script);
				});
				
				return false;
			});
			
			/** Bind read-only stuff */
			$('.readonly').live('click', function() {
				return false;
			});
		},
		
		/* Called before we submit the form */ 
		onSubmit: function(form){
			// Make sure at least there is 1 todo
			if( $(form).find('input[name="todo\\[\\]"][value!=""]').length == 0 )
			{
				alert(S.text['error.todo.add.empty']); 
				return false;
			}
			
			// Rebuild state array (only during editing for now)
			var states = '';
			var doneby = '';
			var doneon = '';
			$(form).find('div.stream-post-todo-list li').each(function(index){
				if($(this).find('input').hasClass('todo-done') ){
					states += '1,';
				} else {
					states += '0,';
				}
				
				doneby += $(this).find('input').attr('done_by')+',';
				doneon += $(this).find('input').attr('done_on')+',';
			});
			$(form).find('input[name="states"]').val(states);
			$(form).find('input[name="done_by"]').val(doneby);
			$(form).find('input[name="done_on"]').val(doneon);

			return true;
		},
		
		onAfterSubmit: function(){
			// Delete all todo input, except the first one
			$('#stream-post-todo li:first').siblings().remove();
			
			// Clear up that remaining input box
			$('#stream-post-todo input[name="todo\\[\\]"]').val('');
		}
	},
	
	/* Event message type */
	event: {
		init: function() {
		
			/* milestone checklist */
			$('input[name="event_type"]').live('change', function(){
				// If selected, disable end_date and chance start date 
				// to due date
				if($(this).prop('checked')){
					$('div#stream-post-event input[name="start_date"]').attr('placeholder', 'Due date');
					$('div#stream-post-event input[name="end_date"]').hide();
					$('div#stream-post-event input[name="type"]').val('milestone');
				} else {
					$('div#stream-post-event input[name="start_date"]').attr('placeholder', 'Start date');
					$('div#stream-post-event input[name="end_date"]').show();
					$('div#stream-post-event input[name="type"]').val('event');
				}
			});
			
			/* Start/end time show/hide */
			$('a[href="#selectTime"]').live('click', function(){
				$(this).hide();
				$(this).siblings('select').show().trigger('click');
				return false;
			});
	
			/* Event - attending */
			$('li.type_event a[href="#follow"]').live("click", function()
			{
				var stream_id = S.utils.getMessageId(this);
				
				$.ajax({
					type: "POST",
					url: S.path['event.follow'],
					data: 'stream_id='+stream_id,
					dataType: 'json',
					cache: false,
					success: function(data){
						// Update the message in the current stream
						$('li#message_' + data.id ).replaceWith(data.html);
					}
				});
				
				return false;	
			});
			
			/* Event won't attend */
			$('li.type_event a[href="#unfollow"]').live("click", function()
			{
				var stream_id = S.utils.getMessageId(this);
				$.ajax({
					type: "POST",
					url: S.path['event.unfollow'],
					data: 'stream_id='+stream_id,
					dataType: 'json',
					cache: false,
					success: function(data){
						// Update the message in the current stream
						$('li#message_' + data.id ).replaceWith(data.html);
					}
				});
				
				return false;	
			});

		},
		
		updateCalendar: function(month, year){
			$.ajax({
				type: "POST",
				url: S.path['event.updateCalendar'],
				data: {month:month, year:year},
				dataType: 'json',
				cache: false,
				success: function(data){
					// Update the message in the current stream
					$('#stream-calendar').html(data.html);
					eval(data.script);
				}
			});
		},
		
		onSubmit: function(form){
			// make sure it is not empty
			if( $(form).find('#stream-post-event textarea[name="message"][value!=""]').length == 0 )
			{
				alert(S.text['error.message.add.empty']);
				return false;
			}
			
			// append start/end time to the date
			var start_date = $(form).find('input[name="start_date"]').val() + ' ' +$(form).find('select[name="start_time"]').val();
			$(form).find('input[name="start_date"]').val(start_date);
			
			// append start/end time to the date
			var end_date = $(form).find('input[name="end_date"]').val() + ' ' +$(form).find('select[name="end_time"]').val();
			$(form).find('input[name="end_date"]').val(end_date);
			
			return true;
		},
		
		onAfterSubmit: function(){
			// clear form 
			$('.stream-post-details input').val('');
		}	
	},
	
	/** Milestone **/
	milestone: {
		init: function(){
			/* Milestone updated */
			$('li.type_milestone a[href="#completed"], li.type_milestone a[href="#uncompleted"]').live("click", function()
			{
				var message_id = S.utils.getMessageId(this);
				var status = ($(this).attr('href') == '#completed') ? '1' : '0';
				$.ajax({
					type: "POST",
					url: S.path['milestone.completed'],
					data: {message_id : message_id,
							status : status},
					dataType: 'json',
					cache: false,
					success: function(data){
						// Update the message in the current stream
						$('li#message_' + data.id ).replaceWith(data.html);
					}
				});
				
				return false;	
			});
		},
		
		onSubmit: function(form){
		},
		
		onAfterSubmit: function(){
		}
	},
	
	/** Page message type **/
	page: {
		onSubmit: function(form){
			// fill the 'message' with editor's content
			$(form).find('#message-box-page').val(tinyMCE.getInstanceById('message-box-page').getContent());
		},
		
		onAfterSubmit: function(){
			// Clear page content
			tinyMCE.getInstanceById('message-box-page').setContent('');
			$('input[name="title"]').val('');
		}
	},
	
	/* videos */
	video: {
		init: function()
		{
			// Click on the video thumbnail
			$('div.message-content-video img').live('click', function(){
				var video_id = $(this).attr('video_id');
				S.video.play(video_id);
			});
		},
		play: function(video_id){
			$.ajax({
				type: "POST",
				url: S.path['video.play'],
				data: {video_id: video_id},
				cache: true,
				dataType: 'json',
				success: function(data){
					// Clear the message box
					$('#video-'+video_id).html(data.html);
				}
			});
		}
	},
	
	/** Groups **/
	groups: {
		init: function(){
			/* Click on "followers" and show the followers list */
			$('a[href="#showFollowers"]').live("click", function()
		    {
		    	var group_id = S.utils.getGroupId(this);
				$().modal('show', S.text['edit'], function(){
		    		$.ajax({
						type: "POST",
						url: S.path['group.followers'],
						data: 'group_id='+group_id,
						cache: true,
						dataType: 'json',
						success: function(data){
							// Clear the message box
							$().modal('content', data.html);
							//$().modal('title', data.title);
							//$().modal('actions', data.actions);
						}
					});
				});
				
				return false;
			});
			
			/* Click on message edit modal box */
		    $('input[name="groups-edit-save"]').live("click", function()
		    {
		    	// test and make sure field is not empty
		    	if( $('form[name="groups-edit"] input[name="name"]').val() != ''){
					$.ajax({
						type: "POST",
						url: S.path['group.save'],
						data: $('form[name="groups-edit"]').serialize(),
						cache: false,
						dataType: 'json',
						success: function(data){
							// Close the box
							$().modal('hide');
							
							// Update the message in the current stream
							window.location = (data.redirect);
						}
					});
				}
				
				return false;
			});
		},
		
		create: function(e){
			/* Show group edit popup */
			$().modal('show', S.text['edit'], function(){
	    		$.ajax({
					type: "POST",
					url: S.path['group.edit'],
					data: 'group_id='+S.utils.getGroupId(e),
					cache: false,
					dataType: 'json',
					success: function(data){
						$().modal('content', data.html);
						$().modal('title', data.title);
						$().modal('actions', data.actions);
					}
				});
			});
		},
		
		archive : function(e) {
			/* Show group edit popup */
			// @todo: ajax confirm?
			if( confirm(S.text['confirm.group.archive'])){
	    		$.ajax({
					type: "POST",
					url: S.path['group.archive'],
					data: 'group_id='+S.utils.getGroupId(e),
					cache: false,
					dataType: 'json',
					success: function(data){
						// Clear the message box
						window.location = (data.redirect);
					}
				});
			}
		},
		
		unarchive : function(e) {
			/* Show group edit popup */
			// @todo: ajax confirm?
			//if( confirm("Are you sure you want to archive this group?"))
			
	    		$.ajax({
					type: "POST",
					url: S.path['group.unarchive'],
					data: 'group_id='+S.utils.getGroupId(e),
					cache: false,
					dataType: 'json',
					success: function(data){
						// Clear the message box
						window.location = (data.redirect);
					}
				});
			
		},
		
		remove : function(e) {
			/* Show group edit popup */
			// @todo: ajax confirm?
			if( confirm(S.text['confirm.group.delete'])){
	    		$.ajax({
					type: "POST",
					url: S.path['group.delete'],
					data: 'group_id='+S.utils.getGroupId(e),
					cache: false,
					dataType: 'json',
					success: function(data){
						// Clear the message box
						window.location = (data.redirect);
					}
				});
			}
		}
	},
	
	/* Auto suggest */
	suggest: {
		started: false,
		active: false,
		current: null,
		startCaret: 0,
		type: '',
		textarea: null,
		add: function( word ){ 
			var message = $(S.suggest.textarea).val();
			message = message.substring(0, S.suggest.startCaret + 1);
			S.suggest.textarea.val(message + word);
			S.suggest.stop();
		},
		
		start: function(type){
			S.suggest.type = type;
			S.suggest.started = true;
			if(!S.suggest.active){
				var activeTab = $('#stream-post div.stream-post-tabs li.active').find("a").attr("href"); 
				S.suggest.textarea = $(activeTab).find('textarea[name="message"]');
			
				S.suggest.active = true;
				$('.stream-post-suggest').slideDown();
				S.suggest.current = '';
				S.suggest.startCaret = $(S.suggest.textarea).caret().start;
			}
		},
		
		stop: function(){
			if(S.suggest.active){
				S.suggest.active = false;
				$('.stream-post-suggest').slideUp();
			}
		},
		
		update: function (event){
			var charcode = event.charCode;
			var inchar = String.fromCharCode(charcode); 
			if(charcode >=  65 && charcode <= 122){
				var currentCaret = $(S.suggest.textarea).caret().start;
				var message = $(S.suggest.textarea).val();
				S.suggest.current = message.substring(S.suggest.startCaret, currentCaret+1);
				S.suggest.current = S.suggest.current + inchar;
				
				// Trigget ajax call for suggestion
				$.ajax({
					type: "POST",
					 url: S.path['system.suggest'],
					 data: {search : S.suggest.current, type: S.suggest.type },
					 dataType: 'json',
					 cache: true,
					 success: function(data){
					 	$("#stream-post div.stream-post-suggest").html(data.html);
					 }
				});	
			} else if(event.keyCode == 8 || event.keyCode == 46 ){
				// Press delete key
				// Check if we have deleted the # or arrow key
				var currentCaret = ($(S.suggest.textarea).caret().start -1);
				if(S.suggest.startCaret >= currentCaret ){
					S.suggest.stop();
				}
			} else if( inchar == ':' || inchar == '|' || inchar == '?'  || inchar == ' '  ||  event.keyCode == 13   ) {
				// Other end char trigger
				S.suggest.stop();
			}
		}
		
	},
	
	/** Maps **/
	maps: {
		init: function (){
		     if ($('.map-fade') != null || $('.map-heatzone') != null) {
				
				$('.map-fade').live('mouseover',function(e) {
					$(this).find('img:eq(2)').fadeOut(0);
				});

				$('.map-fade').live('mouseout',function(e) {
					$(this).find('img:eq(2)').fadeIn(0);
				});

				$('.map-heatzone').live('mouseover',function(e) {
					$(this).parent().find('img:eq(1)').fadeOut(0);
				});

				$('.map-heatzone').live('mouseout',function(e) {
					$(this).parent().find('img:eq(1)').fadeIn(0);
				});

		     }
		}
	}
    
});

$(function() {
	S.stream.init();
	
	// Initialize post box
    S.postbox.init();
    
    // Initialize messages
	S.message.init();
	
	// Initialize comments
	S.comment.init();
	
	// Initialize comments
	S.like.init();
	
	// Initialize todos
	S.todo.init();
	
	// Initialize events
	S.event.init();
	S.milestone.init();
	
	// Initialize groups
	S.groups.init();
	
	// Initizlize maps
	S.maps.init();
	
	// Initialize search
	S.search.init();
	
	// Initialize videos
	S.video.init();
	
	// Init popover
	//$('li.groups li a').popover({html:true, live:true});
	$('li.groups a').popmodal({html:true, live:true, placement: 'right'});
	$('td.running').popmodal({html:true, live:true, placement: 'below'});
	
	// @todo: optimize this, this is pretty horrible
	$("div.message-content-preview img[rel]").live('click', function(){
		if( !$(this).data('overlay_bind')){
			$(this).data('overlay_bind', true);
			$(this).overlay({effect: 'apple'});
			$(this).trigger('click');
			
		}
	});
	
	// Autogrow comment
	$('textarea[name="message"]').autoGrow();
	$('textarea[name="comment"]').autoGrow();
	
	// Initializa drop down
	$('a[href="#selectOption"]').live( 'click', function(){
		return S.utils.setupOptionSelect(this);
	});
	
	// Support for IE input placeholder
 	if(typeof Modernizr != 'undefined'){
		if(!Modernizr.input.placeholder){
	
			$("input").each(
			 function(){
				 if($(this).val()=="" && $(this).attr("placeholder")!=""){
					 $(this).val($(this).attr("placeholder"));
					 $(this).focus(function(){
					 	if($(this).val()==$(this).attr("placeholder")) $(this).val("");
					 });
					 $(this).blur(function(){
					 	if($(this).val()=="") $(this).val($(this).attr("placeholder"));
					 });
				 }
			 });	 
		}
	}
	
	
	
	
	

	/* http://www.tinymce.com/tryit/listbox_splitbutton.php */
   tinyMCE.init({
      mode : "none",
      theme : "advanced",
      	content_css : S.path['system.root'] +"media/editors/tinymce/jscripts/tiny_mce/themes/simple/skins/default/stream_content.css",
		theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,undo,redo,link,unlink",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		plugins : "autoresize"


   });

});
$(document).load(function() {
	// this should be toggle beforehand as it will be immediately toggle off when dom is ready
	// the purpose is when a user try to create a new blog, the user will be navigate to the home page
	// and the blog link on the postbox is triggered. however, in mobile view, the postbox is hidden as a default
	// so the postbox need to be triggered programmatically.
	// double toggle will negate the effect on postbox being displayed on initial load
	// @todo !important: THIS IS NOT A PERMANENT SOLUTION
	S.postbox.toggle($('#compose'), false);
});

$(document).ready(function() {
	// display loading gif on every ajax since the user will be waiting for response
	$('#loading').ajaxStart(function() {
		$(this).show();
	});
	// hide loading when the ajax is complete
	$('#loading').ajaxSuccess(function() {
		$(this).hide();
	});
	$('.tab').click(function() {
		S.postbox.toggle($('#compose'), true);
	});
});