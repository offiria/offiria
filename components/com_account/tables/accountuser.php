<?php
/**
 * @category	Tables
 * @package		Offiria
 * @subpackage	Activities 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

 
class AccountTableAccountUser extends JTable
{
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__users', 'id', $db );
	}
}
