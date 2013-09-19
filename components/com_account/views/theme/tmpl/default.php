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

?>
<div class="account-navbar">
<?php 
echo $this->showNavBar(); 

$defaultLogo = basename(JXConfig::DEFAULT_LOGO);
$showRemove = (JFile::exists($this->configHelper->getCompanyLogoPath()) && !stristr($this->configHelper->getCompanyLogoPath(), $defaultLogo));
?>
</div>
<?php if ($showRemove):?>
<script type="text/javascript">
	$(document).ready(function() {
		$('#btn-remove-logo').click(function() {
			$('#form-remove-logo').submit();
		});
	});
</script>
<?php endif; ?>
<div id="account-edit">
	<form class="edit" action="<?php echo JRoute::_('index.php?option=com_account&view=account');?>" method="post" enctype="multipart/form-data">
		<ul class="account-form">
			<li>
				<label id="params_box-lbl" class="" for="params_box"><?php echo JText::_('COM_ACCOUNT_LABEL_DEFAULT_COLOR');?></label>
				<div class="theme-content">
					<div class="theme blue"></div>
					<div class="theme red"></div>
					<div class="theme grey"></div>
					
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
				
				<label id="params_style-lbl" class="" for="params_style"></label>
				<div class="theme-picker">
					<div class="picker">
						<input type="radio" name="params[style]" <?php if( !$this->style) {echo 'checked'; } ?> value="" />
					</div>
					<div class="picker">
						<input type="radio" name="params[style]" <?php if( $this->style == 'red') {echo 'checked'; } ?> value="red" />
					</div>
					<div class="picker">
						<input type="radio" name="params[style]" <?php if( $this->style == 'grey') {echo 'checked'; } ?> value="grey" />
					</div>
					
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
			</li>
			
			<li class="company-logo">
				<label for="params_logo" class="" id="params_logo-lbl"><?php echo JText::_('COM_ACCOUNT_LABEL_COMPANY_LOGO');?></label>
				<input type="file" name="c_logo" value="" id="c_logo" class="btn btn-info">
				<input type="hidden" value="<?php echo $this->logo;?>" name="params[logo]"/>
				<?php if ($showRemove) :?>	
				<input type="button" class="btn btn-danger" value="<?php echo JText::_('COM_ACCOUNT_LABEL_COMPANY_LOGO_REMOVE');?>" id="btn-remove-logo"/>
				<?php endif; ?>				
				<br/><br/><span class="help-text">
					<?php echo JText::_('COM_ACCOUNT_LABEL_COMPANY_LOGO_HELPTEXT');?>
				</span>
				
				<div class="clear"></div>
				
				<label for="c_logo" class="" id="c_logo-lbl">&nbsp;</label>
				<div class="company-logo-content">
					<img src="<?php echo $this->configHelper->getCompanyLogo();?>" id="company_logo"/>	
					
				</div>
				
				<div class="clear"></div>
			</li>
		</ul>
		<div class="submit">
			<input type="hidden" value="manageTheme" name="task"/>
			<input class="btn btn-info" type="submit" value="<?php echo JText::_('COM_STREAM_LABEL_SAVE');?>" name="submit"/>
		</div>		
	</form>
	
	<?php if ($showRemove) :?>
		<form action="<?php echo JRoute::_('index.php?option=com_account&view=account');?>" method="post" id="form-remove-logo">
		<input type="hidden" name="task" value="removeLogo" />
		</form>
	<?php endif;?>
</div><!--end account-edit-->