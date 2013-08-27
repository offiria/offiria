<?php
// No direct access.
defined('_JEXEC') or die();

jimport('joomla.application.controller');

class StreamControllerCompany extends JController
{
	public function display($cachable = false, $urlparams = false)
	{
		$view = StreamFactory::getView('company', '', 'html');
		echo $view->display();
	}

	public function tagFilter()
	{
		$view = StreamFactory::getView('company', '', 'html');
		echo $view->tagFilter();
	}

	public function morePost() {
		// run a query to get more post for display
		// This is an example should pass some parameter into this template
		// How to load more post without showing Joomla Template???
		$task = JRequest::getVar('task');
		echo $task;
	}
}