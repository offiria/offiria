<?php

// no direct access
defined('_JEXEC') or die;

jimport('joomla.xfactory');
jimport('joomla.environment.browser');

/**
 * System Mobile Plugin
 */
if(!class_exists('plgSystemMobile'))
{
	class plgSystemMobile extends JPlugin
	{
		/**
		 * Store the mobile value by our preferences and what is consider as mobile
		 * @param Boolean true if its a mobile
		 */
		protected $_mobile = false;
		
		function plgSystemMobile(& $subject, $config)
		{
			parent::__construct($subject, $config);

			// detect the browser by checking user agent
			$browser = JBrowser::getInstance();
			$ua = $browser->getAgentString();

			switch ($ua) {
		 	case (preg_match('/ipad/i', $ua) > 0):
		 		// ipad specific css
		 		break;
		 	case (preg_match('/android/i', $ua) > 0):
				$this->_mobile = true;
		 		break;
		 	case (preg_match('/blackberry/i', $ua) > 0):
				$this->_mobile = true;
		 		break;
		 	case (preg_match('/iphone/i', $ua) > 0):
		 		// need to specifically check for user agent
		 		$this->_mobile = true;
		 		break;
			}
			
			// $browser->isMobile will check for opera/opera mini and other multiplatform browser
			if ($this->_mobile || $browser->isMobile()) {
				JRequest::setVar('template', 'mobile');
			}
		 	
		}
	}
}