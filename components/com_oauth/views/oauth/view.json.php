<?php
/**
 * @version     1.0.0
 * @package     com_oauth
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access
defined('_JEXEC') or die;

/**
 * HTML View class for the Oauth component
 */
class OauthViewOauth extends OauthView
{
	/**
	 * The purpose of this view is to retrieve the token generated for the app
	 * Process flow:
	 * 1) Navigate to index.php/component/oauth/?view=oauth&task=authenticate&appId=[appId]
	 * 2) Get the device approved
	 * 3) Run in the background to retrieve the token generated in this view
	 */
	public function display() {
		$model = OauthFactory::getModel('application');
		$token = $model->getAppToken(JRequest::getVar('appId'));

		// make sure only the token belongs to the user will be generate
		if ($model->isAppBelongToUser(JRequest::getVar('appId'))) {
			$vals['token'] = $token;
			echo json_encode($vals);
		}
		exit;
	}
}