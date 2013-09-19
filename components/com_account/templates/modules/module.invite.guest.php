<script type="text/javascript">
	var account = {
		invite : function()	{			
			if ($('#guest_emails').val() != '')
			{
				$.ajax({
					url: "<?php echo JRoute::_('index.php?option=com_account&view=invite&task=ajaxMemberInvite');?>",
					dataType: "json",
					data: $("#guest_invite_form").serialize(),
					type: "post",
					beforeSend: function( xhr ) {
						$('#invite_loading').show();
						$('#invite_form').hide();
					},
					success: function( data ) {
						var error = (data.error || '1')
						var msg = (data.msg || '')
						if (error != '1')
						{
							$('#guest_emails').val('');
						}
						if (msg != "")
						{										
							alert(msg);
						}
						setTimeout("$('#guest_emails').focus()", 100);
					},
					complete: function(jqXHR, textStatus) {						
						$('#invite_loading').hide();
						$('#invite_form').show();
					}
				});
			}
		}
	};
	
	$(document).ready(function() {
		$('#guest_emails').keypress(function(event) {
			var keyCode = event.keyCode ? event.keyCode : event.which;
			if (keyCode == '13')
			{
				$('#guest_invite_button').trigger('click');
				return false;
			}
		})
	});
</script>
<div class="moduletable" id="guest_invite_container">
	<div id="invite_loading" style="display: none">
		<h3><?php echo JText::_('COM_ACCOUNT_LABEL_INVITE_FRIENDS_HERE');?></h3>
		<?php echo JText::_('COM_ACCOUNT_LABEL_PROCESSING_INVITE');?>
	</div>

	<div id="invite_form">
		<h3><?php echo JText::_('COM_ACCOUNT_LABEL_INVITE_FRIENDS_HERE');?></h3>

		<form action="<?php echo JRoute::_('index.php?option=com_account&view=invite');?>" id="guest_invite_form" method="get">
			
			<div class="invitation">
				<input type="text" name="invitation" id="guest_emails">
				<input class="btn" value="<?php echo JText::_('COM_ACCOUNT_LABEL_INVITE');?>" type="button" onclick="account.invite();" id="guest_invite_button">
			</div>
			<span class="help-block"><?php echo JText::_('COM_ACCOUNT_LABEL_INVITE_HELP_TEXT');?></span>
			
			<div class="clear"></div>
		</form>
		
		<?php
		$my = JXFactory::getUser();
		if(! $my->getParam(ALERT_INVITE_INTRO)){
			echo '<div class="alert alert-info" data-alert_id="'.ALERT_INVITE_INTRO.'">
			<a data-dismiss="alert" class="close">×</a>
			<h4>' . JText::_('COM_STREAM_HELPER_INVITE1') . '</h4>
			<ul class="textlist">
				<li class="divider"></li>
				<li>' . JText::_('COM_STREAM_HELPER_INVITE2') . '</li>
				<li>' . JText::_('COM_STREAM_HELPER_INVITE3') . '</li>
				<li>' . JText::_('COM_STREAM_HELPER_INVITE4') . '</li>
			</ul>
			</div>';
	    }
		?>
		
	</div>
</div>