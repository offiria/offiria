<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

class StreamModelTopic {
     public function getStreamId($topic) {
	  $db = JFactory::getDbo();
	  $query = "SELECT * FROM #__stream_topic WHERE topics='$topic'";
	  $db->setQuery( $query );
	  $result	= $db->loadObjectList();
	  
	  $rows = array();
	  foreach( $result as $row )
	  {
	       $obj	= JTable::getInstance( 'Topic', 'StreamTable' );
	       $obj->bind($row);
	       $rows[] = $obj;
	  }
	  
     }
}
