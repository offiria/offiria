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
class StreamDirectType extends StreamType
{   
	public function StreamDirectType($data)
	{
		$this->data = $data;
	}

    public function getItemHTML($format = null)
    {
		$tmpl = new StreamTemplate();

		// If were not in 'direct' view, just show short summary
		$view = JRequest::getVar('view');
		$task = JRequest::getVar('task');

		if ($view == 'direct' || $task == 'show') {
			$tmpl->set('stream', $this->data)
				->set('comment', StreamComment::getCommentSummaryHTML($this->data));
			return $tmpl->fetch('stream.item.direct');
		} else {
			$tmpl->set('stream', $this->data);
			return $tmpl->fetch('stream.compact.direct');
		}
    }
    
}