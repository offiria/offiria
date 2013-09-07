<?php
/**
 * @version     1.0.0
 * @package     com_administrator
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.controller');

class StreamControllerApi extends JController
{
	/**
	 *
	 */	 	
	public function display(){
		exit;
	}

	public function image() {
		$url = JRequest::getVar('url', false);
		preg_match('/.*\.([a-zA-Z]+)/', $url, $extension);

		if ($url) {
			/* use the existing path */
			$imagePath = $url;

			if (false === file_get_contents($url, 0, null, 0, 1)) {
				/* default image is supplied the image path point to broken link */
				$imagePath = JXConfig::getMissingImage();
			}
			/* output the image but its possible to store this for caching purpose */
			if (!empty($extension[1])) {
				switch($extension[1]) {
				case 'jpg': $ext = 'jpeg';
					break;
				default: $ext = $extension[1];
				}
				header('Content-Type: image/'.$ext);
				header('Content-transfer-encoding: binary');
				echo file_get_contents($imagePath);
			}
		}
		exit;
	}
}