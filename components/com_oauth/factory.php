<?php
/**
 * @version     1.0.0
 * @package     com_oauth
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class OAuthFactory 
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
			$modelFile	= JPATH_ROOT.DS.'components'.DS.'com_oauth'.DS.'models'.DS. strtolower( $name ) .'.php';
			if( !JFile::exists( $modelFile ) )
			{
				$modelInstances[ $name ]	= false;
			}
			else
			{
				include_once( $modelFile );
				$classname = 'OauthModel'. ucfirst($name);
				$modelInstances[$name] = new $classname();
			}			
		}
		
		return $modelInstances[$name];
	}
}