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
class StreamUpdateType extends StreamType
{   
	
	public function StreamUpdateType($data)
	{
		$this->data = $data;
	}

	
    public function getItemHTML($format = null)
    {
		$tmpl = new StreamTemplate();
		$tmpl->set( 'stream'	, $this->data)
				->set('comment', StreamComment::getCommentSummaryHTML($this->data));
		return $tmpl->fetch('stream.item.update');
    }
    
}