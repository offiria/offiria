<?php
/**
 * @package		Joomla.Site
 * @copyright	Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die;
jimport('joomla.utilities.xconfig');

if (!isset($this->error)) {
	$this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
	$this->debug = false;
}


// Load language file
$lang			= JFactory::getLanguage();
$config			= new JXConfig();

// First load account setting language (if any) to override joomla! language
$defLanguage	= ($config->getLanguage() != '') ? $config->getLanguage() : $lang->get('default');

$my				= JFactory::getUser();
// Second load user personal language (if any) to override default language
$siteLanguage	= (intval($my->id) > 0 && $my->getParam('language') != '') ? $my->getParam('language') : $defLanguage;

$lang->setLanguage($siteLanguage);
$result = $lang->load('lib_xjoomla');

if (JFile::exists(JPATH_ROOT.DS.'language'.DS.'en-GB'.DS.'en-GB.offiria_custom.ini'))
{
	$result = $lang->load('offiria_custom', JPATH_BASE, 'en-GB', true);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<title><?php echo JText::_('CUSTOM_ERROR_TITLE');?></title>
	<style>
	<!--
	body {
		margin:0 0 0 0;
		margin-top:50px;
		font-family:Arial, Helvetica, sans-serif;
		font-size:24px;
		color:#57595a;
	}
	ul {
		list-style-type:none;
		margin-left:-15px;
		margin-top:25px;
	}
	li {
		margin-bottom:5px;
	}
	a {
		color:#00529b;
		text-decoration:none;
	}
	a:hover {
		color:#202020;
		text-decoration:none;
	}
	a:link {
		text-decoration: none;
	}
	a:visited {
		text-decoration: none;
	}
	a:active {
		text-decoration: none;
	}
	.style1 {
		font-weight: bold
	}
	-->
	</style>
</head>
<body>
	<div style="width:720px; height:460px; margin:0 auto; position:relative">
	  <div style="position:absolute; top:70px; left:328px;"><img src="<?php echo $this->baseurl ?>/templates/<?php echo JText::_('CUSTOM_TEMPLATE');?>/images/sad-face.jpeg" width="300" height="300" /></div>
	  <div style="position:absolute; top:148px; left:55px; width:300px">
	    <p><?php echo JText::_('CUSTOM_ERROR');?></p>
	  </div>
	</div>
</body>
</html>
