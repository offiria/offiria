<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class StreamCron
{	
	/**
	 *
	 */	 	
	static public function run()
	{
		$now = new JDate();
		
		// Clear all old uploaded, but unused files
		$fileModel = StreamFactory::getModel('files');
		$files = $fileModel->getUnusedFiles();
		foreach($files as $file){
			$file->delete();
		}
		
		//
		
		return true;
	}
}

