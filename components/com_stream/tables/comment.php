<?php
/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

/*

CREATE TABLE `[prefix]_stream_comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stream_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `likes` text NOT NULL,
  `comment` text NOT NULL,
  `raw` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `stream_id` (`stream_id`)
) ENGINE=MyISAM ;
 
 */

class StreamTableComment extends JTable
{
	var $id = null;
	var $stream_id = null;
	var $user_id = null;
	var $group_id = null;
	var $likes = null;
	var $comment = null;
	var $raw = null;
	var $created = null;
	var $updated = null;
	
	/**
	 * Constructor
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__stream_comments', 'id', $db );
	}
	
	/**
	 *
	 */	 	
	public function store($preventUpdate = false)
	{
		// Set date if none exist
		if($preventUpdate != true) {
			$now = new JDate();
			$this->created = $now->toMySQL();
			$this->updated = $now->toMySQL();
		}
		
		return parent::store();
	}

	/**
	 *  Return true if the given userid actually like it
	 */
	public function isLike($userid)
	{
		if (empty($this->likes))
			return 0;

		$likesInArray = explode(',', trim($this->likes, ','));
		return in_array($userid, $likesInArray);
	}

	public function like()
	{
		$my = JXFactory::getUser();

		$likesInArray = explode(',', trim($this->likes, ','));
		array_push($likesInArray, $my->id);
		$likesInArray = array_unique($likesInArray);
		$this->likes = ltrim(implode(',', $likesInArray), ',');
	}

	public function unlike()
	{
		$my = JXFactory::getUser();

		$likesInArray = explode(',', trim($this->likes, ','));
		if (in_array($my->id, $likesInArray)) {
			// Remove user like from array
			$key = array_search($my->id, $likesInArray);
			unset($likesInArray[$key]);

			$this->likes = ltrim(implode(',', $likesInArray), ',');
		}
	}

	/**
	 *  Return the number of Likes
	 */
	public function getLikeCount()
	{
		if(empty($this->likes))
			return 0;

		$likesInArray = explode(',', trim($this->likes, ','));
		return count($likesInArray);
	}
}