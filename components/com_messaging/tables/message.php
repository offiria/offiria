<?php
defined('_JEXEC') or die('Restricted access');

/*
CREATE TABLE IF NOT EXISTS `[PREFIX]_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` int(11) NOT NULL,
  `parent` int(11) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  `from_name` varchar(45) NOT NULL,
  `posted_on` datetime NOT NULL,
  `subject` tinytext NOT NULL,
  `body` text NOT NULL,
  `attachment` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
*/

class MessagingTableMessage extends JTable
{
	var $id 		= null;
	var $from 		= null;
	var $parent		= null;
	var $deleted	= null;
	var $from_name	= null;
	var $posted_on	= null;
	var $subject	= null;
	var $body		= null;
	var $attachment = null;

	public function __construct( &$db )
	{
		parent::__construct( '#__msg', 'id', $db );
	}
}