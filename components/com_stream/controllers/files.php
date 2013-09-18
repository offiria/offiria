<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.controller');

class StreamControllerFiles extends JController {

	/**
	 *
	 */
	public function display($cachable = false, $urlparams = false) {
		$view = StreamFactory::getView('files', '', 'html');
		echo $view->display();
	}

	/**
	 * delete an attachment
	 */
	public function delete() {
		$my = JXFactory::getUser();
		$file_id = JRequest::getInt('file_id');
		$file = JTable::getInstance('File', 'StreamTable');
		$file->load($file_id);

		$stream_id = $file->stream_id;
		// @todo: implement this authorise
		if ($my->authorise('stream.file.edit', $file)) {
			$file->delete();
		}


		$data = array();

		// Refresh the stream
		if ($stream_id) {
			$stream = JTable::getInstance('Stream', 'StreamTable');
			$stream->load($stream_id);
			$data['html'] = $stream->getHTML();
			$data['id'] = $stream_id;
		}
		$data['file_id'] = $file_id;

		// @todo: Remove the file from stream data
		echo json_encode($data);
		exit;
	}
	
	public function updateFile()
	{
		$my			= JXFactory::getUser();
		$file_id	= JRequest::getInt('file_id');
		$file_name	= JRequest::getVar('new_name', '');
		
		$result	= new stdClass();
		$result->result = false;
		
		if (intval($file_id) > 0 && preg_match("/^[^\\/?*:;{}\\\\]+$/", $file_name))
		{
			$file = JTable::getInstance('File', 'StreamTable');
			if ($file->load($file_id))
			{
				if ($my->authorise('stream.file.edit', $file)) {
					$originalName = explode('.', $file->filename);
					$extension = array_pop($originalName);

					$file->filename = $file_name.'.'.$extension;
					$file->store();

					$result->result = true;
					$result->fileid = $file_id;
					$result->filename = StreamTemplate::escape(JHtmlString::truncate($file->filename, 32));
					$result->full_filename = StreamTemplate::escape(preg_replace('/\.[a-z]+$/i', '', $file->filename));
				}
				else
				{
					$result->error = JText::_('Not authorized to edit file!');
				}
			}
			else
			{
				$result->error = $file->getError();
			}
		}
		else
		{
			$result->error = JText::_('Invalid filename!');
		}
		
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		exit;
	}
	
	public function replaceNew()
	{				
		$my			= JXFactory::getUser();
		$file_id	= JRequest::getInt('file_id');
		$file		= JTable::getInstance('File', 'StreamTable');
		
		$result		= new stdClass();
		$result->error = true;
		
		if (intval($file_id) > 0)
		{
			$file->load($file_id);
			$previousFilename = $file->filename;

			if($file->user_id != $my->id) continue;
			
			require( JPATH_ROOT . DS . 'components' . DS . 'com_stream' . DS . 'controllers' . DS . 'system.php' );

			$systemController = new StreamControllerSystem();
			$result = $systemController->handleUpload();
			
			if (isset($result['success']) && $result['success'])
			{
				// Get mime type
				if(function_exists('finfo_open')) {
					$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
					$file->mimetype = finfo_file($finfo, JPATH_ROOT.DS.$result['path']);
					finfo_close($finfo);
				} else {
					$file->mimetype = @mime_content_type(JPATH_ROOT.DS.$result['path']);
				}
				
				$newFileExt = array_pop(explode('.', $result['filename']));				
				$oldFileExt = array_pop(explode('.', $file->filename));
				$file->filename = preg_replace('/\.\w+$/', '.'.$newFileExt, $file->filename);
			
				// Delete the old one before saving the new one to the file db entry
				if($file->getParam('has_preview'))
				{
					JFile::delete(JPATH_ROOT.DS.$file->path);
					
					$pathinfo = pathinfo($file->path);
					$thumbPath = JPATH_ROOT .DS . $pathinfo['dirname'] .DS. $pathinfo['filename'].'_thumb.jpg';			
					JFile::delete($thumbPath);

					$thumbPath = JPATH_ROOT .DS . $pathinfo['dirname'] .DS. $pathinfo['filename'].'_preview.jpg';			
					JFile::delete($thumbPath);
				}
				
				// set the file entry to the new uploaded file
				$file->path = $result['path'];
				$file->filesize = filesize(JPATH_ROOT.DS.$file->path );
				$file->store();
				
				// Trigger Mentioned Name Notification
				$notificationType = 'file_replace_new';
				StreamNotification::trigger( $notificationType, $file, $previousFilename );
				
				$result['filename'] = $file->filename;
				$result['newext'] = $newFileExt;
				$result['oldext'] = $oldFileExt;				
				$result['filesize'] = '('.StreamMessage::formatBytes($file->filesize).')';
				if ($file->getParam('has_preview'))
				{	
					$pathinfo = pathinfo($result['path']);
					$result['preview'] = JURI::root() . str_replace(DS, '/', $file->getParam('thumb_path'));
				}
				else
				{
					$result['preview'] = JURI::root();
				}
			}
		}
		
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		exit;
	}
}