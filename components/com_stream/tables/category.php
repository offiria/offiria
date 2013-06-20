<?php

/*
CREATE TABLE IF NOT EXISTS `[prefix]_stream_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
*/

class StreamTableCategory extends JTable
{
	var $id = null;
	var $category = null;
	var $type = null;

	protected $_params = null;
	
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__stream_category', 'id', $db );
	}

	/**
	 * Create a unique new category 
	 * @param String $category the name of the category
	 */
	public function create($category, $type) {
		// don't duplicate
		if ($this->_getParam('category', 
							 array('category'=>$category,
								   'type'=>$type))) {
			return false;
		}

		$this->category = $category;
		$this->type = $type;
		return $this->store();
	}

	/**
	 * Remove existing category
	 * @param mixed can be the name of the category or id of the category
	 */
	public function remove($category) {
		return $this->delete($category);
	}

	/**
	 * Assign a stream to be associated with category
	 * @default is 'Uncategorized';
	 * @param mixed category is the identifier of the category
	 * @param int $stream_id is the id of the stream to be assigned to
	 */
	public function assign($category, $stream_id) {
		$table = $this->load(array('category'=>$category));
		$this->id = $table->id;
		$this->stream_id = JXUtility::csvInsert($table->stream_id, $stream_id);
		return $this->store();
	}

	/**
	 * Standardize method to retrieve categoryId
	 * @param String $category is the category name
	 */
	public function getCategoryId($category) {
		return $this->_getParam('id', array('category'=>$category));
	}

	/**
	 * Standardize method to retrieve category type
	 * @param String $category is the category name
	 */
	public function getCategoryTypeById($id) {
		return $this->_getParam('type', array('id'=>$id));
	}

	/**
	 * Standardize method to retrieve category
	 * @param String $category is the category name
	 */
	public function getCategoryNameById($id) {
		return $this->_getParam('category', array('id'=>$id));
	}

	/**
	 * Return the param requested
	 * @param String $field field to retrieve 
	 * @param Array $condition condition for the array to load
	 * @return String as requested on success and false on empty
	 */
	public function getParam($field, $condition=null) {
		return $this->_getParam($field, $condition);
	}

	/**
	 * Return the param requested
	 * @param String $field field to retrieve 
	 * @param Array $condition condition for the array to load
	 * @return String as requested on success and false on empty
	 */
	private function _getParam($field, $condition=null) {
		if (is_array($condition)) {
			$this->load($condition);
		}
		else {
			$this->load();
		}
		if ($this->{$field} == null) {
			return false;
		}
		return $this->{$field};
	}
}

