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
 
CREATE TABLE IF NOT EXISTS `[prefix]_oauth_nonces` (
`id` int(11) NOT NULL AUTO_INCREMENT,
`nonce` varchar(100) NOT NULL,
`timestamp` timestamp DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

*/

define('NONCE_VALIDITY', '5 MINUTE');

class OauthTableNonce extends JTable
{
	var $id = null;
	var $nonce = null;
	var $timestamp;	
	
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__oauth_nonces', 'id', $db );
	}
	
	/**
	 * Whenever nonce is trying to make a call,
	 * check whether there are existing nonce installed,
	 * or, check whether there are expired nonce that should be removed
	 * 
	 * !Note: nonce is a unique number that does not represent anything, we are looking for non-duplicated request
	 * @param String $nonce is the nonce to check
	 * @return Boolean true on success
	 * @see OauthModelToken::authenticate()
	 */
	public function validCall($nonce) 
	{
		// run clean up one every called to minimize the database size
		$this->cleanUp();

		if ($this->isRegistered($nonce)) {
			return false;
		}
		// nonce is valid to use
		else {
			$this->nonce = $nonce;
			$this->timestamp = date('Y-m-d H:i:s');
			return $this->store();
		}
	}
	
	/**
	 * The purpose of nonce is to avoid replay attack 
	 * Nonce should not have duplicate on request otherwise its void
	 */
	public function isRegistered($nonce) 
	{
		return $this->load(array('nonce'=>$nonce));
	}
	
	/**
	 * Clean up previous nonce if exceed NONCE_VALIDITY
	 * @example 
	 * the request will be made using nonce before authorizing the new nonce
	 * the system will check if there are previous nonce installed that exceed NONCE_VALIDITY and remove them.
	 * In this way the system will limit the storage that is being used 
	 *  
	 * @see OauthTableToken::isValid();
	 */
	public function cleanUp() 
	{
		$db =& $this->getDbo();
		// query will look for any timestamp that passed NONCE_VALIDITY and delete them
		$query = "DELETE FROM " . $db->quoteName($this->getTableName()) . " WHERE " . $db->quoteName('timestamp') . " < (NOW() -INTERVAL " . NONCE_VALIDITY . ")";
		$db->setQuery($query);
		return $db->query();
	}
}