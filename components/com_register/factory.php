<?php
/**
 * @package		Offiria
 * @subpackage	com_register 
 * @copyright 	Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @author      Offiria Team
 */

defined('_JEXEC') or die;

jimport('joomla.xfactory');
jimport('joomla.utitlies.xconfig');

require_once(JPATH_ROOT.DS.'components'.DS.'com_register'.DS.'views'.DS.'view.html.php');
require_once(JPATH_ROOT.DS.'components'.DS.'com_register'.DS.'controller.php');

class RegisterFactory 
{
	/**
	 *  Return single instance of the model object
	 */	 	
	public static function getModel($name)
	{
		static $modelInstances = null;
		
		if(!isset($modelInstances)){
			$modelInstances = array();
		}
		
		if(!isset($modelInstances[$name]))
		{	
			// @rule: We really need to test if the file really exists.
			$modelFile	= JPATH_ROOT.DS.'components'.DS.'com_register'.DS.'models'.DS. strtolower( $name ) .'.php';
			if( !JFile::exists( $modelFile ) )
			{
				$modelInstances[ $name ]	= false;
			}
			else
			{
				include_once( $modelFile );
				$classname = 'RegisterModel'. ucfirst($name);
				$modelInstances[$name] = new $classname();
			}			
		}
		
		return $modelInstances[$name];
	}
	
	/**
	 * Return single instance view
	 */	 	
	public function getView( $name='', $prefix='', $viewType='' )
	{
		static $viewInstances = null;
		
		if(!isset($viewInstances))
		{
			$viewInstances = array();
		}
		
		$viewType = JRequest::getVar('format', 'html', 'REQUEST');
		
		if(!isset($viewInstances[$name.$prefix.$viewType]))
		{
			jimport( 'joomla.filesystem.file' );
			
			$viewFile	= JPATH_ROOT .DS.'components'.DS.'com_register' . DS . 'views' . DS . $name . DS . 'view.' . $viewType . '.php';

			if( JFile::exists($viewFile) )
			{
				include_once( $viewFile );
			}
			
			$classname = $prefix.'RegisterView'. ucfirst($name);
			$viewInstances[$name.$prefix.$viewType] = new $classname;
		}
		
		return $viewInstances[$name.$prefix.$viewType];
	}
}