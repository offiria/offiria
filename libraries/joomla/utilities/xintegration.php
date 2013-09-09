<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Utilities
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JTable::addIncludePath( JPATH_SITE . DS . 'components' . DS . 'com_account' . DS . 'tables' );

/**
 * JXIntegration is a utility function class for 3rd party integrations
 *
 * @package     Joomla.Platform
 * @subpackage  Utilities
 * @since       11.1
 */
abstract class JXIntegration
{
	// each 3rd party integration should have a name, e.g. Facebook, Twitter, ActiveDirectory, etc
	protected $_name = '';
	
	// store the configuration parameters here
	protected $_config;
	
	// store the 3rd exchange information here, should maintain this as an object data
	public $exchange;
	
	// DO NOT SHIFT THE VALUE IN THE SUPPORTED ARRAY!! IT HAS DEPENDECY!!!
	static private $_supported = array('activedirectory');


	public function __construct()
	{
		if (!empty($this->_name))
		{
			$integrationTbl = JTable::getInstance( 'integration' , 'AccountTable' );
			$integrationTbl->load( $this->_name );
			
			$this->_getConfigParam( $integrationTbl );
		}
		
		return $this; 
	}
	
	/**
	 * Get integration configuration parameters
	 */	 	
	protected function _getConfigParam( $tableObj )
	{
		$this->_config = $tableObj->getParam();
		return $this;
	}
	
	public function getConfigInfo( $name = '' )
	{
		if (is_object($this->_config) && property_exists($this->_config, $name))
		{
			return $this->_config->$name;
		}
		
		return null;
	}
	
	public function getExchangeInfo( $name = '' )
	{
		if (is_object($this->exchange) && property_exists($this->exchange, $name))
		{
			return $this->exchange->$name;
		}
		
		return null;
	}
	
	public static function isActiveDirectory($type)
	{
		$adType = self::$_supported[0];
		return ($type === $adType);
	}
	
	/**
	 * try to connect to the 3rd party
	 * try to retrieve and store exchange data into $this->exchange standard object
	 */
	abstract public function connect();
}