jxProfile = {
    extend: function(obj){
		jQuery.extend(this, obj);
    }
}

jxProfile.extend({
	avatar: {
		initImageSelect: function()
		{
			var imgWidth = $('img#large-profile-pic').width();
			var imgHeight = $('img#large-profile-pic').height();
			var x1 = parseInt((imgWidth - 64) / 2);
			var x2 = x1 + 64;
			var y1 = parseInt((imgHeight - 64) / 2);
			var y2 = y1 + 64;
			$('img#large-profile-pic').imgAreaSelect({ 
				x1: x1, y1: y1, x2: x2, y2: y2,
				aspectRatio:'1:1',
				maxWidth: 128, maxHeight: 128, 
				minWidth:64, minHeight:64, handles: true,
				enable: true, hide: false, show: true
			});

			$('div#edit-thumbnail, div#stop-thumbnail, div#save-thumbnail, div#remove-thumbnail').toggle();
		},
		exitImageSelect: function()
		{
			var ias = $('img#large-profile-pic').imgAreaSelect({ instance: true });
			ias.setOptions({ show: false, hide: true, enable:false });
			ias.update();
			$('div#edit-thumbnail, div#stop-thumbnail, div#save-thumbnail, div#remove-thumbnail').toggle();
		},
		saveThumbnail: function(taskUrl) 
		{
			var ias = $('#large-profile-pic').imgAreaSelect({ instance: true });
			var obj = ias.getSelection();

			$.ajax({
				url: taskUrl,
				dataType: 'json',
				data: 'x_pos='+obj.x1+'&y_pos='+obj.y1+'&width='+obj.width+'&height='+obj.height,
				type: 'post',
				beforeSend: function( xhr ) {
				},
				success: function( data ) {
					if (typeof data.error != 'undefined' && data.error != '1')
					{
						if (data.info != undefined)
						{
							$('#thumbnail-profile-pic').attr('src', data.info );
						}
					}
					else
					{
						$('<div class="notification">'+S.text['error.avatar.save.fail']+'</div>').insertBefore('div.large-avatar');
					}
				}
			});
		},
		removeAvatar : function(taskUrl)
		{
			$.ajax({
				url: taskUrl,
				dataType: 'json',
				data: '',
				type: 'post',
				beforeSend: function( xhr ) {
				},
				success: function( data ) {
					if (typeof data.error != 'undefined' && data.error != '1')
					{
						$('div.large-avatar').html('<div class="notification">'+S.text['error.avatar.upload.fail']+'</div>');
						$('div.thumbnail-avatar').remove();
					}
					else
					{
						$('<div class="notification">'+S.text['error.avatar.remove.fail']+'</div>').insertBefore('div.large-avatar');
					}
				}
			});
		}
	}
});

$(function(){
	$('#profile-avatar #avatar-form').submit(function() {
		if ($('#avatar-file').val() == '')
		{
			alert(S.text['error.upload.empty.file']);
			return false;
		}
	});
});