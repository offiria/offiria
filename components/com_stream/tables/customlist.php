<?php
defined('_JEXEC') or die('Restricted access');

class StreamTableCustomlist extends JTable
{
	var $id = null;
	var $title = null;
	var $user_id = null;
	var $filter = null;

	public function __construct(&$db)
	{
		parent::__construct('#__stream_customlist', 'id', $db);
	}
}