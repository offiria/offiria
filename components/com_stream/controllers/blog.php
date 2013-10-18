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

class StreamControllerBlog extends JController
{
	/**
	 *
	 */	 	
	public function display($cachable = false, $urlparams = false)
	{
		$view 	= StreamFactory::getView( 'blog', '', 'html');
		echo $view->display();
	}
	
	public function edit()
	{
		$mainframe	= JFactory::getApplication();
		$view 		= StreamFactory::getView( 'blog', '', 'html');
		$message_id = JRequest::getVar('message_id');
		
		// @todo: make sure message id and message type is valid
		$stream	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->load($message_id);
		
		$my = JXFactory::getUser();
		if( !$my->authorise('stream.message.edit', $stream) ){
			$mainframe->redirect( $stream->getUri(), JText::_('You do not have permission to edit this message'), 'error');
			return;
		}
		
		// Save blog post
		// @todo: validation
		if(JRequest::getVar('action') == 'save')
			{
				//	Update attachement there might be addition and removals
				$oldFiles = $stream->getFiles();
		
				//@todo: validate content
				$errors = array();
				$stream->bind( JRequest::get('POST', JREQUEST_ALLOWRAW) );
				$stream->raw = json_encode( JRequest::get('POST', JREQUEST_ALLOWRAW) );

				if(empty($_POST['title'])){
					$errors[] = JText::_('COM_STREAM_BLOG_POST_NO_TITLE');
				}
				if(empty($_POST['message'])){
					$errors[] = JText::_('COM_STREAM_BLOG_POST_EMPTY');
				}
			
				if(empty($errors)){
					$stream->store();
					$mainframe->enqueueMessage( JText::_('COM_STREAM_BLOG_POST_UPDATED') );
				
					// Delete file attachment that are no longer used
					foreach($oldFiles as $file){
						if(!in_array($file->id, JRequest::getVar('attachment',array())) ){
							$file->delete();
						}
					}
				
					// For all new attachment, we need to set their owner
					$fileModel  = StreamFactory::getModel('files');
					$fileModel->updateOwner($stream);
				
				} else {
					foreach($errors as $err )
						{
							$mainframe->enqueueMessage( $err, 'error' );
						}
				}
			
			
			}
		
		echo $view->edit($stream);
	}
}