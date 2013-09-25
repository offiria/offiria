<?php

/**
 * @package		Offiria
 * @subpackage	com_profile
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
?>
<link rel="stylesheet" type="text/css" href="<?php echo JURI::root(); ?>components/com_profile/assets/imgareaselect/css/imgareaselect-default.css" />
<script type="text/javascript" src="<?php echo JURI::root(); ?>components/com_profile/assets/imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>

<div class="profile-navbar">
<?php echo $this->showNavBar(); ?>
</div>

<?php if (isset($this->notification)) { ?>
<table id="table-application" class="table table-bordered" style="display:none">
	<tr>
		<td class="value table-title" colspan="2">
			<b>Application</b>
		</td>
	</tr>
	<tr>
		<td class="value">Notification Center</td>
		<td class="key"><input class="btn btn-info" type="button" id="allow-webkit-notification" value="Enable" /></td>
	</tr>
</table>
<form method="post" name="profile-notification" id="profile-notification" action="<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=notification', false);?>">
<table id="table-notification" class="formtable table table-bordered" cellspacing="1" cellpadding="0">
<!-- Start New email preference -->
<tr>
	<td class="value table-header">
		<?php echo JText::_('COM_PROFILE_NOTIFICATION_LABEL_NOTIFICATION_DETAILS');?>
	</td>
	<!--td class="key table-header">
		<label for="notification-header"><?php echo JText::_('COM_PROFILE_NOTIFICATION_LABEL_GLOBAL_NOTIFICATION');?></label>    
	</td-->
	<td class="key table-header">
		<label for="notification-header"><?php echo JText::_('COM_PROFILE_NOTIFICATION_LABEL_EMAIL_NOTIFICATION');?></label>    
	</td>
</tr>
<?php 
foreach ($this->notification->getNotification() as $group => $typeInfo) { 
?>
<tr>
	<td class="value table-title" colspan="3">
		<label for="type-title"><?php echo $this->notification->getNotificationTypeLabel( $group );?></label>    
	</td>
</tr>
<?php
	foreach ($typeInfo as $type => $typeSetting)
	{
		//$globalNotification = $this->notification->getGlobalNotificationIndex( $type );
		$emailNotification = $this->notification->getEmailNotificationIndex( $type );
?>
<tr>
	<td class="value">
		<label for="groups_notify_admin"><?php echo $typeSetting->label;?></label>    
	</td>

	<!--td class="key">
		<input id="<?php //echo $globalNotification;?>" type="checkbox" name="<?php //echo $globalNotification;?>" value="1" <?php //echo ($typeSetting->$globalNotification == '1') ? 'checked' : '';?> />
	</td-->
	<td class="key">
		<input id="<?php echo $emailNotification;?>" type="checkbox" name="<?php echo $emailNotification;?>" value="1" <?php echo ($typeSetting->$emailNotification == '1') ? 'checked' : '';?> />
	</td>
</tr>
<?php 
	} // end foreach
} // end foreach ?>
<!-- End New email preference -->
</table>
		
<div class="submit">
	<input type="submit" class="btn btn-info" name="submit" value="<?php echo JText::_('COM_PROFILE_LABEL_SAVE');?>" />
</div>
</form>
<script type="text/javascript">
	if ('webkitNotifications' in window) {
		//$('#table-application').show();
		if (webkitNotifications.checkPermission() == 0) {
			$('#allow-webkit-notification').attr('disabled', true);
		}
		$('#allow-webkit-notification').on('click', function() {
			webkitNotifications.requestPermission(function() { 
				if (webkitNotifications.checkPermission() == 0) {
					// Your user granted you permission to show notifications
					console.log('granted');
				}
			});
		});
	}

</script>

<?php } // end if ?>