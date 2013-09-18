<?php

/**
 * @package		Offiria
 * @subpackage	Core
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.controller');

class StreamControllerTodo extends JController
{
	/**
	 *
	 */
	public function display($cachable = false, $urlparams = false){
		$document 	= JFactory::getDocument();
		$viewType	= $document->getType();	
 		$viewName	= JRequest::getCmd('view', 'todo');
 		
 		$view 		= StreamFactory::getView( $viewName, '', $viewType);
		
		echo $view->display();
	}


}