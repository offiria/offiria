<?php
/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright 	Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */

defined('JPATH_PLATFORM') or die;

/*
CREATE TABLE IF NOT EXISTS `fk280_analytics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `action` varchar(64) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `year` smallint(5) unsigned NOT NULL,
  `month` tinyint(3) unsigned NOT NULL,
  `week` tinyint(3) unsigned NOT NULL,
  `day` tinyint(3) unsigned NOT NULL,
  `hour` tinyint(3) unsigned NOT NULL,
  `ip` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`),
  KEY `year_3` (`year`,`month`),
  KEY `year_2` (`year`,`month`,`day`),
  KEY `year_6` (`year`,`month`,`day`,`hour`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
*/
class AnalyticsTable extends JTable
{
	var $id = null;
	var $action = null;
	var $user_id = null;
	var $group_id = null;
	var $created = null;
	var $year = null;
	var $month = null;
	var $week = null;
	var $day = null;
	var $hour = null;
	var $ip = null;
	
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__analytics', 'id', $db );
	}

}

class JAnalytics 
{
	static protected $groupType = array('hour', 'day', 'week', 'month', 'year');
	
	public static function log($action, $user_id = null, $group_id = 0)
	{
		// exclude known, unwanted logging
		$exclude_actions = array('system.notification', 'system.cron');
		if(in_array($action, $exclude_actions)){
			return;
		}
		
		$table = JTable::getInstance('', 'AnalyticsTable');
		$table->action		= $action;
		$table->user_id 	= $user_id;
		$table->group_id	= $group_id;
		
		$date = new JDate();
		$table->created = $date->toMySQL();
		$table->year 	= $date->format('Y');
		$table->month 	= $date->format('n');
		$table->week 	= $date->format('W');
		$table->day 	= $date->format('j');
		$table->hour 	= $date->format('G');
		
		$table->store();
	}
	
	/**
	 *
	 * $range array('month' => 2)	 
	 */	 	
	public static function get($actions, $user_id, $group_id, $range, $group_by)
	{		
		if ($group_by == 'hour')
		{			
			// todo: db datetime is server utc, might need to calculate for offset
			$config			= new JXConfig();
			$defaultTz		= ($config->getTimezone() != '') ? $config->getTimezone() : JText::_('JXLIB_DEFAULT_TIMEZONE');
			$my				= JXFactory::getUser($user_id);
			$myTimeZone		= $my->getParam('timezone');

			// Second load user personalize timezone (if any) to override system default timezone
			$timeZoneStr	= (empty($myTimeZone)) ? $defaultTz : $myTimeZone;
			$tz				= new DateTimeZone($timeZoneStr);
			$date2			= new JDate('now', $tz);
			$offset			= $date2->getOffset() / 3600;
		}
		else
		{
			$offset = 0;
		}
		
		$result = self::getDbRecord($actions, $user_id, $group_id, $range, $group_by, $offset);
		
		return self::formulateData($result, $group_by, $offset);
	}
	
	public static function getDbRecord($actions, $user_id, $group_id, $range, $group_by, $offset = 0)
	{		
		$db = JFactory::getDbo();
		
		$where = array('1 = 1');
		
		// Actions
		if(!empty($actions)){
			$actions = "'". implode("','", $actions). "'";
			$where[] = $db->nameQuote('action') ." IN (". $actions .") ";
		}
		
		if(!is_null($user_id))
			$where[] = $db->nameQuote('user_id') ." = ". $db->Quote($user_id);
		
		if(!is_null($group_id))
			$where[] = $db->nameQuote('group_id') ." = ". $db->Quote($group_id);
			
		$additionalCondition = self::getGroupBy($group_by, $offset);
		
		// Range
		//$where[] = ' `created` BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW() ';
		if (!empty($additionalCondition['WHERE']))
		{
			$where[] = $additionalCondition['WHERE'];
		}
					
		$sql = 'SELECT *, count(id) as count FROM #__analytics ' 
			 . ' WHERE ' .implode(' AND ', $where )
			 .' '. $additionalCondition['GROUP_BY'];
		//echo $sql;
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		
		return $result;
	}
	
	public static function formulateData($result, $group_by = 'hour', $offset = 0)
	{
		switch ($group_by)
		{
			case 'hour':
				
				$chartData = array();
				$now = new JDate();

				// Placeholder
				for ($i = 0; $i < 24; $i++)
				{
					$chartData[$i] = 0;
				}

				foreach($result as $row){
					$offsetTime = $row->hour + $offset;
					$chartData[$offsetTime] = intval($row->count);
				}

				$chartDataFinal = array();
				foreach($chartData as $row)
				{
					$chartDataFinal[] = intval($row);
				}

				$chartData = $chartDataFinal;
				break;
			case 'day':
				$chartData = array();
				$now = new JDate();

				// Placeholder
				for($i = 14; $i >= 0; $i--)
				{
					$now2 = new JDate($now->toUnix() - (86400*$i));
					$chartData[ $now2->format('Y-n-j', true)] = 0;
				}

				foreach($result as $row){
					$d = new JDate($row->created);
					$chartData[$d->format('Y-n-j')] = intval($row->count);
				}

				$chartDataFinal = array();
				foreach($chartData as $row)
				{
					$chartDataFinal[] = intval($row);
				}

				$chartData = $chartDataFinal;
				break;
			
			case 'week':
				$chartData = array();
				$now = new JDate();
				$currentWeek = $now->format('W');
				if ($currentWeek <= 5)
				{
					$startWeek = 1;
					$endWeek = 11;
				}
				else
				{
					$startWeek = $currentWeek - 5;
					$endWeek = $currentWeek + 5;
				}
				// Placeholder
				for($i = $startWeek; $i <= $endWeek; $i++)
				{
					$chartData[$i] = 0;
				}

				foreach($result as $row){
					$chartData[$row->week] = intval($row->count);
				}

				$chartDataFinal = array();
				foreach($chartData as $row)
				{
					$chartDataFinal[] = intval($row);
				}

				$chartData = $chartDataFinal;
				break;
			case 'month':				
				$now = new JDate();
				$currentMonth = $now->format('n');
				$currentYear = $now->format('Y');
				for($i = 12; $i >= 0; $i--)
				{
					if ($currentMonth - $i <= 0)
					{
						$month = $currentMonth - $i + 12;
						$year = $currentYear - 1;
					}
					else
					{
						$month = $currentMonth - $i;
						$year = $currentYear;
					}
					$time = mktime(0,0,0, $month, 1, $year);
					$now2 = new JDate($time);
					$chartData[$now2->format('n-Y', true)] =  0;
				}

				foreach($result as $row){
					$chartData[$row->month.'-'.$row->year] = intval($row->count);
				}

				$chartDataFinal = array();
				foreach($chartData as $row)
				{
					$chartDataFinal[] = intval($row);
				}

				$chartData = $chartDataFinal;
				break;	
			
			case 'year':				
				$now = new JDate();
				$currentYear = $now->format('Y');
				for ($i = 2008; $i <= $currentYear; $i++)
				{
					$chartData[$i] = 0;
				}
				
				foreach($result as $row){
					$chartData[$row->year] = intval($row->count);
				}

				$chartDataFinal = array();
				foreach($chartData as $row)
				{
					$chartDataFinal[] = intval($row);
				}

				$chartData = $chartDataFinal;
				break;	
		}

		return $chartData;
	}
	
	public static function getGroupBy($group_by, $offset = 0)
	{
		$db = JFactory::getDbo();
		
		switch ($group_by)
		{
			case 'hour':
				$now = (mktime(0,0,0,date('m'), date('d'), date('Y')) - $offset * 3600);
				$now = new JDate($now);				
				// have to group also the year, month and day to get the precise hour in a particular day
				// sql record should only look for records in a day
				$condition['WHERE'] = ' `created` BETWEEN '.$db->quote($now->format('Y-m-d H:i:s', true)).' AND DATE_ADD('.$db->quote($now->format('Y-m-d H:i:s', true)).', INTERVAL + 86399 SECOND) ';
				$condition['GROUP_BY'] = ' GROUP BY `year`, `month`, `day`, `hour` ';
				break;
			case 'day':
				// have to group also the year and month to get the precise day in the month
				// sql record should only look for records in 14 days
				$condition['WHERE'] = ' `created` BETWEEN DATE_SUB(NOW(), INTERVAL 14 DAY) AND NOW() ';
				$condition['GROUP_BY'] = ' GROUP BY `year`, `month`, `day` ';
				break;
			case 'week':
				// have to group also the year to get the precise month in the year
				// sql record should only look for records in a year
				$condition['WHERE'] = ' `year` = '.date('Y');
				$condition['GROUP_BY'] = ' GROUP BY `week` ';
				break;
			case 'month':
				// have to group also the year to get the precise month in the year
				// sql record should only look for records in a year
				$condition['WHERE'] = ' `created` BETWEEN DATE_SUB(NOW(), INTERVAL 12 MONTH) AND NOW() ';
				$condition['GROUP_BY'] = ' GROUP BY `year`, `month` ';
				break;
			case 'year':
				// just grouping itself
				// sql can omit the date between
				$condition['WHERE'] = '';
				$condition['GROUP_BY'] = ' GROUP BY `year` ';
				break;
			case 'active_user':				
				// Add only for the current month
				$timeStart = gmmktime(0, 0, 0, date('m'), 1, date('Y'));
				$dateStart = new JDate($timeStart);
				$condition['WHERE'] = ' `created` BETWEEN '.$db->quote($dateStart->toMySQL()).' AND DATE_ADD('.$db->quote($dateStart->toMySQL()).', INTERVAL 1 MONTH) ';
				$condition['WHERE'] .= ' AND user_id <> (SELECT `id` FROM #__users WHERE `username` = '.$db->quote('admin').' LIMIT 1) ';
				$condition['GROUP_BY'] = ' GROUP BY `user_id` ORDER BY `count` DESC LIMIT 0, 20';
				break;	
			case 'active_group':		
				// Add only for the current month
				$timeStart = gmmktime(0, 0, 0, date('m'), 1, date('Y'));
				$dateStart = new JDate($timeStart);
				$condition['WHERE'] = '`created` BETWEEN '.$db->quote($dateStart->toMySQL()).' AND DATE_ADD('.$db->quote($dateStart->toMySQL()).', INTERVAL 1 MONTH)';
				$condition['GROUP_BY'] = ' GROUP BY `group_id` ORDER BY `count` DESC LIMIT 0, 20';
				break;	
			default:
				$condition['WHERE'] = '';
				break;	
		}
		
		return $condition;
	}	
	
	public static function getXAxisCategory($group_by)
	{
		switch ($group_by)
		{
			case 'hour':
				for ($i = 0; $i < 24; $i++)
				{
					$category[] = str_pad($i, 2, '0', STR_PAD_LEFT);
				}
				break;
			case 'day':
				$now = new JDate();
				for($i = 14; $i >= 0; $i--)
				{
					$now2 = new JDate($now->toUnix() - (86400*$i));
					$category[] =  $now2->format('d', true);
				}
				break;
			case 'week':
				$now = new JDate();
				$currentWeek = $now->format('W');
				if ($currentWeek <= 5)
				{
					$startWeek = 1;
					$endWeek = 11;
				}
				else
				{
					$startWeek = $currentWeek - 5;
					$endWeek = $currentWeek + 5;
				}
				for ($i = $startWeek; $i <= $endWeek; $i++)
				{
					$category[] = $i;
				}
				break;
			case 'month':
				$now = new JDate();
				$currentMonth = $now->format('n');
				$currentYear = $now->format('Y');
				for($i = 12; $i >= 0; $i--)
				{
					if ($currentMonth - $i <= 0)
					{
						$month = $currentMonth - $i + 12;
						$year = $currentYear - 1;
					}
					else
					{
						$month = $currentMonth - $i;
						$year = $currentYear;
					}
					$time = mktime(0,0,0, $month, 1, $year);
					$now2 = new JDate($time);
					$category[] =  $now2->format('M\'y', true);
				}
				break;
			case 'year':
				$now = new JDate();
				$currentYear = $now->format('Y');
				for ($i = 2008; $i <= $currentYear; $i++)
				{
					$category[] = $i;
				}
				break;
			default:
				$category = array();
				break;			
		}
		
		return $category;
	}
	
	public static function getGroupType()
	{
		return self::$groupType;
	}
}
