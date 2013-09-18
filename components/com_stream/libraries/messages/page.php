<?php
/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
include_once('message.php');

/**
 * Templating system for JomSocial
 */
class StreamPageType extends StreamType
{   
	
	public function StreamPageType($data)
	{
		$this->data = $data;
	}
	
    public function getItemHTML($format = null)
    {
    
    	// If were not in 'article' view, just show short summary
    	$view = JRequest::getVar('view');
    	$task = JRequest::getVar('task');
    	
    	if(( $view == 'message' && $task == 'show') || $view == 'blog' ){
			$tmpl = new StreamTemplate();
			$tmpl->set( 'stream'	, $this->data)
				->set('comment', StreamComment::getCommentSummaryHTML($this->data));
			return $tmpl->fetch('stream.item.page');
		} else{
			$tmpl = new StreamTemplate();
			$tmpl->set( 'stream'	, $this->data)
				 ->set('comment', StreamComment::getCommentSummaryHTML($this->data));
			return $tmpl->fetch('stream.compact.page');
		}
    }
    
}