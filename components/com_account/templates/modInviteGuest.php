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
<?php /* Old Style
<div id="guest_invite_container">
	<div id="invite_loading" style="display: none"><?php echo JText::_('COM_ACCOUNT_LABEL_PROCESSING_INVITE');?></div>
	<div id="invite_form">
		<div><?php echo JText::_('COM_ACCOUNT_LABEL_INVITE_FRIENDS_HERE');?></div>
		<form action="<?php echo JRoute::_('index.php?option=com_account&view=invite');?>" id="guest_invite_form" method="get">
			<div><input type="text" name="invitation" id="guest_emails"></div>
			<span class="help-text" style="color: #888888; font-size:10px">- <?php echo JText::_('COM_ACCOUNT_LABEL_INVITE_HELP_TEXT');?> -</span>
			<div style="float:right"><input class="btn" value="<?php echo JText::_('COM_ACCOUNT_LABEL_INVITE');?>" type="button" onclick="account.invite();" id="guest_invite_button"></div>
			<div class="clear"></div>
		</form>
	</div>
</div>
*/ ?>
<div class="moduletable" id="guest_invite_container">
	<div id="invite_loading" style="display: none"><?php echo JText::_('COM_ACCOUNT_LABEL_PROCESSING_INVITE');?></div>
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
		<?php /*
		<div class="alert alert-info invite">
			<h4>Offiria helps:</h4>
			<ul class="textlist">
				<li>Your team stay well-informed.</li>
				<li>Makes collaboration fast and easy.</li>
				<li>Encourages ideas and knowledge sharing.</li>
			</ul>
		</div>
		*/ ?>
	</div>
</div>