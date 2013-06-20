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
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/mobile/css/mobile-login.css" />
		<jdoc:include type="head" />
	</head>
	
	<body>
		<jdoc:include type="message" />
		<div id="offiria_loginbox">
			<jdoc:include type="modules" name="login" style="default"/>
		</div>
		<jdoc:include type="modules" name="analytics" style="default"/>
	</body>

</html>
