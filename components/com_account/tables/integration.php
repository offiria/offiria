<?php
/**
 * @category	Tables
 * @package		Offiria
 * @subpackage	Activities 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

/*
CREATE TABLE IF NOT EXISTS `[prefix]_integrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `params` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
 */
 
class AccountTableIntegration extends JTable
{
	var $id		= null;
	var $name	= null;
	
	protected $_params = null;

	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		$this->_params = new JRegistry;
		parent::__construct( '#__integrations', 'name', $db );
	}
	
	public function load($keys = NULL, $reset = true)
	{
		$ret = parent::load($keys);
		$this->_params->loadString($this->params);
		return $ret;
	}
	
	public function bind($src, $ignore = array() )
	{
		$ret = parent::bind($src);
		$this->_params->loadString($this->params);
		return $ret;
		
	}
	
	public function getParam( $name = '' )
	{
		$paramObj = $this->_params->toObject();
		if (!empty( $name ))
		{
			return $this->_params->get($name, '');
		}
		
		return $paramObj;
	}	
	
	public function setParam( $name, $value )
	{
		$this->_params->set($name, $value);
		$this->params = $this->_params->toString();
		return true;
	}	
	
	public function getHTML()
	{				
		$integrationName = strtolower($this->name);
	    ob_start();                    // Start output buffering
	    require( JPATH_ROOT .DS.'components'.DS.'com_account'.DS.'templates'.DS.$integrationName.'.php' );                // Include the file
	    $contents = ob_get_contents(); // Get the contents of the buffer
	    ob_end_clean();       
		
		return $contents;
	}
}
