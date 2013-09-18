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
CREATE TABLE IF NOT EXISTS `jos_users_invite` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_email` varchar(100) NOT NULL,
  `invite_email` varchar(100) NOT NULL,
  `status` tinyint(3) unsigned NOT NULL,
  `last_invite_date` datetime NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;
 */
 
class AccountTableUsersInvite extends JTable
{
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__payments', 'id', $db );
	}
}
