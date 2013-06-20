<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<link
	rel="stylesheet" type="text/css"
	href="<?php echo JURI::root(); ?>components/com_profile/assets/imgareaselect/css/imgareaselect-default.css" />
<script
	type="text/javascript"
	src="<?php echo JURI::root(); ?>components/com_profile/assets/imgareaselect/scripts/jquery.imgareaselect.pack.js"></script>

<div class="profile-navbar">
<?php echo $this->showNavBar(); ?>
</div>

<style type="text/css">
#applications-list {
	list-style: none;
}

.applications-row {
	margin: 10px 0px;
	padding: 8px 0 5px 5px;
	border-bottom: 1px solid #ccc;
}

.applications-name {
	font-weight: bold;
	font-size: 1.4em;
	display: block;
}

.applications-id {
	font-weight: bold;
	color: #666;
}

.applications-metadata {
	float: left;
}

.applications-metadata span {
	display: block;
}

.applications-revoke {
	margin-top: 8px;
	width: 33%;
	float: right;
}

.applications-revoke input {
	float: right;
}

.applications-expiry {
	font-size: 0.8em;
}

.applications-row.grant {
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=80)";
	filter: alpha(opacity =   80);
	-moz-opacity: 0.8;
	-khtml-opacity: 0.8;
	opacity: 0.8;
}

.applications-row.revoke {
	-ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
	filter: alpha(opacity =   100);
	-moz-opacity: 1.0;
	-khtml-opacity: 1.0;
	opacity: 1.0;
}

.applications-row:after {
	content: ".";
	height: 0;
	visibility: hidden;
	display: block;
	clear: both;
}

#applications-install-toggler {
	float: right;
}

#applications-install-buttons {
	float: right;
}

#applications-install-cancel {
	cursor: pointer;
}
#applications-installed {
	padding:10px 20px;
	background:#fffafa;
	border:1px solid #009933;
}
#applications-installed input[type=submit] {
	float:right;
}
#applications-installed:after {
	content:".";
	visibility:hidden;
	clear:both;
	display:block;
}
</style>

<?php if (JRequest::getVar('authorize')): ?>
<div id="applications-installed">
	<b><?php echo JRequest::getVar('appName'); ?> <?php echo JText::_('COM_PROFILE_HEADLINE_INSTALLED')?></b><br />
	<?php echo JText::_('COM_PROFILE_TEXT_INSTALLED_INSTRUCTIONS')?>
</div> <!-- end applications-installed -->
<?php elseif (JRequest::getVar('installing')): ?>
<div id="applications-installed">
<form action="<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=applications')?>" method="post">
	<h3>
		<?php echo JText::_('COM_OAUTH_PROMPT_APPROVAL'); ?>
	</h3>
	<b><?php echo JRequest::getVar('client_id'); ?></b>
	<input type="hidden" name="client_id" value="<?php echo JRequest::getVar('client_id'); ?>" />
	<input type="hidden" name="client_secret" value="<?php echo JRequest::getVar('client_secret'); ?>" />
	<input type="hidden" name="redirect_uri" value="<?php echo JRequest::getVar('redirect_uri'); ?>" />
	<p>
		<?php echo JText::_('COM_OAUTH_PROMPT_REASON'); ?>
	</p>
	<input class="btn btn-success" type="submit" value="<?php echo JText::_('COM_OAUTH_FORM_APPROVED') ?>" />
</form>
</div>						<!--end applications-installed-->
<?php endif; ?>

<p>
<?php if (count($this->userDevices) > 0): ?>
<?php echo JText::_('COM_PROFILE_TEXT_APPLICATIONS_TEXT');?>
</p>
<ul id="applications-list">
<?php foreach ($this->userDevices as $device) { ?>
<?php // $appStatus is a styling class and action that will differentiate from one another ?>
<?php $appStatus = ($device->isAuthorized) ? 'revoke' : 'grant' ?>
	<li class="applications-row <?php echo $appStatus?>">
		<form
			action="<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=applications')?>"
			method="post">
			<div class="applications-metadata">
				<span class="applications-name">
					<?php echo $device->client_id ?>
				</span>
				<span class="applications-expiry">
					<b>Expired on</b>: <?php echo date('d M Y \a\t H:i:s', strtotime($device->expires)) ?>
				</span>
			</div>
			<!-- end application-metadata -->
			<?php
			  $class = ($device->isAuthorized) ? 'btn-danger' : 'btn-info';
			?>
			<div class="applications-revoke">
				<a href="<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=revokeDevice&consumerId=') . $device->id ?>"><?php echo JText::_('COM_PROFILE_DEVICE_LABEL_DELETE'); ?></a> or <input class="btn <?php echo $class; ?>" type="submit" class="applications-button"
					value="<?php echo ($device->isAuthorized) ? JText::_('COM_PROFILE_DEVICE_LABEL_REVOKE') : JText::_('COM_PROFILE_DEVICE_LABEL_GRANT') ?>" />
				<input type="hidden" value="<?php echo $device->id ?>"
					name="deviceId" /> <input type="hidden"
					value="<?php echo $appStatus ?>" name="deviceAction" />
			</div>
			<!-- end applications-revoke -->
		</form>
	</li>
	<?php } // end foreach ?>
</ul>
<?php else: ?>
There are no installed application yet. Download <?php echo JText::_('CUSTOM_SITE_NAME');?> Desktop to receive notification right on your desktop.
<?php endif; ?>
<script type="text/javascript">
$('#applications-install-toggler').click(function(e) {
	$('#applications-install-form').show();
	$('#applications-install-toggler').hide();
});
$('#applications-install-cancel').click(function(e) {
	$('#applications-install-form').hide();
	$('#applications-install-toggler').show();
});
$('#app-type').change(function() {
	// only show when web is selected
	if ($(this).val() == 'web') {
		$('#app-redirect').show();
	}
	else {
		$('#app-redirect').hide();
	}	
});
</script>
