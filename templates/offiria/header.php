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
$my = JXFactory::getUser();
$inboxUnreadCount = MessagingNotification::getUserNotification($my->id);
?>
	<div id="oHeader-inner" class="navbar-inner">
		
		<div class="container">
		
		<a href="#sidebar-right" class="slide-nav btn btn-navbar">
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</a>
			
		<div class="row">
			
			<div id="oHeader-left" class="span3">
				<h1><a id="home-icon" title="<?php echo JText::_('NAVIGATOR_LABEL_HOME');?>" href="<?php echo JRoute::_('index.php?option=com_stream&view=company'); ?>"><img src="<?php echo $jxConfig->getCompanyLogo(); ?>" alt="<?php echo $app->getCfg('sitename'); ?>"/></a></h1>
			
				<a href="<?php echo JRoute::_('index.php?option=com_stream&view=company'); ?>" class="btn btn-navbar"><span>Menu</span></a>
			</div>
			
			<div class="oHeader-ipad visible-tablet">
				
				<h2>
					
					<a href="javascript:void(0)" data-toggle="collapse" data-target=".nav-collapse">
						
					<?php $view = JRequest::getVar('view'); ?>
					<?php $component = JRequest::getVar('option'); ?>

					<?php if ($view == 'company'): ?>
						<?php echo JText::_('NAVIGATOR_LABEL_HOME');?>
					<?php endif; ?>
					
					<?php if ($component == 'com_profile'): ?>
						<?php echo JText::_('NAVIGATOR_LABEL_PROFILE');?>
					<?php endif; ?>

					<?php if ($view == 'groups' || $view == 'customlist'): ?>
						<?php echo JText::_('NAVIGATOR_LABEL_GROUPS');?>
					<?php endif; ?>
					
					<?php if ($view == 'direct'): ?>
						<?php echo JText::_('NAVIGATOR_LABEL_INBOX');?>
					<?php endif; ?>
					
					<?php if ($view == 'blog'): ?>
						<?php echo JText::_('NAVIGATOR_LABEL_BLOG');?>
					<?php endif; ?>
					
					<?php if ($view == 'events'): ?>
						<?php echo JText::_('NAVIGATOR_LABEL_EVENTS');?>
					<?php endif; ?>
					
					<?php if ($view == 'todo'): ?>
						<?php echo JText::_('NAVIGATOR_LABEL_TODO');?>
					<?php endif; ?>
					
					<?php if ($view == 'files'): ?>
						<?php echo JText::_('NAVIGATOR_LABEL_FILE');?>
					<?php endif; ?>
					
					<?php if ($view == 'members'): ?>
						<?php echo JText::_('NAVIGATOR_LABEL_PEOPLE');?>
					<?php endif; ?>
					
					<?php if (JRequest::getVar('option') == 'com_analytics'): ?>
						<?php echo JText::_('JXLIB_ANALYTICS');?>
					<?php endif; ?>	
					
					<?php if (JRequest::getVar('option') == 'com_account'): ?>
						<?php echo JText::_('JXLIB_SETTINGS');?>
					<?php endif; ?>
					
					<span class="bg-tab-arrow"></span>
					
					</a>
				
				</h2>
			</div>
			
			<div class="nav-collapse">
				
				<div id="navigation" class="span9">
					<ul class="nav clearfix pull-left">
						<!-- company stream -->
						<li class="home<?php if($option == 'com_stream' && $view == 'company' ) { 
							echo ' active'; 

							$lastMessageId = StreamMessage::lastMessageId();
							$my->setParam('message_last_read', $lastMessageId);
							$my->save();

							} ?>">
							<a href="<?php echo JRoute::_('index.php?option=com_stream&view=company'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_HOME');?></a>
						</li>
					
						<!-- groups -->
						<?php if(!$my->isExtranetMember()) : ?>
						<li class="groups<?php if($option == 'com_stream' && $view == 'groups' ) { echo ' active'; } ?>">
							<a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_GROUPS');?></a>
						</li>
						<?php endif; ?>

						<!-- direct massage -->
						<!--<li class="direct<?php if($option == 'com_stream' && $view == 'direct' ) { echo ' active'; } ?>" style="display: none;">
							<a href="<?php echo JRoute::_('index.php?option=com_stream&view=direct'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_INBOX'); ?></a>
						</li>-->

						<!-- messaging -->
						<li class="direct<?php if($option == 'com_messaging' && $view == 'inbox' ) { echo ' active'; } ?>">
							<a href="<?php echo JRoute::_('index.php?option=com_messaging&view=inbox'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_INBOX');?></a>
					    	<?php if($inboxUnreadCount): ?>
							<span class="navigator-notice"><?php echo $inboxUnreadCount; ?></span>
							<?php endif; ?>
						</li>
					
						<!-- articles -->
						<li class="articles<?php if($option == 'com_stream' && $view == 'blog' ) { echo ' active'; } ?>">
							<a href="<?php echo JRoute::_('index.php?option=com_stream&view=blog'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_BLOG');?></a>
						</li>

						<!-- events -->
						<li class="event<?php if($option == 'com_stream' && $view == 'events' ) { echo ' active'; } ?>">
							<a href="<?php echo JRoute::_('index.php?option=com_stream&view=events'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_EVENTS');?></a>
						</li>

						<!-- todo -->
						<li class="todo<?php if($option == 'com_stream' && $view == 'todo' ) { echo ' active'; } ?>">
							<a data-content="hello" href="<?php echo JRoute::_('index.php?option=com_stream&view=todo'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_TODO');?></a>
						</li>

						<!-- files -->
						<li class="files<?php if($option == 'com_stream' && $view == 'files' ) { echo ' active'; } ?>">
							<a href="<?php echo JRoute::_('index.php?option=com_stream&view=files'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_FILE');?></a>
						</li>

						<!-- members -->
						<?php
						// Hide for limited access user 
						if($my->authorise('people.profiles.list')){
						?>
						<li class="members<?php if($option == 'com_people' && $view == 'members' ) { echo ' active'; } ?>">
							<a href="<?php echo JRoute::_('index.php?option=com_people&view=members'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_PEOPLE');?></a>
						</li>
						<?php } ?>
					</ul>
				
					<ul class="nav clearfix pull-right">
						<?php 
							// If current user is an admin, you can pretty much do everything
							if( $my->isAdmin() ){
			
						?>
						<li class="analytics <?php if($option == 'com_analytics') { echo ' active'; } ?>"><a href="<?php echo JRoute::_('index.php?option=com_analytics'); ?>"><?php echo JText::_('JXLIB_ANALYTICS');?></a></li>
						<li class="settings <?php if($option == 'com_account' && $view == 'account' ) { echo ' active'; } ?>"><a href="<?php echo JRoute::_('index.php?option=com_account&view=account'); ?>"><?php echo JText::_('JXLIB_SETTINGS');?></a></li>
						<?php } ?>
				
						<li class="logout"><a href="javascript:void(0);" onclick="document.getElementById('logout-form').submit();"><?php echo JText::_('JXLIB_LOGOUT');?></a></li>
					</ul>
			
				
				</div>

			</div>
		
		</div><!-- .row -->
		
		</div>
		
	</div>