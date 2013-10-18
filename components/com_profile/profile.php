<?php

/* include the architecture */
require_once(JPATH_ROOT.DS.'components'.DS.'com_profile'.DS.'views'.DS.'view.php');
require_once( JPATH_ROOT .DS.'components'.DS.'com_profile'.DS.'factory.php');

// Require specific controller if requested
if($controller = JRequest::getWord('view', 'display')) {
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if (file_exists($path)) {
		require_once $path;
	} else {
		$controller = '';
	}
}

JXModule::addBuffer('right', ProfileView::showSideBar());

// Create the controller
$classname	= 'ProfileController'.ucfirst($controller);
$controller = new $classname( );

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));

// Redirect if set by the controller
$controller->redirect();