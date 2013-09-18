<?php
/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
include_once('message.php');

/**
 * Templating system for JomSocial
 */
class StreamEventType extends StreamType
{
	
	public function StreamEventType($data)
	{
		$this->data = $data;
	}
	
	public function getItemHTML($format = null)
    {
    	// If were not in 'article' view, just show short summary
    	/*
		$view = JRequest::getVar('view');
    	$task = JRequest::getVar('task');
    	
    	if( ($view != 'message' || $task != 'show') && $format != 'full'){
    		$date = new JDate($this->data->start_date);
			$html = '<li class="message-item compact-item"><span class="label-compact label-event">Event</span>
				<div class="message-content-compact">
				<a href="'. $this->data->getUri().'">
				'.StreamMessage::format($this->data->message).'
				</a>
				<span class="small hint">'.JXDate::formatLapse( $date ).'</span>
				</div>
				<div class="clear"></div>
				</li>';
			return $html;
		}
		*/
    	$tmpl = new StreamTemplate();

		$tmpl->set( 'stream'	, $this->data)
				->set('comment', StreamComment::getCommentSummaryHTML($this->data));
		return $tmpl->fetch('stream.item.event');
	}
	
	/**
	 *
	 */	 	
	public function onStore(){
		// If this is a milestone, set the end_date for away into the future
	}
	
}