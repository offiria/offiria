<?php
/**
 * @version     1.0.0
 * @package     com_administrator
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

defined('_JEXEC') or die;
require_once(JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'defines.php');
require_once(JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'views'.DS.'view.html.php');
require_once(JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'template.php');

// Class autoload register
JLoader::register('StreamMobile'		, JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'mobile.php');
JLoader::register('StreamComment'		, JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'comment.php');
JLoader::register('StreamMessage'		, JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'message.php');
JLoader::register('StreamNotification'	, JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'notification.php'); 
JLoader::register('StreamCategory'		, JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'category.php'); 
JLoader::register('StreamMap'			, JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'map.php');
JLoader::register('StreamActivity'		, JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'activity.php'); 
JLoader::register('StreamTag'			, JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'tag.php');
JLoader::register('StreamLinks'			, JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'links.php'); 
JLoader::register('StreamTable'			, JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'tables'.DS.'stream.php');
JLoader::register('MessagingNotification', JPATH_ROOT .DS.'components'.DS.'com_messaging'.DS.'libraries'.DS.'notification.php');

JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_stream' . DS . 'tables' );                     
jimport('joomla.analytics.analytics');
                      
class StreamFactory 
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
			//include_once( JPATH_ROOT.DS.'components'.DS.'com_stream'.DS.'models'.DS.'models.php');
			
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
	public static 	function getView( $name='', $prefix='', $viewType='' )
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
			
			$viewFile	= JPATH_ROOT .DS.'components'.DS.'com_stream' . DS . 'views' . DS . $name . DS . 'view.' . $viewType . '.php';

			if( JFile::exists($viewFile) )
			{
				include_once( $viewFile );
			}
			
			$classname = $prefix.'StreamView'. ucfirst($name);
			$viewInstances[$name.$prefix.$viewType] = new $classname;
		}
		
		return $viewInstances[$name.$prefix.$viewType];
	}
	
	/**
	 * Include the given file
	 */	 	
	public static function load($src){
		$src = str_replace('.', DS, $src);
		include_once(JPATH_ROOT.DS.'components'.DS.'com_stream'.DS.$src. '.php');
	}
	
	
}
