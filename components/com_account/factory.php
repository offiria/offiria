<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.xfactory');
require_once(JPATH_ROOT.DS.'components'.DS.'com_account'.DS.'views'.DS.'view.html.php');
require_once(JPATH_ROOT.DS.'components'.DS.'com_account'.DS.'controller.php');
require_once(JPATH_ROOT.DS.'components'.DS.'com_account'.DS.'helpers'.DS.'access.php');
require_once(JPATH_ROOT.DS.'components'.DS.'com_stream'.DS.'access.php');

JLoader::register('StreamCategory', JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'category.php'); 

class AccountFactory 
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
			$modelFile	= JPATH_ROOT.DS.'components'.DS.'com_account'.DS.'models'.DS. strtolower( $name ) .'.php';
			if( !JFile::exists( $modelFile ) )
			{
				$modelInstances[ $name ]	= false;
			}
			else
			{
				include_once( $modelFile );
				$classname = 'AccountModel'. ucfirst($name);
				$modelInstances[$name] = new $classname();
			}			
		}
		
		return $modelInstances[$name];
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
			
			$viewFile	= JPATH_ROOT .DS.'components'.DS.'com_account' . DS . 'views' . DS . $name . DS . 'view.' . $viewType . '.php';

			if( JFile::exists($viewFile) )
			{
				include_once( $viewFile );
			}
			
			$classname = $prefix.'AccountView'. ucfirst($name);
			$viewInstances[$name.$prefix.$viewType] = new $classname;
		}
		
		return $viewInstances[$name.$prefix.$viewType];
	}
}