<?php
/**
 * @version     1.0.0
 * @package     com_register
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
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
			<input type="submit" value="<?php echo JText::_('COM_REGISTER_LABEL_REGISTER');?>" name="submit" class="offiria_inputsubmit validate" >
		</div>
	</form>
</div><!--end registration-->
<div class="login"><a href="<?php echo JRoute::_('');?>">Back to login page</a></div>

