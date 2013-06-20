<?php
/*
CREATE TABLE IF NOT EXISTS `zokb5_stream_tags_trend` (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`tag` varchar(255) NOT NULL,
	`group_id` int(11) NOT NULL,
	`frequency` int(11) NOT NULL,
	`occurrence_date` date NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
*/

defined('_JEXEC') or die('Restricted access');

class StreamTableTagTrend extends JTable
{
	var $id = null;
	var $tag = null;
	var $group_id = null;
	var $frequency = null;
	var $occurrence_date = null;

	public function __construct( &$db )	{
		parent::__construct( '#__stream_tags_trend', 'id', $db );
	}
}