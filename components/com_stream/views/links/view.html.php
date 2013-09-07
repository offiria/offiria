<?php
/**
 * @version     1.0.0
 * @package     com_administrator
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Administrator component
 */
class StreamViewLinks extends StreamView
{
	function display($tpl = null)
	{
		$jconfig = new JConfig();
		jimport('joomla.html.pagination');
		include_once(JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'html'.DS.'string.php');
        $this->addPathway( JText::_('NAVIGATOR_LABEL_FILE'), JRoute::_('index.php?option=com_stream&view=groups') );
		$title = JText::_("COM_STREAM_LABEL_LINK_LISTING");
		$this->_attachScripts();
		
		// Reset the stream view count every time we visit this page


		$user = JXFactory::getUser();
		
		$filter = array();
		
		// Filter by user_id (cannot be used along with 'by' filter)
		if( $user_id = JRequest::getVar('user_id', '') )
		{
			$filter['user_id'] = $user_id;
			$user = JXFactory::getUser($user_id);
			$title = JText::sprintf("%1s's links", $user->name);
		}
		
		$usrLinks	= $user->getParam('links', '');
		$linkModel	= StreamFactory::getModel('links');
		$links		= $linkModel->getLinks( array('id' => $usrLinks, '!link' => ''), $jconfig->list_limit,JRequest::getVar('limitstart', 0) );
		$total		= $linkModel->getTotal( array('id' => $usrLinks, '!link' => '') );
				
		$doc = JFactory::getDocument();
		$doc->setTitle($title);
		
		// Pagination
		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jconfig->list_limit);
		
		$html = '';   
		
		$tmpl = new StreamTemplate();
		$tmpl->set('links', $links)->set('pagination', $pagination);
		$html .= $tmpl->fetch('links.list');
		
		return $html;	
	}	
}