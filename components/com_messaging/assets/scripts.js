/* Inbox */
var Messaging = {
	inbox: {
		init:function () {
			$('#direct-message-compose').on('click', function(e) {
				e.preventDefault();
				$('#direct-message').slideToggle(150);
			});

			// Message delete
			$('ol.recipient-list a').live('click', function(e){
				$(this).closest('li').remove();
			});

			$('.inbox-delete').live('click', function(e){
				e.preventDefault();

				if(!confirm('Are you sure you want to remove this message?')) {
					return false;
				}

				Messaging.inbox.remove(this);
			});

			// Focus on the input field even if we click on the parent container
			$('.stream-post-details').on('click', function() {
				$(this).find('input').focus();
			});

			$('#messaging-write-form').on('submit',function(e){
				Messaging.inbox.addMessage(this);
				e.preventDefault();
			});

			// Checkbox selectors
			$('#inbox-filter a').on('click', function(e) {
				Messaging.inbox.delegateSelection(this);
			});

			// Dropdown actions
			$('#inbox-action a').on('click', function(e) {
				Messaging.inbox.delegateAction(this);
			});

			// Recipient autocomplete
			Messaging.inbox.bindTypeAhead('.recipient-input');

			// Initialize the file uploader
			Messaging.util.uploader.init('pm-file-uploader', 'pm-attachment-list');
		},
		addMessage: function(el){
			var container = $('.stream-post-message');
			var form = $(el);
			var recipient = container.find('input[name="recipient"]');
			var recipientList = container.find('.recipient-list').children('li');
			var message = container.find('textarea[name="body"]');
			var postData = form.serialize();

			// Loading indicator
			//var loading = $('#stream-post-loading');
			//loading.show();

			// Display the error over here
			var injectErrorTo = container.find('.modal-body');

			// Clear the error message
			S.enqueueMessage();

			if(message.val().length === 0 || recipientList.length === 0) {
				S.enqueueMessage('You must fill in all the fields', 'error', container.parent());
			} else {
				$.ajax({
					type:"POST",
					url:S.path['messaging.compose'],
					data:postData,
					dataType:'json',
					success:function (data) {
						//loading.hide();
						recipient.val('');
						message.val('');
						recipientList.remove();
						container.find('#direct-attachment-list').html('');
						container.find('#direct-error').html('<a href="' + S.path['direct.inbox'] + '">Your message</a> has been sent.');
						document.location = S.path['messaging.inbox'];
					}
				});
			}
		},
		bindTypeAhead: function (el){
			/*$(el).typeahead({
				source: Messaging.typeAheadSource
				, onselect: function (obj) {
					Messaging.inbox.addRecipient(obj.id, obj.value, this.$element);
				}
				//, menu: '<ul class="typeahead dropdown-menu"></ul>'
  				, item: '<li><img src="http://offiria.local.dev/images/avatar/user-thumb.png"><a href="#"></a></li>'
			});*/

			$(el).typeahead({
				source: Messaging.typeAheadSource
				, onselect: function (obj) {
					Messaging.inbox.addRecipient(obj.id, obj.value, this.$element);
				}
				, items: 3
				, menu: '<ul class="people-autocomplete dropdown-menu"></ul>'
				, item: '<li><table><tr><td><img class="people-autocomplete-thumb" src="http://offiria.local.dev/images/avatar/user-thumb.png" /></td><td><a href="#"></a></tr></table></li>'
				, property: 'value'
			});
		},
		addRecipient: function(id, name) {
			var inputField = $('.recipient-input');
			inputField.val('');
			// TODO: template instead
			inputField.before('<li style=""><span title="">' + name + '</span><a class="close small">&times;</a><input type="hidden" value="' + id + '" name="members[]"></li>');
			inputField.focus();
		},
		clearRecipients: function() {
			$('.recipient-list').children().filter('li').remove();
		},
		remove: function(el) {
			var message_id = Messaging.util.getMessageId(el);
			var message = $('li#message_' + message_id);

			$.ajax({
				type:"POST",
				url:S.path['messaging.message.removeFullMessages'],
				data: {msgid: message_id},
				dataType:'json',
				success:function (data) {
					message.fadeOut(300, function() { message.remove(); });
				}
			});
		},
		delegateSelection: function(el) {
			var selectedElId = $(el).attr('id').replace('check-','');

			// Clear all checkboxes first
			$("#inbox-listing input[type='checkbox']").each(function () {
				$(this).attr('checked', false);
			});

			switch (selectedElId) { 
				case 'all': 
					$("#inbox-listing input[type='checkbox']").each(function () {
						$(this).attr('checked', true);
					});
					break;
				case 'read': 
					$("#inbox-listing").children('li').not('.inbox-list-unread').each(function () {
						$(this).find('input[type="checkbox"]').attr('checked', true);
					});
					break;
				case 'unread': 
					$("#inbox-listing li.inbox-list-unread input[type='checkbox']").each(function () {
						$(this).attr('checked', true);
					});
					break;
				case 'none': 
					$("#inbox-listing input[type='checkbox']").each(function () {
						$(this).attr('checked', false);
					});
					break;
			}
		},
		delegateAction: function(el) {
			var actionElId = $(el).attr('id').replace('action-','');

			switch (actionElId) { 
				case 'read': 
					$("#inbox-listing input[type='checkbox']").each(function () {						
						if ($(this).attr('checked')) {
							if ($('#message_' + $(this).attr('value')).hasClass('inbox-list-unread')) {
								$.ajax({
									type:"POST",
									url:S.path['messaging.message.markAsRead'],
									data:{msgid: $(this).attr('value')},
									dataType:'json',
									success:function (data) {
										$('#message_' + data.id)
											.removeClass('inbox-list-unread')
											.addClass('inbox-list-read')
											.find('.inbox-status').removeClass('unread');
										Messaging.inbox.uncheckAllCheckboxes();
									}
								});
							}
						}
					});
					break;
				case 'unread': 
					$("#inbox-listing input[type='checkbox']").each(function () {						
						if ($(this).attr('checked')) {
							if ($('#message_' + $(this).attr('value')).hasClass('inbox-list-read')) {
								$.ajax({
									type:"POST",
									url:S.path['messaging.message.markAsUnread'],
									data:{msgid: $(this).attr('value')},
									dataType:'json',
									success:function (data) {
										$('#message_' + data.id)
											.removeClass('inbox-list-read')
											.addClass('inbox-list-unread')
											.find('.inbox-status').addClass('unread');
										Messaging.inbox.uncheckAllCheckboxes();
									}
								});
							}
						}
					});
					break;
				case 'delete': 
					if($("#inbox-listing input[type='checkbox']:checked").length !== 0 && !confirm('Are you sure you want to remove the selected messages?')) {
						return false;
					}

					$("#inbox-listing input[type='checkbox']").each(function () {						
						if ($(this).attr('checked')) {
							Messaging.inbox.remove(this);
						}
					});
					break;
			}
		},
		uncheckAllCheckboxes: function() {
			$("#inbox-listing input[type='checkbox']").each(function () {
				$(this).attr('checked', false);
			});
		}
	},
	reply: {
		msgid: null,
		init: function (msgid) {
			Messaging.reply.msgid = msgid;

			$('.inbox-delete').live('click', function(e){
				e.preventDefault();

				if(!confirm('Are you sure you want to remove this reply?')) {
					return false;
				}

				Messaging.reply.remove(this);
			});
			
			$('ol.recipient-list a.close').live('click', function(event){
				if(confirm('Do you really want to remove this participant and all of their messages?')) {
					var userid = $(this).data('userid');
					if (typeof userid != 'undefined') {
						Messaging.reply.removeRecipient(userid, this);
					};
				}
			});

			$('.stream-post-details').on('click', function() {
				$(this).find('input').focus();	
			});

			$('a.cInbox-ShowMore').on('click', function (e) {
				e.preventDefault();
				$('#cInbox-Recipients').removeClass('cHidden');
				$(this).addClass('cHidden');
			});

			$('#inbox-reply-form').on('submit', function(e) {
				Messaging.reply.addReply(this);
				e.preventDefault();
			});

			// Participant autocomplete
			Messaging.reply.bindTypeAhead('.recipient-input');

			// Initialize the file uploader
			Messaging.util.uploader.init('pm-file-uploader', 'pm-attachment-list');
		},
		remove: function(el) {
			var message_id = Messaging.util.getMessageId(el)
				,message = $('li#message_' + message_id);

			$.ajax({
				type:"POST",
				url:S.path['messaging.message.remove'],
				data: {msgid: message_id},
				dataType:'json',
				success:function (data) {
					message.fadeOut(300, function() { message.remove(); });
				}
			});
		},
		bindTypeAhead: function (el){
			$(el).typeahead({
				source: Messaging.typeAheadSource,
				onselect: function (obj) {
					Messaging.reply.addRecipient(obj.id, obj.value, this.$element);
				}
			});
		},
		addRecipient: function(id, name, el) {
			$.ajax({
				type:"POST",
				url:S.path['messaging.participant.add'],
				data: {participantid: id, msgid: Messaging.reply.msgid},
				dataType:'json',
				success:function (data) {
					var inputField = $('.recipient-input');
					inputField.val('');
					inputField.before('<li style=""><img src="' + data.thumb + '"><span title="">' + name + '</span><a class="close small" data-userid="' + id + '">&times;</a><input type="hidden" value="' + id + '" name="members[]"></li>');
					Messaging.reply.recountRecipients();	
				}
			});
		},
		removeRecipient: function(id, el) {
			$.ajax({
				type:"POST",
				url:S.path['messaging.participant.remove'],
				data: {participantid: id, msgid: Messaging.reply.msgid},
				dataType:'json',
				success:function (data) {
					$(el).closest('li').remove(); // Remove the user container from the list
					if(Messaging.reply.recountRecipients() == 1) {
						// Can't remove the last guy left
						$('.recipient-list').find('a.close').remove();
					}
				}
			});
		},
		addReply: function(el) {
			var html = '<div class=\'ajax-wait\'>&nbsp;</div>';
			var replyBox = $('textarea#replybox');
			$('table tbody').append(html);

			var attachment = [];
			$('#pm-attachment-list input[name="attachment[]"]').each(function(){
				attachment.push($(this).val());
			});

			if($.trim(replyBox.val()).length === 0) {
				S.enqueueMessage(S.text['error.message.add.empty'], 'error', $('.inbox-reply'));
				return false;
			} else {
				replyBox.attr('disabled', 'disabled');
				$('button#replybutton').attr('disabled', 'disabled');

				$.ajax({
					type:"POST",
					url:S.path['messaging.reply'],
					data:{msgid:Messaging.reply.msgid, reply: replyBox.val(), 'attachment[]': attachment},
					cache:false,
					dataType:'json',
					success:function (data) {
						$('div.ajax-wait').remove();
						$('textarea#replybox').removeAttr('disabled');
						$('button#replybutton').removeAttr('disabled');
						$('textarea#replybox').val('');
						$('div.inbox-table.thread').append(data.html);

						// Clear the error message
						S.enqueueMessage();
					}
				});
			}
		},
		recountRecipients: function() {
			var inboxShowMore = $('.cInbox-ShowMore')
				,newCountText = inboxShowMore.text().replace(/^\d+/, $('.recipient-list').children('li').length);
			inboxShowMore.text(newCountText);

			return parseInt(newCountText);
		},
	},
	// The source is shared between the inbox and single message typeahead
	typeAheadSource: function (typeahead, query) {
		$.ajax({
			type:"POST",
			url:S.path['messaging.people.autocomplete'],
			data:{search_key:query},
			cache:false,
			dataType:'json',
			success:function (data) {
				// Get the people already added to the list
				var existList = [];
				
				$('.recipient-list').find('li').each(function (i, v) {
					existList.push($(v).find('span').text())
				});

				// Exclude them
				var diff = [];
				diff = jQuery.grep(data, function (item) {
					return jQuery.inArray(item.user_fullname, existList) < 0;
				});

				var returnList = [], i = diff.length;
				while (i--) {
					returnList[i] = {id: diff[i].user_id, value:diff[i].user_fullname, thumb:diff[i].thumb};
				}

				typeahead.process(returnList);
			}
		});
	},
	util: {
		getMessageId: function(e){
			// check if the element itself is carrying the message_id data
			var message_id = $(e).data('message_id');

			if(typeof message_id != 'undefined'){
				return message_id;
			} else {
				var message_id = $(e).parents('li.inbox-list').attr('id');
				message_id = message_id.match(/^\w+_([0-9]+)$/)[1];
				return message_id;
			}
		},
		// TODO: script.js's S.uploader to be made reusable
		uploader:{
			init: function(uploaderElement, uploadedList){
				var uploader = new qq.FileUploader({
					multiple: false,
					element: document.getElementById(uploaderElement),
					action: S.path['messaging.upload'],
					debug: true,
					listElement: document.getElementById(uploadedList),
					fileTemplate: '<li style="list-style: none outside none;margin-left:0px;">' +
							'<div class="message-content-file"><span class="qq-upload-file"></span>' +
							'<span class="qq-upload-spinner"></span>' +
							'<span class="qq-upload-size"></span>' +
							'<a class="qq-upload-cancel" href="#">Cancel</a>' +
							'<span class="qq-upload-failed-text">Failed</span>' +
							'<input type="hidden" name="attachment[]" value="" />' +
							'</div>' +
						'</li>',
					onComplete: function(id, fileName, response) {
						// get the element,
						var item = uploader._getItemByFileId(id); 
						
						// attach file uploader
						$(item).find('input').val(response.file_id);
						
						// add 'remove' button
						$(item).find('div.message-content-file').append('<span class="qq-upload-remove"><a file_id="'+response.file_id+'" href="#removeAttachment">Remove</a></span>');			 
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
		}
	}
};