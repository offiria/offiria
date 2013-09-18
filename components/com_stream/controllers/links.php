<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.controller');

class StreamControllerLinks extends JController {

	/**
	 *
	 */
	public function display($cachable = false, $urlparams = false) {
		$view = StreamFactory::getView('links', '', 'html');
		echo $view->display();
	}

}