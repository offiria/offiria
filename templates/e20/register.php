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
?>
<!-- START Joomla! HTML -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >

<!-- Joomla! head -->
<head>
<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/e20/css/e20.css">

<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css' />

<jdoc:include type="head" />
<style>
	.register{
		border-radius:6px;
		background-color: #FFFFFF;
    	border: 3px solid #DDDDDD;
    	padding:30px;
	}
	
</style>
</head>
<!-- END Joomla! head -->

<!-- START Joomla! Body -->
<body>

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

<div id="offiria_loginbox">
	<jdoc:include type="message" />
 	<jdoc:include type="component" />
</div>
<jdoc:include type="modules" name="analytics" style="default"/>
<!-- END Joomla! Body -->
</body>

<!-- START Joomla! HTML -->
</html>
