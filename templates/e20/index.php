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

jimport('joomla.xfactory');
include_once(JPATH_ROOT.DS.'components'.DS.'com_stream'.DS.'factory.php');
include_once(JPATH_ROOT.DS.'components'.DS.'com_account'.DS.'factory.php');

$document =& JFactory::getDocument();
$document->setGenerator('Offiria');

/* Global javascripts */
$document->addScript(JURI::root().'media/jquery/jquery-1.7.min.js');
$document->addScript(JURI::root().'media/jquery/js/jquery-ui-1.8.16.custom.min.js');
$document->addScript(JURI::root().'media/jquery/autogrow.js');
$document->addScript(JURI::root().'media/jquery/bootstrap-twipsy.js');
$document->addScript(JURI::root().'media/jquery/bootstrap-popover.js');
$document->addScript(JURI::root().'media/jquery/jquery.tools.min.js');
$document->addScript(JURI::root().'media/tipsy/src/javascripts/jquery.tipsy.js');
$document->addScript(JURI::root().'media/editors/tinymce/jscripts/tiny_mce/tiny_mce.js');
$document->addScript(JURI::root().'media/stream/script.pack.js');
$document->addScript(JRoute::_('index.php?option=com_stream&view=system&task=script', false));
$document->addStyleSheet(JURI::root().'media/jquery/css/flick/jquery-ui-1.8.16.custom.css');


// get params
$app 	= JFactory::getApplication();
$config = new JConfig();

$tParams = $app->getTemplate(true)->params;

//JHtml::_('behavior.framework', true);

// If user is not yet logged in, show login page
$my = JXFactory::getUser();
if( !$my ->id )
{
	include('guest.php');
	return;
}

$option = JRequest::getVar('option');
$view = JRequest::getVar('view');

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable = no" />
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta http-equiv="X-UA-Compatible" content="IE=9" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/e20/css/e20.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/media/tipsy/src/stylesheets/tipsy.css" />

	<!-- PIE CHART -->
	<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/e20/js/piecanvas.js"></script>
	<!--[if IE]><script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/e20/js/excanvas.js"></script><![endif]-->
	<jdoc:include type="head" />

	<!--[if IE]> 
	<script type="text/javascript" src="<?php echo JURI::root().'media/jquery/jquery-placeholder.js'; ?>"></script>
	<script type="text/javascript" src="<?php echo JURI::root().'media/jquery/modernizr.custom.js'; ?>"></script>
	<![endif]-->
</head>

<body>

<div class="main-container">

	<!-- #eHeader -->
	<div id="eHeader">
		<?php include_once(JPATH_ROOT . "/templates/" . $this->template . '/header.php'); ?>
	</div>
	<!-- #eHeader -->

	<!-- BODY CONTENT-->
	<div id="eContent">
		<div id="eContent-inner" class="container_12">
	
			<!-- Modules -->
			<div id="sidebar-left" class="grid_3 alpha omega">
				<div class="search-content">
					<form id="searchform" action="<?php echo JRoute::_('index.php?option=com_search');?>">
						<input type="text" name="searchword" />
						<input type="submit">
						<div class="clear"></div>
					</form>
				</div>
			
				<div class="user-profile">
					<div class="user-avatar">
						<img src="<?php echo $my->getThumbAvatarURL(); ?>" />
					</div>
				
					<div class="user-details">
						<?php 
						$user  = JFactory::getUser(); 
						?>
						<h3><a href="<?php echo JRoute::_('index.php?option=com_profile&view=display'); ?>"><?php echo $user->name; ?></a></h3>
						<span><a href="<?php echo JRoute::_('index.php?option=com_profile&view=edit'); ?>"><?php echo JText::_('COM_PROFILE_LABEL_EDIT_PROFILE');?></a></span>
					</div>
					
					<div class="clear"></div>
					
				</div>			
				<jdoc:include type="modules" name="left" style="default"/>
				
				<div class="clear"></div>
			</div>
	
			<!-- Components -->
			<div id="main-content" class="grid_9 alpha omega">
			
				<div id="content-left" class="grid_6">
					<jdoc:include type="modules" name="breadcrumbs" style="none"/>
					<jdoc:include type="message" />
					<jdoc:include type="modules" name="component_top" style="default"/>
					<?php
				
					// If page title is set, add it here. BUT, don't show it
					// if it is the same as site title (which mean, the title is not set)
					$docTitle = $document->getTitle();
					if(!empty($docTitle) && $docTitle != $config->sitename ) {
						echo '<h1>'. StreamTemplate::escape($docTitle).'</h1>';
					}
					?>
					<jdoc:include type="component" />
				
					<jdoc:include type="modules" name="component_bottom" style="default"/>
				</div>
			
				<div id="sidebar-right" class="grid_3">
					<?php
					// Add guest invite right module
					$accountView = AccountFactory::getView('invite');
					JXModule::addBuffer('right', $accountView->modMemberInvite());
					
					$buffer =& JXModule::getBuffer('right');
				
					foreach($buffer as $buff)
					{
						echo $buff;
					}
			
					?>
					<?php /* if($option == 'com_stream' && $view == 'company') { ?>
					<img src="<?php echo JURI::root().'components/com_stream/assets/images/hot_topics.png'; ?>" />
					<?php } */?>

				</div>
			</div>
		</div><!-- #eContent -->

	</div>

	<div class="clear"></div>

	<!-- #eFooter -->
	<div id="eFooter">
		<?php include_once(JPATH_ROOT . "/templates/" . $this->template . '/footer.php'); ?>
	</div>
	<!-- #eFooter -->

</div>

<script type="text/javascript">var _kiq = _kiq || [];</script>
<script type="text/javascript" src="//s3.amazonaws.com/j.kissinsights.com/u/1899/2142436f937b79855471e3b8941e688e4dd50a4f.js" async="true"></script>

<!-- tipsy -->
<script type='text/javascript'>
$(function() {
  $('.tips').tipsy({live: true, gravity: 's'});
  
});
</script>

<jdoc:include type="modules" name="analytics" style="default"/>
</body>
</html>
