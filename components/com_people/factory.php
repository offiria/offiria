<?php
/**
 * @version     1.0.0
 * @package     com_People
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

defined('_JEXEC') or die;
JLoader::register('StreamCategory', JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'category.php'); 
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_profile' . DS . 'tables' );                     
JTable::addIncludePath( JPATH_ROOT .DS.'components'.DS.'com_stream' . DS . 'tables' );                     

class AFactory 
{
	/**
	 *  Return single instance of the model object
	 */	 	
	public function getModel($name)
	{
		static $modelInstances = null;
		
		if(!isset($modelInstances)){
			$modelInstances = array();
		}
		
		if(!isset($modelInstances[$name]))
		{	
			// @rule: We really need to test if the file really exists.
			$modelFile	= JPATH_ROOT.DS.'components'.DS.'com_people'.DS.'models'.DS. strtolower( $name ) .'.php';
			if( !JFile::exists( $modelFile ) )
			{
				$modelInstances[ $name ]	= false;
			}
			else
			{
				include_once( $modelFile );
				$classname = 'PeopleModel'. ucfirst($name);
				$modelInstances[$name] = new $classname();
			}			
		}
		
		return $modelInstances[$name];
	}
}