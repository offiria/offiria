<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class StreamModelEvents {
	
	const DEFAULT_DURATION_OF_EVENT = '+1 hour';
	/**
	 * Return all events/milestone pending
	 */	 	
	public function getPending($group_id = null, $limit = 20){
		$db = JFactory::getDbo();
		
		// Filter event up to 24 hours earlier
		$date = new JDate();
		$date->setOffset(-24);
					
		// Select pending event and uncompleted milestone
		$where = '(	('.$db->nameQuote('type') . '=' . $db->Quote('event')
					.' AND '.$db->nameQuote('start_date') . '>' . $db->Quote($date->toMySQL())
					.')' 
					/*
					.' OR '
					.'('.$db->nameQuote('type') . '=' . $db->Quote('milestone')
						.' AND '
						.$db->nameQuote('status') . '=' . $db->Quote('0')
					.')'
					*/
				  .')';
		
		// Filter by group id if necessary
		if($group_id){
			$where .= ' AND ' . $db->nameQuote('group_id') . '=' .$db->Quote($group_id);
		}
		
		$order_by = ' ORDER BY start_date ASC ';
		
		$query = 'SELECT * FROM #__stream WHERE '. $where. $order_by. ' LIMIT '. $limit;
		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		
		$rows = array();
		if( !empty($result)) {
			foreach( $result as $row )
			{
				$obj	= JTable::getInstance( 'Stream', 'StreamTable' );
				$obj->bind($row);
				$rows[] = $obj;
			}
		}
		
		return $rows;
	}
	
	/**
	 * Retturn list of all event that start with the given month/year
	 */	 	
	public function getMonthEvent($month, $year)
	{
		$db = JFactory::getDbo();
		
		// Filter event up to 24 hours earlier
		$date = new JDate();
		$date->setOffset(-24);
					
		// Select pending event and uncompleted milestone
		$where = '(	('.$db->nameQuote('type') . '=' . $db->Quote('event')
					.' AND '.$db->nameQuote('start_date') . '>' . $db->Quote($date->toMySQL())
					.')' 
					.' OR '
					.'('.$db->nameQuote('type') . '=' . $db->Quote('milestone')
						.' AND '
						.$db->nameQuote('status') . '=' . $db->Quote('0')
					.')'
				  .')';
		
		$where 	 = $db->nameQuote('start_date') . '>= DATE('.$db->Quote($year.'-'.$month.'-1').') ';
		$where 	.= ' AND '. $db->nameQuote('start_date') . '<= DATE_ADD( DATE('.$db->Quote($year.'-'.$month.'-1').') , INTERVAL 1 MONTH ) ';
		
		$where2 	 = $db->nameQuote('end_date') . '>= DATE('.$db->Quote($year.'-'.$month.'-1').') ';
		$where2 	.= ' AND '. $db->nameQuote('end_date') . '<= DATE_ADD( DATE('.$db->Quote($year.'-'.$month.'-1').') , INTERVAL 1 MONTH ) ';
		$where2 	.= ' AND '. $db->nameQuote('type') . '='.$db->Quote('todo');
		
		$my = JXFactory::getUser();
		$userGroups = $my->getParam('groups_member');
		
		$where  =  '( '. $where . ') AND ('. $db->nameQuote('access') ."=". $db->Quote(0) ;
			if(!empty($userGroups))
				$where .= ' OR '. $db->nameQuote('group_id') .'IN ('. $userGroups .') )';
			else
				$where .= ')';
				
		$where2 =  '( '. $where2 . ') AND ('. $db->nameQuote('access') ."=". $db->Quote(0) ;
		if(!empty($userGroups))
				$where2 .= ' OR '. $db->nameQuote('group_id') .'IN ('. $userGroups .') )';
			else
				$where2 .= ')';

		$query = 'SELECT * FROM #__stream WHERE '. $where . ' UNION SELECT * FROM #__stream WHERE '. $where2 . ' ORDER BY ' . $db->nameQuote('start_date') . ' ASC';

		$db->setQuery( $query );
		$result	= $db->loadObjectList();
		
		$rows = array();
		if( !empty($result)) {
			foreach( $result as $row )
			{
				$obj	= JTable::getInstance( 'Stream', 'StreamTable' );
				$obj->bind($row);
				$rows[] = $obj;
			}
		}
		
		return $rows;
	}
	
	/**
	 * Time creation of start and end time/date of event is flexible
	 * When the end event is not specified a default action is to create a duration of DEFAULT_DURATION_OF_EVENT of event
	 * @param DateTime $startDate date when the event is start
	 * @param DateTime $endDate date when the event is ended
	 * @return Array (mixed)
	 * 			$arr['startDate'] = JDate $startDate,
	 * 			$arr['endDate'] = JDate $endDate
	 */
	public function determinedEventDuration($startDate, $endDate) {
		/**
		 * Time Reasoning
		 * ----------
		 * Start Date and Start Time is validated with Javascript both is compulsory as the entries will be stored in actual time format
		 * In this case, if the 12:00am is the intended time, the system can determined what is expected outcome of the creation
		 * On the other hand, if the 12:00am is consider as default value or unset time, it's harder to guest their action
		 *
		 * Logic:
		 * If the Start (Date && Time) is specified but End (Date && Time) is not the value of End (Date && Time) is DEFAULT_DURATION_OF_EVENT
		 */
		$result['startDate'] = $result['startTime'] = NULL;
		$result['endDate'] = $result['endTime'] = NULL;

		/* if the time is a valid time simply return a JDate object */
		if (!empty($startDate) && strpos($startDate, '0000-00-00 00:00') === false) {
			$result['startDate'] = new JDate($startDate);
			$result['startTime'] = $result['startDate']->format(JText::_('JXLIB_TIME_SHORT_FORMAT'));
		}
		/* if the time is a valid time simply return a JDate object */
		if (!empty($endDate) && strpos($endDate, '0000-00-00 00:00') === false) {
			$result['endDate'] = new JDate($endDate);
			$result['endTime'] = $result['endDate']->format(JText::_('JXLIB_TIME_SHORT_FORMAT'));
		}
		/* Output is normal, return now */
		if (!in_array(NULL, $result)) {
			return $result;
		}

		if (!$startDate instanceof JDate) {
			$startDate = new JDate($startDate);
		}
		if (!$endDate instanceof JDate) {
			$endDate = new JDate($endDate);
		}

		/* sometime epoch date might exist with altered time */
		if (strpos($startDate, '0000-00-00 00:00') === true || preg_match('/-0001-11-30\s(\d{2}:){2}\d{2}/', $startDate)) {
			$date = new JDate();
			/* begin the date at the start of the day */
			$date->setTime(0, 0, 0);
			$result['startDate'] = $startDate = $date;
			$result['startTime'] = $startTime = $startDate->format(JText::_('JXLIB_TIME_SHORT_FORMAT'));
		}

		/**
		 * If no end of event the duration will be increased by DEFAULT_DURATION_OF_EVENT
		 * JDate can reset the date to defaulted epoch time
		 */
		if (strpos($endDate, '0000-00-00 00:00') == true || preg_match('/-0001-11-30\s(\d{2}:){2}\d{2}/', $endDate)) {
			$result['endDate'] = $endDate = $startDate->modify(self::DEFAULT_DURATION_OF_EVENT);
			$result['endTime'] = $endTime = $endDate->format(JText::_('JXLIB_TIME_SHORT_FORMAT'));
		}

		return $result;
	}

	/**
	 * To check if the event is already a passed event
	 * @param JDate $endDate the end of event to compare to
	 * @return Boolean true on passed event
	 */
	public function isEventPassed($endDate) {
		$userTime = JXUser::getUserTime();
		$now = new JDate($userTime);
		$dateDiff = JXDate::timeDifference($endDate->toUnix(), $now->toUnix());
		$eventIsDue = (!empty($dateDiff['days']) && ($dateDiff['days'] < 0)) ? false : true;
		return $eventIsDue;
	}


	private function _buildFilterConditions($filter)
	{
		$db = JFactory::getDbo();
		$my = JXFactory::getUser();
		$userGroups = $my->getParam('groups_member');

		$sql = implode(' AND ',$where);
		$sql =  '( '. $sql . ') AND ('. $db->nameQuote('access') ."=". $db->Quote(0) .' OR '. $db->nameQuote('group_id') .'IN ('. $userGroups .') )';
		return $sql;
	}
}