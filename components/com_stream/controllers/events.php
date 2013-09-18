<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.controller');

class StreamControllerEvents extends JController {

	/**
	 *
	 */
	public function display($cachable = false, $urlparams = false) {
		$view = StreamFactory::getView('events', '', 'html');
		echo $view->display();
	}

	public function show() {
		$view = StreamFactory::getView('events', '', 'html');
		echo $view->show();
	}

	public function updateCalendar() {
		$month = JRequest::getVar('month');
		$year = JRequest::getVar('year');

		StreamFactory::load('helpers' . DS . 'calendar');

		$html = StreamCalendarHelper::generate_calendar($year, $month);
		$data = array();
		$data['html'] = $html;
		$data['script'] = '$(\'div.popover\').hide();$(\'td.running\').popmodal({html:true, live:true, placement: \'below\'});';

		echo json_encode($data);
		exit;
	}

}