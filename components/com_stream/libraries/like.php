<?php

/**
 * @package		Offiria
 * @subpackage		Core 
 * @copyright		(C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class StreamLike 
{
	/**
	 *
	 * @param type $streamid 
	 */
	public function like( $streamid )
	{
		
	}
	
	/**
	 *
	 * @param type $streamid 
	 */
	public function unlike( $streamid )
	{
		
	}
	
	
	/**
	 * Return number of likes
	 */
	public function getLikeCount($streamid)
	{
		$like	=&  JTable::getInstance( 'Like' , 'StreamTable' );
		$like->load( $element, $itemId );
		$count = 0;
		
		if( !empty ($like->like) )
		{
			$likesInArray	=   explode( ',', trim( $like->like, ',' ) );
			$count		=	count( $likesInArray );
		}
		
		return $count;
	}
	
	
	public function getHTML( $stream_id )
	{
	}
}