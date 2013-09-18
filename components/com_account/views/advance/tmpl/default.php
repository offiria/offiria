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
	<col width="30%"/>
	<col width="70%"/>
	<!-- CROCODOCS SETTINGS -->
	<tr>
		<td class="table-title"><label><?php echo JText::_('COM_ACCOUNT_LABEL_CROCODOCS');?></label></td>
		<td class="table-title"><label style="float: right !important;">
			<div class="checkboxThree" >
				<input type="checkbox" id="jform_crocodocsenable" name="jform[crocodocsenable]" value="1" <?php echo ($this->crocodocsenable == '1') ? 'checked="checked"' : '';?> onchange="javascript:enableFields();"/>
				<label for="jform_crocodocsenable"></label>
			</div></label>
		</td>
	</tr>
	<tr>
		<td><label id="jform_crocodocs-lbl" for="jform_crocodocs" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_TOKEN');?></label></td>
		<td><input type="text" name="jform[crocodocs]" id="jform_crocodocs" value="<?php echo $this->crocodocs;?>" size="30"></td>
	</tr>
	<tr>
		<td colspan="2"><span class="api-note"><?php echo JText::_('COM_ACCOUNT_LABEL_HELPER_CROCODOCS'); ?></span></td>
	</tr>
	<!-- SCRIBD SETTINGS -->
	<tr>
		<td class="table-title"><label><?php echo JText::_('COM_ACCOUNT_LABEL_SCRIBD');?></label></td>
		<td class="table-title"><label style="float: right !important;">
		  	<div class="checkboxThree">
				<input type="checkbox" id="jform_scribdenable" name="jform[scribdenable]" value="1" <?php echo ($this->scribdenable == '1') ? 'checked="checked"' : '';?> onchange="javascript:enableFields();"/>
				<label for="jform_scribdenable"></label>
			</div></label>
		</td>
	</tr>
	<tr>
		<td><label id="jform_scribd_api-lbl" for="jform_scribd_api" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_API_KEY');?></label></td>
		<td><input type="text" name="jform[scribd_api]" id="jform_scribd_api" value="<?php echo $this->scribd_api;?>" size="30"></td>
	</tr>
	<tr>
		<td><label id="jform_scribd_secret-lbl" for="jform_scribd_secret" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_API_SECRET');?></label></td>
		<td><input type="text" name="jform[scribd_secret]" id="jform_scribd_secret" value="<?php echo $this->scribd_secret;?>" size="30"></td>
	</tr>
	<tr>
		<td colspan="2"><span class="api-note"><?php echo JText::_('COM_ACCOUNT_LABEL_HELPER_SCRIBD'); ?></span></td>
	</tr>
	<!-- DIFFBOT SETTINGS -->
	<tr>
		<td class="table-title"><label><?php echo JText::_('COM_ACCOUNT_LABEL_DIFFBOT');?></label></td>
		<td class="table-title"><label style="float: right !important;">
		  	<div class="checkboxThree">
				<input type="checkbox" id="jform_diffbotenable" name="jform[diffbotenable]" value="1" <?php echo ($this->diffbotenable == '1') ? 'checked="checked"' : '';?> onchange="javascript:enableFields();"/>
				<label for="jform_diffbotenable"></label>
			</div></label>
		</td>
	</tr>
	<tr>
		<td><label id="jform_diffbot-lbl" for="jform_diffbot" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_TOKEN');?></label></td>
		<td><input type="text" name="jform[diffbot]" id="jform_diffbot" value="<?php echo $this->diffbot;?>" size="30"></td>
	</tr>
	<tr>
		<td colspan="2"><span class="api-note"><?php echo JText::_('COM_ACCOUNT_LABEL_HELPER_DIFFBOT'); ?></span></td>
	</tr>
	<!-- MAIL SETTINGS -->
	<tr>
		<td colspan="2" class="table-title"><label><?php echo JText::_('COM_ACCOUNT_LABEL_MAIL_SETTING');?></label></td>
	</tr>
	<tr>
		<td><label id="jform_mailer-lbl" for="jform_mailer" class="hasTip required" title="" aria-invalid="false"><?php echo JText::_('COM_ACCOUNT_LABEL_MAILER');?><span class="star">&nbsp;*</span></label></td>
		<td>
			<select id="jform_mailer" name="jform[mailer]" class="required" aria-required="true" required="required" aria-invalid="false" onchange="javascript:enableFields();">
				<option value="mail" <?php echo ($this->mailer == 'mailer') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_PHP_MAIL');?></option>
				<option value="sendmail" <?php echo ($this->mailer == 'sendmail') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_SENDMAIL');?></option>
				<option value="smtp" <?php echo ($this->mailer == 'smtp') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP');?></option>
			</select>		
		</td>
	</tr>
	<tr>
		<td><label id="jform_mailfrom-lbl" for="jform_mailfrom" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_FROM_EMAIL');?></label></td>
		<td><input type="email" name="jform[mailfrom]" class="validate-email" id="jform_mailfrom" value="<?php echo $this->mailfrom;?>" size="30"></td>
	</tr>	
	<tr>
		<td><label id="jform_fromname-lbl" for="jform_fromname" class="hasTip" title="" aria-invalid="false"><?php echo JText::_('COM_ACCOUNT_LABEL_FROM_NAME');?></label></td>
		<td><input type="text" name="jform[fromname]" id="jform_fromname" value="<?php echo $this->fromname;?>" size="30" class="" aria-invalid="false"></td>
	</tr>
	<tr>
		<td><label id="jform_sendmail-lbl" for="jform_sendmail" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SENDMAIL_PATH');?></label></td>
		<td><input type="text" name="jform[sendmail]" id="jform_sendmail" value="<?php echo $this->sendmail;?>" size="30"></td>
	</tr>
	<tr>
		<td><label id="jform_smtpauth-lbl" for="jform_smtpauth" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_AUTHENTICATION');?></label></td>
		<td>
			<label>
		  	<div class="checkboxThree">
				<input type="checkbox" id="jform_smtpauth" name="jform[smtpauth]" value="1" <?php echo ($this->smtpauth == '1') ? 'checked="checked"' : '';?>/>
				<label for="jform_smtpauth"></label>
			</div></label>		
		</td>
	</tr>
	<tr>
		<td><label id="jform_smtpsecure-lbl" for="jform_smtpsecure" class="hasTip" title="" aria-invalid="false"><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_SECURITY');?></label></td>
		<td>
			<select id="jform_smtpsecure" name="jform[smtpsecure]" class="" aria-invalid="false">
				<option value="none" <?php echo ($this->smtpsecure == 'none') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_NONE');?></option>
				<option value="ssl" <?php echo ($this->smtpsecure == 'ssl') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_SSL');?></option>
				<option value="tls" <?php echo ($this->smtpsecure == 'tls') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_TLS');?></option>
			</select>		
		</td>
	</tr>
	<tr>
		<td><label id="jform_smtpport-lbl" for="jform_smtpport" class="hasTip required" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_PORT');?><span class="star">&nbsp;*</span></label></td>
		<td><input type="text" name="jform[smtpport]" id="jform_smtpport" value="<?php echo $this->smtpport;?>" class="required" size="6" aria-required="true" required="required"></td>
	</tr>
	<tr>
		<td><label id="jform_smtpuser-lbl" for="jform_smtpuser" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_USERNAME');?></label></td>
		<td><input type="text" name="jform[smtpuser]" id="jform_smtpuser" value="<?php echo $this->smtpuser;?>" size="30"></td>
	</tr>
	<tr>
		<td><label id="jform_smtppass-lbl" for="jform_smtppass" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_PASSWORD');?></label></td>
		<td><input type="password" name="jform[smtppass]" id="jform_smtppass" value="<?php echo $this->smtppass;?>" size="30"></td>
	</tr>
	<tr>
		<td><label id="jform_smtphost-lbl" for="jform_smtphost" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_HOST');?></label></td>
		<td><input type="text" name="jform[smtphost]" id="jform_smtphost" value="<?php echo $this->smtphost;?>" size="30"></td>
	</tr>
	<!-- MODULE SETTINGS -->
	<tr>
		<td colspan="2" class="table-title"><label><?php echo JText::_('COM_ACCOUNT_LABEL_MODULES');?></label></td>
	</tr>
	<tr>
		<td><label id="jform_mailer-lbl" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_MODULES_TITLE');?></label></td>
		<td>
			<table>
				<?php foreach ($GLOBALS['MODULES'] as $key => $value) {
					$checked = (($this->{'module_' . $key}) ? 'checked="yes"' : ''); ?>
					<tr>
						<td><?php echo $value; ?></td>
						<td>
							<label style="float: right !important;">
							<div class="checkboxOne" >
								<input type="checkbox" id="jform[module_<?php echo $key; ?>]" name="jform[module_<?php echo $key; ?>]" value="1" <?php echo $checked;?>/>
								<label for="jform[module_<?php echo $key; ?>]"></label>
							</div></label>
						</td>
					</tr>
				<?php } ?>				
			</table>
		</td>
	</tr>		
</table>
<div class="submit">
	<input type="hidden" value="advance" name="task">
	<input class="btn btn-info" type="submit" value="<?php echo JText::_('COM_STREAM_LABEL_SAVE');?>" name="submit" style="float: right !important;">
</div>
</form>
<script type="text/javascript">
	function enableFields() {
		if (document.getElementById('jform_crocodocsenable').checked == true) {
			document.getElementById("jform_crocodocs").readOnly=false;
		} else {
			document.getElementById("jform_crocodocs").readOnly=true;
		}

		if (document.getElementById('jform_scribdenable').checked == true) {
			document.getElementById("jform_scribd_api").readOnly=false;
			document.getElementById("jform_scribd_secret").readOnly=false;
		} else {
			document.getElementById("jform_scribd_api").readOnly=true;
			document.getElementById("jform_scribd_secret").readOnly=true;
		}

		if (document.getElementById('jform_diffbotenable').checked == true) {
			document.getElementById("jform_diffbot").readOnly=false;
		} else {
			document.getElementById("jform_diffbot").readOnly=true;
		}
		
		var e = document.getElementById("jform_mailer");
		var strMailer = e.options[e.selectedIndex].value;
		switch(strMailer) {
			default:
			case 'mailer':
				document.getElementById("jform_sendmail").readOnly=true;
				document.getElementById("jform_smtpauth").readOnly=true;
				document.getElementById("jform_smtpauth").disabled=true;
				document.getElementById("jform_smtpsecure").readOnly=true;
				document.getElementById("jform_smtpsecure").disabled=true;
				document.getElementById("jform_smtpport").readOnly=true;
				document.getElementById("jform_smtpuser").readOnly=true;
				document.getElementById("jform_smtppass").readOnly=true;
				document.getElementById("jform_smtphost").readOnly=true;
				break;
			case 'sendmail':
				document.getElementById("jform_sendmail").readOnly=false;
				document.getElementById("jform_smtpauth").readOnly=true;
				document.getElementById("jform_smtpauth").disabled=true;
				document.getElementById("jform_smtpsecure").readOnly=true;
				document.getElementById("jform_smtpsecure").disabled=true;
				document.getElementById("jform_smtpport").readOnly=true;
				document.getElementById("jform_smtpuser").readOnly=true;
				document.getElementById("jform_smtppass").readOnly=true;
				document.getElementById("jform_smtphost").readOnly=true;				
				break;
			case 'smtp':
				document.getElementById("jform_smtpauth").readOnly=false;
				document.getElementById("jform_smtpauth").disabled=false;
				document.getElementById("jform_smtpsecure").readOnly=false;
				document.getElementById("jform_smtpsecure").disabled=false;
				document.getElementById("jform_smtpport").readOnly=false;
				document.getElementById("jform_smtpuser").readOnly=false;
				document.getElementById("jform_smtppass").readOnly=false;
				document.getElementById("jform_smtphost").readOnly=false;			
				break;
		}
		return false;
	}
	enableFields();
</script>

<!--
mailer
sendmail
smtp

	jform_sendmail
	
	jform_smtpauth
	jform_smtpsecure	
	jform_smtpport
	jform_smtpuser
	jform_smtppass
	jform_smtphost
	
	-->