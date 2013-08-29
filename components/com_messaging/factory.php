<?php
defined('_JEXEC') or die;

require_once(JPATH_ROOT .DS.'components'.DS.'com_messaging'.DS.'views'.DS.'view.html.php');
require_once(JPATH_ROOT .DS.'components'.DS.'com_messaging'.DS.'libraries'.DS.'template.php');

JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_messaging' . DS . 'tables' );
                      
class MessagingFactory
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
			//include_once( JPATH_ROOT.DS.'components'.DS.'com_messaging'.DS.'models'.DS.'models.php');
			
			// @rule: We really need to test if the file really exists.
			$modelFile	= JPATH_ROOT.DS.'components'.DS.'com_messaging'.DS.'models'.DS. strtolower( $name ) .'.php';
			if( !JFile::exists( $modelFile ) )
			{
				$modelInstances[ $name . $prefix ]	= false;
			}
			else
			{
				include_once( $modelFile );
				$classname = $prefix.'MessagingModel'.$name;
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
			
			$viewFile	= JPATH_ROOT .DS.'components'.DS.'com_messaging' . DS . 'views' . DS . $name . DS . 'view.' . $viewType . '.php';

			if( JFile::exists($viewFile) )
			{
				include_once( $viewFile );
			}
			
			$classname = $prefix.'MessagingView'. ucfirst($name);
			$viewInstances[$name.$prefix.$viewType] = new $classname;
		}
		
		return $viewInstances[$name.$prefix.$viewType];
	}
	
	/**
	 * Include the given file
	 */	 	
	public function load($src){
		$src = str_replace('.', DS, $src);
		include_once(JPATH_ROOT.DS.'components'.DS.'com_messaging'.DS.$src. '.php');
	}
}