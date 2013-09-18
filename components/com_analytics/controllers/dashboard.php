<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.controller');

$my = JXFactory::getUser();

if (!$my->isAdmin())
{
	$mainframe = JFactory::getApplication();
	$mainframe->redirect(JURI::base(), JText::_('COM_ACCOUNT_ERRMSG_ACCESS_DENIED'), 'error');
}

class AnalyticsControllerDashboard extends JController
{
	/**
	 *
	 */	 	
	public function display($cachable = false, $urlparams = false)
	{		
		JRequest::setVar('view', 'dashboard');
		parent::display();
	} 	
		
	public function ajaxGenerateAnalytics()
	{
		$view		= AnalyticsFactory::getView('dashboard');
		$html		= $view->generateAnalytics();
		
		echo $html;
		exit;
	}
}