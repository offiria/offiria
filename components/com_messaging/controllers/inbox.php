<?php
defined('_JEXEC') or die('Restricted access');

require_once(JPATH_ROOT.DS.'components'.DS.'com_messaging'.DS.'factory.php');
require_once(JPATH_ROOT.DS.'components'.DS.'com_messaging'.DS.'tables'.DS.'file.php');  

class MessagingControllerInbox extends JController
{
	public function display($cachable = false, $urlparams = false)
	{
		JRequest::setVar('view', 'inbox');
		parent::display();
	}

	public function read()
	{
		$msgId = JRequest::getVar('msgid', '', 'REQUEST');
		$my = JFactory::getUser();

		$filter = array();

		$filter['msgId'] = $msgId;
		$filter['to'] = $my->id;

		$messagingModel = MessagingFactory::getModel('inbox');

		$data = new stdClass();
		$data->messages = $messagingModel->getMessages($filter);

		// mark as "read"
		$filter ['parent'] = $msgId;
		$filter ['user_id'] = $my->id;
		$messagingModel->markMessageAsRead ($filter);	

		$view = MessagingFactory::getView('inbox');
		echo $view->read($data);
	}

	/* Ajax */
	public function addReply()
	{
		$msgId = JRequest::getVar('msgid', '', 'POST');
		$reply = JRequest::getVar('reply', '', 'POST');
		$attachment = JRequest::getVar('attachment', array(), 'POST', 'array');

		$my = JXFactory::getUser();
		$messagingModel = MessagingFactory::getModel('inbox');

		$message = $messagingModel->getMessage($msgId);

		if ($my->id == 0) {
			return;
		}

		$now = new JDate();

		$obj = new stdClass();
		$obj->id = null;
		$obj->from = $my->id;
		$obj->posted_on = $now->toMySQL();
		$obj->from_name = $my->name;
		$obj->subject = 'RE:' . $message->subject;
		$obj->body = $reply;
		$obj->attachment = implode(', ', $attachment);

		$messagingModel->sendReply($obj, $msgId);
		$deleteLink = JRoute::_('index.php?option=com_community&view=inbox&task=remove&msgid=' . $obj->id);
		$authorLink = JRoute::_('index.php?option=com_community&view=profile&userid=' . $my->id);

		$this->_addAttachments($obj->id);
		$attachments = $messagingModel->getAttachments($obj);

		$tmpl = new MessagingTemplate();
		$tmpl->set('user', JXFactory::getUser($obj->from));
		$tmpl->set('msg', $obj);
		$tmpl->set('removeLink', $deleteLink);
		$tmpl->set('authorLink', $authorLink);
		$tmpl->set('attachments', $attachments);
		$html = $tmpl->fetch('inbox.message');

		$data = array();
		$data['html'] = $html;

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}

	/* Ajax */
	public function addMessage()
	{
		$my = JXFactory::getUser();

		$messageData['subject'] = JRequest::getVar('subject', '', 'POST');
		$messageData['body'] = JRequest::getVar('body', '', 'POST');
		$messageData['to'] = JRequest::getVar('members', array(), 'POST', 'array');
		$messageData['attachment'] = JRequest::getVar('attachment', array(), 'POST', 'array');

		if ($my->id == 0) {
			return false;
		}

		$messagingModel = MessagingFactory::getModel('inbox');

		$lastSent = $messagingModel->getLastSentTime($my->id);
		$doCont = true;
		$errMsg = "";
		$html = '';

		if ($doCont) {
			$pattern = "/<br \/>/i";
			$replacement = "\r\n";
			$messageData['body'] = preg_replace($pattern, $replacement, $messageData['body']);

			$msgid = $messagingModel->send($messageData);

			$html = JText::_('COM_COMMUNITY_INBOX_MESSAGE_SENT');

			$this->_addAttachments($msgid);
		}

		$data = array();
		$data['html'] = $html;

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}

	public function removeMessage()
	{
		$msgId = JRequest::getVar('msgid', '', 'POST');

		$my = JXFactory::getUser();
		$messagingModel = MessagingFactory::getModel('inbox');
		$data = array();

		if ($messagingModel->removeReceivedMsg($msgId, $my->id)) {
			$data['id'] = $msgId;
			header('Content-Type: text/json');
			echo json_encode($data);
		}

		exit;
	}

	/**
	 * Remove a message via ajax
	 * A user can only remove a message that he can read/reply to.
	 */
	public function removeFullMessages()
	{
		$msgId = JRequest::getVar('msgid', '', 'POST');

		$my = JXFactory::getUser();
		$messagingModel = MessagingFactory::getModel('inbox');
			
		if($my->id == 0)
		{
			exit;
		}
		
		$conv = $messagingModel->getFullMessages($msgId);						
		$delCnt = 0;

		$filter = array ();
		$parentId = $messagingModel->getParent( $msgId );

		$filter ['msgId'] = $parentId;
		$filter ['to'] = $my->id;

		$data = new stdClass();
		$data->messages = $messagingModel->getMessages($filter, true);
			   
		$childCount = count($data->messages);
				  
		if(!empty($conv))
		{
			foreach($conv as $msg)
			{
				if($messagingModel->canReply($my->id, $msg->id)) {
					if ($messagingModel->removeReceivedMsg($msg->id, $my->id)) {						
						$delCnt++;
					}
				}
			}
		}
		
		$data = array();
		$data['id'] = $msgId;

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}

	public function addParticipant()
	{
		$participantId = JRequest::getVar('participantid', '', 'POST');
		$msgId = JRequest::getVar('msgid', '', 'POST');

		$user = JXFactory::getUser($participantId);

		$messagingModel = MessagingFactory::getModel('inbox');

		$messageData = new stdClass();
		$messageData->message = $messagingModel->getMessage($msgId);

		$messagingModel->addReceipient($messageData->message, $user->id);

		$data = array();
		$data['thumb'] = $user->getThumbAvatarURL();

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}

	public function removeParticipant()
	{
		$participantId = JRequest::getVar('participantid', '', 'POST');
		$msgId = JRequest::getVar('msgid', '', 'POST');

		$messagingModel = MessagingFactory::getModel('inbox');
		$messagingModel->removeReceipient($msgId, $participantId);

		$data = array();
		$data['id'] = $participantId;

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}

	public function peopleAutocomplete() {
		$db = JFactory::getDbo();
		$query = "SELECT id FROM #__users"
			. " WHERE " . $db->nameQuote('username')
			. " NOT LIKE " . $db->quote('%admin%');

		$db->setQuery( $query );
		$results	= $db->loadObjectList();

		$peopleList = array();
		foreach($results as $row){
			$user = JXFactory::getUser($row->id);
			$peopleList[] = array('user_id'=>$user->id, 'user_name' => $user->name, 'user_fullname' => $user->username, 'thumb' => $user->getThumbAvatarURL());
		}

		header('Content-Type: text/json');
		echo json_encode($peopleList);
		exit;
	}

	private function _addAttachments($msgid)
	{
		$attachments = JRequest::getVar('attachment', array(), 'POST', 'array');

		// If there are attachments, update the file records
		if(!empty($attachments)){
			foreach($attachments as $fileid)
			{
				$file = JTable::getInstance('File' , 'MessagingTable');
				if(!empty($fileid) && $file->load($fileid) ){
					$file->msg_id = $msgid;
					$file->store();
				}
			} 
		}
	}

	/**
	 * Handle qqUplaoder - this is taken from the system controller. Needs to be reused.
	 */	 	
	public function upload()
	{		
		$my = JXFactory::getUser();
		
		$result = $this->handleUpload();

		// File upload successful, store data in db
		if(isset($result['success'])) {
			$file = JTable::getInstance('File', 'MessagingTable');

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
		
		// To pass data through iframe you will need to encode all html tags
		echo htmlspecialchars(json_encode($result), ENT_NOQUOTES);
		exit;
	}

	/**
	 * Mark message as Read
	 */
	public function markAsRead(){
		$msgId = JRequest::getVar('msgid', '', 'POST');
				
		$my = JXFactory::getUser();
		$messagingModel = MessagingFactory::getModel('inbox');
				
		if($my->id == 0)
		{
			exit;
		}

		$filter = array(
			'parent'	=> $msgId,
			'user_id'   => $my->id
		);

		if ($messagingModel->markAsRead($filter)) {
			$data['id'] = $msgId;
			header('Content-Type: text/json');
			echo json_encode($data);
		}

		exit;
	}

	/**
	 * Mark message as Unread
	 */
	public function markAsUnread(){
		$msgId = JRequest::getVar('msgid', '', 'POST');
				
		$my = JXFactory::getUser();
		$messagingModel = MessagingFactory::getModel('inbox');
				
		if($my->id == 0)
		{
			exit;
		}

		$filter = array(
			'parent'	=> $msgId,
			'user_id'   => $my->id
		);

		if ($messagingModel->markAsUnread($filter)) {
			$data['id'] = $msgId;
			header('Content-Type: text/json');
			echo json_encode($data);
		}

		exit;
	}

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

	/**
	 * Download file
	 */	 	
	public function download() {
		$my = JXFactory::getUser();
		$fileid = JRequest::getVar('file_id');
		$file = JTable::getInstance('File' , 'MessagingTable');
		
		if($file->load($fileid))
		{
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
			
			$file->store();
			exit;
		} else {
			echo "File Not found";
		}
		return;
	}
}