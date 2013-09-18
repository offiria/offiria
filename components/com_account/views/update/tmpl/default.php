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
<style type="text/css">
.current-info {
	font-size:1.1em;
	margin:5px 5px;
}
.updates-info {
	border-radius: 10px;
	border: 1px solid #CCCCCC;
	padding: 10px 20px;
	margin: 10px auto;
	line-height: 2em;
}
.updates-info:first-line {
	font-size: 1.2em;
	font-weight:bold;
} 
</style>
<div class="account-navbar">
<?php echo $this->showNavBar(); ?>
</div>
<div class="alert alert-info"><?php echo $this->renderData->msg;?></div>
<div class="current-info"><?php echo JText::_('COM_ACCOUNT_LABEL_CURRENT_INSTALLED_VERSION');?>: <b><?php echo $this->renderData->current_version;?></b></div>
<br/>
<?php 
	if (count($this->renderData->data) > 0) { 
		foreach($this->renderData->data as $package) {
?>
<div class="updates-info">
	<?php echo JText::_('COM_ACCOUNT_LABEL_PACKAGE_VERSION');?>: <?php echo $package->version;?><br/>
	<?php echo JText::_('COM_ACCOUNT_LABEL_DOWNLOAD_URL');?>: <a href="<?php echo $package->package_url;?>"><?php echo $package->package_url;?></a><br/>
	<?php echo JText::_('COM_ACCOUNT_LABEL_CHANGE_LOG');?>: <br/>
	<p><?php echo nl2br(trim($package->change_log));?></p>
</div>
<?php 
		}
	} 
?>
