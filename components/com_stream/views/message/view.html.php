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
require_once(JPATH_ROOT .DS.'components'.DS.'com_account'.DS.'helpers'.DS.'access.php');

/**
 * HTML View class for the Administrator component
 */
class StreamViewMessage extends StreamView
{
	public function show($stream)
	{
		
		$my = JXFactory::getUser();
		if (!AccountAccessHelper::allowPublicStream($my->id))
		{
			$app	= JFactory::getApplication();
		    $app->enqueueMessage( JText::_('COM_STREAM_ERROR_NO_ACCESS' ) , 'error' );
			return;
		}

		$doc = JFactory::getDocument();
		$this->_attachScripts();
		// Pathway to group
		if($stream->group_id){
			$group	= JTable::getInstance( 'Group' , 'StreamTable' );
			$group->load($stream->group_id);
			$this->addPathway( JText::_('NAVIGATOR_LABEL_GROUPS'), JRoute::_('index.php?option=com_stream&view=groups') );
			$this->addPathway( $group->name, JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='.$group->id) );
		} 
		else
		{
			// Pathway to public item	
			// Breadcrumb for know 'type'
			switch($stream->type){
				case 'page':
					$this->addPathway( JText::_('NAVIGATOR_LABEL_BLOG'), JRoute::_('index.php?option=com_stream&view=blog') );
					break;
				case 'todo':
					$this->addPathway( JText::_('NAVIGATOR_LABEL_TODO'), JRoute::_('index.php?option=com_stream&view=todo') );
					break;
				case 'event':
					$this->addPathway( JText::_('NAVIGATOR_LABEL_EVENTS'), JRoute::_('index.php?option=com_stream&view=events') );
					break;
				default:
					// Show profile link
					$user = JXFactory::getUser($stream->user_id);
					$this->addPathway( $user->name, $user->getURL() );
			}
		}
		
		$this->addPathway( JText::sprintf('COM_STREAM_BLOG_MESSAGE_ID', $stream->id), JRoute::_('index.php?option=com_stream&view=company') );
		
		// Add attendee if this is an event
		if($stream->type == 'event'){
			JXModule::addBuffer('right', $this->modGetAttendeeHTML($stream));
		}
		
		// Add attachment script
		$doc->addScript(JURI::root(). 'media/uploader/fileuploader.js');
		$doc->addStyleSheet(JURI::root().'media/uploader/fileuploader.css');
		
		$html = '<div class="update"><ol id="stream" class="stream">';
		$html .= $stream->getHTML();
		$html .= '</ol></div>';
		return $html;
	}
	
	public function showMessages()
	{
		jimport('joomla.html.pagination');
		$app	= JFactory::getApplication();
		$my = JXFactory::getUser();
		$jconfig = new JConfig();
		$html = '';
		
		if( $ids = JRequest::getVar('ids', '') ){
			$filter['id'] = $ids;
		}

		$model	= StreamFactory::getModel('stream');
		$data	= $model->getStream( $filter, $jconfig->list_limit, JRequest::getVar('limitstart', 0) );
		
		// for some reason, data retrieved has not permissable view message
		foreach ($data as $streamMsg)
		{
			if( !$my->authorise('stream.message.read', $streamMsg) ){
				$app	= JFactory::getApplication();
				$app->redirect( JURI::base(), JText::_('COM_STREAM_ERROR_NO_ACCESS' ) , 'error' );
				exit;
			}
		}
		
		$total	= $model->countStream( $filter );
				
		// Pagination
		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jconfig->list_limit);

		// If URI is defined, we need to set the pagination link properly
		if( $uri = JRequest::getVar('uri', '') ){
	
			$uri = new JURI($uri);
	
			$router = $app->getRouter();
			$result = $router->parse($uri);
	
			foreach($result as $key => $val)
				$pagination->setAdditionalUrlParam($key, $val);
		}
		
		$tmpl	= new StreamTemplate();
		$tmpl->set('rows', $data);
		
		$tmpl->set('total', $total);
		$tmpl->set('pagination', $pagination);
		$html .= $tmpl->fetch('stream.data');
		return $html;
	}
}