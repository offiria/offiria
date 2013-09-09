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

class StreamView extends JView
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
		$pathway		= $mainframe->getPathway();
		
		$pathwayNames	= $pathway->getPathwayNames();
		
		// Test for duplicates before adding the pathway
		if( !in_array( $text , $pathwayNames ) )
		{
			$pathway->addItem( $text , $link );
		}
	}
	
	/**
	 *  Return upcoming event
	 */	 	
	public function modUpcomingEventHTML()
	{
	}
	
	public function modGetAttendeeHTML($stream)
	{
		$attendees = $stream->getFollowers();
		$tmpl = new StreamTemplate();
		$tmpl->set('stream', $stream)->set('attendees', $attendees);
		$html = $tmpl->fetch('event.module.attendee');
		return $html;
	}

	public function modTagsTrendingHTML($group = null)
	{
		$tags = new StreamTag();
		$trendingTags = $tags->getTrending($group);

		$tmpl = new StreamTemplate();
		$tmpl->set('title', JText::_('COM_STREAM_LABEL_TRENDING_TAGS'));
		$tmpl->set('trendingTags', $trendingTags);

		if(!is_null($group)) {
			// Filter tags in viewed group
			$tmpl->set('groupId', $group->id);
		}

		$html = $tmpl->fetch('stream.tag.trending');
		return $html;
	}
}