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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$isSignUp = (empty($this->token));
?>
<div class="registration">
	<form id="member-registration" action="<?php echo JRoute::_('index.php?option=com_register');?>" method="post"  class="form-validate">		
		<fieldset>
			<legend><?php echo JText::_("COM_REGISTER_LABEL_USER_REGISTRATION");?></legend>
			<dl>
				<dt>
					<span class="spacer"><span class="before"></span><span class="text"><label id="jform_spacer-lbl" class=""><strong class="red">*</strong><?php echo JText::_("COM_REGISTER_LABEL_REQUIRED_FIELD");?></label></span><span class="after"></span></span>								
				</dt>
				<dd></dd>
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
			</dl>
		</fieldset>
		<div>
			<?php echo JHtml::_('form.token'); ?>
			<input type="hidden" value="connect" name="view">
			<input type="submit" value="Connect" name="submit" class="offiria_inputsubmit validate" >
		</div>		
	</form>
</div><!--end registration-->

