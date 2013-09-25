<?php
/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright 	Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class JXString {
	
	public static function isPlural( $num )
	{
		$singularnumbers = explode(',', '0,1');
		
		return !in_array($num, $singularnumbers);
	}

	/**
	 * Return a truncated string by the number of words
	 */
	public static function truncateWords($str, $words)
	{
		if(str_word_count($str) > $words) {
			$str = trim(preg_replace('/((\w+\W*){' . $words . '}(\w+))(.*)/', '${1}', $str)) . '&hellip;';
		}

		return $str;
	}
}