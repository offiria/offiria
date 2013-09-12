<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class MessagingView extends JView
{
	// Attach required javascript
	function _attachScripts()
	{
	}

	public function addPathway($text, $link = '')
	{
		// Set pathways
		$mainframe = JFactory::getApplication();
		$pathway = $mainframe->getPathway();

		$pathwayNames = $pathway->getPathwayNames();

		// Test for duplicates before adding the pathway
		if (!in_array($text, $pathwayNames)) {
			$pathway->addItem($text, $link);
		}
	}
}