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



CREATE TABLE IF NOT EXISTS `prefix_stream` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` tinyint(4) NOT NULL,
  `type` varchar(64) NOT NULL,
  `message` text NOT NULL,
  `raw` text NOT NULL,
  `likes` text NOT NULL,
  `topics` text NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  `source_id` tinyint(4) NOT NULL DEFAULT '0',
  `params` text NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `followers` text NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`id`),
  FULLTEXT KEY `message` (`message`,`raw`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 ;

 */
 
class StreamTableStream extends JTable
{
	var $id = null;
	var $type		= null;
	var $status		= null;
	var $access		= null;
	var $message	= null;
	var $raw		= null;
	var $likes		= null;
	var $topics		= null;
	var $user_id	= null;
	var $group_id	= null;
	var $created	= null;
	var $updated	= null;
	var $followers	= null;
	var $category_id = null;
	
	var $start_date = null;
	var $end_date 	= null;
	var $params 	= null;
  	
  	private $_handler	= null;
  	private $_params	= null;
  	
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__stream', 'id', $db );
	}
	
	/**
	 *
	 */	 	
	public function load( $id = NULL, $reset = true )
	{
		$ret = parent::load($id);
		$this->_params = new JParameter($this->params);
		return $ret;
	}
	
	
	/**
	 *  Should only be called within template
	 */	 	
	function getGroup()
	{
		static $group = array();
		if(empty($group[$this->group_id])){
		 
			$group[$this->group_id]	= JTable::getInstance( 'Group' , 'StreamTable' );
			$group[$this->group_id]->load($this->group_id);
		}
		
		return $group[$this->group_id];
	}
	
	/**
	 *
	 */	 	
	public function store( $preventUpdate = false)
	{
		// Set date if none exist
		$now = new JDate();
		if( $this->created == null)
			$this->created =  $now->toMySQL();
		
		// Always update the stream last updated time
		if(!$preventUpdate)
			$this->updated =  $now->toMySQL();		
		
		// Send to handler, and see if they need to do any setup
		$this->_getHandler();
		if(method_exists($this->_handler, 'onStore')) {
        	$this->_handler->onStore();
        }
        
        // Update params after store call. The handler might changed it
		$this->params = $this->_params->toString();
        
		return parent::store();
	}
	
	/**
	 * 
	 */
	public function getHTML($format = null)
	{
		include_once(JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'html'.DS.'string.php');
		$this->_getHandler();
		return $this->_handler->getItemHTML($format);
	}
	
	/**
	 *
	 */
	private function _getHandler()
	{
		//@todo: check for valid type
		include_once(JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'messages'.DS.$this->type.'.php');
		$classname = 'Stream'.ucfirst($this->type). 'Type';

		$obj = new $classname($this);
		$this->_handler = $obj;
	}	 	
	
	/**
	 * 
	 */
	public function like()
	{
		$my = JXFactory::getUser();
		
		$likesInArray	=   explode( ',', trim( $this->likes, ',' ) );
		array_push( $likesInArray, $my->id );
		$likesInArray	=   array_unique( $likesInArray );
		$this->likes	=   ltrim( implode( ',', $likesInArray ), ',' );
	}
	
	
	/**
	 *  Current user 'unlike' the message
	 */	 	
	public function unlike()
	{
		
		$my = JXFactory::getUser();
		
		$likesInArray	    =   explode( ',', trim( $this->likes, ',' ) );
		if( in_array( $my->id, $likesInArray ) )
		{
			// Remove user like from array
			$key	=   array_search( $my->id, $likesInArray );
			unset( $likesInArray[$key] );

			$this->likes =   ltrim(implode( ',', $likesInArray ), ',' );
		}
	}
	

	/**
	 *  Return the number of Likes
	 */	 	
	public function countLike()
	{
		if(empty($this->likes))
			return 0;
			
		$likesInArray	=   explode( ',', trim( $this->likes, ',' ) );
		return count($likesInArray);
	}
	
	/**
	 *  Return true if the given userid actually like it
	 */	 	
	public function isLike( $userid )
	{
		if(empty($this->likes))
			return 0;
			
		$likesInArray	=   explode( ',', trim( $this->likes, ',' ) );
		return in_array($userid, $likesInArray);
	}
	
	/**
	 *  Add current user to the follower list
	 */	 	
	public function follow()
	{
		$my = JXFactory::getUser();
		
		$likesInArray	 =   explode( ',', trim( $this->followers, ',' ) );
		array_push( $likesInArray, $my->id );
		$likesInArray	 =   array_unique( $likesInArray );
		$this->followers =   ltrim( implode( ',', $likesInArray ), ',' );	
	}
	
	/**
	 *  Remove current user to the follower list
	 */	 	
	public function unfollow()
	{
		$my = JXFactory::getUser();
		
		$likesInArray	= explode( ',', trim( $this->followers, ',' ) );
		$likesInArray	= array_unique( $likesInArray );
		$likesInArray	= array_diff($likesInArray, array($my->id));
		
		$this->followers =   ltrim( implode( ',', $likesInArray ), ',' );	
	}
	
	/**
	 *  Return JXUser object of the followers
	 */	 	
	public function getFollowers()
	{
		if(empty($this->followers))
			return array();
			
		$followers	=   explode( ',', trim( $this->followers, ',' ) );
		$followers	=   array_unique( $followers );
		$users = array();
		foreach($followers as $row)
		{
			$users[] = JXFactory::getUser($row);
		}
		
		return $users;
	}
	
	/**
	 *  Check if the given userid is in the followers list
	 */	 	
	public function isFollowing( $user_id )
	{
		if(empty($this->followers))
			return false;
			
		$followers	=   explode( ',', trim( $this->followers, ',' ) );
		$followers	=   array_unique( $followers );
		return in_array($user_id, $followers);
	}
	
	
	/**
	 * To mark the message as when advance action is applied
	 * Blog: viewed
	 * Video: viewed
	 * Link: cliked
	 * File: downloaded
	 * @param int $user_id
	 * @param int $item_id the id of the read item, for example files/message
	 */
	public function actionIsTaken($user_id = NULL, $item_id = NULL) {
		$jxuser = JXFactory::getUser();
		$my = (!$user_id) ? $jxuser->id : $user_id;

		$items = json_decode($this->getParam('action_by_user'), true);

		// placeholder for previous value
		$readers = array();

		// since the implementation of individual tracking comes later convert the previous int to json string
		if (!is_array($items)) {
			// store the previous data
			$readers[0] = $items;
			// because of array_merge, make sure we always provide and array to merge
			$items = array();
		}

		// to enable tracking per unique item, (!IMPORTANT: id of 0 is general to the message)
		$params = ($item_id) ? $item_id : 0;
		$addUserToItem = (isset($items[$params])) ? JXUtility::csvInsert($items[$params], $my) : $my;
		$readers[$params] = $addUserToItem;
		$readers = array_merge($items, $readers);

		// store the array of the reader based on item
		return $this->setParam('action_by_user', json_encode($readers));
	}

	/**
	 * Return a list of user who initiate action but not the super admin himself
	 * @return Array id of the user
	 * @note: make sure the sender from Javascript use the same identification CONSTANT 
	 */
	public function whoMakesAction($item_id = NULL, $type = NULL) {
		$EMBED_TYPE = 'embed_';
		$FILE_TYPE = 'file_';
		$VIDEO_TYPE = 'video_';
		$LINK_TYPE = 'link_';
		$SLIDESHARE_TYPE = 'slideshare_';
		$BLOG_TYPE = 'blog_';

		switch ($type) {
		case 'file': $item_id = $FILE_TYPE.$item_id;
			break;
		case 'embed': $item_id = $EMBED_TYPE.$item_id;
			break;
		case 'video': $item_id = $VIDEO_TYPE.$item_id;
			break;
		case 'link': $item_id = $LINK_TYPE.$item_id;
			break;
		case 'slideshare': $item_id = $SLIDESHARE_TYPE.$item_id;
			break;
		case 'blog': $item_id = $BLOG_TYPE.$item_id;
			break;
		}

		// older implementation contains string 
		$users = json_decode($this->getParam('action_by_user'), true) ;
		if (is_array($users) && $item_id) {
			if (isset($users[$item_id])) {
				$users = explode(',', $users[$item_id]);
			}
			else {
				$users = array();
			}
		}
		// compatibility with old data, before individual item tracking, return value is a string
		else if (is_int($users)) {
			$users = array($users);
		}
		// compatibility with old data, before individual item tracking, return value is a string
		else if ($users == NULL) {
			$users = array();
		}
		
		$author = $this->user_id;

		foreach ($users as $key=>$value) { 
			if ($value == '42' || $value == $author || isset($value) == 0) {
				unset($users[$key]);
			}
		}
		return $users;
	}

	/**
	 *  For all unrecognized call, pass it to the handler
	 */	 	
	public function __call($name, $arguments) {
        $this->_getHandler();
        return call_user_func_array(array($this->_handler, $name), $arguments);
        //$this->_handler->$name();
    }
    
    /**
     *  Bind the data. BUT, if it is not part of the table column, add them to
     *  RAW data instead     
     */	     
    public function bind($src, $ignore = array())
    {
    	if(empty($src)){
    		return;
		}
		
    	parent::bind($src, $ignore);
    	
    	// Bind the source value, excluding the ignored fields.
    	// If the source value is an object, get its accessible properties.
		if (is_object($src)) {
			$src = get_object_vars($src);
		}
		
    	$rawObj = json_decode($this->raw);
    	$properties = $this->getProperties();
    	$rawColumn = @array_diff($src, $properties);
    	
		foreach ( $rawColumn as $k => $v)
		{ 
			// Only process fields not in the ignore array.
			if (!in_array($k, $ignore)) {
				
				if (isset($src[$k])) {
					$rawObj->$k = $src[$k];
				} else if (is_null($src[$k])) {
					$rawObj->$k = '';
				}
			}
		}
		
		// Store back in raw format
		$this->raw = json_encode( $rawObj );
		$this->_params = new JParameter($this->params);
	}
	
	/**
     * Check edit permission. Allow edit if the userid is the same as message id
     */	     
    public function allowEdit($userid)
    {
		return ($this->user_id == $userid);
	}
	
	/**
	 * Any registered user can comment
	 */	 	
	public function allowAdd($userid)
	{
		return ($userid != 0);
	}
	
	/**
	 * Only owner can delete a message
	 * @todo: site admin can delete it as well	 	
	 */	
	public function allowDelete($userid)
	{
		return ($this->user_id == $userid);
	}
	
	
	/**
	 * Return the link to the specific message
	 */	 	
	public function getUri()
	{
		$uri	= JURI::getInstance();
		$base	= $uri->toString( array('scheme', 'host', 'port'));
		if (intval($this->group_id) > 0)
		{
			return $base . JRoute::_( 'index.php?option=com_stream&view=groups&task=show&message_id=' . $this->id . '&group_id=' . $this->group_id );
		}
		else
		{
			return $base . JRoute::_( 'index.php?option=com_stream&view=message&task=show&message_id=' . $this->id);
		}
	}
	
	/**
	 * Return RAW data object
	 */	 	
	public function getData()
	{
		$data = json_decode($this->raw);
		return $data;
	}
    
    /**
     * Return an array of file attachement (if there is any)
     */	     
    public function getFiles()
	{
		$data = json_decode($this->raw);
		
		$files = array();
    	// Attachment
		if(!empty($data->attachment)){
			foreach($data->attachment as $fileid)
			{
				$file = JTable::getInstance( 'File' , 'StreamTable' );
				if( !empty($fileid) && $file->load($fileid) )
				{	
					$files[] = $file;
				}
			}
		}
		
		return $files;
	}
    
    /**
     * Return param object
     */	     
    public function getParam($key, $default = null)
	{
		return $this->_params->get($key, $default);
	}

	/**
	 * Method to set a parameter
	 *
	 * @param   string  $key    Parameter key
	 * @param   mixed   $value  Parameter value
	 */
	public function setParam($key, $value)
	{
		return $this->_params->set($key, $value);
	}
    
    /**
     *  If the method is not in the db, grab it from the RAW data
     */	     
    public function __get ( $name )
    {
    	$rawObj = json_decode($this->raw);
    	return property_exists($rawObj, $name) ? $rawObj->$name: null;
	}
	
	/**
     *  If the method is not in the db, grab it from the RAW data
     */	     
    public function __set ( $name, $value )
    {
    	$rawObj = json_decode($this->raw);
    	if(empty($rawObj)){
    		$rawObj = new stdClass();
    	}
    	$rawObj->$name =  $value;
    	$this->raw = json_encode( $rawObj );
	}
}
