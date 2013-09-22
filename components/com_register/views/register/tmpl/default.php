<?php
/**
 * @version     1.0.0
 * @package     com_register
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
//JHtml::_('behavior.tooltip');
//JHtml::_('behavior.formvalidation');

$isSignUp = (empty($this->token));
?>
<div class="registration login-top">
	<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_register');?>" method="post"  class="form-validate">
		<fieldset>
			<legend><?php echo JText::_("COM_REGISTER_LABEL_USER_REGISTRATION");?></legend>
			<br />
			<dl>
				<dt>
					<label id="jform_name-lbl" for="name" class="hasTip required" title="<?php echo JText::_("COM_REGISTER_TOOLTIP_NAME");?>"><?php echo JText::_("COM_REGISTER_LABEL_NAME");?>:<span class="star">&#160;*</span></label>
				</dt>
				<dd>
					<input type="text" name="name" id="name" value="<?php echo $this->name;?>" class="required" size="30"/>
				</dd>
				<dt>
					<label id="jform_username-lbl" for="username" class="hasTip required" title="<?php echo JText::_("COM_REGISTER_TOOLTIP_USERNAME");?>"><?php echo JText::_("COM_REGISTER_LABEL_USERNAME");?>:<span class="star">&#160;*</span></label>
				</dt>
				<dd>
					<input type="text" name="username" id="username" value="<?php echo $this->username;?>" class="validate-username required" size="30"/>
				</dd>
				<dt>
					<label id="jform_password1-lbl" for="password" class="hasTip required" title="<?php echo JText::_("COM_REGISTER_TOOLTIP_PASSWORD");?>"><?php echo JText::_("COM_REGISTER_LABEL_PASSWORD");?>:<span class="star">&#160;*</span></label>
				</dt>
				<dd>
					<input type="password" name="password" id="password" value="<?php echo $this->password;?>" autocomplete="off" class="validate-password required" size="30"/>
				</dd>
				<dt>
					<label id="jform_password2-lbl" for="confirm_password" class="hasTip required" title="<?php echo JText::_("COM_REGISTER_TOOLTIP_CONFIRM_PASSWORD");?>"><?php echo JText::_("COM_REGISTER_LABEL_CONFIRM_PASSWORD");?>:<span class="star">&#160;*</span></label>
				</dt>
				<dd>
					<input type="password" name="confirm_password" id="confirm_password" value="<?php echo $this->confirm_password;?>" autocomplete="off" class="validate-password required" size="30"/>
					<div class="" id="passwordStrength"></div>
				</dd>
				<dt>
					<label id="jform_email1-lbl" for="email" class="hasTip required" title="<?php echo JText::_("COM_REGISTER_TOOLTIP_EMAIL");?>"><?php echo JText::_("COM_REGISTER_LABEL_EMAIL_ADDRESS");?>:<span class="star">&#160;*</span></label>
				</dt>
				<dd>
					<input type="text" name="email" class="validate-email required" id="email" value="<?php echo $this->email;?>" size="30" <?php echo ($isSignUp) ? '': 'readonly';?>/>
				</dd>
			</dl>
		</fieldset>
		<div>
			<input type="hidden" value="<?php echo $this->code;?>" name="code">
			<input type="hidden" value="<?php echo $this->token;?>" name="token">
			<input type="hidden" value="<?php echo ($isSignUp) ? 'signup': 'register';?>" name="view">
			<?php echo JHtml::_('form.token');?>
			<input type="submit" value="<?php echo JText::_('COM_REGISTER_LABEL_REGISTER');?>" name="submit" id="registerProfile" class="offiria_inputsubmit validate">
		</div>
	</form>
</div><!--end registration-->
<div class="login"><a href="<?php echo JRoute::_('');?>"><?php echo JText::_('COM_USERS_LOGIN_BACK'); ?></a></div>

<script type="text/javascript">
	$(document).ready(function() {
		$('#password, #confirm_password, #name').on('keyup', function(e) {
			$('#registerProfile').attr('disabled', 'disabled');
			if($('#password').val() == '' && $('#confirm_password').val() == '')
			{
				$('#passwordStrength').removeClass().html('');
				return false;
			}
			if(($('#password').val() != '' && $('#confirm_password').val() == '') ||
				($('#password').val() == '' && $('#confirm_password').val() != '') ||	
				($('#password').val() != $('#confirm_password').val()))
			{
				$('#passwordStrength').removeClass().addClass('alert alert-error').html('<?php echo JText::_('COM_REGISTER_ERRMSG_PASSWORD_MISMATCH'); ?>');
				return false;
			}
 
			// Must have capital letter, numbers and lowercase letters
			var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
 
			// Must have either capitals and lowercase letters or lowercase and numbers
			var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
 
			// Must be at least 6 characters long
			var okRegex = new RegExp("(?=.{4,}).*", "g");
 
			
			if (okRegex.test($(this).val()) === false) {
				// If ok regex doesn't match the password
				$('#passwordStrength').removeClass().addClass('alert alert-error').html('<?php echo JText::_('COM_REGISTER_ERRMSG_PASSWORD_SHORT'); ?>');
			} else if (strongRegex.test($(this).val())) {
				// If reg ex matches strong password
				$('#passwordStrength').removeClass().addClass('alert alert-success').html('<?php echo JText::_('COM_REGISTER_ERRMSG_PASSWORD_GOOD'); ?>');
				$('#registerProfile').removeAttr('disabled');
			} else if (mediumRegex.test($(this).val())) {
				// If medium password matches the reg ex
				$('#passwordStrength').removeClass().addClass('alert alert-info').html('<?php echo JText::_('COM_REGISTER_ERRMSG_PASSWORD_MEDIUM'); ?>');
				$('#registerProfile').removeAttr('disabled');
			} else {
				// If password is ok
				$('#passwordStrength').removeClass().addClass('alert alert-error').html('<?php echo JText::_('COM_REGISTER_ERRMSG_PASSWORD_WEAK'); ?>');
				$('#registerProfile').removeAttr('disabled');
			}
        
			return true;
		});
	});
</script>