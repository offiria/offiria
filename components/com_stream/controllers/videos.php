<?php
/**
 * @version     1.0.0
 * @package     com_administrator
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.controller');

class StreamControllerVideos extends JController
{
	/**
	 * 
	 */	 	
	public function play(){
		
		$type = JRequest::getVar('embed_type');
		$width = JRequest::getVar('embed_width');
		$height = JRequest::getVar('embed_height');
		switch($type){
			case 'videos':
				$videoid = JRequest::getInt('embed_id');
		
				$video = JTable::getInstance( 'Video' , 'StreamTable' );
				$video->load($videoid);
				$html = $video->getEmbedHTML($width, $height);
				break;
				
			case 'slideshare':
				$slideid = JRequest::getInt('embed_id');
		
				$video = JTable::getInstance( 'Slideshare' , 'StreamTable' );
				$video->load($slideid);
				$html = $video->getEmbedHTML();
				break;
		}
		
		
		$data = array();
		$data['html'] = $html;
		
		echo json_encode($data);
		exit;
	}
}