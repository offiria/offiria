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
		<h3 class="section-title"><?php echo JText::_('COM_ACCOUNT_LABEL_BASIC_SETTING');?></h3>
		<ul class="account-form">
			<li>
				<label for="params_sitename" class="" id="params_sitename-lbl"><?php echo JText::_('COM_ACCOUNT_LABEL_SITE_NAME');?></label>
				<input type="text" name="params[sitename]" value="<?php echo $this->sitename;?>" id="params_sitename">
			</li>
			<div class="clear"></div>
			<li>
			  <?php echo $this->profileForm->getLabel('language', 'params'); ?>
			  <?php echo $this->profileForm->getInput('language', 'params', $this->default_language); ?>
			</li>			
			<li>
			  <?php echo $this->profileForm->getLabel('timezone', 'params'); ?>
			  <?php echo $this->profileForm->getInput('timezone', 'params', $this->default_timezone); ?>
			</li>		
			<li>
				<label for="params_domain_name" class="" id="params_domain_name-lbl"><?php echo JText::_('COM_ACCOUNT_LABEL_DOMAIN_NAME');?></label>
				<input type="text" name="params[domain_name]" value="<?php echo $this->domain_name;?>" id="params_domain_name" readonly<?php //echo ($this->domain_editable) ? '' : 'readonly';?>><span class="domain-name"><?php echo $jxConfig->getDomainSuffix();?></span>
			</li>
			<li class="clearfix">
			  <label class="" for="params_allow_anon" id="params_allow_anon-lbl">Comment Setting</label>			  
			  <label class="checkbox"><input type="checkbox" value="1" id="params_allow_anon" name="params[allow_anon]" <?php echo (intval($this->allow_anon) == 1) ? 'checked' : '';?> ><?php echo JText::_('COM_ACCOUNT_ALLOW_ANONYMOUS_COMMENT');?></label>	
			</li>
		</ul>

		<h3 class="section-title"><?php echo JText::_('COM_ACCOUNT_LABEL_REGISTRATION');?></h3>
		<ul class="account-form">
			<li class="clearfix">
			  <label class="" for="params_allow_invite" id="params_allow_invite-lbl">Member Setting</label>			  
			  <label class="checkbox"><input type="checkbox" value="1" id="params_allow_invite" name="params[allow_invite]" <?php echo (intval($this->allow_invite) == 1) ? 'checked' : '';?> ><?php echo JText::_('COM_ACCOUNT_LABEL_ALLOW_MEMBERS_INVITE');?></label>	
			</li>
					
			<li>
				<label for="params_limit_email_domain" class="" id="params_limit_email_domain-lbl"><?php echo JText::_('COM_ACCOUNT_LABEL_LIMIT_EMAIL_DOMAIN');?></label>
				<input type="text" name="params[limit_email_domain]" value="<?php echo $this->limit_email_domain;?>" id="params_limit_email_domain">
			</li>	
		</ul>
		
		<div class="submit">
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
