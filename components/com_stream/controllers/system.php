<?php

/**
 * @package		Offiria
 * @subpackage	Core
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');
define('GROUP_MESSAGE_PREVIEW_LIMIT', 5);

jimport('joomla.application.controller');

class StreamControllerSystem extends JController
{
	/**
	 *
	 */
	public function display($cachable = false, $urlparams = false){
		//parent::display( null );
		echo "To do listing";
	}
	
	/**
	 * Cron calls
	 */	 	
	public function cron(){
		/* this will run once a week */
		if (JRequest::getVar('maintenance')) {
			// Diffbot weekly maintenance 
			if (StreamLinks::unjump()) {
				exit('Maintenance complete');
			}
			exit('Failed to perform maintenance');
		}

		/* weekly cron task */
		if (JRequest::getVar('weekly')) {
			// only allow script to run from the server
			if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
				StreamFactory::load('libraries.activity');
				$activity = new StreamActivity();
				$activity->scan();
			} 
			else {
				throw new Exception('Unaccessible');
			}
		}

		// Since the cron job already exist here, use the same jobs to perform link refetching
		StreamLinks::refetch();
		StreamFactory::load('libraries.cron');
		$cron = new StreamCron();
		$cron->run();
		exit;
	}
	
	public function hidewelcome(){
		$my = JXFactory::getUser();
		$my->setParam('swl', 1);
		$my->save();
		
		exit;
	}
	
	// Hide specific alert
	public function hidealert(){
		$my = JXFactory::getUser();
		
		$alert_id = JRequest::getVar('alert_id');
		
		$my->setParam($alert_id, 1);
		$my->save();
		
		exit;
	}
	
	// Lookahead query
	public function query(){
		$query = JRequest::getVar('query');
		$data = array();
		
		$userModule = StreamFactory::getModel('user');
		$users = $userModule->search($query);
		
		foreach($users as $row){
			$obj = new StdClass();
			$obj->name = $row->name;
			$obj->value = $row->username;
			$data[] = $row->name;
		}
		
		header('Content-Type: text/json');
		echo json_encode($data);
		//echo "[['value', 'Charlie'], ['value', 'Gudbergur']]";
		exit;
	}
	
	/**
	 * Load @mention auto-suggest
	 */	 	
	public function suggest(){
		$suggest = JRequest::getVar('search');
		$type = JRequest::getVar('type');
		$data = array();
		
		$tmpl = new StreamTemplate();
		$tmpl->set('highlight', $suggest);
		
		$suggest = trim($suggest, '@#');
		
		switch($type){
			case 'mention':
				
				$userModule = StreamFactory::getModel('user');
				
				$users = $userModule->search($suggest);		
				$tmpl->set('users', $users);
				
				$data['html'] = $tmpl->fetch('autosuggest');		
				break;
			case 'hashtag':
				
				$db = JFactory::getDbo();
		
				// Select those with username start with the given name
				// and later search those with the given username in the middle
				$query = "SELECT hashtag FROM #__stream_hashtags WHERE `hashtag` LIKE " . $db->Quote($suggest.'%')
						." UNION  "
						."SELECT hashtag FROM #__stream_hashtags WHERE `hashtag` LIKE " . $db->Quote('%'.$suggest.'%')
						." LIMIT 10";
				
				$db->setQuery( $query );
				$result	= $db->loadColumn();
				
				$tmpl->set('hashtags', $result );
				$data['html'] = $tmpl->fetch('suggest.hashtag');
				break;
			case 'member':
				$group_id = JRequest::getVar('group_id');
				$group	= JTable::getInstance( 'Group' , 'StreamTable' );
				$group->load($group_id);
				$memberIds = $group->members;

				$userModule = StreamFactory::getModel('user');

				$filter['!id'] = $memberIds;
				$users = $userModule->search($suggest, $filter);

				$tmpl->set('users', $users);

				$data['html'] = $tmpl->fetch('autosuggest.member');
				break;
		}
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	/**
	 * Download file
	 */	 	
	public function download(){
		$my = JXFactory::getUser();
		$fileid = JRequest::getVar('file_id');
		$file = JTable::getInstance( 'File' , 'StreamTable' );
		
		if( $file->load($fileid) &&  $my->authorise('stream.file.download', $file))
		{
			// @todo: check file view permission (use ->authrise)
			// @todo: keep a download count ?
			
			// If the mimetype is zip, it could be vendor specific mimetype
			// use file extension to send out the mimetype
			$mimetype = $file->mimetype;
			if($mimetype == 'application/zip')
			{
				$mimetype = $file->getMIMETypeFromFilename();
			}
			header('Content-type: '.$mimetype.'');
			header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 604800)); // 1 week
			
			// If not on direct display mode, setup for download
			if( !JRequest::getVar('display')){
				header('Content-Disposition: attachment; filename="'.$file->filename.'"');	
			}
			readfile(JPATH_ROOT.DS.$file->path);
			
			// add donwnloader info
			$file->downloaded($my->id);
			$file->store();

			// mark the file as an action taken by the user
			$stream = JTable::getInstance('Stream', 'StreamTable');
			$stream->load($file->stream_id);
			$stream->actionIsTaken();
			$stream->store(true);

			exit;
		} else {
			echo "File Not found";
		}
		return;
	}
	
	/**
	 * Handle qqUplaoder
	 */	 	
	public function upload()
	{		
		$my = JXFactory::getUser();
		
		$result = $this->handleUpload();
		
		// File upload successful, store data in db
		if( isset($result['success']) ) {
			$file = JTable::getInstance( 'File' , 'StreamTable' );
			
			// Store info
			$file->user_id	= $my->id;
			$file->filename = $result['filename'];
			$file->path = $result['path'];
			$file->filesize = filesize(JPATH_ROOT.DS.$file->path );
			
			// Get mime type
			if(function_exists('finfo_open')) {
				$finfo = finfo_open(FILEINFO_MIME_TYPE); // return mime type ala mimetype extension
				$file->mimetype = finfo_file($finfo, JPATH_ROOT.DS.$file->path);
				finfo_close($finfo);
			} else {
				$file->mimetype = @mime_content_type(JPATH_ROOT.DS.$file->path);
			}
			
			$file->store();
			
			// Pass it over to the browser
			$result['file_id'] = $file->id;
		}
		
		// to pass data through iframe you will need to encode all html tags
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		exit;
	}
	
	/**
	 * 
	 */	 	
	public function preview(){
		$my = JXFactory::getUser();
		$fileid = JRequest::getVar('file_id');
		$file = JTable::getInstance( 'File' , 'StreamTable' );
		$jxConfig	= new JXConfig();
		$data = array();
		$data['reload'] = false;
		
		if( $file->load($fileid) && $my->authorise('stream.file.download', $file) && ($jxConfig->isCrocodocsEnabled() || $jxConfig->isScribdEnabled()))
		{
			// Grab the uuid
			$uuid = $file->getParam('uuid');
			$docid = $file->getParam('doc_id');
			if(($jxConfig->isCrocodocsEnabled() && empty($uuid)) || ($jxConfig->isScribdEnabled() && empty($docid))){
				// haven't been uploaded yet, upload and grab the uuid
				$store = $file->preparePreview();
				
				// reload file for updated content
				$file->load($fileid);
				$uuid = $file->getParam('uuid');
				$docid = $file->getParam('doc_id');
			}

			// Check if file is ready
			$previewReady = $file->getParam('preview-ready');
			$isViewable = false;
			if(empty($previewReady)){

				if ($jxConfig->isCrocodocsEnabled())
				{
					// Preview not ready, query status
					$options = new JRegistry();
					$transport = new JHttpTransportCurl($options);
					$http = new JHttp($options, $transport);

					$response =  $http->get('https://crocodoc.com/api/v2/document/status?token='.$jxConfig->get(JXConfig::CROCODOCS).'&uuids='.$file->getParam('uuid'));

					$responseObj = json_decode($response->body);
					//print_r($responseObj); exit;
					if (!isset($responseObj->error))
					{
						$isViewable = !empty($responseObj[0]->viewable) ;
						if($isViewable){
							$previewReady = true;
							$file->setParam('preview-ready', 1);
							$file->store();
						}
					}
				}
				else
				{
					// Query Scribd Preview status
					$http = new JHttp();
					$statusUrl = 'http://api.scribd.com/api?api_key='.$jxConfig->get(JXConfig::SCRIBD_API).'&doc_id='.$file->getParam('doc_id').'&method=docs.getConversionStatus&my_user_id='.$file->user_id;
					$response =  $http->get($statusUrl);
					
					$result = simplexml_load_string($response->body); 
					if (isset($result->conversion_status) && $result->conversion_status == 'DONE')
					{
						$previewReady = true;
						$file->setParam('preview-ready', 1);
						$file->store();
					}
				}
			}
			
			if($previewReady && ( !empty($uuid) || !empty($docid) ))
			{
				if ($jxConfig->isCrocodocsEnabled())
				{
					$session_id = $file->getParam('previewSession');

					if( time() > $file->getParam('previewExpiry')) {
						// File uploaded, try to create session
						$options = new JRegistry();
						$transport = new JHttpTransportCurl($options);
						$http = new JHttp($options, $transport);
						
						$response =  $http->post( 'https://crocodoc.com/api/v2/session/create' , array('token' => '$jxConfig->get(JXConfig::CROCODOCS)', 'uuid' => $file->getParam('uuid') ) );
						//$response =  $http->post( 'https://crocodoc.com/api/v2/session/create' , array('token' => 'Oe8fA1mQ59LSwtBlKy4Nkbvn', 'uuid' => $uuid ) );

						$responseObj = json_decode($response->body);
						$session_id = $responseObj->session;

						// Store this session so that we don't have to fetch it again
						$file->setParam('previewSession', $session_id);
						$file->setParam('previewExpiry', time() + (60*50) ); // ste it 50 mins from now
						$file->store();
					}

					$html = '<iframe style="border:0px;width:100%;height:100%" src="https://crocodoc.com/view/'.$session_id.'"></iframe>';
				}
				else // Use Scribd HTML codes
				{
					$html = "<div id='embedded_doc' data-doc_id=\"".$file->getParam("doc_id")."\" data-access_key=\"".$file->getParam("access_key")."\"><a href='http://www.scribd.com'>Scribd</a></div>						
							 <script type=\"text/javascript\">
								// Instantiate iPaper
								$(document).ready(function() {
									scribd_doc = scribd.Document.getDoc($('#embedded_doc').data('doc_id'), $('#embedded_doc').data('access_key'));
									// Parameters
									scribd_doc.addParam('height', 750);
									scribd_doc.addParam('width', 750);
									scribd_doc.addParam('auto_size', true);
									scribd_doc.addParam('mode', 'slideshow');
									scribd_doc.addParam('jsapi_version', 2);

									// Attach event listeners
									scribd_doc.addEventListener('docReady', onDocReady);

									// Write the instance
									scribd_doc.write('embedded_doc');
								});
								  
								var onDocReady = function(e) {
									scribd_doc.api.setPage(1);
								}
								  
								// Bookmark Helpers
								var goToPage = function(page) {
									if (scribd_doc.api){
										scribd_doc.api.setPage(page);
									}
								}
								  
								var goToMiddle = function() {
									if (scribd_doc.api){
										goToPage( Math.floor(scribd_doc.api.getPageCount()/2) );
									}
								}

								var goToEnd = function() {
									if (scribd_doc.api) {
										goToPage(scribd_doc.api.getPageCount());
									}
								}
							  </script>";
				}
				$data['html'] = $html;
			} else {
				$data['reload'] = 1;
				$data['file_id'] = $fileid;
				$data['html'] = '<p>Please wait while we prepare your document</p>';
			}

		} else {
			$data['html'] = "<p>Not allowed to preview.</p>";
		}
		
		echo json_encode($data);
		exit;
	}
	
	/**
	 *
	 */	 	
	public function handleUpload()
	{		
		include_once(JPATH_ROOT.DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'uploader.php');
		jimport('joomla.filesystem.folder');
		
		//@todo: check for upload permission. Use ->authorise
		
		// list of valid extensions, ex. array("jpeg", "xml", "bmp")
		$mediaparams = JComponentHelper::getParams('com_media');
		$allowedExtensions = explode(',', $mediaparams->get('upload_extensions'));
		
		// max file size in bytes
		$sizeLimit = 5 * 1024 * 1024;
		
		$uploader = new qqFileUploader($allowedExtensions, $sizeLimit);
		
		// Build /YEAR/MONTH/DATE filepage
		$date = new JDate();
		$uploaderPath = 'files'.DS.$date->format('Y').DS.$date->format('m').DS.$date->format('d');
		JFolder::create(JPATH_ROOT.DS.$uploaderPath);
		
		$result = $uploader->handleUpload($uploaderPath.DS);
		
		return $result;
	}
	
	function truncate($text, $length) {
		$length = abs((int)$length);
		if(strlen($text) > $length) {
			$text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
		}
		return($text);
	}
	
	/**
	 * Mark the message as an action taken by the user
	 */
	public function messageViewed() {
		$message_id = JRequest::getVar('message_id');
		// exit on no parameter
		if (!$message_id) exit;

		// exit if request made on non-login user
		$my = JXFactory::getUser();
		if (!$my->id) exit;

		$table = JTable::getInstance('Stream', 'StreamTable');
		// exit if message_dont exist
		if (!$table) exit;

		$table->load($message_id);
		$table->actionIsTaken($my->id, JRequest::getVar('item_id'));
		$table->store(true);
		exit;
	}

	/**
	 * Auto-complete search	 	
	 */ 
	public function search(){

		$result = array();
		
		// Search stream
		$filter = array();
		$filter['limit'] = 6;
		if( $term = JRequest::getVar('term', '') ){
			$filter['search'] = $term;
		}
		
		$model	= StreamFactory::getModel('stream');
		$data	= $model->getStream( $filter );
		
		if(!empty($data)) {
			foreach($data as $row){
				$obj = new StdClass();
				$obj->label = $this->truncate($row->message, 32);
				$obj->category = "test";
				
				$obj2 = new StdClass();
				$obj2->label = $this->truncate($row->message, 32);
				$obj2->category = "test2";
				
				//$result[] = array('label' =>$this->truncate($row->message, 32), 'category' => 'test');
				$result[] = $obj;
			}
			
			foreach($data as $row){
				$obj = new StdClass();
				$obj->label = $this->truncate($row->message, 32);
				$obj->category = "test";
				
				$obj2 = new StdClass();
				$obj2->label = $this->truncate($row->message, 32);
				$obj2->category = "test2";
				
				//$result[] = array('label' =>$this->truncate($row->message, 32), 'category' => 'test');
				$result[] = $obj2;
			}
		}
		
		// Search for people
		// @todo
		
		// Search for groups
		// @todo
		
		echo json_encode($result);
		exit;
	}
	
	public function notification()
	{
		$my = JXFactory::getUser();
		$data = array();
		
		
		// No notification for guest. Trigger a logout in user's browser
		if( !$my->id ) {
			$data['logout'] = true;
			echo json_encode($data);
			exit;
		}
		
		include_once(JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'html'.DS.'string.php');
		$phash = JRequest::getVar('pHash'); // public data cache hash
		
		$data['notification'] 	= array();
		$data['comments']	  	= array();
		$data['updates'] 		= array();
		
		// Get my groups
		$streamModel = StreamFactory::getModel('stream');
		$groupsModel = StreamFactory::getModel('groups');
		
		$groupIJoin 	= $my->getParam('groups_member');
		$groupIFollow 	= $my->getParam('groups_follow');
		$myGroups 	= JXUtility::csvMerge($groupIFollow, $groupIJoin);

		$myGroups = $groupsModel->getGroups(
			array('id' => $myGroups)
		);

		// Get public notices
		$lastMessageRead = $my->getParam('message_last_read');
		$publicCount = StreamMessage::countMessageSince($lastMessageRead);

		$data['notification']['company_updates'] = $publicCount;
		// we can merge this request with the group request so that template are fetched on the same loop but the group content is unique to each group
		// @todo: optimize the whole request
		$publicMessageContents = $streamModel->getStream(array('group_id' => 0), $publicCount);
		$publicMessagesHTML = '<ul>';
		foreach ($publicMessageContents as $row) {
			$tmpl = new StreamTemplate();
			$tmpl->set('stream', $row);
			$publicMessagesHTML .= $tmpl->fetch('notification.message');
			$data['updates'][0]['message_id'][] = $row->id;
		}
		$publicMessagesHTML .= '</ul>';
		$data['data_content'][0] = $publicMessagesHTML;
		$data['updates'][0]['name'] = 'public';
		$data['updates'][0]['notification'] = $publicCount;
		$data['updates'][0]['content'] = $publicMessagesHTML;
		
		// @todo: this is kinda heavy. Optimize further. Otherwise the live update
		// will kill us
		$updatedGroups = array();
		$updatedGroupsComment = array();
		foreach($myGroups as $group)
		{
			$lastReadId 		= $my->getParam('group_'.$group->id.'_read');
			$lastCommentId 		= $my->getParam('group_'. $group->id.'_comment');
			$groupLastMsg 		= $group->getParam('last_message');
			$groupLastComment	= $group->getParam('last_comment');
			$groupNewMsg 		= $streamModel->countStream(array('!user_id' => $my->id, 'id_more' => $lastReadId, 'group_id' => $group->id));
			$groupNewComment	= $streamModel->countComments(array('group_id' => $group->id, 'id_more' => $lastCommentId));
			
			//
			// the array key is the same as html id element of the <span> that
			// will take this value
			$data['notification']['groups_'.$group->id] 	= intval($groupNewMsg); // rand ( 0 , 20 );
			$data['comments']['groups_'.$group->id] 		= intval($groupNewComment); // rand ( 0 , 20 );
			$data['updates'][$group->id]['name'] = $group->name;
			$data['updates'][$group->id]['notification'] = intval($groupNewMsg);
			$data['updates'][$group->id]['comments'] = intval($groupNewComment);
			
			// Add popover data
			if($groupNewMsg > 0){
				$updatedGroups[] = $group;
			}
			
			if($groupNewComment > 0){
				$updatedGroupsComment[] = $group;
			}
		}
		
		// Get the popover data if necessary
		// This is done saperately to give opportunity for caching
		if( md5(serialize($data['notification']) . serialize($data['comments'])) != $phash ) {
		
			if(!empty($updatedGroups)){
				foreach($updatedGroups as $group)
				{
					$limit = $data['notification']['groups_'.$group->id];
					$newMessages = $streamModel->getStream(array('group_id' => $group->id), $limit);
					$newMessagesHTML ='<ul>';
					foreach($newMessages as $row)
					{
						$tmpl = new StreamTemplate();
						$tmpl->set('stream', $row);
						$newMessagesHTML .= $tmpl->fetch('notification.message');
					}
					$newMessagesHTML .= '</ul>';
					$data['data_content']['#groups_'.$group->id.'_link'] = $newMessagesHTML;
					$data['updates'][$group->id]['content'] = $newMessagesHTML;
				}
			}
			/*
			if(!empty($updatedGroupsComment)){
				foreach($updatedGroupsComment as $group)
				{
					$data['data_comment']['#groups_'.$group->id.'_link'] = '';
				}
			}
			*/
		}
		
		if($phash == md5(serialize($data['notification']) . serialize($data['comments']) ) ){
			$data['data_content'] = array();
			$data['notification'] = array();
			$data['comments']	  = array();
			$data['updates'] 	  = array();
		}
		
		$data['pHash'] = md5(serialize($data['notification']) . serialize($data['comments']) );
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
}