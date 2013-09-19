<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

// no direct access
defined('_JEXEC') or die;
$jxConfig = new JXConfig();

?>
<div class="account-navbar">
<?php echo $this->showNavBar(); ?>
</div>
<style>
/**
 * Start by hiding the checkboxes
 */
input[type=checkbox] {
	visibility: hidden;
}
</style>

<form class="edit" action="<?php echo JRoute::_('index.php?option=com_account&view=account');?>" method="post">
<table border="0" cellpadding="0" class="table">
	<col width="40%"/>
	<col width="60%"/>
	
	<tr>
		<td colspan="2" class="table-title"><label><?php echo JText::_('COM_ACCOUNT_LABEL_BASIC_SETTING');?></label></td>
	</tr>
	<tr>
		<td><label for="params_sitename" class="" id="params_sitename-lbl"><?php echo JText::_('COM_ACCOUNT_LABEL_SITE_NAME');?></label></td>
		<td><input type="text" name="params[sitename]" value="<?php echo $this->sitename;?>" id="params_sitename"></td>
	</tr>
	<tr>
		<td><?php echo $this->profileForm->getLabel('language', 'params'); ?></td>
		<td><?php echo $this->profileForm->getInput('language', 'params', $this->default_language); ?></td>
	</tr>
	<tr>
		<td><?php echo $this->profileForm->getLabel('timezone', 'params'); ?></td>
		<td><?php echo $this->profileForm->getInput('timezone', 'params', $this->default_timezone); ?></td>
	</tr>
	<tr>
		<td><label for="params_domain_name" class="" id="params_domain_name-lbl"><?php echo JText::_('COM_ACCOUNT_LABEL_DOMAIN_NAME');?></label></td>
		<td><input type="text" name="params[domain_name]" value="<?php echo $this->domain_name;?>" id="params_domain_name" readonly<?php //echo ($this->domain_editable) ? '' : 'readonly';?>><span class="domain-name"><?php echo $jxConfig->getDomainSuffix();?></span></td>
	</tr>
	<tr>
		<td><label class="" for="params_allow_anon" id="params_allow_anon-lbl"><?php echo JText::_('COM_ACCOUNT_ALLOW_ANONYMOUS_COMMENT');?></label></td>
		<td><label>
		  	<div class="checkboxThree">
				<input type="checkbox" id="params_allow_anon" name="params[allow_anon]" value="1" <?php echo (intval($this->allow_anon) == 1) ? 'checked' : '';?>/>
				<label for="params_allow_anon"></label>
			</div></label>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="table-title"><label><?php echo JText::_('COM_ACCOUNT_LABEL_REGISTRATION');?></label></td>
	</tr>
	<tr>
		<td><label class="" for="params_allow_invite" id="params_allow_invite-lbl"><?php echo JText::_('COM_ACCOUNT_LABEL_ALLOW_MEMBERS_INVITE');?></label></td>
		<td><label>
		  	<div class="checkboxThree">
				<input type="checkbox" id="params_allow_invite" name="params[allow_invite]" value="1" <?php echo (intval($this->allow_invite) == 1) ? 'checked' : '';?> onchange="javascript:enableFields();"/>
				<label for="params_allow_invite"></label>
			</div></label>
		</td>
	</tr>
	<tr>
		<td><label for="params_limit_email_domain" class="" id="params_limit_email_domain-lbl"><?php echo JText::_('COM_ACCOUNT_LABEL_LIMIT_EMAIL_DOMAIN');?></label></td>
		<td><input type="text" name="params[limit_email_domain]" value="<?php echo $this->limit_email_domain;?>" id="params_limit_email_domain"><br /></td>
	</tr>
	<tr>
		<td colspan="2"><span class="api-note"><?php echo JText::_('COM_ACCOUNT_LABEL_LIMIT_EMAIL_DOMAIN_NOTE'); ?></span></td>
	</tr>
</table>
<div class="submit">
	<input type="hidden" value="display" name="task">
	<input class="btn btn-info" type="submit" value="<?php echo JText::_('COM_STREAM_LABEL_SAVE');?>" name="submit" style="float: right !important;">
</div>
</form>
<!--end account-edit-->

<script type="text/javascript">
	$(document).ready(function() {
//		S.validate.form($('#account-edit form'), {
//			'notEmpty': $('#params_sitename')
//		}); 
	});
	
	function enableFields() {
		var d = document;
		if (d.getElementById('params_allow_invite').checked == true) {
			d.getElementById("params_limit_email_domain").readOnly=false;
		} else {
			d.getElementById("params_limit_email_domain").readOnly=true;
		}
		
		return false;
	}
	enableFields();
</script>
