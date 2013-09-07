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

/**
 * HTML View class for the Administrator component
 */
class StreamViewItem extends JView
{
	function display($tpl = null)
	{
        //parent::display($tpl);
		$document = JFactory::getDocument();
		$document->addScript(JURI::root().'media/jquery/jquery-1.7.min.js');
		$document->addScript(JURI::root().'media/jquery/autogrow.min.js');
		//$document->addScript(JURI::root().'components/com_stream/assets/javascript/script.js'); // this doesn't exist, do we still need this?
		$document->addScript(JRoute::_('index.php?option=com_stream&view=system&task=script'));
	}
}