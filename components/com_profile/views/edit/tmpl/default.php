<?php
/**
 * @package		Offiria
 * @subpackage	com_profile
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
			<input type="submit" class="btn btn-info " name="btnSubmit" value="<?php echo JText::_('COM_PROFILE_LABEL_SAVE');?>" />
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
</script>