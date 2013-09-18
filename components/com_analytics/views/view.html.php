<?php
/**
 * @version     1.0.0
 * @package     com_administrator
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class AnalyticsView extends JView
{
	
	/**
	 *  Attaché required javascript
	 */	 	
	function _attachScripts()
	{
		
	
	}
	
	public function addPathway( $text , $link = '' )
	{
		// Set pathways
		$mainframe		= JFactory::getApplication();
		$pathway		=& $mainframe->getPathway();
		
		$pathwayNames	= $pathway->getPathwayNames();
		
		// Test for duplicates before adding the pathway
		if( !in_array( $text , $pathwayNames ) )
		{
			$pathway->addItem( $text , $link );
		}
	}
	
}