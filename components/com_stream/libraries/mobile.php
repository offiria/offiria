<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.xfactory');
jimport('joomla.environment.browser');

class StreamMobile {	
	public static function isMobile() {
		/**
		 * Store the mobile value by our preferences and what is consider as mobile
		 * @param Boolean true if its a mobile
		 */
		$mobile = false;

		 // detect the browser by checking user agent
		 $browser = JBrowser::getInstance();
		 $ua = $browser->getAgentString();

		 switch ($ua) {
		 	case (preg_match('/ipad/i', $ua) > 0):
		 		// ipad specific css
		 		break;
		 	case (preg_match('/android/i', $ua) > 0):
				$mobile = true;
		 		break;
		 	case (preg_match('/blackberry/i', $ua) > 0):
				$mobile = true;
		 		break;
		 	case (preg_match('/iphone/i', $ua) > 0):
		 		// need to specifically check for user agent
		 		$mobile = true;
		 		break;
		 }
		 	
		 // $browser->isMobile will check for opera/opera mini and other multiplatform browser
		 if ($mobile || $browser->isMobile()) {
			 return true;
		 }
		 else {
			 return false;
		 }
	}
}

