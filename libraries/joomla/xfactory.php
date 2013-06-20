<?php
/**
 * @package     Joomla.Platform
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;
jimport('joomla.utilities.xconfig');

// Load language file
$lang			= JFactory::getLanguage();
$config			= new JXConfig();

// First load account setting language (if any) to override joomla! language
$defLanguage	= ($config->getLanguage() != '') ? $config->getLanguage() : $lang->get('default');

$my				= JFactory::getUser();
// Second load user personal language (if any) to override default language
$siteLanguage	= (intval($my->id) > 0 && $my->getParam('language') != '') ? $my->getParam('language') : $defLanguage;

$lang->setLanguage($siteLanguage);
$result = $lang->load('lib_xjoomla');

if (JFile::exists(JPATH_ROOT.DS.'language'.DS.'en-GB'.DS.'en-GB.offiria_custom.ini'))
{
	$result = $lang->load('offiria_custom', JPATH_BASE, 'en-GB', true);
}

// Class autoload register
jimport('joomla.user.xuser');
jimport('joomla.utilities.xdate');
jimport('joomla.utilities.xstring');
jimport('joomla.utilities.xutility');
jimport('joomla.utilities.xmodule');

/**
 * Joomla Framework Factory class
 *
 * @package Joomla.Platform
 * @since   11.1
 */
abstract class JXFactory
{
	
	public static function getUser($id = null)
	{
		static $instances = array();
		
		if( empty($instances[$id]) )
		{
			$user = JFactory::getUser( $id );
			
			$jxuser = new JXUser();
			$jxuser->init($user);
			
			// Reload params from db, just for myself
			if( is_null($id) ){
				jimport('joomla.html.parameter' );
				$db = JFactory::getDbo();
		
				// For current user, always reload params from db
				$query = "SELECT params FROM #__users WHERE ".$db->nameQuote('id')." = " . $db->Quote($user->id);
				$db->setQuery( $query );
				
				$params = $db->loadResult();

				$jxuser->setParameters( new JParameter($params));
			}
			
			$instances[$id] = $jxuser;
		}
		
		return $instances[$id];
	}
	
	/**
	 * Do not store anon user
	 */	 	
	public function save()
	{
		if($this->id == -1)
		{
			return;
		}
		
		return parent::save();
	}
}

