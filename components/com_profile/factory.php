<?php
/**
 * @version     1.0.0
 * @package     com_profile
 * @copyright	(C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die;
jimport('joomla.xfactory');
jimport('joomla.utilities.ximage');
jimport('joomla.user.xuser');
jimport('joomla.utilities.xmodule');

JLoader::register('Notifications', JPATH_ROOT .DS.'components'.DS.'com_profile'.DS.'libraries'.DS.'notification.php');
JLoader::register('Applications', JPATH_ROOT .DS.'components'.DS.'com_profile'.DS.'libraries'.DS.'applications.php');

// Class autoload register
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_stream' . DS . 'tables' );
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_profile' . DS . 'tables' );
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_oauth' . DS . 'tables' );
                      
class ProfileFactory 
{
	
	/**
	 * Return the model instance
	 * @param type $name 
	 */
	public static function getModel( $name = '', $prefix = '', $config = array() )
	{
		static $modelInstances = null;
		
		if(!isset($modelInstances)){
			$modelInstances = array();
		}
		
		if(!isset($modelInstances[$name.$prefix]))
		{			
			// @rule: We really need to test if the file really exists.
			$modelFile	= JPATH_ROOT.DS.'components'.DS.'com_profile'.DS.'models'.DS. strtolower( $name ) .'.php';
			if( !JFile::exists( $modelFile ) )
			{
				$modelInstances[ $name . $prefix ]	= false;
			}
			else
			{
				include_once( $modelFile );
				$classname = $prefix.'ProfileModel'.$name;
				$modelInstances[$name.$prefix] = new $classname();
			}			
		}
		
		return $modelInstances[$name.$prefix];
	}
	
	/**
	 * Return the model instance
	 * @param type $name 
	 */
	public function getStreamModel( $name = '', $prefix = '', $config = array() )
	{
		static $modelInstances = null;
		
		if(!isset($modelInstances)){
			$modelInstances = array();
		}
		
		if(!isset($modelInstances[$name.$prefix]))
		{			
			// @rule: We really need to test if the file really exists.
			$modelFile	= JPATH_ROOT.DS.'components'.DS.'com_stream'.DS.'models'.DS. strtolower( $name ) .'.php';
			if( !JFile::exists( $modelFile ) )
			{
				$modelInstances[ $name . $prefix ]	= false;
			}
			else
			{
				include_once( $modelFile );
				$classname = $prefix.'StreamModel'.$name;
				$modelInstances[$name.$prefix] = new $classname();
			}			
		}
		
		return $modelInstances[$name.$prefix];
	}
	
	/**
	 * Return single instance view
	 */	 	
	public static function getView( $name='', $prefix='', $viewType='' )
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
			
			$viewFile	= JPATH_ROOT .DS.'components'.DS.'com_profile' . DS . 'views' . DS . $name . DS . 'view.' . $viewType . '.php';

			if( JFile::exists($viewFile) )
			{
				include_once( $viewFile );
			}
			
			$classname = $prefix.'ProfileView'. ucfirst($name);
			$viewInstances[$name.$prefix.$viewType] = new $classname;
		}
		
		return $viewInstances[$name.$prefix.$viewType];
	}
	
	
}