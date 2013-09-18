<?php
/**
 * @category	Tables
 * @package		Offiria
 * @subpackage	Activities 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

/*
CREATE TABLE IF NOT EXISTS `cxh65_user_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `field` varchar(35) CHARACTER SET utf8 NOT NULL,
  `value` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 ;
 */
 
class ProfileTableDetailUser extends JTable
{
	var $id = null;
	var $user_id = null;
	var $field = null;
	var $value = null;

	public function __construct( &$db )
	{
		parent::__construct( '#__user_details', 'id', $db );
	}
}
