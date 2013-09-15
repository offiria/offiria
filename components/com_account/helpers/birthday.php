<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */
 
// No direct access
defined('_JEXEC') or die;


class AccountBirthdayHelper
{
	/**
	 * Check user access
	 * 	 	 
	 */	 	
	public function getBirthdayMembest() {
		$birthdaymember = array();
		$today = getdate();
		
		$db = JFactory::getDbo();
			$query = 'SELECT u.username, u.name, ud.value FROM ' . $db->nameQuote('#__users') . ' u' .
				' LEFT JOIN ' . $db->nameQuote('#__user_details') . ' ud ON ud.user_id = u.id' .
				' WHERE u.block = 0 AND ud.field = "personal_birthday"';	
		$db->setQuery($query);
		$result = $db->loadObjectList();

		foreach ($result as $key => $value) {
			// [{"personal_birthday_date":"26.11.1978","personal_birthday_age_public":"Public"}]
			$data = json_decode($value->value, true);
			
			foreach($data as $elementKey=>$elementValue) {
				// To-do: after the proper format has been applied in profile edit field;
				$mday = substr($elementValue["personal_birthday_date"],0,2);
				$month = substr($elementValue["personal_birthday_date"],3,2);
				if ($elementValue["personal_birthday_age_public"] == 'Public' && $mday == $today['mday'] && $month == $today['mon']) {
					$birthdaymember[$value->username] = $value->name;
				}
			}
		}
		return $birthdaymember;
	}
}