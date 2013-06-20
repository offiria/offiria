S = {
    jQuery: window.jQuery,
    extend: function(obj){
		this.jQuery.extend(this, obj);
    }
}

S.extend({
	text: [], /* server generated text definitions */
	path: [], /* Server generated ajax path */
	
	utils: {
		/* Return the message id for any given child element within the message */
		getMessageId: function(e){
			// check if the element itself carry the message_id data
			var stream_id = $(e).data('message_id');
			
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
			var g_id = $(e).data('group_id');
			
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
			var target = $(e).data('target-input');
			var name = $(e).html();
			
			$('form input[name="'+target+'"]').val(val);
			$('a.dropdown-toggle[href="#'+target+'"]').html( name + ' <b class="caret">');
			return false;
		},
		// normal sprintf for our language file format
		sprintf: function(str, args) {
			// make this usable for current purpose and enrich when necessary

			//$.apply(this, arguments);
			// make it a readable array
			var args = $.makeArray(arguments);
			// remove the first since its a duplicate of the string
			args.splice(0, 1);
			var replaceString = function(str, args) {
				// replace one by one
				str = str.replace(/%[0-9]\$s/, args[0])
				// remove the first element again
				args.splice(0, 1);
				if (str.match(/%[0-9]\$s/)) {
					replaceString(str, args);
				}
				return str;
			}
			return replaceString(str, args);
		},
		hightlight: function(elemId) {
			var elem = $(elemId);
			elem.css('background-color', '#ffffaa');
			elem.animate({ backgroundColor: '#FFFFFF' }, 4000);
		},
		// this is to disable additional call that achievable via multiple clicks
		// button passed will be disable until the ajax is complete
		buttonWaitingForAjax: function(button) {
			$button = $(button);
			$button.one('ajaxStart', function() {
				$button.attr('disabled', true);
			});
			$button.one('ajaxComplete', function() {
				$button.attr('disabled', false);
			});
			$button.click(function(e) {
				e.preventDefault();
			});
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
					
					// add filename data attributes
					$(item).find('div.message-content-file').attr('data-filename', fileName);
					
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
			var message_id = S.utils.getMessageId(e);
			// Load waiting message
			// Destroy any previous modal
			if ($("#preview-modal").length > 0){
				$("#preview-modal").remove();
			}			
			$('body').append('<div id="preview-modal" class="modal hide modal-wide" style="max-height:900px;width: 750px; margin: -250px 0 0 -375px;bottom:30px;top:280px"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">×</button><h3>'+file_name+'</h3></div><div class="modal-body" style="bottom:0px;max-height:900px">Loading</div></div>');
			$("#preview-modal div.modal-body").html('<img style="width: 220px;   height: 20px;   position: absolute;   left: 50%;   top: 50%;    margin-left: -140px;   margin-top: -10px;" src="/templates/offiria/images/hloading.gif">');
			$("#preview-modal").modal({ backdrop:true, show:true });
			S.preview.load(file_id, message_id );
			return false;
		},
		
		load:function( file_id, message_id ){
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
					 S.message.isViewed(message_id, file_id, 'file');
				 	if( data.reload ){
				 		// Only reload if the modal is still open
				 		if($('div#preview-modal').css('display') == 'block'){
				 			S.preview.load(data.file_id, message_id);
				 			//console.log(data.file_id);
				 		}
					}
				 	// set proper height
				 	$('div#preview-modal div.modal-body').css('height', $('#preview-modal').height() - 90 );
				 	$('div#preview-modal div.modal-body').html(data.html);
				 }
			});
		}
	},
	
	alert : {
		hide: function(e){
			$(e).closest('div.alert').slideUp();
			var alert_id = $(e).closest('div.alert').data('alert_id');
			 
			$.ajax({
				type: "POST",
				 url: S.path['system.hidealert'],
				 data: {alert_id: alert_id},
				 dataType: 'json',
				 cache: true,
				 success: function(data){
				 	
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
					// between these interval request, there is another empty request that catchable via ajax
					// this becomes problem as the notification detect the pHash as a new pHash and keep reassigning between
					// the concurrent request while maintaining the state of notification as new, breaking this empty request
					// will prevent the notification from updating itself
					else if ($.isEmptyObject(data.notification)) {
						return;
					}
					else if(data.pHash != S.notification.pHash) 
					{
						S.notification.pHash = data.pHash;
						//S.notification.gHash = data.gHash;
						// Update notification bubble
						if (typeof data.notification != undefined ){
							var totalUpdateCount = 0;
							$.each(data.notification, function(key, value) {
								// collect all counts 
								if (value != 0) {
									$('span#'+key).html(value); 
									$('span#'+key).show();
									$('ol#stream div.'+key +' a').html( value + ' new updates');
									$('ol#stream div.'+key +' a').attr('limit', value);
								}
								else {
									$('.side-notification a').html(S.text['label.no.new.notification']);
									$('.side-notification span').hide();
									$('span#'+key).hide();
									$('ol#stream div.'+key).hide();
								}
								// used for main notification
								totalUpdateCount += +value;
							});
							// rewrite the value until notification is finalize
							totalUpdateCount = data.notification.company_updates;
							if (totalUpdateCount != undefined && totalUpdateCount != 0) { 
								// rename the document title so user will able to see the notification count
								var pageTitle = (totalUpdateCount > 0) ? '(' + totalUpdateCount + ')' : '';
								document.title = pageTitle + ' ' + document.title.replace(/\(\d+\)\s?/, '');

								// $('ol#stream div.'+key).show();
								$('.side-notification span').html(totalUpdateCount);
								$('.side-notification a').html(S.text['label.new.notification']);
								$('.side-notification span').show();
							}
						}
						
						// Update notification data content - (the popover)
						if (typeof data.data_content != undefined ){
							var totalUpdateString = '';
							$.each(data.data_content, function(key, value) {
								// used for main notification
								if (value.length > 0) {
									totalUpdateString+= value.replace(/<\/?ul>/g, '');
								}
								$(key).attr('data-content', value);
							});
							$('.side-notification a').attr('data-content', '<ul>' + totalUpdateString + '</ul>');
						}
						
						// Update comment notification
						if (typeof data.comments != undefined ){
							$.each(data.comments, function(key, value) {
								if(value != 0)
								{ 
									$('a#'+key+ '_link').find('.comment-notice').show();
								}
								else
								{
									$('a#'+key+ '_link').find('.comment-notice').hide();
								}
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
					
					// If there is no data yet, no need to test for first item,
					if( $("ol#stream li" ).length == 0 ){
						$("ol#stream").html(data.html);
						$("ol#stream li:first").slideDown("slow");
					} else {
						$("ol#stream li:first").before(data.html);
						$("ol#stream li:first").slideDown("slow");
					}
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
		    }).done(function( data ) {
		    	$('#stream-post-loading').hide();
		    	
		    	// Re-enabled form elements and submit form
				$(activeType + ' :input, #stream-form input[type="submit"]').attr("disabled", false);
				
				$('#message-box').height($('#message-box').data('original-height'));				
				$('#message-box-page_ifr').height($('#message-box').data('original-height'));

				S.links.unlink();
				$('div.stream-loading').remove();
				
				// Trigger onAfterSubmit
				// @todo: check make sure function exist first
				trigger = 'S.'+type+'.onAfterSubmit( data );';
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
			$('#message-box').data('original-height', $('#message-box').height());
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
				if(activeTab == '#stream-post-page') {
					tinyMCE.execCommand("mceAddControl", true, "message-box-page");
				}
				//	CKEDITOR.replace($('textarea.message-page-editor').get());

				// reset end date condition, for non-event type date no minDate should be set
				$(this).find('.end-date').datepicker('enable')
					.datepicker('option', 'minDate', 0);
					
				return false;
			});
			
			// Initialize it with a click
			$(".stream-post-tabs li.active").click();

			// === MINIMIZE POSTBOX ===
			// make the stream box expand, so we could save a few space at the topfold of the page
			$('#stream-post-update #message-box').focus(function(e) {
				var $container = $(this).parents('.minimized');
				$container.removeClass('minimized');
			});

			// remove minimization after the activation (clicked) event is complete
		    $('.stream-post-tabs li').click(function() {
				$('#stream-post-message').removeClass('minimized');
			});
			// === END MINIMIZE ===
			
			// Initizlize date picker
			//$( "input.start-date, input.end-date" ).datepicker({ dateFormat: 'yy-mm-dd' });
			$( "input.start-date" ).datepicker({ 
				dateFormat: 'yy-mm-dd',								
				onSelect: function(dateText, inst) {
					var startDate = $(this).val();
					var endDate = $(this).parent().next().find('.end-date').val() || '';
					if (endDate != '')
					{
						var splitStartDate = startDate.split(/-/);
						startDate = new Date(splitStartDate[0], splitStartDate[1], splitStartDate[2]);

						var splitEndDate = endDate.split(/-/);
						endDate = new Date(splitEndDate[0], splitEndDate[1], splitEndDate[2]);

						if (startDate > endDate)
						{
							$(this).parent().next().find('.end-date').val($(this).val());
						}
					}
					// make sure this only applies to event mode
					var type = $('#stream-form input[name="type"][disabled!="disabled"]').val();
					if (type == 'event') {
						// endDate should start with the selected startDate
						var selectedStartDate = $(this).datepicker('getDate');
						selectedStartDate = new Date(selectedStartDate.getTime());
						selectedStartDate.setDate(selectedStartDate.getDate());
						$(this).parent().next().find('.end-date').datepicker('enable')
							.datepicker('option', 'minDate', selectedStartDate)
						// by default, unintialized end-date datepicker will send a null end date which create wrong entry
							.datepicker('setDate', selectedStartDate);
						$('a[href="#selectTime"]').trigger('click');
						$('select[name="start_time"]').trigger('change');
					}
				}
			});
			$( "input.end-date" ).datepicker({
				dateFormat: 'yy-mm-dd',
				onSelect: function(dateText, inst) {
					var endDate = $(this).val();
					var startDate = $(this).parent().prev().find('.start-date').val() || '';
					
					var splitStartDate = startDate.split(/-/);
					startDate = new Date(splitStartDate[0], splitStartDate[1], splitStartDate[2]);

					var splitEndDate = endDate.split(/-/);
					endDate = new Date(splitEndDate[0], splitEndDate[1], splitEndDate[2]);

					if (startDate > endDate)
					{
						$(this).parent().prev().find('.start-date').val($(this).val());
					}
					// run the time validation again
					$('select[name="start_time"]').trigger('change');
				}
			});
			//$( "input.start-date, input.end-date" ).dateinput({format: 'yyyy-mm-dd'});
	
			// empty out the message box
			$('#message-box').val('');
		}
    },
	tag: {
		init: function() {
			$('.tag-add').live('click', function(e) {
				e.preventDefault();
				S.tag.add(this);
			});

			$('.tag-cancel').live('click', function(e) {
				S.tag.showTagEdit(this, false);
			});

			$('.tag-remove').live('click', function(e) {
				S.tag.remove(this);
			});

			$('a[href="#editTags"]').live('click', function(e) {
				$('.tag-element span a').click(function(e) {
					e.preventDefault();
				});
				S.tag.showTagEdit(this);
				S.tag.bindTypeAhead(this);
				e.preventDefault();
			});
		},
		showTagEdit: function(sender, show) {
			if (typeof show === "undefined" || show===null) show = true;
			var stream_id = S.utils.getMessageId(sender);
			var message = $('li#message_' + stream_id);

			// Clear the error message
			S.enqueueMessage();

			if(show) {
				message.find('.message-content-tag').hide();
				message.find('.message-content-tag-edit').show();
				message.find('.tag-input').focus();
			} else {
				message.find('.message-content-tag').show();
				message.find('.message-content-tag-edit').hide();
			}
		},
		add: function(sender) {
			var stream_id = S.utils.getMessageId(sender);
			var message = $('li#message_' + stream_id);
			var tag = $(sender).parents('.tag-container-parent').find('.tag-input').val();

			// Display the error over here
			var injectErrorTo = message.find('.message-content-tag-error');

			// Clear the error message
			S.enqueueMessage();

			if($.trim(tag).length === 0) {
				S.enqueueMessage(S.text['error.tag.assign.empty'], 'error', injectErrorTo);
			} else if(!/^[a-z0-9\ \'-]+$/i.test(tag)) {
				S.enqueueMessage(S.text['error.tag.unsupported.characters'], 'error', injectErrorTo);
			} else if($.trim(tag).length > 20) {
				S.enqueueMessage(S.utils.sprintf('<b>%1$s-character</b> limit reached', 20), 'error', injectErrorTo);
			} else  {
				$.ajax({
					type: "POST",
					url: S.path['tag.add'],
					data: {message_id : stream_id, tag : tag},
					cache: false,
					dataType: 'json',
					success: function(data){
						// Update the message in the current stream
						message.find('.message-content-tag-parent').replaceWith(function() {
							return $(data.html);
						});
						// get updated message
						var newMessage = $('li#message_' + stream_id);
						// hide tags and show edit box
						newMessage.find('.message-content-tag').hide();
						newMessage.find('.message-content-tag-edit').show();
						// put focus back on add tag field
						newMessage.find('.tag-input').focus();
						S.tag.bindTypeAhead(newMessage.find('.tag-input'));
					}
				});

				return false;
			}
		},
		remove: function(sender) {
			var closeButton = $(sender).closest('.tag-element').find('a:first');
			var stream_id = S.utils.getMessageId(sender);
			var message = $('li#message_' + stream_id);

			$.ajax({
				type: "POST",
				url: S.path['tag.delete'],
				data: {message_id : stream_id, tag : closeButton.text()},
				cache: false,
				dataType: 'json',
				success: function(data){
					// Update the message in the current stream
					message.find('.message-content-tag-parent').replaceWith(function() {
						return $(data.html);
					});
					// get updated message
					var newMessage = $('li#message_' + stream_id);
					// hide tags and show edit box
					newMessage.find('.message-content-tag').hide();
					newMessage.find('.message-content-tag-edit').show();
					S.tag.bindTypeAhead(newMessage.find('.tag-input'));

					// Clear the error message
					S.enqueueMessage();
				}
			});
		},
		bindTypeAhead: function(sender) {
			var stream_id = S.utils.getMessageId(sender);
			var message = $('li#message_' + stream_id);
			var tagInput = message.find('.tag-input');
			// prevent default action if the typeahead doesnt exist yet
			tagInput.on('keypress', function(e) {
				switch(e.keyCode) {
				case 9: // tab
				case 13: // enter
					e.preventDefault();					
					S.tag.add(this);
					break;
				}
			});
			tagInput.typeahead({
				source: function(typeahead, query) {
					$.ajax({
						type:"POST",
						url:S.path['tag.autocomplete'],
						data:{query:query},
						cache:false,
						dataType:'json',
						success:function (data) {
							// Get the tags already used in the message
							var existList = [];
							message.find('.tag-container li').each(function (i, v) {
								existList.push($(v).find('a').text())
							});

							// Exclude them from suggested tags
							var diff = new Array();
							diff = jQuery.grep(data, function (item) {
								return jQuery.inArray(item, existList) < 0;
							});

							typeahead.process(diff);
						}
					});
				},
				onselect: function(val) {
					S.tag.add(this.$element);
				},
				onenterkey: function() {
					S.tag.add(this.$element);
				}
			});
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
		    });
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
		    });
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
			
			/* Generic filtering */
			$('a[href="#filterMassage"]').live("click",function(){
    			$(this).closest('ul').find('li').removeClass('active');
				$(this).closest('li').addClass('active');

				var filter = $(this).data('filter-data');
    			
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
							if (html == null || html == undefined)
							{
								html = $(data.html).eq(7).html();
							}
							$('.pagination').html(html);
							
							$('.stream-loading').remove();
						}
						 // resize the element since filtering might have different length of content
						 // reset first
						 $('.sidebar-right-inner').attr('style', '');
						 // adjust to height
						 if ($('.sidebar-right-inner').length && $('.sidebar-left-inner').length) {
							 var $sidebarLeftInner = $('.sidebar-left-inner');
							 var $sidebarRightInner = $('.sidebar-right-inner');
							 var higherHeight = Math.max($sidebarLeftInner.height(), $sidebarRightInner.height());
							 // adjust to higher height
							 $sidebarRightInner.height(higherHeight);
						 }
					 }
				});
				
				$(this).blur();
    			return false;
			});
			
    		/* Filtering */
    		$('a[href="#filterMentions"], a[href="#filterAll"], a[href="#filterMine"], a[href="#filterDue"]').live("click",function(){
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
				
				$(this).blur();
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
				var editType = $(this).data('type') || '';
		    	var stream_id = S.utils.getMessageId(this);
		    	
				//$().modal('show', S.text['edit'], function(){
		    		$.ajax({
						type: "POST",
						url: S.path['message.edit'],
						data: {message_id: stream_id, edit_type: editType},
						cache: false,
						dataType: 'json',
						success: function(data){
							// Clear the message box
							//$().modal('content', data.html);
							//$().modal('title', data.title);
							//$().modal('actions', data.actions);
							//$().modal('hide');
							
							$('li#message_'+ stream_id).html(data.html);
							
							// Autogrow textarea
							$('#stream-post-update textarea').autoGrow({minHeight:60});
							//$().modal('resize');
							
							// set up date picker if necessary
							$('li#message_'+ stream_id + ' input.start-date').datepicker({ 
								dateFormat: 'yy-mm-dd',								
								onSelect: function(dateText, inst) {
									var startDate = $(this).val();
									var endDate = $(this).parent().next().find('.end-date').val() || '';
									if (endDate != '')
									{
										var splitStartDate = startDate.split(/-/);
										startDate = new Date(splitStartDate[0], splitStartDate[1], splitStartDate[2]);

										var splitEndDate = endDate.split(/-/);
										endDate = new Date(splitEndDate[0], splitEndDate[1], splitEndDate[2]);

										if (startDate > endDate)
										{
											$(this).parent().next().find('.end-date').val($(this).val());
										}
									}
									// endDate should start with the selected startDate on event mode only
									var type = $('#stream-form input[name="type"][disabled!="disabled"]').val();
									if (type == 'event') {
										var selectedStartDate = $(this).datepicker('getDate');
										selectedStartDate = new Date(selectedStartDate.getTime());
										selectedStartDate.setDate(selectedStartDate.getDate());
										$('li#message_'+ stream_id + ' input.end-date').datepicker('option', 'minDate', selectedStartDate);
									}
								} 
							});
							$('li#message_'+ stream_id + ' input.end-date').datepicker({ 
								dateFormat: 'yy-mm-dd',								
								onSelect: function(dateText, inst) {
									var endDate = $(this).val();
									var startDate = $(this).parent().prev().find('.start-date').val() || '';

									var splitStartDate = startDate.split(/-/);
									startDate = new Date(splitStartDate[0], splitStartDate[1], splitStartDate[2]);

									var splitEndDate = endDate.split(/-/);
									endDate = new Date(splitEndDate[0], splitEndDate[1], splitEndDate[2]);

									if (startDate > endDate)
									{
										$(this).parent().prev().find('.start-date').val($(this).val());
									}

									// trigger the time change on event mode only
									var type = $('#stream-form input[name="type"][disabled!="disabled"]').val();
									if (type == 'event') {
										// run the time validation again
										$('select[name="start_time"]').trigger('change');
									}
								} 
							});
							// not all stream have event form inside, thus error will occur
							if ($('li#message_'+ stream_id + ' input.end-date').length > 0) {
								// since we cannot trigger the event of datepicker onSelect, duplication is easier than modifying entire plugin
								var selectedStartDate = $('li#message_'+ stream_id + ' input.start-date').datepicker('getDate');
								selectedStartDate = new Date(selectedStartDate.getTime());
								selectedStartDate.setDate(selectedStartDate.getDate());
								$('li#message_'+ stream_id + ' input.end-date').datepicker('option', 'minDate', selectedStartDate);
							}

							$( "#cWindow input.start-date, #cWindow input.end-date" ).datepicker({ dateFormat: 'yy-mm-dd' });
							
							// if shared link is inside, allow removing of link
							$('li#message_'+ stream_id + ' .stream-message-links-remove').click(function() {
								$(this).parent('.stream-message-links-edit').remove();
								$('li#message_'+ stream_id + ' input[name="linkable_link"]').val('');
							});
							
							// If there is any js, excute them
							eval(data.script);
						}
					});
				//});

				return false;	
		    });
		    
		    /* Click save on message edit */
		    $('[name="message-edit-save"]').live("click", function()
		    {
		    	var stream_id = S.utils.getMessageId(this);
		    	var type = $('#message_'+ stream_id + ' form[name="message-edit"]').find('input[name="type"]').val();
		    	// Call onSubmit trigger. If it return a false, do not proceed with the submit
				// The todo/etc is responsible for displaying its one errror message
				// @todo: check make sure function exist first
				var trigger = 'S.'+type+'.onSubmit(\'#message_'+ stream_id + ' form[name="message-edit"]\');';
				if( eval(trigger) == false ){
					return false;
				}
				
				$.ajax({
					type: "POST",
					url: S.path['message.save'],
					data: $('#message_'+ stream_id + ' form[name="message-edit"]').serialize(),
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
			
			/* Click cancel on message edit */
		    $('[name="message-edit-cancel"]').live("click", function()
		    {
				var stream_id = S.utils.getMessageId(this);
				$.ajax({
					type: "POST",
					url: S.path['message.fetch'],
					data: $('#message_'+ stream_id + ' form[name="message-edit"]').serialize(),
					cache: false,
					dataType: 'json',
					success: function(data){
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

				// If there is no File-id, that means, the file upload has failed, Find the nearest <li> parent
				// and simply remove it
				if(file_id != 'undefined'){		    	
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
				}
				else
				{
					// Just remove the <li> element
					$(this).parents('li').remove();
				}
				
				return false;	
		    });
		    
		    /* Unline attachment. Doesn't get deleted, used during message editing */
		    $('.message-content-file a[href="#unlinkAttachment"]').live("click",function()
		    {
		    	$(this).parents('li.qq-upload-success').remove();
		    	return false;
			});
			
		},
		isViewed: function(message_id, item_id, type) {
			var EMBED_TYPE = 'embed_';
			var FILE_TYPE = 'file_';
			var VIDEO_TYPE = 'video_';
			var LINK_TYPE = 'link_';
			var SLIDESHARE_TYPE = 'slideshare_';

			switch (type) {
			case 'embed': item_id = EMBED_TYPE+item_id;
				break;	  
			case 'file': item_id = FILE_TYPE+item_id;
				break;	  
			case 'videos': item_id = VIDEO_TYPE+item_id;
				break;
			case 'link': item_id = LINK_TYPE+item_id;
				break;
			case 'slideshare': item_id = SLIDESHARE_TYPE+item_id;
				break;
			default:
				item_id = item_id;
			}

			$.ajax({
				'url': S.path['message.viewed'],
				'data': { message_id: message_id,
						  item_id: item_id }
			}).done(function() {
				console.log('updated');
			});
		}
	},
	
	/* Comments */
	comment: {
	
		init: function(){
			$('textarea[name=comment]').each(function(idx, el) {
			});

			/* Comment Save button */
			$('.comment-action button').live("click",function()
		    {
				S.enqueueMessage();
				var commentForm = $(this).parents('form').find('textarea[name="comment"]');
				// Make sure comment is not empty
				if (!S.validate.element('notEmpty', commentForm, $(commentForm).parent(), 'comment')) {
					return false;
				}

				var data = $(this).parents('form').serialize();
				var stream_id = S.utils.getMessageId(this);
				
				S.utils.buttonWaitingForAjax($(this));
				$.ajax({
					type: "POST",
					url: S.path['comment.add'],
					data: data,
					cache: false,
					success: function(html){
						// Clear the message box
						$('#message_' + stream_id + ' .stream-comment textarea[name="comment"]').val('').attr('style', '');

						// Append the response to the last item above the form
						$('#message_' + stream_id + ' .stream-comment .comment-form').before(html);
						
						// Close comment box
						$('#message_' + stream_id + ' a[href="#cancelPostinComment"]').click();
					}
				});

				return false;
			});
			
			/* Delete comment */
		    $('div.comment-item a[href="#delete"]').live("click",function()
		    {
				var comment_id = S.utils.getCommentId(this);
				var stream_id = S.utils.getMessageId(this); 
				$.ajax({
					type: "POST",
					url: S.path['comment.delete'],
					data: { comment_id: S.utils.getCommentId(this)},
					cache: false,
					dataType: 'json',
					success: function(data){
						// Remove the comment
						$('#comment-'+ comment_id).remove();
						
						// If the reply box is empty, put it back in
						// TODO: uncomment this part to re-open the feature where deleted comment added back to post new comment, like an edit function
						/*if($('#message_' + stream_id + ' .stream-comment textarea[name="comment"]').val().length == 0)
						{
							$('#message_' + stream_id + ' .stream-comment a[href="#reply"]').click();
							$('#message_' + stream_id + ' .stream-comment textarea[name="comment"]').val(data.comment);
						}*/
						
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
						if (/^#comment-[0-9]+$/i.test(document.location.hash) && $('#message_' + stream_id).find(document.location.hash).length) {
							S.utils.hightlight(document.location.hash);
						}
						// !NOTE: when clicked on the show all comment while a user about to comment, the comment is cleared
						// this will remove the comment that about to be commented by the user and discrupts the flow
						// disabling this.
						// $('#message_' + stream_id + ' .stream-comment textarea[name="comment"]').val('');
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
				var textarea = $('#message_' + stream_id + ' .stream-comment .comment-form form textarea');

				var peopleAutocomplete = '';
				$.ajax({
					type:"POST",
					url:S.path['people.xautocomplete'],
					cache:false,
					dataType:'json',
					success:function (data) {
						peopleAutocomplete = data;
						textarea.typeahead({
							items: 3,
							menu: '<ul class="people-autocomplete dropdown-menu"></ul>',
							item: '<li><table><tr><td><img class="people-autocomplete-thumb" /></td><td><a href="#"></a></tr></table></li>',
							property: 'value',
							matcher: function(item) {
								var lastPos = this.query.lastIndexOf('@');
								var query = this.query.substring(lastPos).match(/.*(@[a-zA-Z]+).*/i) || '';
								query = (query && query[1]) ? query[1].substring(1) : '';
								return item.toLowerCase().match('.*'+query.toLowerCase()+'.*');
							},
							source:function (typeahead, query) {
								// unless the comment is preceed by symbol ignore the autosuggest
								if (!query.match(/.*@[a-z]+$/i)) {
									typeahead.hide();
									return false;
								}
								typeahead.process(peopleAutocomplete);
							},
							highlighter: function(item) {
								var lastPos = this.query.lastIndexOf('@');
								return item.replace(new RegExp('(' + this.query.substring(lastPos + 1) + ')', 'ig'), function ($1, match) {
									return '<strong>' + match + '</strong>'
								})
							},
							onselect:function (subject) {
								var mentioned = '@'+subject.username;
								// always replace the last completion
								var lastPos = this.query.lastIndexOf('@');
								var pattern = '.{0,'+lastPos+'}(@[a-z]+)$';
								var matched = this.query.match(pattern)
								if (matched != null && matched[1] != null) {
									var pre = this.query.substring(0, lastPos);
									var post = this.query.substring(lastPos).replace(matched[1], mentioned);
									var value = pre + post;
									if (textarea.val(value)) {
										textarea.focus();
									}
								}
							}
						});
					}
				});

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

			/* Show the like/remove links on hover */
			$('.comment-item').live("hover",
				function () {
					$(this).find(".comment-option").show();
					$(this).find(".comment-like").parent('span').show();
				}).live("mouseleave",
				function () {
					$(this).find(".comment-option").hide();

					var commentLike = $(this).find(".comment-like");
					// TODO: We probably need another tooltip plugin that supports ajax
					commentLike.tipsy("hide");

					// Hide the count and love icon on zero likes
					if(parseInt(commentLike.html()) <= 0) commentLike.parent('span').hide();
				});

			/* Like comment */
			$('.comment-item a[href="#commentlike"]').live("click", function () {
				var comment_id = S.utils.getCommentId(this);

				$.ajax({
					type:"POST",
					url:S.path['like.comment.like'],
					data:{ comment_id:comment_id},
					dataType:'json',
					cache:false,
					success:function (data) {
						var comment = $('.message-item #comment-' + comment_id + '.comment-item');
						comment.find('a[href="#commentlike"]').html(data.label).attr('href', '#commentunlike');
						comment.find('.comment-like').html(data.count);
					}
				});

				return false;
			});

			/* Unlike comment */
			$('.comment-item a[href="#commentunlike"]').live("click", function () {
				var comment_id = S.utils.getCommentId(this);

				$.ajax({
					type:"POST",
					url:S.path['like.comment.unlike'],
					data:{ comment_id:comment_id },
					dataType:'json',
					cache:false,
					success:function (data) {
						var comment = $('.message-item #comment-' + comment_id + '.comment-item'),
							commentLike = comment.find('.comment-like');
						comment.find('a[href="#commentunlike"]').html(data.label).attr('href', '#commentlike');
						commentLike.html(data.count);
					}
				});

				return false;
			});

			/* Fetch and show comment likes tooltip on hover */
			$('.comment-item .comment-like').live("mouseover",
				function () {
					var comment_id = S.utils.getCommentId(this);

					$.ajax({
						type:"POST",
						url:S.path['like.comment.showall'],
						data:{ comment_id:comment_id },
						dataType:'json',
						cache:false,
						success:function (data) {
							var comment = $('.message-item #comment-' + comment_id + '.comment-item').find('.comment-like');
							comment.attr('original-title', data.likes).tipsy({fallback:'No likes', gravity:'s', trigger:'manual'}).tipsy('show');
						}
					});

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
			// clear the message
			S.enqueueMessage();
			// make sure it is not empty
			if( $(form).find('#stream-post-update textarea[name="message"][value!=""]').length == 0 )
			{
				//alert(S.text['error.message.add.empty']);
				var injectTo = ($(form).attr('id') == 'stream-form') ? 'reset' : $(form);
				S.enqueueMessage(S.text['error.message.add.empty'], 'error', injectTo);
				return false;
			}
			return true;
		},
		
		onAfterSubmit: function(){
			// clear form 
		}	
	},
	
	/* Direct message type (direct/private messaging) */
	/*direct: {
		onSubmit: function(form){
			// clear the message
			S.enqueueMessage();
			// make sure it is not empty
			if( $(form).find('#stream-post-update textarea[name="message"][value!=""]').length == 0 )
			{
				//alert(S.text['error.message.add.empty']);
				var injectTo = ($(form).attr('id') == 'stream-form') ? 'reset' : $(form);
				S.enqueueMessage(S.text['error.message.add.empty'], 'error', injectTo);
				return false;
			}
			return true;
		},
		
		onAfterSubmit: function(){
			// clear form 
		}	
	},*/

	/* Direct message type (direct/private messaging) */
	direct: {
		init:function () {
			$('.compose-direct-message').on('click', function (e) {
				var self = $(this);
				$.ajax({
					url:S.path['direct.compose'],
					dataType:'html',
					success:function (data) {
						$('body').append(data);
						$('#direct-message-modal')
							.modal({ backdrop:true, show:true })
							.on('hidden', function () {
								$(this).remove();
							});

						S.direct.clearRecipients();

						// Get the default recipient from the data-to attribute
						var to = self.data('to') || '';

						if (to.length !== 0) {
							S.direct.addRecipient(to);
						}

						S.uploader.init('direct-file-uploader', 'direct-attachment-list');
						S.direct.bindTypeAhead('.recipient-input');
					}
				});

				e.preventDefault();
			});

			$('#stream-form').live('submit',function(e){
				S.direct.onSubmit(this);
				e.preventDefault();
			});

			$('ol.recipient-list a').live('click', function(event){
				$(this).closest('li').remove();
			});
		},

		bindTypeAhead: function (el){
			$(el).typeahead({
				source:function (typeahead, query) {
					$.ajax({
						type:"POST",
						url:S.path['people.autocomplete'],
						data:{query:query},
						cache:false,
						dataType:'json',
						success:function (data) {
							// Get the people already added to the list
							var existList = [];
							$('.recipient-list').find('li').each(function (i, v) {
								existList.push($(v).find('span').text())
							});

							// Exclude them
							var diff = new Array();
							diff = jQuery.grep(data, function (item) {
								return jQuery.inArray(item, existList) < 0;
							});
							typeahead.process(diff);
						}
					});
				},

				onselect:function (obj) {
					// TODO: why is obj the value?
					S.direct.addRecipient(obj);
				}
			});
		},

		onSubmit: function(el){
			var container = $('#direct-message-modal');
			var form = $(el);
			var recipient = container.find('input[name="recipient"]');
			var recipientList = container.find('.recipient-list').children('li');
			var message = container.find('textarea[name="message"]');
			var postData = form.serialize();

			// Loading indicator
			//var loading = $('#stream-post-loading');
			//loading.show();

			// Display the error over here
			var injectErrorTo = container.find('.modal-body');

			// Clear the error message
			S.enqueueMessage();

			if(message.val().length === 0 || recipientList.length === 0) {
				S.enqueueMessage('You must fill in all the fields', 'error', injectErrorTo);
			} else {
				$.ajax({
					type:"POST",
					url:S.path['message.add'],
					data:postData,
					dataType:'json',
					success:function (data) {
						recipient.val('');
						message.val('');
						recipientList.remove();
						container.find('#direct-attachment-list').html('');
						container.find('#direct-error').html('<a href="' + S.path['direct.inbox'] + '">Your message</a> has been sent.');
						//loading.hide();
					}
				});
			}
		},

		addRecipient: function(username) {
			var inputField = $('.recipient-input'); // TODO: cache
			inputField.val('');
			// TODO: template instead
			inputField.before('<li style=""><span title="">' + username + '</span><a class="close small">&times;</a><input type="hidden" value="' + username + '" name="members[]"></li>');
		},

		clearRecipients: function() {
			$('.recipient-list').children().filter('li').remove();
		}
	},

	/* Todo Message Type*/
    todo: {

		getIndex: function(e){
			// check for the todo_index in the element itself
			var i = $(e).data('todo_index');
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
			$('li.todo-item a[class*="done-todo-item"]:not([class*="readonly"])').live('click', function(e){
				e.preventDefault();
    			$(this).parents('li.todo-item').toggleClass('todo-done');
    			
				// Get current state and send the current state as the user
				// sees. We don't care what it is in the server
				var todoState = $(this).parents('li.todo-item').hasClass('todo-done') ? 1 : 0;
				var currentIndex = S.todo.getIndex(this);
                var stream_id = S.utils.getMessageId(this);
				
				var todoActionType = $(this).data('type') || '';
	    
				// Send ajax call
				$.ajax({
					type: "POST",
					url: S.path['todo.done'],
					dataType: 'json',
					data: {'message_id':stream_id,
							'state': todoState,
							'item': currentIndex
							},
					success: function( data ) {
						// All done, refresh the stream
						$('li#message_' + data.id ).replaceWith(data.html);
						var milestoneId = data.milestone_id || false;
						if (milestoneId && todoActionType == 'list')
						{
							$('li#message_' + data.milestone_id ).replaceWith(data.html2);
						}

						// Execute scripts
						eval(data.script);
						return false;
					}
				});
				

				return false;
			});
			
    		/* Bind #done click */
			$('.complete-todo').live('click', function(){
    			var milestone = $(this).attr('id').split('_')[1];
				var buttonLI  = $(this).parent();
				var status = $(this).data('status');
    			
				// Get current state and send the current state as the user
				// sees. We don't care what it is in the server
	    
				// Send ajax call
				$.ajax({
					type: "POST",
					url: S.path['milestone.completeAll'],
					dataType: 'json',
					data: {'message_id':milestone,
							'status': status},
					success: function (data) {	
						// Execute scripts						
						eval(data.script);
					}
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
			// clear the system message
			S.enqueueMessage();
			// Make sure at least there is 1 todo
			if( $(form).find('input[name="todo\\[\\]"][value!=""]').length == 0 )
			{
				//alert(S.text['error.todo.add.empty']); 
				var injectTo = ($(form).attr('id') == 'stream-form') ? 'reset' : $(form);
				S.enqueueMessage(S.text['error.todo.add.empty'], 'error', injectTo);
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
	
			// If the start time is selected only the time after start_time is available
			var startTime = $('select[name="start_time"]');
			var endTime = $('#stream-post select[name="end_time"]');
			startTime.live('change', function() {
				var selectedStart = $(this).find('option:selected');
				// if the below selector returns none, we are looking from edit inside share box
				var endOptions = $(this).parents('#stream').find('select[name="end_time"] option');
				endOptions = (endOptions.length == 0) ? endTime.find('option') : endOptions;
				$(endOptions).attr('disabled', false);
				// make first option selected by default
				$(endOptions).first().attr('selected', true);

				// we need to traverse element since create/edit have different structure
				var startDatePicker = $(this).parents().find('input.start-date');
				var endDatePicker = $(this).parents().find('input.end-date');
				// if the user have not choose the date yet, ignore
				if (startDatePicker.datepicker('getDate') != null &&
					endDatePicker.datepicker('getDate') != null) {
					// if date (start|end) is not the same, user may select different end time, regardless of start time
					if (startDatePicker.datepicker('getDate').getTime() !=
						endDatePicker.datepicker('getDate').getTime()) {
						return;
					}
				}
				// since the ordering of the select option might be different based on timezone, manually recalculate the position
				// since we have the interval of 30 min increment by a step (not including the final interval (24:00))
				var i, availableSteps = 24 * 2 - 1,
				hoursSteps = [],
				betweenHour = true,
				// mini-optimization
				hourInterval,
				isLastOption = false;
				// to add padding to single integer number
				var fillZeroes = "00";  
				function zeroFill(number, width) {
					var input = number + "";  // make sure it's a string
					return(fillZeroes.slice(0, width - input.length) + input);
				}
				for (i = 0; i <= availableSteps; i++) {
					// between steps add 30 min interval
					hourInterval = (betweenHour) ? '00' : '30';
					hourInLoop = Math.floor(i/2);
					// make sure its zero-filling
					hoursSteps.push(zeroFill(hourInLoop, 2) + ':' + hourInterval);
					betweenHour = !betweenHour;
				}
				// disable all first
				endOptions.attr('disabled', true)
				// get the selected position in steps
				var positionOnSteps = $.inArray($(selectedStart).val(), hoursSteps);
				// only the option forward from available steps will be toggle on
				var $endDateField = $(this).parents('.stream-post-time').next().find('.end-date');
				endOptions.filter(function(idx) {
					// if the value is on bigger than steps enable them
					if ($.inArray($(this).val(), hoursSteps) > positionOnSteps) {
						$(this).attr('disabled', false);
					}
					// make the next available option on steps as selected
					if (hoursSteps[positionOnSteps+1] == $(this).val()) {
						$(this).attr('selected', true);
					}
					// if the options is the last available option 
					if (positionOnSteps+1 >= availableSteps) {
						if (isLastOption) return false;

						// date increment must be outside the filter 
						var selectedStartDate = new Date($endDateField.datepicker('getDate').getTime());
						selectedStartDate.setDate(selectedStartDate.getDate() + 1);

						// change date of selector since its option available is on the next day
						$endDateField.datepicker('option', 'minDate', selectedStartDate);
						$(this).parent().find('option').attr('disabled', false);
						isLastOption = true;
					}
				});
			});

			/* Event - attending */
			$('li.type_event .btn.follow').live("click", function()
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
			$('li.type_event .btn.unfollow').live("click", function()
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

			/* Show all attendees in the event */
			$('.user-more').live("click", function(e) {
				e.preventDefault();
				$(this).hide(0, function () {
					var el = $(this).parent().parent().children('.user-horizontal-list'),
						curHeight = el.height(),
						autoHeight = el.css('height', 'auto').height();

					el.height(curHeight).animate({height:autoHeight}, 300);
				});
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
			S.enqueueMessage();
			// injectTo is a position of where the error message should be display
			var injectTo = ($(form).attr('id') == 'stream-form') ? 'reset' : $(form);
			// validate start and end date
			if (S.validate.isValidDate($(form).find('input[name="start_date"]').val()) === false 
				|| S.validate.isValidDate($(form).find('input[name="end_date"]').val()) === false) {
				S.enqueueMessage('Invalid date is supplied, please check again', 'error', injectTo);
				return false;
			}
			// make sure it is not empty
			if( $(form).find('#stream-post-event textarea[name="message"][value!=""]').length == 0 )
			{
				//alert(S.text['error.message.add.empty']);
				S.enqueueMessage(S.text['error.message.add.empty'], 'error', injectTo);
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
			// hide the event time selection 
			$('a[href="#selectTime"]').show();
			$('a[href="#selectTime"]').siblings('select').hide();
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
		
		onAfterSubmit: function(data){
			//alert(data.script);
			//eval(data.script);
			// Update necessary milestone list
			$.ajax({
				type: "POST",
				url: S.path['milestone.updateSelect'],
				data: {group_id : data.group_id},
				dataType: 'json',
				cache: false,
				success: function(data){
					// Update all current select with new updated drop down
					$('select[name="milestone"]').replaceWith(data.html);
				}
			});
			// empty the date
			$('#stream-post-milestone').find('.start-date').val('');			
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
	
	// links (as in excerpt from shared link)
	links: {
		cleanup: function() {
			$('#stream-form .loading').remove();
			$('.stream-message-links').hide();
			$('.stream-message-links').children().each(function(idx, el) {
				if (el.tagName == 'IMG') {
					$(el).attr('src', '');
				}
				if (el.tagName == 'SPAN' || el.tagName == 'DIV') {
					$(el).html('');
				}
			});
		},
		// some links doesnt need to be fetched
		exception: function(url) {
			var pattern = /.*((youtu\.?be|slideshare).*|\.(exe|dmg|zip|gz|bz2|dll))$/;
			return pattern.test(url);
		},
		unlink: function() {
			S.links.cleanup();
			$('.stream-message-is-linkable').val(false);
		}
	},
	/* videos */
	embed: {
		init: function()
		{
			// Click on the video thumbnail
			$('div.message-content-video img').live('click', function(){
				var embed_id = $(this).attr('embed_id');
				var embed_type = $(this).attr('embed_type');
				S.embed.play(embed_id, embed_type, this);
			});
		},
		play: function(embed_id, embed_type, reference){
			message_id = (S.utils.getMessageId(reference));
			$.ajax({
				type: "POST",
				url: S.path['video.play'],
				data: {embed_id: embed_id, embed_type: embed_type },
				cache: true,
				dataType: 'json',
				success: function(data){
					// Clear the message box
					$('#video-'+embed_id).html(data.html);
					// mark the video as viewed
					console.log(embed_type);
					S.message.isViewed(message_id, embed_id, embed_type);
				}
			});
		}
	},

	/* Custom list filter */
	customlist: {
		init: function() {
			$('#customlist-form').live('submit',function(e){
				return S.customlist.onSubmit(this);
			});
		},
		create:function (el) {
			var customlist_id = $(el).data('customlist_id');

			$.ajax({
				url:S.path['customlist.create'],
				data: {customlist_id: customlist_id},
				dataType:'html',
				success:function (data) {
					$('body').append(data);
					$('#customlist-edit-message-modal')
						.modal({ backdrop:true, show:true })
						.on('hidden', function () {
							$(this).remove();
						});
				}
			});
		},
		onSubmit: function(el) {
			var form = $(el);

			S.enqueueMessage();

			if(form.find('#title').val().length === 0) {
				S.enqueueMessage('You must fill in the title', 'error', form.find('.modal-body'));
				return false;
			} else {
				return true;
			}
		}
	},
	
	/** Groups **/
	groups: {
		init: function(){
			/* Click on "followers" and show the followers list */
			/* $('a[href="#showFollowers"]').live("click", function()
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
			}); */

			$('.group-module-members a[href="#showFollowers"]').popmodal({html:true, live:true, placement: 'left', reposition: true});
			$('.group-module-members a[href="#showFollowers"]').click(function(e) { e.preventDefault(); });

			/* Click on message edit modal box */
			$('a[name="groups-edit-save"]').live("click", function () {
				var container = $(this).closest('#group-create-message-modal');
				
				// test and make sure field is not empty
				if (container.find('input[name="name"]').val().length !== 0) {
					S.utils.buttonWaitingForAjax($(this));
					$.ajax({
						type:"POST",
						url:S.path['group.save'],
						data:$('form[name="groups-edit"]').serialize(),
						cache:false,
						dataType:'json',
						success:function (data) {
							// Close the box
							$().modal('hide');

							// Update the message in the current stream
							window.location = (data.redirect);
						}
					});
				} else {
					S.enqueueMessage();
					S.enqueueMessage('You must fill in the group name', 'error', container.find('.modal-body'));
				}

				return false;
			});
		},
		
		create: function(e){
			/* Show group edit popup */
			/*$().modal('show', S.text['edit'], function(){
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
			});*/
			$.ajax({
				url:S.path['group.edit'],
				data: 'group_id='+S.utils.getGroupId(e),
				dataType:'html', 
				error: function( xhr, text, error) {
					// should queue error?
				},
				success:function (data) {
					$('body').append(data);
					$('#group-create-message-modal')
						.modal({ backdrop:true, show:true })
						.on('hidden', function () {
							$(this).remove();
						});
				}
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
		},

		memberRemove: function(e){
			/* Remove member from group */
			if( confirm(S.text['confirm.group.member.remove'])){
				$.ajax({
					type: "POST",
					url: S.path['group.member.remove'],
					data: {group_id : $(e).data('group_id'), user_id: $(e).data('user_id') },
					cache: false,
					dataType: 'json',
					success: function(data){
						var li = $(e).parent('li');
						li.slideUp('slow', function() {
								li.remove();
								var newGroupMemberCount = $('.groupMemberList').children('li.newGroupMember').size();

								if(newGroupMemberCount <= 0) {
									// Hide the new group member seperator
									$('.groupMemberList li.newGroupMember-separator').hide();
								}
							}
						);
					}
				});
			}

			return false;
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
			var charcode = event.charCode || event.keyCode;
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
	},
	validate: {
		/* form will output system message when failed */
		form: function(form, /*  mixed is an object matching element and type */ mixed, 
				inject /* inject will place the system-message into other container */) {
			$(form).find('input[type="submit"]').click(function(e) {
				e.preventDefault();
				// reset system message first
				S.enqueueMessage();
				// collect errors
				var errors = [];
				$(mixed).each(function(idx, val) {
					$.map(val, function(el, type) {
						// added boolean value in error collection
						errors.push(S.validate.element(type, el, inject));
					});
				});
				// if there's no error, submit the form
				if ($.inArray(false, errors) == -1) {
					$(form).submit();
				}
				else {
					// scroll to top to preview error
					$('body,html').animate({
						// if the system message container was meant for another form, move the container to the form
						scrollTop: $('#system-message-container').position().top
					}, 250);
				}

			});
		},
		// notes: element validate system message need to be handle individually
		// else, if the first element is producing error; the error will be removed when the second one throws the message
		// to clear the system message call for empty message like so: S.enqueueMessage()
		element: function(type, el, 
							inject /* inject will place the system-message into other container */,
							custom /* custom is a custom label to be alerted to the message-box */) {
			// remove error when user acknowledge the error
			$(el).focus(function() {
				$(el).parent().removeClass('control-group error');
			});

			// label the error by retrieving the label
			var label = $(el).siblings('label').text() || $(el).attr('placeholder');
			// replace label with custom if defined
			label = (custom != null) ? custom : label;
			
			// add invalid class which will be removed if its a valid value
			$(el).addClass('control-group error');
			switch (type) {
			case 'email':
				if (!S.validate.isEmail($(el).val())) {
					S.enqueueMessage(S.utils.sprintf(S.text['error.validate.invalid.email'], label) ,'error', inject);
					return false;
				}
				break;
			case 'notEmpty':
				if (!S.validate.isNotEmpty($(el).val())) {
					S.enqueueMessage(S.utils.sprintf(S.text['error.validate.is.empty'], label), 'error', inject);
					return false;
				};
				break;
			case 'limitChar':
				if (!S.validate.limitChar($(el))) {
					return false;
				};
				break;
			}

			// if its passes, make it a valid field
			$(el).removeClass('control-group error');
			return true;
		},
		isNotEmpty: function(str) {
			// strip spaces from string
			var str = str.replace(/\s/g, '');
			if (str != null) {
				return str.length > 0;
			}
			return false;
		},
		isEmail: function(str, msg) {
			// RFC 2822 email format
			var pattern = /[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
			return pattern.test(str);
		},
		isValidDate: function(str) {
			var pattern = /^(19|20)\d\d[- \/.](0[1-9]|1[012])[- \/.](0[1-9]|[12][0-9]|3[01])$/;
			return pattern.test(str);
		},
		// valid url predicate is a basic url determinator, its probably not going to be a valid one on the backend but close
		isValidUrlPredicate: function(str) {
			// capitalize [Hh] for ipad auto-capitalization
			var pattern = /([Hh]ttp|[Hh]ttps):\/\/([\w-]+\.)+[\w-]+(\/[\w\-.\/?%&=]*)?/;
			return pattern.test(str);
		},
		limitChar: function(el) {
			var charLength,
			limit = el.attr('maxlength');
			// run through each element otherwise keydown confuse which should be focused to
			$(el).each(function(idx, textarea) {
				$(textarea).keydown(function() {
					charLength = $(this).val().length;
					if (charLength >= limit) {
						S.enqueueMessage();
						// change the value if it exceeds || html markup maxlength will handle this
						// $(this).val($(this).val().substring(0, limit));
						S.enqueueMessage(S.utils.sprintf(S.text['error.validate.character.limit'], limit), 'error');
					}
					else {
						S.enqueueMessage();
						return true;
					}
				});
			});
			return true;
		}
	},
	enqueueMessage: function(message, type, inject /* will append system message-container to the injected element */)
	{
		inject = (!inject) ? 'reset' : inject;
		// in case the message container is dispose
		$systemMessageContainer = ($('#system-message-container').length == 0) 
			? $('<div id="system-message-container" />') 
			: $('#system-message-container');
		// placement or the system message container depends on *inject* or original
		if (inject) {
			// reset the position to original if needed to
			if (inject == 'reset') {
				var original = ($('.main-content-inner .breadcrumb').length == 0) 
					? $systemMessageContainer.prependTo($('.main-content-inner'))
					: $systemMessageContainer.insertAfter($('.main-content-inner .breadcrumb'));
			}
			else {
				$systemMessageContainer.prependTo(inject);
			}
		}
		// if its not defined clear the system message/reset
		if (!message) {
			$systemMessageContainer.html('');
			return;
		}
		// default type is message
		type = (!type) ? 'message' : type;

		$(document).ready(function() {
			// look for existing error or message
			var errors = ($('#system-message-container .alert-error ul').length == 0) ?
				$systemMessageContainer :
				$systemMessageContainer.find('.alert-error ul');
			var messages = ($('#system-message-container .alert-success ul').length == 0) ?
				$systemMessageContainer :
				$systemMessageContainer.find('.alert-success ul');

			switch(type) {
			case 'error':
				var cssClass = 'error';
				var el = errors;
				break;
			case 'message':
				var cssClass = 'success';
				var el = messages;
				break;
			};
			// if message is more than 1 it is a list append to the list
			if ($(el).is('ul')) {
				el.append('<li>' + message + '</li>');
			}
			// if no message existed create a container for message
			else if ($(el).is('div')) {
				$systemMessageContainer.append('<div class="alert alert-' + cssClass + '"><ul><li>' + message + '</li></ul></div>'); 
			}

		});
	},

	/* TODO: modify existing stream suggest object to support options */
	suggestMember: {
		started: false,
		active: false,
		current: null,
		startCaret: 0,
		type: '',
		group_id: '',
		inputField: null,

		add: function(event, user_id){
			$.ajax({
				type: "POST",
				url: S.path['group.member.add'],
				data: {user_id:user_id, group_id:S.suggestMember.group_id },
				dataType: 'json',
				cache: true,
				success: function(data){
					// Show the new group member seperator
					$('.groupMemberList li.newGroupMember-separator').show();
					$(event).fadeOut(300, function() { $(event).remove(); });
					var ul = $('<li class="newGroupMember" />').insertAfter('.groupMemberList li:first-child');
					$(data.html).appendTo(ul).hide().slideDown();
				}
			});
		},

		start: function(type){
			group_id = $('.groupMember').attr("id");
			S.suggestMember.group_id = group_id.match(/^\w+_([0-9]+)$/)[1];
			S.suggestMember.type = type;
			S.suggestMember.started = true;
			if(!S.suggestMember.active){
				S.suggestMember.inputField = $('.searchMembers');
				S.suggestMember.active = true;
				$('.groupMember .stream-post-suggest').slideDown();
				S.suggestMember.current = '';
				S.suggestMember.startCaret = $(S.suggestMember.inputField).caret().start;
			}
		},

		stop: function(){
			if(S.suggestMember.active){
				S.suggestMember.active = false;
				$('.groupMember .stream-post-suggest').slideUp();
			}
		},

		update: function (event){
			var charcode = event.charCode || event.keyCode;
			var inchar = String.fromCharCode(charcode);
			if(charcode >=  65 && charcode <= 122){
				var currentCaret = $(S.suggestMember.inputField).caret().start;
				var message = $(S.suggestMember.inputField).val();
				S.suggestMember.current = message.substring(S.suggestMember.startCaret, currentCaret+1);
				S.suggestMember.current = S.suggestMember.current + inchar;

				// Trigger ajax call for suggestion
				$.ajax({
					type: "POST",
					url: S.path['system.suggest'],
					data: {search : S.suggestMember.current, type: S.suggestMember.type, group_id: S.suggestMember.group_id },
					dataType: 'json',
					cache: true,
					success: function(data){
						$(".groupMember .stream-post-suggest").html(data.html);
					}
				});
			} else if(event.keyCode == 8 || event.keyCode == 46 ){
				// Press delete key
				// Check if we have deleted the # or arrow key
				var currentCaret = ($(S.suggestMember.inputField).caret().start -1);
				if(S.suggestMember.startCaret >= currentCaret ){
					S.suggestMember.stop();
				}
			} else if( inchar == ':' || inchar == '|' || inchar == '?'  || inchar == ' '  ||  event.keyCode == 13   ) {
				// Other end char trigger
				S.suggestMember.stop();
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
	S.embed.init();

	// Initialize tags
	S.tag.init();

	// Initialize custom lists
	S.customlist.init();
	
	// Init popover
	//$('li.groups li a').popover({html:true, live:true});
	$('li.groups a').popmodal({html:true, live:true, placement: 'right', reposition: true});
	$('.calendar tr:not(:last) td.running').popmodal({html:true, live:true, placement: 'below', height: 200});
	$('.calendar tr:last td.running').popmodal({html:true, live:true, placement: 'above', height: 200, reposition: true});
	$('a[href="#showReaders"]').popmodal({html:true, live:true, placement: 'right', reposition: true});

	var NotificationPopover = function() {
		var selector = '.side-notification a',
		$element = $(selector), 
		isVisible = false,
		options = {
			html: true,
			placement: 'right',
			trigger: 'manual'
		};
		$(selector).popover(options);
		$(selector).live('click', function() {
			$('div.main-popover').remove();
			if (!isVisible) {
				// if data-content is null ignore clicks
				var content = $(selector).attr('data-content') || '';
				content = content.replace(/<\/?ul>/g, '');
				if (!content || content.length == 0) return false;

				$(selector).parent().addClass('active');
				$(selector).popover('show');
				var mainPopover = $($('div.popover')[0]).clone() || $('div.main-popover');
				// remove as the styling will be in 'popover class'
				$('div.popover').remove();
				// append clone to display so that the styling is independent
				mainPopover.addClass('popover main-popover')
					.prependTo($('body'));

				$('.main-popover').popScroll({ height: 800, sender: selector });
				$('div.main-popover').css('top', $(selector).offset().top + $(selector).height());
				$('div.main-popover .inner').css({
					'position': 'absolute',
					'top': -($(selector).height())
				});
			}
			else {
				$(selector).parent().removeClass('active');
				$(selector).popover('hide');
				$('div.main-popover').remove();
			}
			isVisible = !isVisible;
		});
		return $element;
	};
	var notification = new NotificationPopover();

	// @todo: optimize this, this is pretty horrible
	$("div.message-content-preview img[rel]").live('click', function(){
		
		var navigateImage = function(e) {
			if (e.keyCode == 37) { 
				$(e.data.obj).children('.image_prev').trigger('click');
				return false;
			}
			if (e.keyCode == 39) { 
				$(e.data.obj).children('.image_next').trigger('click');
				return false;
			}
		};

		if( !$(this).data('overlay_bind')){
			// resize max height if necessary
			var img = $($(this).attr('rel')).find('img');
			var relImage = $($(this).attr('rel'));
			
			w = $(window);	
			// reset img height/width if height is too big for the screen
			// generally, this would be 760px height to accommodate 640 px height image
			var maxHeight = parseInt(0.8 * w.height());
			if( w.height() < 760 ){
				if(img.attr('height') >  maxHeight){
					newWidth = (maxHeight/img.attr('height')) * img.attr('width');
					img.attr('width',  newWidth);
					$($(this).attr('rel')).width( newWidth );
					img.attr('height', maxHeight);
				}
			}
			
			$(this).data('overlay_bind', true);
			$(this).overlay({
				effect: 'apple',
				onLoad: function() {
					// create new key event
					$(document).bind('keydown', {obj: relImage}, navigateImage);
				},
				onClose: function() {
					// remove the same key event
					$(document).unbind('keydown', navigateImage);
				}
			});
			$(this).trigger('click');
		}
		
		if ($.browser.msie && $.browser.version == '9.0')
		{
			var overlay = $('.apple_overlay');
			var screenWidth = screen.width;
			var overlayWidth = overlay.width();
			var left = (screenWidth / 2 - overlayWidth / 2);
			overlay.css({left : left});
		}
	});
	
	// Autogrow comment
	$('textarea[name="message"]').autoGrow();

	// TODO: noautogrow class is a temporary solution
	$('textarea[name="comment"]:not(.noautogrow)').autoGrow();
	
	// Initializa drop down
	$('a[href="#selectOption"]').live( 'click', function(){
		return S.utils.setupOptionSelect(this);
	});
	
	// Alert hiding
	$('a[data-dismiss="alert"]').click( function(){
		S.alert.hide(this);
	});
	
	// show all group item
	var showGroupMore = function(e) {
		e.preventDefault();
		var groups = $(this).parents('ul');
		groups.find('li').show();
		$(this).hide();
		groups.find('li a.less').show();
	}
	var showGroupLess = function(e) {
		e.preventDefault();
		var LIMIT = $(this).data('limit');
		var groups = $(this).parents('ul');
		groups.find('li:not(.moreless)').hide()
			.each(function(idx, el) {
				if (idx >= LIMIT) {
					return;
				}
				$(el).show();
			});
		$(this).hide();
		groups.find('li a.more').show();
	}
	$('a[href="#showAllGroups"]').bind('click', showGroupMore);
	$('a[href="#showLessGroups"]').bind('click', showGroupLess);

	// Hide/show srach form
	$('a[href="#search_stream"],button[href="#search_stream"]').click( function() {
		$('form.form-search').toggle();
		return false;	
	});
	
	// if ($('html').hasClass('ipad')) {
	// 	var a = document.getElementsByTagName("a");
	// 	for(var i=0;i<a.length;i++)	{
	// 		if (a[i].getAttribute('href')) {
	// 			if (a[i].getAttribute('href').indexOf('#') == -1) {
	// 				a[i].onclick = function()	{
	// 					window.location = this.getAttribute("href");
	// 					return false;
	// 				}
	// 			}
	// 		}
	// 	}
	// }

	$('.stream-message-links-in-post').click(function(e) {
		// ignore on avatar clicked
		if (e.target.className == 'tips') { return true; }
		var url = $(this).find('.stream-message-links-url').html(),
		message_id = S.utils.getMessageId(this);
		// mark the link as viewed
		S.message.isViewed(message_id, message_id, 'link');
		window.open(url);
		return false;
	});

	// resize to make equal height
	if ($('#oContent').length && $('#main-content-right').length) {
		var leftColumn = $('.sidebar-left-inner').height();
		var centerColumn = $('.main-content-inner').height();
		var rightColumn = $('.sidebar-right-inner').height();
		var rightPositionTop = $('.sidebar-right-inner').position().top;
		var heightToFollow = Math.max(leftColumn, centerColumn, rightColumn);
		$('.sidebar-right-inner').height(heightToFollow);
	}

	// support for ipad icon tab (browser detection is not implemented yet)
	var miniNav = (function() {
		var sidebar = $('.mini-sidebar');
		
		sidebar.children('li').each(function(idx, el) {
			// just put it here in case customization is needed
			var prefix = 'mini-',
			content = $('#'+$(el).get('id') + '-content'),
			placeholder = $('#mini-sidebar-placeholder'),
			displayed = ($(placeholder).children().length > 0) || false;

			$(el).click(function() {
				$(placeholder).empty();
				displayed = !displayed;
				if (!displayed) {
					$(el).removeClass('active');
					return;
				}
				$(el).addClass('active');

				var klone = content.clone() || [];
				//klone.css('display', 'none');
				$(placeholder).append(klone);
				klone.show(100);
				klone.animate({
					'width': $(placeholder).width(),
					'display': 'block !important',
					'right': '+25px'
				}, 100, function() {
				});
			});
		});
	})();

	
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
		paste_auto_cleanup_on_paste : true,
        paste_remove_styles: true,
        paste_remove_styles_if_webkit: true,
        paste_strip_class_attributes: true,
		plugins : "autoresize,paste",
		autoresize_max_height:"500px"


	});
});
