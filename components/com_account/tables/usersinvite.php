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
	var $id					= null;
	var $from_email			= null;
	var $invite_email		= null;
	var $status				= null;
	var $last_invite_date	= null;
	var $created			= null;
	
	const PENDING			= 1;
	const SENT				= 2;
	const CANCEL			= 3;
	const REGISTERED		= 4;


	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__users_invite', 'id', $db );
	}
	
	public function translateStatus( $intStatus )
	{
		switch($intStatus)
		{
			case self::PENDING:
				return JText::_('COM_ACCOUNT_LABEL_PENDING');
				break;
			
			case self::SENT:
				return JText::_('COM_ACCOUNT_LABEL_REGISTERED');
				break;
			
			case self::CANCEL:
				return JText::_('COM_ACCOUNT_LABEL_CANCELLED');
				break;
			
			case self::REGISTERED:
				return JText::_('COM_ACCOUNT_LABEL_REGISTERED');
				break;
		}
		
		return $intStatus;
	}
	
	public function getRowHtml()
	{
		$jxConfig = new JXConfig();
		$rowData = $this;
		$allowInvite = $jxConfig->allowUsersRegister();
		$pendingStat = self::PENDING;
		
		ob_start();
		include(JPATH_ROOT . DS . 'components' . DS . 'com_account' . DS . 'views' . DS . 'invite'. DS . 'tmpl' . DS . 'default.invitation.php');
		$content = ob_get_contents();
		ob_end_clean();
		
		return $content;
	}
}
