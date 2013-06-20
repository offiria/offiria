<?php
// if theres another session already exist
// Forgot Username | Forgot Password will redirect the request to this page
// redirect them to home page
$mainframe = JFactory::getApplication();
$mainframe->redirect(JRoute::_('index.php?option=com_stream&view=company'));
?>