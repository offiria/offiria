<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.utilities.ximage');
jimport('joomla.utilities.xconfig');
jimport('joomla.utilities.simplexml');


class AccountControllerAccount extends JController
{
	public function display($cachable = false, $urlparams = false) 
	{		
		if ($_POST)
		{
			$proceedSave	= true;
			$configHelper	= new JXConfig();
			$mainframe		= JFactory::getApplication();
			
			// Set the posted data to update the configuration
			$postdata = JRequest::getVar('params');
						
			$param[JXConfig::TIMEZONE]		= $postdata['timezone'];
			$param[JXConfig::LANGUAGE]		= $postdata['language'];
			$param[JXConfig::SITENAME]		= $postdata['sitename'];
			$param[JXConfig::ALLOW_INVITE]	= (isset($postdata['allow_invite'])) ? intval($postdata['allow_invite']) : '0';
			$param[JXConfig::ALLOW_ANON]	= (isset($postdata['allow_anon'])) ? intval($postdata['allow_anon']) : '0';
			
			// Check list of limit email domains and append limit email list into param
			$limitEmailDomain			= JXConfig::LIMIT_EMAIL_DOMAIN;
			if (empty($postdata['limit_email_domain']))
			{
				$param[$limitEmailDomain]	= '';
			}
			else
			{
				$arrEmailDomain				= explode(',', $postdata['limit_email_domain']);
				$arrEmailDomain				= $this->_cleanEmailDomain($arrEmailDomain);
				if (is_array($arrEmailDomain))
				{
					$param[$limitEmailDomain]	= implode(',', $arrEmailDomain);
				}
				elseif ($arrEmailDomain === false)
				{
					$mainframe->enqueueMessage(JText::_('COM_ACCOUNT_ERRMSG_INVALID_EMAIL_DOMAIN_PROVIDED'), 'error');
					$proceedSave = false;
				}
			}			
			
			// Default to no domain changed
			$updatedDomain = false;
			// Only allow to change domain if registered user does not exceed the predefined number in JXConfig
			if ($configHelper->allowChangeDomain() && $proceedSave)
			{
				// This part needs to identify if the domain is changed and need to perform cURL to change the domain at signup.offiria.com
				if ($postdata['domain_name'] != $configHelper->getDomainName())
				{
					// New domain is valid and can be changed
					if (preg_match('/^[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*$/', $postdata['domain_name']))
					{
						// Use library function to do cURL request to signup.offiria.com to check if domain can be used						
						$sanitizedDomain = $configHelper->sanitizeDomain(strtolower($postdata['domain_name']));
						
						// cURL shows domain can be used 
						if ($sanitizedDomain['error'] === false)
						{
							$user = JXFactory::getUser();
							// perform another cURL to change the domain
							$updateCurl = $configHelper->updateDomain($user->get('username'), $configHelper->getDomainName(), $sanitizedDomain['msg']);								
							// Domain changed successfully
							if ($updateCurl['error'] === false)
							{
								$updatedDomain = true;
								$param[JXConfig::DOMAIN_NAME]	= $sanitizedDomain['msg'];
							}
							else
							{
								$updatedDomain = false;
								$sanitizedDomain['error'] = true;
								$sanitizedDomain['msg'] = $updateCurl['msg'].' Please contact the site administrator for more information.';
							}
						}
					}
					else // invalid new domain provided
					{
						$sanitizedDomain['error'] = true;
						$sanitizedDomain['msg'] = JText::sprintf('COM_ACCOUNT_ERRMSG_INVALID_ITEM', JText::_('COM_ACCOUNT_LABEL_DOMAIN_NAME'));
					}
				}

				// IF there is error from Curl Sanitize domain
				if (isset($sanitizedDomain['error']) && $sanitizedDomain['error'] === true)
				{
					$mainframe->enqueueMessage($sanitizedDomain['msg'], 'error');
					$proceedSave = false;
				}
			}
			
			// If everything is safe and sound to proceed saving, then only write into configuration.php
			if ($proceedSave)
			{
				$saveAction = $configHelper->saveConfig($param);

				/* Redirect to clear the previous post values */									
				if (!$updatedDomain)
				{
					$newUrl	= JRoute::_('index.php?option=com_account&view=account', false);
				}
				else
				{
					$jUri	= JURI::getInstance();
					$newUrl	= $jUri->toString(array('scheme')).$param[JXConfig::DOMAIN_NAME].$configHelper->getDomainSuffix();
				}

				// if failed to save into configuration.php
				if (!$saveAction)
				{
					$mainframe->enqueueMessage(JText::_('COM_ACCOUNT_ACTION_SAVE_SETTING_FAIL'), 'error');
				}
				else // save successfully
				{
					/* Redirect to clear the previous post values */
					$mainframe->redirect($newUrl, JText::_('COM_ACCOUNT_ACTION_SAVE_SETTING_SUCCESS'));
				}
			}
		}
		
		parent::display();
	}
	
	/*
	 * Some advance settings for crocodocs, diffbot and SMTP
	 */
	public function advance() 
	{		
		if ($_POST)
		{
			$configHelper	= new JXConfig();
			$mainframe		= JFactory::getApplication();
			
			// Set the posted data to update the configuration
			$postdata = JRequest::getVar('jform');
			
			$param['crocodocs']		= $postdata['crocodocs'];
			$param['crocodocsenable']= $postdata['crocodocsenable'];
			$param['scribd_api']	= $postdata['scribd_api'];
			$param['scribd_secret']	= $postdata['scribd_secret'];
			$param['scribdenable']	= $postdata['scribdenable'];
			$param['diffbot']		= $postdata['diffbot'];
			
			$param['mailer']		= $postdata['mailer'];
			$param['mailfrom']		= $postdata['mailfrom'];
			$param['fromname']		= $postdata['fromname'];
			$param['sendmail']		= $postdata['sendmail'];
			$param['smtpauth']		= $postdata['smtpauth'];
			$param['smtpuser']		= $postdata['smtpuser'];
			$param['smtppass']		= $postdata['smtppass'];
			$param['smtphost']		= $postdata['smtphost'];
			$param['smtpsecure']	= $postdata['smtpsecure'];
			$param['smtpport']		= $postdata['smtpport'];
			
			$saveAction = $configHelper->saveConfig($param);
			
			// if failed to save into configuration.php
			if (!$saveAction)
			{
				$mainframe->enqueueMessage(JText::_('COM_ACCOUNT_ACTION_SAVE_SETTING_FAIL'), 'error');
			}
			else // save successfully
			{
				/* Redirect to clear the previous post values */
				$mainframe->redirect(JRoute::_('index.php?option=com_account&view=account&task=advance'), JText::_('COM_ACCOUNT_ACTION_SAVE_SETTING_SUCCESS'));
			}
			
		}
		
		JRequest::setVar('view', 'advance');
		parent::display();
	}
	
	/*
	 * Check if there is any available updates
	 */
	public function getUpdate()
	{		
		// This can also be overwritten using config file
		$serverXML = 'http://www.offiria.com/releases.xml';
		$app = JFactory::getApplication();
		
		$defaultFile = JPATH_ROOT . DS . 'offiria.xml';
		$objXml = new JSimpleXML();
		$objXml->loadFile($defaultFile);
		
		$children = $objXml->document->children();
		$localversion = $children[0]->attributes('number');
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $serverXML);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);		
		$result = curl_exec($curl);
		curl_close($curl);		
		
		$objXml2 = new JSimpleXML();
		$objXml2->loadString($result);
		
		$renderData = new stdClass();
		$renderData->data = array();
		$renderData->current_version = $localversion;
		
		if (isset($objXml2->document) && isset($objXml2->document->releases))
		{
			$releases = $objXml2->document->releases[0];
			try 
			{
				// Always assume the first child is the latest
				$latestversion = $releases->version[0]->attributes('number');
				
				if ( trim($localversion) == trim($latestversion) )
				{
					$renderData->msg = 'No new updates available.';
				}
				else
				{
					$renderData->msg = 'New updates available.';
					$notMet = true;
					foreach($releases->version as $version)
					{
						if (trim($localversion) == trim($version->attributes('number')))
						{
							// found
							break;
						}
						else
						{
							$index = count($renderData->data);
							$renderData->data[$index] = new stdClass();
							$renderData->data[$index]->version = $version->attributes('number');
							$renderData->data[$index]->package_url = $version->package[0]->attributes('url');
							$renderData->data[$index]->change_log = $version->changelog[0]->data();
						}
					}
				}
			}
			catch(Exception $ex)
			{
				$renderData->msg = 'Unable to retrieve update logs.';
			}
		}
		else
		{
			$renderData->msg = 'Unable to retrieve update logs.';
		}
		
		require_once(JPATH_ROOT . DS . 'components'. DS . 'com_account' . DS . 'views' . DS . 'update' . DS . 'view.html.php');
		$view = new AccountViewUpdate($renderData);
		$view->display($renderData);
	}
	
	/*public function refresh()
	{
		$configHelper	= new JXConfig();
		$mainframe		= JFactory::getApplication();
		$session		= JFactory::getSession();
		$user			= JXFactory::getUser();
		$oldDomain		= $session->get('dold_sess_sign');
		$session->clear('dold_sess_sign');
		if (!empty($oldDomain))
		{		
			$configHelper->removeDomain($user->get('username'), $oldDomain);
		}
		
		$mainframe->redirect(JRoute::_('index.php?option=com_account&view=account', false), JText::_('COM_ACCOUNT_ACTION_SAVE_SETTING_SUCCESS'));
	}*/
	
	public function manageTheme()
	{
		if ($_POST)
		{
			$configHelper	= new JXConfig();
			$file			= JRequest::getVar('c_logo' , '' , 'FILES' , 'array');			
			$mainframe		= JFactory::getApplication();
			
			if ( !empty($file["tmp_name"]) )
			{
				if ( !JXImage::isValidType( $file['type'] ) )
				{
					$mainframe	= JFactory::getApplication();
					$mainframe->redirect(JRoute::_('index.php?option=com_account&view=account&task=manageTheme', false), JText::_('COM_PROFILE_IMAGE_FILE_NOT_SUPPORTED'), 'error');
				}

				//start image processing
				$logoMaxWidth	= 200;
				$logoMaxHeight	= 56;

				// Get a hash for the file name.
				$fileName		= JUtility::getHash( $file['tmp_name'] . time() );
				$hashFileName	= JString::substr( $fileName , 0 , 24 );

				//avatar store path
				$storage			= JPATH_ROOT . DS . 'images';
				$storageImage		= $storage . DS . $hashFileName . JXImage::getExtension( $file['type'] );
				$image				= 'images' . '/' . $hashFileName . JXImage::getExtension( $file['type'] );

				// Generate full image
				list($currentWidth, $currentHeight) = getimagesize( $file['tmp_name'] );

				// Calculate ratio based on height first as height got lower value
				$ratioToUse = intval($currentHeight / $logoMaxHeight);
				// If logoMaxwidth * ratio > original width, recalculate ratio based on width
				if (($logoMaxWidth * $ratioToUse) > $currentWidth)
				{
					$ratioToUse = intval($currentWidth / $logoMaxWidth);
				}

				$maxWidth = $ratioToUse * $logoMaxWidth;
				$maxHeight = $logoMaxHeight * $ratioToUse;
				$sourceX = intval(($currentWidth - $maxWidth) / 2);
				$sourceY = intval(($currentHeight - $maxHeight) / 2);

				if(!JXImage::crop($file['tmp_name'], $storageImage, $maxWidth, $maxHeight, $sourceX, $sourceY, $logoMaxWidth, $logoMaxHeight))
				{
					$mainframe->redirect(JRoute::_('index.php?option=com_account&view=account&task=manageTheme', false), JText::sprintf('COM_PROFILE_ERROR_MOVING_UPLOADED_FILE' , $storageImage), 'error');
				}
				else
				{
					// Remove previous logo
					$originalFilePath	= $configHelper->getCompanyLogoPath();
					$defaultLogo		= basename(JXConfig::DEFAULT_LOGO);
					// Do not remove default logo
					if (JFile::exists($originalFilePath) && !stristr($originalFilePath, $defaultLogo))
					{
						JFile::delete($originalFilePath);
					}

					$param['logo']		= $image;
				}
			}
							
			$postdata = JRequest::getVar('params');
			$param[JXConfig::STYLE]		= $postdata['style'];

			if (!$configHelper->saveConfig($param))
			{
				/* Redirect to clear the previous post values */
				$mainframe->redirect(JRoute::_('index.php?option=com_account&view=account&task=manageTheme', false), JText::_('COM_ACCOUNT_ACTION_SAVE_SETTING_FAIL'), 'error');
			}
			/* Redirect to clear the previous post values */
			$mainframe->redirect(JRoute::_('index.php?option=com_account&view=account&task=manageTheme', false), JText::_('COM_ACCOUNT_ACTION_SAVE_SETTING_SUCCESS'));
			
		}
		JRequest::setVar('view', 'theme');
		parent::display();
	}
	
	public function manageDepartment() {
		$table = JTable::getInstance('Category', 'StreamTable');
		$mainframe = JFactory::getApplication();

		if ($_POST) {
			$c = new StreamCategory();
			$department = JRequest::getVar('department', NULL);
			$position = JRequest::getVar('position', NULL);
			$type = JRequest::getVar('type', null);

			// No position/department specified, throw an error
			if(empty($$type)){
				$mainframe->redirect(JRoute::_('index.php?option=com_account&task=manageDepartment&view=account'), 
										   JText::_('COM_ACCOUNT_LABEL_'.strtoupper($type).'_ERROR_CREATE'), 'error');
			}

			$category = (!empty($department)) ? $department : $position;
			if ($table->create($category, $type)) {
				$mainframe->redirect(JRoute::_('index.php?option=com_account&task=manageDepartment&view=account'), 
									 JText::_('COM_ACCOUNT_LABEL_'.strtoupper($type).'_SUCCESS_CREATE'));
			}
			else {
				
				if ($c->isExist($category, $$type)) {
					$mainframe->redirect(JRoute::_('index.php?option=com_account&task=manageDepartment&view=account'), 
										 JText::_('COM_ACCOUNT_LABEL_'.strtoupper($type).'_ALREADY_EXIST'), 'error');
				}

				$mainframe->redirect(JRoute::_('index.php?option=com_account&task=manageDepartment&view=account'), 
										   JText::_('COM_ACCOUNT_LABEL_'.strtoupper($type).'_ERROR_CREATE'), 'error');
			}
		}

		// if this is remove action
		if (JRequest::getVar('action') == 'remove') {
			$categoryId = JRequest::getVar('category_id');
			$name = $table->getCategoryNameById($categoryId);
			$type = $table->getCategoryTypeById($categoryId);
			if ($table->remove($categoryId)) {
				$mainframe->redirect(JRoute::_('index.php?option=com_account&task=manageDepartment&view=account'), 
									 JText::sprintf('COM_ACCOUNT_LABEL_SUCCESS_REMOVE_'.strtoupper($type), $name));
			}
			else {
				$mainframe->redirect(JRoute::_('index.php?option=com_account&task=manageDepartment&view=account'), 
									 JText::sprintf('COM_ACCOUNT_LABEL_ERROR_REMOVE_'.strtoupper($type), $name), 'error');
			}
		}

		JRequest::setVar('view', 'department');
		parent::display();
	}

	public function billing()
	{		
		if ($_POST)
		{
		}
		JRequest::setVar('view', 'billing');
		parent::display();
	}
	
	public function removeLogo()
	{
		// Remove previous logo
		$configHelper		= new JXConfig();
		$originalFilePath	= $configHelper->getCompanyLogoPath();
		if (JFile::exists($originalFilePath))
		{
			JFile::delete($originalFilePath);
		}

		$param['logo']		= '';
		
		$mainframe = JFactory::getApplication();

		if (!$configHelper->saveConfig($param))
		{
			/* Redirect to clear the previous post values */
			$mainframe->redirect(JRoute::_('index.php?option=com_account&view=account&task=manageTheme', false), JText::_('COM_ACCOUNT_ACTION_REMOVE_LOGO_FAIL'), 'error');
		}

		/* Redirect to clear the previous post values */
		$mainframe->redirect(JRoute::_('index.php?option=com_account&view=account&task=manageTheme', false), JText::_('COM_ACCOUNT_ACTION_REMOVE_LOGO_SUCCESS'));
	}
		
	private function _cleanEmailDomain($arrValues)
	{
		$num = count($arrValues);
		for ( $i = 0; $i < $num; $i++ )
		{
			$arrValues[$i] = strtolower(trim($arrValues[$i]));
			if (empty($arrValues[$i]) || !preg_match('/^[a-z0-9-]+(\.[a-z0-9-]+)+$/i', $arrValues[$i]))
			{
				return false;
			}
		}

		return $arrValues;
	}

	public function categories() {
		JRequest::setVar('view', 'categories');
		$table = JTable::getInstance('Category', 'StreamTable');
		$c = new StreamCategory();
		$category = JRequest::getVar('category');
		$mainframe = JFactory::getApplication();

		// if this is remove action
		if (JRequest::getVar('action') == 'remove') {
			if ($table->remove($category)) {
				$mainframe->redirect(JRoute::_('index.php?option=com_account&task=categories&view=account'), 
									 JText::sprintf('COM_ACCOUNT_LABEL_SUCCESS_REMOVE_CATEGORY', $table->getCategoryNameById($category)));
			}
			else {
				$mainframe->redirect(JRoute::_('index.php?option=com_account&task=categories&view=account'), 
									 JText::sprintf('COM_ACCOUNT_LABEL_ERROR_REMOVE_CATEGORY', $table->getCategoryNameById($category)), 'error');
			}
		}

		if ($_POST) {
			$type = JRequest::getVar('type', null);
			if (JRequest::getVar('blog_category')) { 
				$category = JRequest::getVar('blog_category');
			}
			else if (JRequest::getVar('event_category')) {
				$category = JRequest::getVar('event_category');
			}
			else if (JRequest::getVar('group_category')) {
				$category = JRequest::getVar('group_category');
			}
			else {
				$mainframe->redirect(JRoute::_('index.php?option=com_account&task=categories&view=account'), 
										   JText::_('COM_ACCOUNT_LABEL_ERROR_CREATE'), 'error');
				return false;
			}

			if ($table->create($category, $type)) {
				$mainframe->redirect(JRoute::_('index.php?option=com_account&task=categories&view=account'), 
									 JText::_('COM_ACCOUNT_LABEL_SUCCESS_CREATE'));
			}
			else {
				
				if ($c->isExist($category, $type)) {
					$mainframe->redirect(JRoute::_('index.php?option=com_account&task=categories&view=account'), 
						JText::_('COM_ACCOUNT_LABEL_ALREADY_EXIST'), 'error');
				} else {

					$mainframe->redirect(JRoute::_('index.php?option=com_account&task=categories&view=account'), 
										   JText::_('COM_ACCOUNT_LABEL_ERROR_CREATE'), 'error');
				}
			}

		}
		parent::display();
	}
}