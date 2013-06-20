<?php

/**
 * @package		com_stream
 * @subpackage	Core 
 * @copyright	(C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
jimport('joomla.utilities.simplexml');

class JXConfig
{
	const LOGO = 'logo';
	const STYLE = 'style';
	const PREMIUM = 'premium';
	const TIMEZONE = 'default_timezone';
	const LANGUAGE = 'default_language';
	const SITENAME = 'sitename';
	const ALLOW_INVITE = 'allow_invite';
	const ALLOW_ANON = 'allow_anon';
	const DOMAIN_NAME = 'domain_name';
	const LIMIT_EMAIL_DOMAIN = 'limit_email_domain';
	const PLAN = 'plan';
	const MAX_USERS = 'max_users';
	const POSTFIX_DOMAIN = 'postfix_domain';
	const DOMAIN_SUFFIX = '.offiria.com';
	const RENAME_CURL = 'rename_curl';
	const CREATE_DATE = 'create_date';
	const CROCODOCS = 'crocodocs';
	const CROCODOCS_ENABLE = 'crocodocsenable';
	const SCRIBD_API = 'scribd_api';
	const SCRIBD_SECRET = 'scribd_secret';
	const SCRIBD_ENABLE = 'scribdenable';
	const DIFFBOT = 'diffbot';
	const DIFFBOT_ENABLE = 'diffbotenable';
	const CURL_LINK = "https://signup.offiria.com/index.php/subscription/";
	
	// Number of users exceed this will disable the system from allowing user to change domain
	const MAX_DOMAIN_CHANGE_USER_COUNT = 5;
	
	// TODO: change the IS_PREMIUM value to the required value
	const IS_PREMIUM = '123';
	// TODO: change the MAX_ALLOWED_USERS value to the required value
	const MAX_ALLOWED_USERS = '30';
	
	// Common, allow/disallow
	const DISALLOW = '0';
	const ALLOW = '1';
	// TODO: change the DEFAULT_LOGO value to the required value
	const DEFAULT_LOGO = 'images/logo.png';
	const DEFAULT_MISSING_IMAGE = 'images/missing.jpg';

	protected $_config;
	protected $_plans;
	protected $_default_plan = 'starter';
	
	public function __construct()
	{
		$this->_config = new JConfig();
		$this->_getPlanSettings();
	}
	
	public function checkPremium()
	{
		return ($this->_getProp(self::PREMIUM) == self::IS_PREMIUM) ? true : false;
	}
	
	public function get( $propertyName )
	{
		return $this->_getProp($propertyName);
	}
	
	public function getAvailablePlans($name = '')
	{
		$name = strtolower(trim($name));
		if (empty($name))
		{
			return $this->_plans;
		}
		else
		{
			for ($i = 0; $i < count($this->_plans); $i++)
			{
				if ($name == $this->_plans[$i][0])
				{
					return $this->_plans[$i];
				}
			}
		}
		
		// must return false to ease condition checking
		return false;
	}
	
	public function getDomainName()
	{
		$domainName = $this->get(self::DOMAIN_NAME);
		if (empty($domainName))
		{
			$uri = JURI::getInstance();
			$domainName = str_replace($this->getDomainSuffix(), '', $uri->toString(array('host')));
		}
		return $domainName;
	}
		
	public function getDomainSuffix()
	{
		$postfixDomain = $this->get(self::POSTFIX_DOMAIN);
		
		// IF postfix domain doesnt exists, use default ".offiria.com"
		if (empty($postfixDomain))
		{
			$configIndex = self::POSTFIX_DOMAIN;
			$data[$configIndex]	= self::DOMAIN_SUFFIX;
		
			$this->saveConfig($data);
			
			$this->_config->$configIndex = self::DOMAIN_SUFFIX;
		}
		
		return $this->get(self::POSTFIX_DOMAIN);
	}
	
	public function getRenameCurl()
	{
		$renameCurlLink = $this->get(self::RENAME_CURL);
		
		// IF rename_curl doesnt exists, use default "https://signup.offiria.com/index.php/subscription/"
		if (empty($renameCurlLink))
		{
			$configIndex = self::RENAME_CURL;
			$data[$configIndex]	= self::CURL_LINK;
		
			$this->saveConfig($data);
			
			$this->_config->$configIndex = self::CURL_LINK;
		}
		
		return $this->get(self::RENAME_CURL);
	}
	
	public function getCurrentPlan()
	{
		$currentPlan = $this->get(self::PLAN);
		
		// plan does not exists
		if (empty($currentPlan) || $this->getAvailablePlans($currentPlan) === false)
		{
			$starterPlan		= $this->getAvailablePlans($this->_default_plan);
			$configIndex		= self::PLAN;
			$data[$configIndex]	= $starterPlan[0];
		
			$this->saveConfig($data);
			
			$this->_config->$configIndex = $starterPlan[0];
		}
		
		return $this->get(self::PLAN);
	}
	
	public function getCompanyLogo()
	{
		// $mainFrame		= JFactory::getApplication();
		// $templateName	= $mainFrame->getTemplate();
		$templateName   = JText::_('CUSTOM_TEMPLATE');
		$physicalPath	= JPATH_ROOT;
		$urlPath		= JURI::base();		
		$logoImage		= 'templates'.'/'.$templateName.'/'.self::DEFAULT_LOGO;
		
		$companyLogo	= $this->_getProp(self::LOGO);
		if ($companyLogo != '')
		{
			if (JFile::exists($physicalPath.DS.$companyLogo))
			{
				$logoImage = $companyLogo;
			}
		}
		
		return $urlPath.$logoImage;
	}
	
	public function getCompanyLogoPath()
	{
		$mainFrame		= JFactory::getApplication();
		$templateName	= $mainFrame->getTemplate();
		$physicalPath	= JPATH_ROOT;
		$logoImage		= 'templates'.'/'.$templateName.'/'.self::DEFAULT_LOGO;
		
		$companyLogo	= $this->_getProp(self::LOGO);
		if ($companyLogo != '')
		{
			if (JFile::exists($physicalPath.DS.$companyLogo))
			{
				$logoImage = $companyLogo;
			}
		}
		
		return $physicalPath.DS.$logoImage;
	}
	
	public function getMissingImage() {
		$mainFrame		= JFactory::getApplication();
		$templateName	= $mainFrame->getTemplate();
		$physicalPath	= JPATH_ROOT;
		$missingImage		= 'templates'.'/'.$templateName.'/'.self::DEFAULT_MISSING_IMAGE;
		return $physicalPath.DS.$missingImage;
	}

	public function getLanguage()
	{
		return $this->get(self::LANGUAGE);
	}
	
	public function getTimezone()
	{
		return $this->get(self::TIMEZONE);
	}
	
	public function allowInvite()
	{
		return ($this->get(self::ALLOW_INVITE) == self::ALLOW);
	}
	
	public function saveConfig($param)
	{
		$config = new JConfig();
		// Fetch the original data
		$data = JArrayHelper::fromObject($config);

		// Set the posted data to update the configuration
		if (is_array($param) && !empty($param))
		{
			$data = array_merge($data, $param);
			
			// Create the new configuration object.
			$config = new JRegistry('config');
			$config->loadArray($data);

			// Write the configuration file.
			jimport('joomla.filesystem.path');
			jimport('joomla.filesystem.file');

			// Set the configuration file path.
			$file = JPATH_CONFIGURATION . '/configuration.php';

			// Overwrite the old FTP credentials with the new ones.
			$temp = JFactory::getConfig();
			$temp->set('ftp_enable', $data['ftp_enable']);
			$temp->set('ftp_host', $data['ftp_host']);
			$temp->set('ftp_port', $data['ftp_port']);
			$temp->set('ftp_user', $data['ftp_user']);
			$temp->set('ftp_pass', $data['ftp_pass']);
			$temp->set('ftp_root', $data['ftp_root']);

			// Get the new FTP credentials.
			jimport('joomla.client.helper');
			$ftp = JClientHelper::getCredentials('ftp', true);
			if ($ftp !== false)
			{

				// Attempt to make the file writeable if using FTP.
				if (!$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0644')) {
					JError::raiseNotice('SOME_ERROR_CODE', JText::_('COM_CONFIG_ERROR_CONFIGURATION_PHP_NOTWRITABLE'));
				}

				// Attempt to write the configuration file as a PHP class named JConfig.
				$configString = $config->toString('PHP', array('class' => 'JConfig', 'closingtag' => false));
				if (!JFile::write($file, $configString)) {
					$this->setError(JText::_('COM_CONFIG_ERROR_WRITE_FAILED'));
					return false;
				}

				// Attempt to make the file unwriteable if using FTP.
				if ($data['ftp_enable'] == 0 && !$ftp['enabled'] && JPath::isOwner($file) && !JPath::setPermissions($file, '0444')) {
					JError::raiseNotice('SOME_ERROR_CODE', JText::_('COM_CONFIG_ERROR_CONFIGURATION_PHP_NOTUNWRITABLE'));
				}

				return true;
			}
		}
		
		return false;
	}
	
	public function getMaxAllowUsers()
	{
		$currentPlanName = $this->getCurrentPlan();
		$currentPlanInfo = $this->getAvailablePlans($currentPlanName);
		
		$configIndex = self::MAX_USERS;
		$maxAllowedUsers = ($currentPlanInfo[1]) ? $currentPlanInfo[1] : self::MAX_ALLOWED_USERS;
		
		// If the max_users flag is not exits or different from the constant in this library class
		if (($this->get($configIndex) === '') || (intval($this->get($configIndex)) != intval($maxAllowedUsers)) )
		{
			$data[$configIndex] = $maxAllowedUsers;
		
			$this->saveConfig($data);
			
			$this->_config->$configIndex = $maxAllowedUsers;
		}
		return $this->get($configIndex);
	}
		
	public function allowUsersRegister()
	{		
		return ($this->getRegisteredAndInvitedCount() >= $this->getMaxAllowUsers()) ? false : true;
	}
	
	public function getRegisteredAndInvitedCount()
	{
		jimport('joomla.html.parameter');
		jimport('joomla.user.xuser');
		$arrUsers	= JXUser::getRegisteredUsers();
		
		require_once(JPATH_ROOT . DS . 'components' . DS . 'com_account' . DS . 'tables' . DS . 'usersinvite.php');
		JModel::addIncludePath(JPATH_ROOT . DS . 'components' . DS . 'com_account' . DS . 'models');
		$userInviteModel = JModel::getInstance('usersInvite', 'AccountModel');
		$totalInvite = $userInviteModel->getTotal(array('status' => AccountTableUsersInvite::PENDING));
		$userCount = count($arrUsers) + $totalInvite;
		
		return $userCount;
	}
	
	public function allowChangeDomain()
	{
		jimport('joomla.user.xuser');
		$arrUsers	= JXUser::getRegisteredUsers();
		return (count($arrUsers) >= self::MAX_DOMAIN_CHANGE_USER_COUNT) ? false : true;
	}
	
	/* 
	 * Get Instance Activation Date (create_date)
	 * NOTE: create_date will be updated upon upgrade and renew package plan
	 * The create_date might not be the instance sign up date
	 */
	
	public function getActivationDate()
	{		
		$configIndex = self::CREATE_DATE;
		// If the max_users flag is not exits or different from the constant in this library class
		if (($this->get($configIndex) === ''))
		{
			$data[$configIndex] = '2012-08-01';
		
			$this->saveConfig($data);
			
			$this->_config->$configIndex = $maxAllowedUsers;
		}
		return $this->get($configIndex);
	}
	
	/* 
	 * Get Instance Current Package Expiry Date 
	 * (calculated based on create_date in configuration.php with duration from accplan.xml)
	 * NOTE: the duration can be change on specific instances by using custom-accplan.xml to overwrite the default setting
	 */
	
	public function getExpiryDate()
	{		
		$currentPlan = $this->getAvailablePlans($this->getCurrentPlan());
		$activateDate = $this->getActivationDate();
		$activateTimestamp = strtotime($activateDate);
		
		if ($activateTimestamp === false)
		{
			$activateTimestamp = strtotime('2012-08-21');
		}
		
		$currentPlanDuration = $currentPlan[4] * 60 * 60 * 24;
		$expiryTimestamp = $activateTimestamp + $currentPlanDuration;
		
		return date('Y-m-d', $expiryTimestamp);
	}
	
	/*
	 * Check if scribd is enabled 
	 */
	public function isScribdEnabled()
	{
		return ($this->get(self::SCRIBD_ENABLE) == '1' && trim($this->get(self::SCRIBD_SECRET, '')) != '' && trim($this->get(self::SCRIBD_API, '')) != '');
	}
	
	/*
	 * Check if crocodocs is enabled and if token is not empty
	 */
	public function isCrocodocsEnabled()
	{
		return ($this->get(self::CROCODOCS_ENABLE) == '1' && trim($this->get(self::CROCODOCS, '')) != '');
	}
	
	/*
	 * Check if diffbot is enabled and if token is not empty
	 */
	public function isDiffBotEnabled()
	{
		return ($this->get(self::DIFFBOT_ENABLE) == '1' && trim($this->get(self::DIFFBOT, '')) != '');
	}
		
	public function cleanEmailList($arrValues)
	{
		$num = count($arrValues);
		$checkDomain = false;
		$allowEmailDomain = $this->get(self::LIMIT_EMAIL_DOMAIN);
		if ($allowEmailDomain != '')
		{
			$checkDomain = true;
			$allowEmailDomain = explode(',',$allowEmailDomain);
		}
		
		for ( $i = 0; $i < $num; $i++ )
		{
			$arrValues[$i] = JString::strtolower(trim($arrValues[$i]));
			if (empty($arrValues[$i]) || !preg_match("/[a-z0-9!#$%&'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/", $arrValues[$i]))
			{
				// invalid email format
				return JText::_('COM_ACCOUNT_ERRMSG_INVALID_INVITATION_EMAIL');
			}
			
			// check if email domain is in allowed list
			if ($checkDomain)
			{
				$emailDomain = explode('@', $arrValues[$i]);			
				if (!isset($emailDomain[1]) || (!in_array(JString::strtolower(trim($emailDomain[1])), $allowEmailDomain)))
				{					
					return JText::sprintf('COM_ACCOUNT_ERRMSG_INVALID_EMAIL_DOMAIN_PROVIDED', $this->get(self::LIMIT_EMAIL_DOMAIN));
					//return false;
				}
			}
		}
		
		return $arrValues;
	}
	
	private function _getProp( $propertyName )
	{
		if ( isset($this->_config->$propertyName) )
		{
			return $this->_config->$propertyName;
		}
		
		return '';
	}	
	
	private function _getPlanSettings()
	{
		// plan-keyword, user-limit, storage, price, duration-to-expire, display-label
		$hardcodedPlan = array(
							array('professional', 120, '40', '149', '365', 'Venti'),
							array('plus', 60, '20', '89', '365', 'Grande'),
							array('starter', 30, '10', '48', '365', 'Tall')
						);
		$defaultFile = JPATH_ROOT . DS . 'components' . DS . 'com_account' . DS . 'models' . DS . 'accplan.xml';
		$customFile = JPATH_ROOT . DS . 'components' . DS . 'com_account' . DS . 'models' . DS . 'custom-accplan.xml';
		
		// If acc xml file is not found
		if (!JFile::exists($customFile) && !JFile::exists($defaultFile))
		{
			$this->_plans = $hardcodedPlan;
			return false;
		}
		
		// Use custom file if exists; for white labeling
		if (JFile::exists($customFile))
		{
			$defaultFile = $customFile;
		}
		
		$objXml = new JSimpleXML();
		$objXml->loadFile($defaultFile);
		
		if ($objXml->document->attributes('default'))
		{
			$this->_default_plan = $objXml->document->attributes('default');
		}
		
		// if no plan info is found in the XML file
		if (isset($objXml->document->plan) && count($objXml->document->plan) > 0)
		{
			$availablePlans = $objXml->document->plan;
			$plans = array();
			//do this
			for ($i = 0; $i < count($availablePlans); $i++)
			{
				$plans[] = array(
								$availablePlans[$i]->attributes('name'),//plan keyword
								$availablePlans[$i]->userlimit[0]->data(),//user limit
								$availablePlans[$i]->storage[0]->data(),//storage size
								$availablePlans[$i]->price[0]->data(),//price
								$availablePlans[$i]->duration[0]->data(),//duration to expire
								$availablePlans[$i]->attributes('description')//label (at last position, to allow easy retrieval using end();
								);
			}
			$this->_plans = $plans;
		}
		else
		{
			$this->_plans = $hardcodedPlan;
		}
		return true;
	}
	
	
	/* CURL functions */
	public function sanitizeDomain($domain)
	{		
		$action = 'sanitize/';
		$cUrl	= curl_init();
		curl_setopt($cUrl, CURLOPT_URL, $this->getRenameCurl() . $action . $domain);
		curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($cUrl);				
		curl_close ($cUrl);
		
		if (preg_match('/^error/', $result)) //an error
		{
			$result = preg_replace('/^error:?\s?/', '', $result);
			return self::returnCurlRespond(true, $result);
		}
		
		return self::returnCurlRespond(false, $result);
	}
	
	public function updateDomain($username, $oldDomain, $newDomain)
	{
		$action = 'updateDomain/';
		$data	= array('username' => $username, 'domain' => $oldDomain, 'new_domain' => $newDomain);
		
		$cUrl	= curl_init();
		curl_setopt($cUrl, CURLOPT_URL, $this->getRenameCurl() . $action);
		curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($cUrl, CURLOPT_POST, 1);
		curl_setopt($cUrl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($cUrl, CURLOPT_REFERER, JURI::base());
		
		$result = curl_exec($cUrl);				
		curl_close ($cUrl);
		
		if (preg_match('/^error/', $result)) //an error
		{
			$result = preg_replace('/^error:?\s?/', '', $result);
			return self::returnCurlRespond(true, $result);
		}
		
		return self::returnCurlRespond(false, $result);
	}
	
	/*public function removeDomain($username, $oldDomain)
	{
		$action = 'removeDomain/';
		$data	= array('username' => $username, 'domain' => $oldDomain);
		
		$cUrl	= curl_init();
		curl_setopt($cUrl, CURLOPT_URL, self::CURL_LINK . $action);
		curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);		
		curl_setopt($cUrl, CURLOPT_POST, 1);
		curl_setopt($cUrl, CURLOPT_POSTFIELDS, $data);
		
		$result = curl_exec($cUrl);				
		curl_close ($cUrl);
		
		if (preg_match('/^error/', $result)) //an error
		{
			//todo: send another curl request to delete the previous symbolic link
			
			$result = preg_replace('/^error:?\s?/', '', $result);
			return self::returnCurlRespond(true, $result);
		}
		
		return self::returnCurlRespond(false, $result);
	}*/
	
	public function returnCurlRespond($error, $msg)
	{
		return array('error' => $error, 'msg' => $msg);
	}
}
?>
