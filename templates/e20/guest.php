<?php
/**
 * @version                $Id: index.php 21097 2011-04-07 15:38:03Z jomsocial $
 * @package                Joomla.Site
 * @subpackage			   e20 - Guest View
 * @copyright        	   Copyright (C) Slashes n Dots Sdn Bhd. All rights reserved.
 * @license                GPL
 */
// No direct access.
defined('_JEXEC') or die;

// get params
$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$tParams = $app->getTemplate(true)->params;

// If user trying to register, use register template
$option = JRequest::getVar('option');
$my = JFactory::getUser();
if( !$my ->id && $option == 'com_users' )
{
	include('register.php');
	return;
}

?>
<!-- START Joomla! HTML -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >

<!-- Joomla! head -->
<head>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/e20/css/e20.css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
<jdoc:include type="head" />
<script type="text/javascript" src="<?php echo JURI::root(); ?>media/jquery/jquery-1.7.min.js"></script>
</head>
<!-- END Joomla! head -->

<!-- START Joomla! Body -->
<body>
<div class="container_12">
	<jdoc:include type="message" />
</div>
<div class="container_12">

 	<div id="offiria_loginbox">
		<jdoc:include type="modules" name="login" style="default"/>
	</div>
	<script>
	$(document).ready(function(){
							   
	 $(window).resize(function(){

	  $('#offiria_loginbox').css({
	   position:'absolute',
	   left: ($(window).width() 
	     - $('#offiria_loginbox').outerWidth())/2,
	   top: ($(window).height() 
	     - $('#offiria_loginbox').outerHeight())/2
	  });
			
	 });
	 
	 // To initially run the function:
	 $(window).resize();

	});
	</script>
</div>
<jdoc:include type="modules" name="analytics" style="default"/>
<!-- END Joomla! Body -->
</body>

<!-- START Joomla! HTML -->
</html>
