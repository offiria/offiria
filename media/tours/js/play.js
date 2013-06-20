var Tour = {
	play: function() {
		var tipGuideClass = 'joyride-tip-guide';
		$tour = this;
		// traverse through element to make sure the displayed tips is what we want to run
		$('.'+tipGuideClass).each(function(idx, el) {
			if ($(el).is(':visible')) {
				var playPosition = $(el).attr('class').replace(tipGuideClass, '').replace(/ /g, '');
				console.log(playPosition);
				$tour[playPosition](el);
			}
		});
	},
	onStart: function(el) {
		// anything to trigger when the tour start
	},
	onMessage: function() {
		var selector = '.stream-post-tabs li:not(.li-text) a';
		var originalColor = $(selector).css('color');
		function highlight(el) {
			$(el).animate({
				'color': '#faaf40'
			}, 500, function() {
				$(this).css('color', originalColor);
			});
		}
		$(selector).each(function(idx, el) {
			setTimeout(highlight, idx * 100, el);
		});
	},
	onGroup: function(el) {
		$tour = this;
		var bindToGroup = function() {
		}
		// in case the user try to create a group
		$('#group-create').click(function() {
			$(el).hide();			
			$(this).ajaxComplete(function(e) {
				// this will trigger onGroupCreate
				$(el).find('.button').trigger('click');
				$('.onGroupCreate').find('.button').hide();

				$('#group-create-message-modal').one('hidden', function() {
					$('.onGroupCreate').find('.button').trigger('click');
				});
				$(e.currentTarget).unbind('ajaxComplete');
			});
		})
	},
	onGroupCreate: function(el) {
		$(el).css('z-index', 999999);
		// anything to trigger when the groupCreate is active
	},
	onInvite: function() {
		// anything to trigger when the onInvite is active
	},
	onGetStarted: function() {
		// anything to trigger when the onGetStarted is active
	}
}