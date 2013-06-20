S = {
    jQuery: window.jQuery,
    extend: function(obj){
		this.jQuery.extend(this, obj);
    }
};

S.extend({
	text: [], /* server generated text definitions */
	path: [], /* Server generated ajax path */
	popup: {
		/*
			S.popup						- Simple popup trigger for any use. No call backs. but include close button with it.
										  Popup includes body-wide transluscent overlay, and also trigger S.apps.bodyFreeze when shown
			Usage:
			S.popup.show(content)		- where 'content' is any HTML code
			S.popup.hide()				- will clear the content and hide the popup
		*/
		show: function(popContent) {
			$('.offPopupContent').html(popContent).show();
			$('#offPopupWrap').show().css({
				'height': $(document).height() + 20 + 'px'
			});
			//S.apps.bodyFreeze();
		},
		hide: function() {
			$('.offPopupContent').html('');
			$('#offPopupWrap').hide();
			//S.apps.bodyFreeze(false);
		}
	},
	apps: {
		init: function() {
			// make link open in the same window
			var a = document.getElementsByTagName("a");
			for(var i=0;i<a.length;i++)	{
				var href = a[i].getAttribute('href');
				if (href) {
					// if its not an anchor
					if (href.indexOf('#') == -1 &&
						// and also not an inline action
						href.match(/javascript:void\(0\);?/) == null) {
						a[i].onclick = function()	{
							window.location = this.getAttribute("href");
							return false;
						}
					}
				}
			}

			$('img:not(.interactive)').each(function(idx, el) {
				$(el).click(function() {
					// proceed if the current image is a thumbnail for a bigger picture
					// thumbnail contains rel to trigger the image slideshow
					if ($(el).attr('rel')) {
						var src = $(this).attr('src');
						window.location = (src.indexOf('_thumb')) ? $(this).attr('src').replace('_thumb', '_preview') : src;
						return false;
					}
					return true;
				});
			});
			$('.stream-message-links-in-post').click(function() {
				var url = $(this).find('.stream-message-links-url').html();
				window.location = url;
				return false;
			});

			$(window).resize(function() {
				// setup some popup height and position
				var theHeight = $(window).height();
				var optiHeight = theHeight - 104;
				var theWidth = $(window).width();
				var optiWidth = theWidth - 46;
				var optiLeft = (theWidth - optiWidth) / 4;
				/*
				if( $('.contentContainer').length ) {
					$('.contentContainer').css({
						'width' : theWidth - 8 + 'px',
						'padding' : '0px 4px'
					})
				}
				*/
				if($('ul.filter').length) {
					$('ul.filter').css({
						width: (theWidth * 0.90) + 'px'
					});

					$('ul.filter>li').css({
						'width': (100 / $('ul.filter').children().length) + '%'
					})
				}
				
				$('.popOver').css({
					'height': optiHeight + 'px',
					'width' : optiWidth + 'px',
					'left' : optiLeft + 'px'
				});
				$('#searchform input[type=text]').css({
					'width': optiWidth - 28 + 'px'
				});
				// fire the initial setup
				
				$('#offPopup').css({
					'width' : optiWidth + optiLeft + optiLeft + 'px',
					//'max-height' : optiHeight + 'px',
					'left' : optiLeft + 'px'
				});
				
			}).trigger('resize');
			
			
			$('#topMainButton').live('click',function(e) {
				e.preventDefault();
				S.apps.toggle('home');
				$('#secondToolbar').toggle();
			});
			
			$('#topSearchButton').live('click',function(e) {
				e.preventDefault();
				S.apps.toggle('search');
				$('#searchBar').toggle();
			});
			
			$('#topGroupButton').live('click',function(e) {
				e.preventDefault();
				S.apps.toggle('group');
				S.popup.show($('#popoverContainer').html());
				/*
				$('#popoverContainer').toggle(0,function() {
					if( $('#popoverContainer').is(":visible") ) {
						S.apps.bodyFreeze();
					} else {
						S.apps.bodyFreeze(false);
					}
				});
				*/
			});
			
			$('#topPostButton').live('click',function(e) {
				e.preventDefault();
				S.apps.toggleComposeTab();
			});
			
			$('.dropdown-toggle').live('click',function(e) {
				e.preventDefault();
				S.apps.toggleFilterBar($(this).parent());
			});
			
			$('td.running').live('click', function() {
				//alert($(this).attr('data-content'));
				S.popup.show('<div class="popupDate">' + $(this).html() + ' ' + $('.month-title').html() + '</div>' + $(this).attr('data-content'));
			});
			
			$('#offPopupClose').live('click', function(e) {
				e.preventDefault();
				S.popup.hide();
			})

		},
		bodyFreeze: function(wantToFreeze) {
			/*
				S.apps.bodyFreeze() - used to freeze the html and body container on the mobile view to prevent accidental double-scrolling
				
				Usage:
				S.apps.bodyFreeze() 		- default (will freeze the body)
				S.apps.bodyFreeze(false) 	- will unfreeze the body.
			*/
			if (typeof wantToFreeze === "undefined") { wantToFreeze = true; }
			if(wantToFreeze) {
				$('html, body').css({
					'overflow-y' : 'hidden',
					'height' : $(window).height() + 'px'
				});
			} else {
				$('html, body').css({
					'overflow-y' : 'auto',
					'height' : 'auto'
				});
			}
		},
		toggle: function(tabName) {
			console.log(tabName);
			if (tabName != 'home') $('#secondToolbar:visible').hide();
			if (tabName != 'search') $('#searchBar:visible').hide();
			if (tabName != 'group') S.popup.hide(); //$('#popoverContainer:visible').hide();
			if (tabName != 'compose') $('#composeScreen:visible').hide();
		},
		toggleComposeTab: function() {
			S.apps.toggle('compose');
			$('#composeScreen').toggle(0, function() {
				if( $('#composeScreen').is(":visible") ) {
					S.apps.bodyFreeze();
					$(this).find('textarea').focus();
				} else {
					S.apps.bodyFreeze(false);
				}
			});
		},
		toggleFilterBar: function(objContainer) {
			objContainer.find('.dropdown-menu').slideToggle(200);
		}
	},
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
			console.log(name);
			
			$('form input[name="'+target+'"]').val(val);
			$('a.dropdown-toggle[href="#'+target+'"]').html( name + ' <b class="caret">');
			return false;
		}
	},
	stream: {
		init: function() {
			$('#stream-form').find('input[type="submit"]').click(function(e) {
				// halt the submit button
				e.preventDefault();
				S.stream.post(S.path['message.add']);
			});
		},
		post: function( posturl) {
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
				}
		    }).done(function( data ) {
		    	S.apps.toggleComposeTab();
		    	$('#stream-post-loading').hide();
		    	
		    	// Re-enabled form elements and submit form
				$(activeType + ' :input, #stream-form input[type="submit"]').attr("disabled", false);
				
				// Trigger onAfterSubmit
				// @todo: check make sure function exist first
				trigger = 'S.'+type+'.onAfterSubmit( data );';
				eval(trigger);
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
			$('.message-meta a[href="#unlike"]').live("click",function() {
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
			$('.stream-like a[href="#showLikes"]').live("click",function() {
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
	    /* Generic messages script */
    message: {
    	init: function(){
    		/* Filtering */
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
							$('.pagination').html(html);
							
							$('.stream-loading').remove();
						}
					}
				});
				
				$(this).blur();
    			return false;
			});
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
		    	
				//$().modal('show', S.text['edit'], function(){
		    		$.ajax({
						type: "POST",
						url: S.path['message.edit'],
						data: {message_id: stream_id},
						cache: false,
						dataType: 'json',
						success: function(data){
							// Clear the message box
							//$().modal('content', data.html);
							//$().modal('title', data.title);
							//$().modal('actions', data.actions);
							//$().modal('hide');
							
							$('li#message_'+ stream_id).html(data.html);
							
							// set up date picker if necessary
							$('li#message_'+ stream_id + ' input.start-date').datepicker({ dateFormat: 'yy-mm-dd' });
							$('li#message_'+ stream_id + ' input.end-date').datepicker({ dateFormat: 'yy-mm-dd' });
							$( "#cWindow input.start-date, #cWindow input.end-date" ).datepicker({ dateFormat: 'yy-mm-dd' });
							
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
		    
			// Hide/show srach form
			$('a[href="#search_stream"],button[href="#search_stream"]').click( function() {
				$('form.form-search').toggle();
				return false;	
			});

		    /* Unline attachment. Doesn't get deleted, used during message editing */
		    $('.message-content-file a[href="#unlinkAttachment"]').live("click",function()
		    {
		    	$(this).parents('li.qq-upload-success').remove();
		    	return false;
			});
		},
		isViewed: function(message_id) {
			$.ajax({
				'url': S.path['message.viewed'],
				'data': { message_id: message_id }
			}).done(function() {
				console.log('updated');
			});
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
						if (milestoneId)
						{
							$('li#milestone_' + data.milestone_id ).replaceWith(data.html2);
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
					//eval(data.script);
				}
			});
		},
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
		}
	},

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
	
	preview : {
		modal: false, 
		show: function(e) {
			
			// get file id
			var file_id = $(e).attr("data-file_id");
			var file_name = $(e).attr("data-filename");
			// @todo: Load waiting message
			S.preview.load(file_id);
			return false;
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
				 		S.preview.load(data.file_id);
					}
				 	var previewLink = $(data.html).attr('src');
				 	console.log(previewLink);
				 	window.open(previewLink, '_blank');
				 }
			});
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
				data: {embed_id: embed_id, embed_type: embed_type, embed_width: 280 },
				cache: true,
				dataType: 'json',
				success: function(data){
					// Clear the message box
					$('#video-'+embed_id).html(data.html);
					// mark the video as viewed
					S.message.isViewed(message_id);
				}
			});
		}
	},
	
	notification: {
		update: function() {
			
		}
	},
	enqueueMessage: function(message, type, inject /* will append system message-container to the injected element */) {
		// placement or the system message container depends on *inject* or original
		if (inject) {
			var original = $('#main-content-left.span6');
			// reset the position to original if needed to
			var inject = (inject == 'reset') ? original : inject;
			$('#system-message-container').prependTo(inject);
		}
		// if its not defined clear the system message/reset
		if (!message) {
			$('#system-message-container').html('');
			return;
		}
		// default type is message
		type = (!type) ? 'message' : type; 

		$(document).ready(function() {
			// look for existing error or message
			var errors = ($('#system-message-container .alert-error ul').length == 0) ?
								$('#system-message-container'):
								$('#system-message-container .alert-error ul');
			var messages = ($('#system-message-container .alert-success ul').length == 0) ?
								$('#system-message-container'):
								$('#system-message-container .alert-success ul');

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
				$('#system-message-container').append('<div class="alert alert-' + cssClass + '"><ul><li>' + message + '</li></ul></div>'); 
			}

		});
	}
});

$(document).ready(function() {
	$('a[href="#showReaders"]').popmodal({html:true, live:true, placement: 'right', reposition: true});

	// run all init method
	$.map(S, function(methods) {
		if ($.isFunction(methods.init)) {
			methods.init();
		}
	});
});