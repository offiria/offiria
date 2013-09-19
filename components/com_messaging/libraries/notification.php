<?php
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ROOT.DS.'components'.DS.'com_messaging'.DS.'factory.php');

class MessagingNotification
{
	public static function getUserNotification($userid)
	{
		$messagingModel = MessagingFactory::getModel('inbox');
		return $messagingModel->getTotalNotifications($userid);
	}
}
?>