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
$jxConfig = new JXConfig();

?>
<div class="account-navbar">
<?php echo $this->showNavBar(); ?>
</div>

<div id="account-edit">
	<form class="edit" action="<?php echo JRoute::_('index.php?option=com_account&view=account');?>" method="post">
		<!-- CROCODOCS SETTINGS -->
		<h3 class="section-title"><?php echo JText::_('COM_ACCOUNT_LABEL_CROCODOCS');?></h3>
		<fieldset class="adminform">
			<ul class="adminformlist">
				<li>
					<label id="jform_crocodocsenable-lbl" for="jform_crocodocsenable" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_ENABLE');?></label>					
					<fieldset id="jform_crocodocsenable-fs" class="radio">
						<input type="checkbox" id="jform_crocodocsenable" name="jform[crocodocsenable]" value="1" <?php echo ($this->crocodocsenable == '1') ? 'checked="checked"' : '';?>/>
					</fieldset>
				</li>
				<li>
					<label id="jform_crocodocs-lbl" for="jform_crocodocs" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_TOKEN');?></label>					
					<input type="text" name="jform[crocodocs]" id="jform_crocodocs" value="<?php echo $this->crocodocs;?>" size="30">
				</li>
			</ul>
			<span class="api-note"><?php echo JText::_('COM_ACCOUNT_LABEL_HELPER_CROCODOCS'); ?></span>
		</fieldset>
		
		<!-- SCRIBD SETTINGS -->
		<h3 class="section-title"><?php echo JText::_('COM_ACCOUNT_LABEL_SCRIBD');?></h3>
		<fieldset class="adminform">
			<ul class="adminformlist">
				<li>
					<label id="jform_scribdenable-lbl" for="jform_scribdenable" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_ENABLE');?></label>					
					<fieldset id="jform_scribdenable-fs" class="radio">
						<input type="checkbox" id="jform_scribdenable" name="jform[scribdenable]" value="1" <?php echo ($this->scribdenable == '1') ? 'checked="checked"' : '';?>/>
					</fieldset>
				</li>
				<li>
					<label id="jform_scribd_api-lbl" for="jform_scribd_api" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_API_KEY');?></label>					
					<input type="text" name="jform[scribd_api]" id="jform_scribd_api" value="<?php echo $this->scribd_api;?>" size="30">
				</li>
				<li>
					<label id="jform_scribd_secret-lbl" for="jform_scribd_secret" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_API_SECRET');?></label>					
					<input type="text" name="jform[scribd_secret]" id="jform_scribd_secret" value="<?php echo $this->scribd_secret;?>" size="30">
				</li>
			</ul>
			<span class="api-note"><?php echo JText::_('COM_ACCOUNT_LABEL_HELPER_SCRIBD'); ?></span>
		</fieldset>
		
		<!-- DIFFBOT SETTINGS -->
		<h3 class="section-title"><?php echo JText::_('COM_ACCOUNT_LABEL_DIFFBOT');?></h3>
		<fieldset class="adminform">
			<ul class="adminformlist">
				<li>
					<label id="jform_diffbot-lbl" for="jform_diffbot" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_TOKEN');?></label>					
					<input type="text" name="jform[diffbot]" id="jform_diffbot" value="<?php echo $this->diffbot;?>" size="30">
				</li>
			</ul>
			<span class="api-note"><?php echo JText::_('COM_ACCOUNT_LABEL_HELPER_DIFFBOT'); ?></span>
		</fieldset>
		
		<!-- MAIL SETTINGS -->
		<h3 class="section-title"><?php echo JText::_('COM_ACCOUNT_LABEL_MAIL_SETTING');?></h3>
		<fieldset class="adminform">
				<ul class="adminformlist">
					<li><label id="jform_mailer-lbl" for="jform_mailer" class="hasTip required" title="" aria-invalid="false"><?php echo JText::_('COM_ACCOUNT_LABEL_MAILER');?><span class="star">&nbsp;*</span></label>					
						<select id="jform_mailer" name="jform[mailer]" class="required" aria-required="true" required="required" aria-invalid="false">
							<option value="mail" <?php echo ($this->mailer == 'mailer') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_PHP_MAIL');?></option>
							<option value="sendmail" <?php echo ($this->mailer == 'sendmail') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_SENDMAIL');?></option>
							<option value="smtp" <?php echo ($this->mailer == 'smtp') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP');?></option>
						</select>
					</li>
					<li>
						<label id="jform_mailfrom-lbl" for="jform_mailfrom" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_FROM_EMAIL');?></label>					
						<input type="email" name="jform[mailfrom]" class="validate-email" id="jform_mailfrom" value="<?php echo $this->mailfrom;?>" size="30">
					</li>
					<li>
						<label id="jform_fromname-lbl" for="jform_fromname" class="hasTip" title="" aria-invalid="false"><?php echo JText::_('COM_ACCOUNT_LABEL_FROM_NAME');?></label>					
						<input type="text" name="jform[fromname]" id="jform_fromname" value="<?php echo $this->fromname;?>" size="30" class="" aria-invalid="false">
					</li>
					<li>
						<label id="jform_sendmail-lbl" for="jform_sendmail" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SENDMAIL_PATH');?></label>					
						<input type="text" name="jform[sendmail]" id="jform_sendmail" value="<?php echo $this->sendmail;?>" size="30">
					</li>
					<li>
						<label id="jform_smtpauth-lbl" for="jform_smtpauth" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_AUTHENTICATION');?></label>					
						<fieldset id="jform_smtpauth" class="radio">
							<input type="radio" id="jform_smtpauth0" name="jform[smtpauth]" value="1" <?php echo ($this->smtpauth == '1') ? 'checked="checked"' : '';?>/>
							<label for="jform_smtpauth0"><?php echo JText::_('COM_ACCOUNT_LABEL_YES');?></label>
							<input type="radio" id="jform_smtpauth1" name="jform[smtpauth]" value="0" <?php echo ($this->smtpauth == '0') ? 'checked="checked"' : '';?>>
							<label for="jform_smtpauth1"><?php echo JText::_('COM_ACCOUNT_LABEL_NO');?></label>
						</fieldset>
					</li>
					<li><label id="jform_smtpsecure-lbl" for="jform_smtpsecure" class="hasTip" title="" aria-invalid="false"><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_SECURITY');?></label>					
						<select id="jform_smtpsecure" name="jform[smtpsecure]" class="" aria-invalid="false">
							<option value="none" <?php echo ($this->smtpsecure == 'none') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_NONE');?></option>
							<option value="ssl" <?php echo ($this->smtpsecure == 'ssl') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_SSL');?></option>
							<option value="tls" <?php echo ($this->smtpsecure == 'tls') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_TLS');?></option>
						</select>
					</li>
					<li>
						<label id="jform_smtpport-lbl" for="jform_smtpport" class="hasTip required" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_PORT');?><span class="star">&nbsp;*</span></label>					
						<input type="text" name="jform[smtpport]" id="jform_smtpport" value="<?php echo $this->smtpport;?>" class="required" size="6" aria-required="true" required="required">
					</li>
					<li>
						<label id="jform_smtpuser-lbl" for="jform_smtpuser" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_USERNAME');?></label>					
						<input type="text" name="jform[smtpuser]" id="jform_smtpuser" value="<?php echo $this->smtpuser;?>" size="30">
					</li>
					<li>
						<label id="jform_smtppass-lbl" for="jform_smtppass" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_PASSWORD');?></label>					
						<input type="password" name="jform[smtppass]" id="jform_smtppass" value="<?php echo $this->smtppass;?>" size="30">
					</li>
					<li>
						<label id="jform_smtphost-lbl" for="jform_smtphost" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_HOST');?></label>					
						<input type="text" name="jform[smtphost]" id="jform_smtphost" value="<?php echo $this->smtphost;?>" size="30">
					</li>
				</ul>
		</fieldset>
		
		<div class="submit">
			<input type="hidden" value="advance" name="task">
			<input class="btn btn-info" type="submit" value="<?php echo JText::_('COM_STREAM_LABEL_SAVE');?>" name="submit">
		</div>		
	</form>
</div><!--end account-edit-->

<script type="text/javascript">
	$(document).ready(function() {
//		S.validate.form($('#account-edit form'), {
//			'notEmpty': $('#params_sitename')
//		}); 
	});
</script>
