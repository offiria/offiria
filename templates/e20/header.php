<?php
/**
 * @version                $Id: index.php 21097 2011-04-07 15:38:03Z jomsocial $
 * @package                Joomla.Site
 * @subpackage			   e20 - Enterprise 2.0
 * @copyright        	   Copyright (C) Slashes n Dots Sdn Bhd. All rights reserved.
 * @license                GPL
 */
// No direct access.
defined('_JEXEC') or die;
$user = JXFactory::getUser();
	?>
	
	<div id="eHeader-inner" class="container_12">
		<div id="eHeader-left" class="grid_3">
			<h1><?php echo $app->getCfg('sitename'); ?></h1>
		</div>
		
		<div id="eHeader-content" class="grid_7">
			<div class="ticker-content"></div>
		</div>
		
		<div id="eHeader-right" class="grid_3">
			<form id="logout-form" method="post" action="<?php echo JURI::root(); ?>">
			<div class="logout-button" style="display:none;">
				<input type="submit" value="Log out" class="button" name="Submit">
				<input type="hidden" value="com_users" name="option">
				<input type="hidden" value="user.logout" name="task">
				<?php echo JHtml::_('form.token'); ?>	
			</div>
			</form>

			<ul class="header-menu clearfix">
				<?php 
					// If current user is an admin, you can pretty much do everything
					if( $user->isAdmin() ){
			
				?>
				<li class="settings"><a href="<?php echo JRoute::_('index.php?option=com_account&view=account'); ?>"><?php echo JText::_('JXLIB_SETTINGS');?></a></li>
				<?php } ?>
				
				<li class="logout"><a href="javascript:void(0);" onclick="document.getElementById('logout-form').submit();"><?php echo JText::_('JXLIB_LOGOUT');?></a></li>
			</ul>
		</div>
	</div>