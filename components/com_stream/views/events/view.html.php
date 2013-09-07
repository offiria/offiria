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
class StreamViewEvents extends StreamView
{
	function display($tpl = null)
	{
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_("COM_STREAM_LABEL_ALL_EVENTS"));
		$html = '';
        $this->addPathway( JText::_('NAVIGATOR_LABEL_EVENTS'), JRoute::_('index.php?option=com_stream&view=groups') );
		
		$this->_attachScripts();
		
		$tmpl = new StreamTemplate();
		$html .= $tmpl->fetch('event.page');
		
		$my = JXFactory::getUser();
		
		if(! $my->getParam(ALERT_CALENDAR_INTRO)){
		$html .= '
			<div class="alert alert-success" data-alert_id="'.ALERT_CALENDAR_INTRO.'">
	        <a data-dismiss="alert" class="close">×</a>
			'.JText::_('COM_STREAM_HELPER_EVENT').'</div>';
	    }
        
		JXModule::addBuffer('right', $this->getUpcomingHotEvent() );
		// Show calendar
		$now = new JDate();
		StreamFactory::load('helpers'.DS.'calendar');
		$html .='<div id="stream-calendar">'.StreamCalendarHelper::generate_calendar($now->format('Y'), $now->format('m')) .'</div>';
		
		echo $html;
		//echo $this->getStreamDataHTML();
	}

	function show()
	{
		$tmpl = new StreamTemplate();
		$html = $tmpl->fetch( 'events.list' );
		return $html;
	}
	
	/**
	 * Keyword-based search
	 */	 	
	public function search()
	{
		
	}
	
	/**
	 *
	 */	 	
	public function getStreamDataHTML()
	{
		jimport('joomla.html.pagination');
		$jconfig = new JConfig();
		$filter = array();
		if( $mention = JRequest::getVar('mention', '') ){
			$filter['mention'] = '@'.$mention;
		}
		
		if( $search = JRequest::getVar('search', '') ){
			$filter['search'] = $search;
			exit;
		}
		
		$filter['type'] = 'event';
		/*
		echo '<h2>Q1 2012</h2>';
		echo '<img width="156" src="'.JURI::root().'/components/com_stream/assets/images/calendar.png" />';
		echo '<img width="156" src="'.JURI::root().'/components/com_stream/assets/images/calendar.png" />';
		echo '<img width="156" src="'.JURI::root().'/components/com_stream/assets/images/calendar.png" />';
		*/
		$tmpl	= new StreamTemplate();
		$model	= StreamFactory::getModel('stream');
		$data	= $model->getStream( $filter );
		$total	= $model->countStream( $filter );
		
		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jconfig->list_limit);
		
		$tmpl	= new StreamTemplate();
		$html = $tmpl->fetch('stream.filter');
		
		$tmpl	= new StreamTemplate();
		$tmpl->set('rows', $data);
		$tmpl->set('total', $total);
		$tmpl->set('pagination', $pagination);
		$html .= $tmpl->fetch('stream.data');
		
		return $html;
	}

	public function getUpcomingHTML($filter = null, $limit = 6)
	{
		if($filter == null) {
			$filter = array();
		}
		
		/*
		// Filter event up to 24 hours earlier
		$date = new JDate();
		$date->setOffset(-24);
					
		$filter['type']	= 'event';
		$filter['start_date'] = $date->toMySQL();
		$filter['order_by_asc'] = 'start_date';
		                                    
		$model	= StreamFactory::getModel('stream');
		$data	= $model->getStream( $filter, $limit );
		*/
		
		$group_id = ( !empty($filter['group_id'])) ? $filter['group_id'] : null;
		$group = JTable::getInstance('group', 'StreamTable');
		
		$model = StreamFactory::getModel('events');
		$data = $model->getPending($group_id, 10);
		
		$my = JXFactory::getUser();
		$count = 0;
		$pendingEvent = array();
		
		foreach($data as $index => $event)
		{
			if ($count >= 6)
			{
				break;
			}
			
			$group->load($event->group_id);
			if (!$my->authorise('stream.group.read', $group) && $event->group_id > 0)
			{
				continue;
			}
			
			$pendingEvent[] = $event;
			$count++;
		}
		
		$tmpl = new StreamTemplate();
		$tmpl->set('events', $pendingEvent);
		$tmpl->set('title', JText::_('COM_STREAM_LABEL_UPCOMING_EVENTS'));
		$html = $tmpl->fetch('group.module.eventlist');
		return $html;
	}
	
	public function getUpcomingHotEvent($filter = null, $limit = 6)
	{
		if($filter == null) {
			$filter = array();
		}
		
		// Filter event up to 24 hours earlier
		$date = new JDate();
		$date->setOffset(-24);
					
		$filter['type']	= 'event';
		$filter['start_date'] = $date->toMySQL();
		$filter['order_by'] = 'length(`followers`) DESC';
		                                    
		$model	= StreamFactory::getModel('stream');
		$data	= $model->getStream( $filter, $limit );
		$group = JTable::getInstance('group', 'StreamTable');
		
		$my = JXFactory::getUser();
		$count = 0;
		$pendingEvent = array();
		
		foreach($data as $index => $event)
		{
			if ($count >= 6)
			{
				break;
			}
			
			$group->load($event->group_id);
			if (!$my->authorise('stream.group.read', $group))
			{
				continue;
			}
			
			$pendingEvent[] = $event;
			$count++;
		}
		
		$tmpl = new StreamTemplate();
		$tmpl->set('events', $pendingEvent);
		$tmpl->set('title', JText::_('COM_STREAM_LABEL_POPULAR_EVENTS'));
		$html = $tmpl->fetch('group.module.eventlist');
		return $html;
	}

	public function getGroupEventHTML($filter = null)
	{
		jimport('joomla.html.pagination');

		$jconfig = new JConfig();

		if($filter == null) {
			$filter = array();
		}

		$group_id = ( !empty($filter['group_id'])) ? $filter['group_id'] : null;
		$filter['type']	= 'event';
		$filter['order_by_desc'] = 'start_date';
		
		$filterStatus = JRequest::getVar('status', 'upcoming');
		if ($filterStatus == 'upcoming')
		{
			$filter['event_occurring_date'] = date('Y-m-d');
		}
		elseif ($filterStatus == 'past')
		{
			$filter['event_past_date'] = date('Y-m-d');
		}
		
		
		// Filter by "by/creator"
		$by = JRequest::getVar('by', '');
		if( $by == 'mine' ){
			$my = JXFactory::getUser();
			$title = JText::sprintf("%1s's files", $my->name);
			$filter['user_id'] = $my->id;
		}		
		// Filter by user_id (cannot be used along with 'by' filter)
		else if( $user_id = JRequest::getVar('user_id', '') )
		{			
			$user = JXFactory::getUser($user_id);
			$title = JText::sprintf("%1s's events", $user->name);
			$filter['user_id'] = $user->id;
		}

		$eventsModel = StreamFactory::getModel('stream');
		$events = $eventsModel->getStream($filter, $jconfig->list_limit, JRequest::getVar('limitstart', 0));
		$total = $eventsModel->countStream( $filter );

		$tempArray = array();
		$eventsDue = array();
		$now = new JDate();

		// Sort events ASC while past events as DESC. Could have utilized the db's UNION ALL?
		foreach($events as $key=>$event) {
			$startDate = new JDate($event->start_date);
			$dateDiff = JXDate::timeDifference($startDate->toUnix(), $now->toUnix());
			if(!empty($dateDiff['days']) && ($dateDiff['days'] < 0)) {
				// Store the events in a temporary array and then remove it from the main events
				$tempArray[] = $event;
				unset($events[$key]);
				$eventsDue[$key] = 1;
			} else {
				$eventsDue[$key] = 0;
			}
		}

		// Sort the moved events. Anonymous functions supported in 5.3 only
		usort($tempArray, function($a, $b)
			{
				return strcmp($a->start_date, $b->start_date);
			}
		);

		// put it back in the main events
		$events = $tempArray + $events;

		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jconfig->list_limit);

		$tmpl = new StreamTemplate();
		$tmpl->set('events', $events);
		$tmpl->set('eventsDue', $eventsDue);
		$tmpl->set('pagination', $pagination)
			->set('showOwnerFilter', (JRequest::getVar('user_id', 0) == 0 ))
			->set('filterStatus', $filterStatus);
		$html = $tmpl->fetch('event.header');
		$html .= $tmpl->fetch('events.list');

		return $html;
	}
}