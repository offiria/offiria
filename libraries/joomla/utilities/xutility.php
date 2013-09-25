<?php
/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright 	Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */

defined('JPATH_PLATFORM') or die;

/**
 * JUtility is a utility functions class
 *
 * @package     Joomla.Platform
 * @subpackage  Utilities
 * @since       11.1
 */
class JXUtility
{
	/**
	 * Inser data into csv string
	 */	 	
	static public function csvInsert($csv, $item)
	{
		$items	=   explode( ',', trim( $csv, ',' ) );
		array_push( $items, $item );
		$items	=   array_unique( $items );
		return ltrim( implode( ',', $items ), ',' );
	}
	
	/**
	 * Remove data into csv string
	 */	 	
	static public function csvRemove($csv, $item)
	{
		$items	=   explode( ',', trim( $csv, ',' ) );
		$items = array_diff( $items, array($item ));
		return ltrim( implode( ',', $items ), ',' );
	}
	
	/**
	 * Return true if the item is part of the csv data
	 */	 	
	static public function csvExist($csv, $item)
	{
		$items	=   explode( ',', trim( $csv, ',' ) );
		return in_array( strval($item), $items );
	}
	
	/**
	 *
	 */	 	
	static public function csvCount($csv)
	{
		$csv = trim( $csv, ',' );
		
		if(empty($csv)){
			return 0;
		}
		
		$items	=   explode( ',', $csv);
		return count($items);
	}
	
	/**
	 * Merge 2 csv string
	 */	 	
	static public function csvMerge($csv1,$csv2)
	{
		$items1	=   explode( ',', trim( $csv1, ',' ) );
		$items2	=   explode( ',', trim( $csv2, ',' ) );
		$items = array_unique(array_merge($items1, $items2));
		return ltrim( implode( ',', $items ), ',' );
	}
	
	static public function csvDiff( $csv1, $csv2)
	{
		$items1	=   explode( ',', trim( $csv1, ',' ) );
		$items2	=   explode( ',', trim( $csv2, ',' ) );
		$items = array_unique(array_diff($items1, $items2));
		return ltrim( implode( ',', $items ), ',' );
	}
	
	static public function csvToArray(){
	}
}