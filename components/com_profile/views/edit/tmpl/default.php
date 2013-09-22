<?php
/**
 * @package		JomSocial
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;
$my = JXFactory::getUser();
?>
<div class="profile-navbar">
  <?php echo $this->showNavBar(); ?>
</div><!--end profile-navbar-->

<div id="profile-edit">
	<form class="edit" action="<?php echo JRoute::_('index.php?option=com_profile&view=edit');?>" method="post">
		<ul class="profile-form">
			<li>
				<label id="params_username-lbl" class="" for="params_username"><?php echo JText::_('COM_PROFILE_LABEL_USERNAME');?></label>
				<input type="text" value="<?php echo $this->escape($this->user->username);?>" id="username" name="username" disabled="disabled">
			</li>			
			<li>
				<?php echo $this->profileForm->getLabel('name', 'params'); ?>
				<?php echo $this->profileForm->getInput('name', 'params', $this->escape($this->user->name)); ?>
			</li>			
			<li>
			  <?php echo $this->profileForm->getLabel('email', 'params'); ?>
			  <?php echo $this->profileForm->getInput('email', 'params', $this->userEmail); ?>
			</li>	
			<?php if ( !$this->isIntegration) : ?>
			<li>
				<label id="params_password-lbl" class="" for="params_password"><?php echo JText::_('COM_PROFILE_LABEL_PASSWORD');?></label>
				<input type="password" value="" id="password" name="password">
			</li>			
			<li>
				<label id="params_confirm_password-lbl" class="" for="params_confirm_password"><?php echo JText::_('COM_PROFILE_LABEL_CONFIRM_PASSWORD');?></label>
				<input type="password" value="" id="confirm_password" name="confirm_password">
				<div class="" id="passwordStrength"></div>
			</li>
			<?php endif; ?>
			<li>
				<?php echo $this->profileForm->getLabel('about_me', 'params'); ?>
				<?php echo $this->profileForm->getInput('about_me', 'params', $this->escape($this->userAboutMe)); ?>
			</li>
			<li>
			  <?php echo $this->profileForm->getLabel('language', 'params'); ?>
			  <?php echo $this->profileForm->getInput('language', 'params', $this->userLanguage); ?>
			</li>			
			<li>
			  <?php echo $this->profileForm->getLabel('timezone', 'params'); ?>
			  <?php echo $this->profileForm->getInput('timezone', 'params', $this->userTimezone); ?>
			</li>
			
			<li>
				<label id="params_style-lbl" class="" for="params_style"><?php echo JText::_('COM_PEOPLE_LABEL_TEMPLATE');?></label>
				<select name="params[style]" id="params_style">
					<option <?php if( !$my->getParam('style')) {echo 'selected="selected"'; } ?> value="">Default</option>
					<option <?php if( $my->getParam('style') == 'blue') {echo 'selected="selected"'; } ?> value="blue">Blue</option>
					<option <?php if( $my->getParam('style') == 'red') {echo 'selected="selected"'; } ?>value="red">Red</option>
					<option <?php if( $my->getParam('style') == 'grey') {echo 'selected="selected"'; } ?>value="grey">Grey</option>
				</select>
			</li>
			<?php if ( $this->isIntegration) : ?>
			<li><?php echo JText::_('COM_ACCOUNT_AD_LABEL_ACCOUNT_LINK');?></li>		
			<?php endif; ?>
		</ul>
		
		<div class="submit">
			<input type="submit" class="btn btn-info" id="submitProfile" name="btnSubmit" value="<?php echo JText::_('COM_PROFILE_LABEL_SAVE');?>" />
		</div>
		
	</form>

</div><!--end profile-edit-->

<script type="text/javascript">
	$(document).ready(function() {
		S.validate.form($('#profile-edit form'), {
			'notEmpty': $('#params_name'),
			'email': $('#params_email')
		});
	});

	$(document).ready(function() {
		$('#password, #confirm_password').on('keyup', function(e) {
			if($('#password').val() == '' && $('#confirm_password').val() == '')
			{
				$('#passwordStrength').removeClass().html('');
				return false;
			} 
			$('#submitProfile').attr('disabled', 'disabled');
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
				$('#submitProfile').removeAttr('disabled');
			} else if (mediumRegex.test($(this).val())) {
				// If medium password matches the reg ex
				$('#passwordStrength').removeClass().addClass('alert alert-info').html('<?php echo JText::_('COM_REGISTER_ERRMSG_PASSWORD_MEDIUM'); ?>');
				$('#submitProfile').removeAttr('disabled');
			} else {
				// If password is ok
				$('#passwordStrength').removeClass().addClass('alert alert-error').html('<?php echo JText::_('COM_REGISTER_ERRMSG_PASSWORD_WEAK'); ?>');
				$('#submitProfile').removeAttr('disabled');
			}
        
			return true;
		});
	});
</script>
