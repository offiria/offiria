<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright 	Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class JXDate extends JDate
{
	const LONG_DATE_FORMAT = 'long';
	const SHORT_DATE_FORMAT = 'short';
	
	static public function formatLapse($date)
	{
		$now = new JDate();
		$dateDiff = JXDate::timeDifference($date->toUnix(), $now->toUnix());

		if( $dateDiff['days'] > 0){
			if ($dateDiff['days'] < 30){
				$lapse = JText::sprintf( (JXString::isPlural($dateDiff['days'])) ? 'JXLIB_LAPSED_DAY_MANY':'JXLIB_LAPSED_DAY', $dateDiff['days']);
			}else{
				$lapse = self::formatDate($date, false);
			}
		}elseif( $dateDiff['hours'] > 0){
			$lapse = JText::sprintf( (JXString::isPlural($dateDiff['hours'])) ? 'JXLIB_LAPSED_HOUR_MANY':'JXLIB_LAPSED_HOUR', $dateDiff['hours']);
		}elseif( $dateDiff['minutes'] > 0){
			$lapse = JText::sprintf( (JXString::isPlural($dateDiff['minutes'])) ? 'JXLIB_LAPSED_MINUTE_MANY':'JXLIB_LAPSED_MINUTE', $dateDiff['minutes']);
		}else {
			if( $dateDiff['seconds'] == 0){
				$lapse = JText::_("JXLIB_LAPSED_MOMENT_AGO");
			}else{
				$lapse = JText::sprintf( (JXString::isPlural($dateDiff['seconds'])) ? 'JXLIB_LAPSED_SECOND_MANY':'JXLIB_LAPSED_SECOND', $dateDiff['seconds']);
			}	
		}

		return $lapse;
	}

	static public function timeDifference( $start , $end )
	{
		jimport('joomla.utilities.date');
		
		if(is_string($start) && ($start != intval($start))){
			$start = new JDate($start);
			$start = $start->toUnix();
		}
		
		if(is_string($end) && ($end != intval($end) )){
			$end = new JDate($end);
			$end = $end->toUnix();
		}

		$uts = array();
	    $uts['start']      =    $start ;
	    $uts['end']        =    $end ;
	    if( $uts['start']!==-1 && $uts['end']!==-1 )
	    {
	        //if( $uts['end'] >= $uts['start'] )
	        {
	            $diff    =    $uts['end'] - $uts['start'];
	            if( $days=intval((floor($diff/86400))) )
	                $diff = $diff % 86400;
	            if( $hours=intval((floor($diff/3600))) )
	                $diff = $diff % 3600;
	            if( $minutes=intval((floor($diff/60))) )
	                $diff = $diff % 60;
	            $diff    =    intval( $diff );            
	            return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
	        }
	        //else
	        //{
	        //    trigger_error( JText::_("JXLIB_TIME_IS_EARLIER_THAN_START_WARNING"), E_USER_WARNING );
	        //}
	    }
	    else
	    {
	        trigger_error( JText::_("JXLIB_INVALID_DATETIME"), E_USER_WARNING );
	    }
	    return( false );
	}
	
	/**
	 * Format the date without user timezone
	 */	 	
	static function formatRawDate($date, $long = self::LONG_DATE_FORMAT)
	{
		if ($date instanceof JDate)
		{
			$date = $date->format('Y-m-d h:i:s');
		}
		
		$date = new JDate($date);
		return ($long == self::LONG_DATE_FORMAT) ? $date->format(JText::_('JXLIB_DATE_FORMAT')) : $date->format(JText::_('JXLIB_DATE_SHORT_FORMAT'));
	}
	
	/**
	 * Return standardized formatted date
	 * NOTE: FOR TEMPLATE DISPLAY PURPOSE ONLY!!! DO NOT USE FOR DB QUERIES
	 * Also use only for server generated time, NOT	 
	 */	 	
	static function formatDate($date, $long = self::LONG_DATE_FORMAT, $user = null)
	{
		// return original input date that cannot be processed
		if (strtotime($date) === false)
		{
			return $date;
		}
		
		$config			= new JXConfig();
		
		// First load account setting (if any) timezone to override timezone in language file
		$defaultTz		= ($config->getTimezone() != '') ? $config->getTimezone() : JText::_('JXLIB_DEFAULT_TIMEZONE');
		$my				= (!($user instanceof JUser) && !($user instanceof JXUser)) ? JXFactory::getUser() : $user;
		$timeZoneStr	= $my->getParam('timezone');
		
		// Second load user personalize timezone (if any) to override system default timezone
		$timeZoneStr	= (empty($timeZoneStr)) ? $defaultTz : $timeZoneStr;
		$tz				= new DateTimeZone($timeZoneStr);		
		
		if ($date instanceof JDate)
		{
			$date = $date->format('Y-m-d h:i:s');
		}
		
		$datenow = new JDate('now', $tz);
		$offset = $datenow->getOffset() / 3600;	
		$date = new JDate($date);
		$date->setOffset($offset);
		
		$dateStr = ($long == self::LONG_DATE_FORMAT) ? $date->format(JText::_('JXLIB_DATE_FORMAT'), true) : $date->format(JText::_('JXLIB_DATE_SHORT_FORMAT'), true);
	
		if($long == self::LONG_DATE_FORMAT){
			// Test for today
			$dmy = $datenow->format(JText::_('JXLIB_DATE_DMY'), true);
			$dateStr = str_replace($dmy, JText::_('JXLIB_DATE_TODAY'), $dateStr);
			
			// Test for yesterday
			$datenow->modify('-1 day');
			$dmy = $datenow->format(JText::_('JXLIB_DATE_DMY'), true);
			$dateStr = str_replace($dmy, JText::_('JXLIB_DATE_YESTERDAY'), $dateStr);
		}
		return $dateStr;
	}
	
	/**
	 * An event yesterday or earlier
	 */	 	
	public function isOverdue($date)
	{ 
		if($this->format('Y') > $date->format('Y')){
			return true;
		}
		
		return ($this->format('z') > $date->format('z')  );
	}
	
	public function isToday($jdate)
	{
		// same year, month, day
		return ($this->format('d M Y') == $jdate->format('d M Y'));
	}
	
	public function isThisWeek($jdate)
	{
		
		return ($this->format('W') == $jdate->format('W'));
	}
}