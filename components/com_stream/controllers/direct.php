<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.controller');

class StreamControllerDirect extends JController
{
	public function display()
	{
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$viewName = JRequest::getCmd('view', 'direct');

		$view = StreamFactory::getView($viewName, '', $viewType);

		echo $view->display();
	}

	public function compose()
	{
		$tmpl = new StreamTemplate();
		$html = $tmpl->fetch('stream.post.direct');
		echo $html;
		exit;
	}
}