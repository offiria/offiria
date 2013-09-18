<?php

/**
 * @package		Offiria
 * @subpackage		Core 
 * @copyright		(C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class StreamCategory 
{
	/* Category table */
	private $table = null;

	public function __construct() {
		$this->table = JTable::getInstance('Category', 'StreamTable');
	}

	/**
	 * Return an object of installed category
	 * @return stdClass containing category props 
	 */
	public function getByCategory($category) {
		$db = JFactory::getDbo();
		$query = 'SELECT * FROM ' . $db->nameQuote('#__stream_category') .
			'WHERE ' . $db->nameQuote('type') . "='$category'";
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	/**
	 * Return an object of installed category
	 * @return stdClass containing category props
	 * @deprecated @see getByCategory($category)
	 */
	public function getBlogs() {
		$db = JFactory::getDbo();
		$query = 'SELECT * FROM ' . $db->nameQuote('#__stream_category') .
			'WHERE ' . $db->nameQuote('type') . "='blog'";
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	/**
	 * Return an object of installed category
	 * @return stdClass containing category props 
	 * @deprecated @see getByCategory($category)
	 */
	public function getEvents() {
		$db = JFactory::getDbo();
		$query = 'SELECT * FROM ' . $db->nameQuote('#__stream_category') .
			'WHERE ' . $db->nameQuote('type') . "='event'";
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	/**
	 * Return an object of installed category
	 * @return stdClass containing category props 
	 * @deprecated @see getByCategory($category)
	 */
	public function getGroups() {
		$db = JFactory::getDbo();
		$query = 'SELECT * FROM ' . $db->nameQuote('#__stream_category') .
			'WHERE ' . $db->nameQuote('type') . "='group'";
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	/**
	 * Return an object of installed category
	 * @return stdClass containing category props 
	 * @deprecated @see getByCategory($category)
	 */
	public function getDepartment() {
		$db = JFactory::getDbo();
		$query = 'SELECT * FROM ' . $db->nameQuote('#__stream_category') .
			'WHERE ' . $db->nameQuote('type') . "='department'";
		
		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	public function countMessageByCategoryId($category, $type='') {
		/* group category is stored in another table */
		$table = ($type == 'group') ? '#__groups' : '#__stream';
		$db = JFactory::getDbo();
		$query = 'SELECT * FROM ' . $db->nameQuote($table) . ' ' .
			'WHERE ' . $db->nameQuote('category_id') . "='$category'";
		$db->setQuery($query);
		$result = $db->loadObjectList();

		return count($result);
	}

	/**
	 * Check if the category exist
	 * @param String $category name of the category
	 */
	public function isExist($category, $type) {
		$table = JTable::getInstance('Category', 'StreamTable');
		return $table->getParam('category', 
									  array('category'=>$category,
											'type'=>$type));
	}

	/**
	 * Check the value of category
	 * The category is in int value for filtering by int and not by string
	 */
	public function getCategoryName($category_id) {
		$table = JTable::getInstance('Category', 'StreamTable');
		return $table->getParam('category', 
									  array('id'=>$category_id));
	}
}