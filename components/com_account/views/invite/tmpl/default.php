<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// no direct access
defined('_JEXEC') or die;

?>
<!-- @TODO: Remove inline styling -->
<style type="text/css">
	input[type="checkbox"] {
	   margin-right:10px !important;
	}
</style>

<div class="account-navbar">
<?php echo $this->showNavBar(); ?>
</div>

<div id="account-invite">
	<form class="edit" action="<?php echo JRoute::_('index.php?option=com_account&view=invite');?>" method="post">
		<ul class="account-form">
			<li>
				<label for="params_invitation" class="" id="params_params_invitation-lbl"><?php echo JText::_('COM_ACCOUNT_LABEL_INVITE_EMAILS');?></label>
				<div class="params-invitation">
					<textarea type="file" name="params[invitation]" value="" id="params_invitation" class="vertical"><?php echo $this->inviteEmail;?></textarea>
					<span class="help-text">
						<?php echo JText::_('COM_ACCOUNT_LABEL_INVITE_HELP_TEXT');?>
					</span>
				</div>
				<div class="clear"></div>
			</li>	
			<li>
				<label for="params_group_limited" class=""><?php echo JText::_('COM_ACCOUNT_LABEL_GROUP_LIMITED');?></label>
				<div class="params-invitation">
					<?php foreach($this->myJoinedGroups as $idx=>$group): ?>
					<input type="checkbox" class="checkbox" name="params[group_limited][]" value="<?php echo $group->id; ?>"><?php echo StreamTemplate::escape($group->name); ?><br>
					<?php endforeach; ?>
				</div>
				<div class="clear"></div>
			</li>	
		</ul>		
		<div class="submit">
			<?php if ($this->allowInvite) { ?>
			<input type="hidden" value="invite" name="task"/>
			<input class="btn btn-info" type="submit" value="<?php echo JText::_('COM_ACCOUNT_LABEL_INVITE');?>" name="submit"/>
			<?php } else { ?>
			<span><?php echo JText::_('COM_ACCOUNT_ERRMSG_REGISTRATION_REACHED'); ?></span>
			<?php } ?>
		</div>		
	</form>
</div><!--end account-invite-->

<?php if (!empty($this->results)) { ?>
<table id="table-invite" class="table table-bordered table-striped table-condensed table-novborder">
	<thead>
		<tr>
			<th><?php echo JText::_('COM_ACCOUNT_LABEL_SENDER_EMAIL');?></th>
			<th><?php echo JText::_('COM_ACCOUNT_LABEL_INVITED_EMAIL');?></th>
			<th><?php echo JText::_('COM_ACCOUNT_LABEL_STATUS');?></th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$no = count($this->results);
		for ($i = 0; $i < $no; $i++)
		{	
			echo $this->results[$i]->getRowHtml();
		} // end for loop
		?>
	</tbody>
</table>
<?php } ?>

<div class="pagination">
<?php echo $this->pagination->getPagesLinks(); ?>
</div>

<script type="text/javascript">
	
	var invitation = {
		resendInvitation : function(resendEmail, id, source) 
		{
			clickedButton = $(source);
			deleteButton = $(source).siblings('input');
			sourceParent = clickedButton.parent();

			var dateSpan = $(source).parent().siblings(".to-update").find(".last-invite-date");
			$.ajax({
				url: '<?php echo JRoute::_('index.php?option=com_account&view=invite&task=ajaxResendInvitation', false);?>',
				dataType: 'json',
				data: 'invitation_id='+id+'&email='+encodeURIComponent(resendEmail),
				type: 'post',
				beforeSend: function( xhr ) {
					sourceParent.html('<?php echo addslashes(JText::_('COM_ACCOUNT_LABEL_SENDING'));?>');
				},
				success: function( data ) {
					if (typeof data.error != 'undefined' && data.error != '1')
					{
						if (data.info != undefined)
						{
							//$(dateSpan).html(data.info );
							//console.log(data.html);
							//console.log($('#row'+id));
							$('#row'+id).replaceWith(data.html);
						}

						if (data.msg != undefined)
						{
							alert(data.msg );
						}
					}
					else
					{
						alert('<?php echo addslashes(JText::_('COM_ACCOUNT_ERRMSG_FAIL_RESEND_INVITATION'));?>');
					}
				},
				complete: function() {
					sourceParent.html(clickedButton);
					sourceParent.append(deleteButton);
				}
			});
		},
		deleteInvitation: function(invitation) {
			$.ajax({
				url: '<?php echo JRoute::_('index.php?option=com_account&view=invite&task=ajaxDeleteInvitation', false);?>',
				dataType: 'json',
				data: 'invitation='+invitation,
				type: 'post',
				beforeSend: function( xhr ) {
				},
				success: function( data ) {
					if (typeof data.error != 'undefined' && data.error != '1')
					{
						$('#row'+invitation).remove();
					}
					else
					{
						alert('<?php echo addslashes(JText::_('COM_ACCOUNT_ERRMSG_FAIL_DELETE_INVITATION'));?>');
					}
				}
			});
		}
	}
</script>