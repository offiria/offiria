<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Utilities
 *
 * @copyright   Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;
jimport('joomla.utilities.xintegration');

/**
 * JXIntegration is a utility function class for 3rd party integrations
 *
 * @package     Joomla.Platform
 * @subpackage  Utilities
 * @since       11.1
 */
class JActiveDirectory extends JXIntegration
{	
	const USERNAME			= 'un';
	const PASSWORD			= 'pw';
	const HOSTIP			= 'hi';
	const DIRECTORYCONTROL	= 'dc';
	const DOMAIN			= 'dm';
	
	private $_fieldsToSearch= array("cn", "displayname", "samaccountname", "homedirectory", "telephonenumber", "mail");

	public function __construct()
	{
		$this->_name = 'activedirectory';
		return parent::__construct();		
	}
	
	/**
	 * try to connect to the 3rd party
	 * try to retrieve and store exchange data into $this->exchange standard object
	 */
	public function connect()
	{
		$un = $this->getConfigInfo( self::USERNAME );
		$pw = $this->getConfigInfo( self::PASSWORD );
		$hi = $this->getConfigInfo( self::HOSTIP );
		$dm = $this->getDomain();
		
		// formulate the Directory Control; e.g. offiria.my => DC=offiria,DC=my
		$dc = $this->getConfigInfo( self::DIRECTORYCONTROL );
		$dc = explode('.', $dc);
		array_walk($dc, 'JActiveDirectory::addPrefix', 'DC=');
		$dc = implode(',', $dc);		
		
		$SearchFor			= JRequest::getString('username');               
		$SearchField		= "samaccountname";   

		$LDAPHost			= $hi;       //Your LDAP server DNS Name or IP Address
		$dn					= $dc; //Put your Base DN here
		$LDAPUserDomain		= $dm;  //Needs the @, but not always the same as the LDAP server domain
		$LDAPUser			= JRequest::getString('username');//$un;        //A valid Active Directory login
		$LDAPUserPassword	= JRequest::getString('password');//$pw;
		$LDAPFieldsToFind	= $this->_fieldsToSearch;

		$cnx				= ldap_connect($LDAPHost) or die("Could not connect to LDAP");
		ldap_set_option($cnx, LDAP_OPT_PROTOCOL_VERSION, 3);  //Set the LDAP Protocol used by your AD service
		ldap_set_option($cnx, LDAP_OPT_REFERRALS, 0);         //This was necessary for my AD to do anything
		$connect			= @ldap_bind($cnx,$LDAPUser.$LDAPUserDomain,$LDAPUserPassword);
		
		if (!$connect)
		{
			return false;
		}
		
		$filter				= "($SearchField=$SearchFor*)"; //Wildcard is * Remove it if you want an exact match
		$sr					= ldap_search($cnx, $dn, $filter, $LDAPFieldsToFind);
		$info				= ldap_get_entries($cnx, $sr);
		/*Array
		(
			[cn] =&gt; Array
				(
					[count] =&gt; 1
					[0] =&gt; Administrator
				)

			[0] =&gt; cn
			[samaccountname] =&gt; Array
				(
					[count] =&gt; 1
					[0] =&gt; Administrator
				)

			[1] =&gt; samaccountname
			[count] =&gt; 2
			[dn] =&gt; CN=Administrator,CN=Users,DC=offiria,DC=my
		)*/
		
		if ( $info["count"] == 0 ) 
		{ 
			return false; 
		}
		else
		{
			$this->exchange = $this->_extractInfo($info[0]);
		}
		
		return true;
	}
	
	public function getDomain()
	{
		return preg_match('/^@/', $this->getConfigInfo( self::DOMAIN )) ? $this->getConfigInfo( self::DOMAIN ) : '@'.$this->getConfigInfo( self::DOMAIN );
	}
	
	private function _extractInfo($info = array())
	{
		$data	= new stdClass();
		$fields = $this->_fieldsToSearch;
		$num	= count($fields);
		
		for ($x = 0; $x < $num; $x++)
		{
			if (isset($info[$fields[$x]]) && isset($info[$fields[$x]][0]))
			{
				$data->$fields[$x] = $info[$fields[$x]][0];
			}
		}
			
		return $data;
	}
	
	public function isEnabled()
	{
		$dc = $this->getConfigInfo(self::DIRECTORYCONTROL);
		$hi = $this->getConfigInfo(self::HOSTIP);
		$dm = $this->getConfigInfo(self::DOMAIN);
		$un = $this->getConfigInfo(self::USERNAME);
		$pw = $this->getConfigInfo(self::PASSWORD);
		
		return (!is_null($dc) && !empty($dc) &&
				!is_null($hi) && !empty($hi) &&
				!is_null($dm) && !empty($dm) && 
				!is_null($un) && !empty($un) &&
				!is_null($pw) && !empty($pw));
	}
	
	public function addPrefix(&$value, $key, $prefix)
	{
		$value = $prefix.$value;
	}
}