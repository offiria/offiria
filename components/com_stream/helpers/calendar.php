<?php
/**
 * @category	Helper
 * @package		Offiria
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
Class StreamCalendarHelper
{
	static public function generate_calendar($year, $month, $days = array(), $day_name_length = 3, $month_href = NULL, $first_day = 0, $pn = array()){
		$first_of_month = gmmktime(0,0,0,$month,1,$year);
		// remember that mktime will automatically correct if invalid dates are entered
		//  for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
		//  this provides a built in "rounding" feature to generate_calendar()
		
		//highlight date
		$model	= StreamFactory::getModel( 'events' );
		//$highlight_day = array(); //$model->getMonthlyEvents($month,$year);
		$events = $model->getMonthEvent($month, $year);
		$temp_day = array();
		$eventList = array();
		$mstoneList = array();
		$todoList = array();
		
		foreach($events as $event){
			$date = new JDate($event->start_date);
			$tday = ltrim($date->toFormat('%d'), '0'); 
			$temp_day[$tday] = $tday;
			
			switch($event->type){
			case 'todo':
				
				if(!isset($todoList[$tday])){
					$todoList[$tday] = array();
				}
				
				$todoList[$tday][] = $event;
				
				break;
			case 'event':
				if(!isset($eventList[$tday])){
					$eventList[$tday] = array();
				}
				
				$eventList[$tday][] = $event;
				break;
			case 'milestone':
				if(!isset($mstoneList[$tday])){
					$mstoneList[$tday] = array();
				}
				
				$mstoneList[$tday][] = $event;
			}
		}
		
		$highlight_day = $temp_day;
		//$highlight_day = array();
		
		$pmonth = ($month > 1) ? $month -1 : 12;
		$pyear	= ($pmonth != 12) ? $year : $year -1;
		
		$nmonth = ($month == 12) ? 1 : $month + 1;
		$nyear	= ($nmonth != 1) ? $year : $year +1;
		
		$day_names = array(); // generate all the day names according to the current locale
		for($n=0,$t=(3+$first_day)*86400; $n<7; $n++,$t+=86400){ // January 4, 1970 was a Sunday
			$dayDate = new JDate($t);
			$day_names[$n] = $dayDate->toFormat('%a', false); // %A means full textual day name
		}

		list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
		$weekday = ($weekday + 7 - $first_day) % 7; // adjust for $first_day
		$title   = htmlentities(ucfirst($month_name)).'&nbsp;'.$year;  /// note that some locales don't capitalize month and day names

		// Begin calendar. Uses a real <caption>. See http://diveintomark.org/archives/2002/07/03
		@list($p, $pl) = each($pn); 
		@list($n, $nl) = each($pn); // previous and next links, if applicable
		$p = '<span class="calendar-prev"><a href="#" onclick="return S.event.updateCalendar('.$pmonth.','.$pyear.');">&lt;</a></span><div class="month-title">';
		$n = '</div><span class="calendar-next"><a href="#" onclick="return S.event.updateCalendar('.$nmonth.','.$nyear.');">&gt;</a></span>';
		$calendar = '<table class="table calendar">'.
			'<tr><td colspan="7"><div class="calendar-month">'.$p.($month_href ? '<a href="'.htmlspecialchars($month_href).'">'.$title.'</a>' : $title).$n.'</div></td></tr><tr>';


		if($day_name_length){ // if the day names should be shown ($day_name_length > 0)
			// if day_name_length is >3, the full name of the day will be printed
			foreach($day_names as $d){
				$day_name = mb_substr($d,0,$day_name_length,'UTF-8');
				$calendar .= '<th abbr="'.htmlentities($d).'">'.$day_name.'</th>';
			}
			$calendar .= "</tr>\n<tr>";
		}

		if($weekday > 0) $calendar .= '<td colspan="'.$weekday.'">&nbsp;</td>'; // initial 'empty' days
		for($day=1,$days_in_month=gmdate('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){
			//check if day is in the highlighted list
			$class = '';
			if(in_array((string)$day,$highlight_day)){
				$class = ' class="running"';
			}
			
			if($weekday == 7){
				$weekday   = 0; // start a new week
				$calendar .= "</tr>\n<tr>";
			}
			
			if(isset($days[$day]) and is_array($days[$day])){
				@list($link, $classes, $content) = $days[$day];
				if(is_null($content))  $content  = $day;
				$calendar .= '<td'.($classes ? ' class="'.htmlspecialchars($classes).'">' : '>').
					($link ? '<a href="'.htmlspecialchars($link).'">'.$content.'</a>' : $content).'</td>';
			}
			else {
				
				
				$calContent = '';
				$tmpl = new StreamTemplate();
				$tmpl->set('eventList', $eventList);
				$tmpl->set('mstoneList', $mstoneList);
				$tmpl->set('todoList', $todoList);
				
				$tmpl->set('day', $day);
				$popOverContent = $tmpl->fetch('calendar.popover');
				
				if(array_key_exists($day, $eventList)){
					$calContent .= '<span class="calendar-event">'.count($eventList[$day]). ' ' . JText::_('COM_STREAM_CALENDAR_LABEL_EVENT') .'</span>';
				}
				
				if(array_key_exists($day, $mstoneList)){
					/* mstoneList is an array of JTable object  */
					$mstone = $mstoneList[$day][0]; // since we need to access data; we need to get into the first object; 

					/* get the time based on user */
					$xuser = new JXUser();
					$time = $xuser->getUserTime();

					// this is a simple check to append styling class if the milestone already passed
					$milestonePassed = (strtotime($mstone->start_date) < strtotime($time)) ? 'calendar-past' : 'calendar-mstone';
					$calContent .= '<span class="' . $milestonePassed . '">'. count($mstoneList[$day]). ' ' .JText::_('COM_STREAM_CALENDAR_LABEL_MILESTONE') .'</span>';
				}
				
				if(array_key_exists($day, $todoList)){
					$calContent .= '<span class="calendar-todo">'. count($todoList[$day]). ' task</span>';
				}
				
				if(!empty($popOverContent)){
					$popOverContent = '<ul class="popover-black">'.$popOverContent.'</ul>';
				}
				
				$calendar .= "<td $class data-content=\"".StreamTemplate::escape($popOverContent)."\">$day";
				$calendar .= $calContent. "</td>";
			}
		}
		if($weekday != 7) $calendar .= '<td colspan="'.(7-$weekday).'">&nbsp;</td>'; // remaining "empty" days

		return $calendar."</tr>\n</table>\n<input class='cal-month-year' type='hidden' value='".$year.";".$month."'/>";
	}

}