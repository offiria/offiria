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

CREATE TABLE IF NOT EXISTS `[prefix]_users_token` (
  `user_id` int(11) NOT NULL,
  `token_id` VARCHAR(100) NOT NULL,
  `expires` datetime NOT NULL,
  `authorized` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

 */
 
class AccountTableToken extends JTable
{
  	private $_handler	= null;
  	private $_params	= null;
  	
  	var $user_id = null;
  	var $token_id = null;
  	var $expires = null;
  	
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__users_token', 'id', $db );
	}
	
	/**
	 * Get the record by token
	 */	 	
	public function load($token_id)
	{
		$row = parent::load($token_id);
		return $row;
	}
}
