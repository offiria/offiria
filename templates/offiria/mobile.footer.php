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

	?>
	
	<div id="oFooter-inner" class="container">
		<div id="oFooter-left" class="span6">
			<span>Copyright &copy; 2007 - 2012 <?php echo JText::_('CUSTOM_COMPANY_SITE');?>. Powered by <?php echo JText::_('CUSTOM_SITE_NAME');?></span>
		</div>
		
		<div id="oFooter-right" class="span4">
			<jdoc:include type="modules" name="footer" style="default"/>
		</div>
	</div>
	