<?php
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.messaging.messaging');

class MessagingViewInbox extends MessagingView
{
	function display($tpl = null)
	{
		$doc = JFactory::getDocument();
		$my = JXFactory::getUser();

		$doc->setTitle(JText::_('COM_COMMUNITY_INBOX_PRIVATE_MESSAGING'));
		$this->addPathway(JText::_('COM_COMMUNITY_INBOX'));

		// Add file attachment library
		$doc->addScript(JURI::root() . 'media/uploader/fileuploader.js');
		$doc->addStyleSheet(JURI::root() . 'media/uploader/fileuploader.css');

		//JXModule::addBuffer('right', '<br /><h4>Side bar stuff goes here</h4>');

		$messagingModel = MessagingFactory::getModel('inbox');
		$msg =& $messagingModel->getInbox();

		// Add small avatar to each image
		if (!empty ($msg)) {
			foreach ($msg as $key => $val) {
				// based on the grouped message parent. check the unread message
				// count for this user.
				$filter ['parent'] = $val->parent;
				$filter ['user_id'] = $my->id;
				$unRead = $messagingModel->countUnRead($filter);
				$msg [$key]->unRead = $unRead;
			}
		}
		$data = new stdClass();
		$data->msg = $msg;

		$newFilter ['user_id'] = $my->id;
		$data->inbox = $messagingModel->countUnRead($newFilter);
		$data->pagination = & $messagingModel->getPagination();

		for ($i = 0; $i < count($data->msg); $i++) {
			$row =& $data->msg[$i];
			$user = JXFactory::getUser($row->from);

			$row->avatar = $user->getThumbAvatarURL();
			$row->isUnread = ($row->unRead > 0) ? true : false;

			$row->from_name = $user->name;
			$row->reply_count = $messagingModel->countReplies($row->parent);

			$row->recipients = $messagingModel->getParticipantsID($row->id, $my->id);
			$row->recipientsCount = count($row->recipients);
		}

		$to	= JRequest::getString('to', null, 'GET');
		
		if($to) {
			$toUser = JXFactory::getUser(JUserHelper::getUserId($to));
		}

		$this->assignRef('my', $my);
		$this->assignRef('toUser', $toUser);
		$this->assignRef('messages', $data->msg);
		$this->assign('totalMessages', $messagingModel->getUserInboxCount());

		parent::display($tpl);
	}

	/**
	 * Show the message reading page
	 */
	public function read($data)
	{
		$mainframe = JFactory::getApplication();
		$my = JFactory::getUser();
		$doc = JFactory::getDocument();

		// Add file attachment library
		$doc->addScript(JURI::root() . 'media/uploader/fileuploader.js');
		$doc->addStyleSheet(JURI::root() . 'media/uploader/fileuploader.css');

		$this->addPathway(JText::_('COM_COMMUNITY_INBOX'), JRoute::_('index.php?option=com_messaging&view=inbox'));

		//JXModule::addBuffer('right', '<br /><h4>Side bar stuff goes here</h4>');

		$inboxModel = MessagingFactory::getModel('inbox');

		$msgid = JRequest::getVar('msgid', 0, 'REQUEST');

		$parentData = '';
		$html = $htmlContent = '';
		$messageHeading = '';
		$recipient = array();

		$parentData = $inboxModel->getMessage($msgid);

		if (!empty($data->messages)) {
			$document = JFactory::getDocument();

			$document->setTitle($this->escape($parentData->subject));
			$this->addPathway($this->escape($parentData->subject));

			foreach ($data->messages as $row) {
				// onMessageDisplay Event trigger
				$args = array();
				$args[] =& $row;
				$user = JXFactory::getUser($row->from);

				//construct the delete link
				$deleteLink = JRoute::_('index.php?option=com_community&task=remove&msgid=' . $row->id);
				$authorLink = JRoute::_('index.php?option=com_profile&view=display&user=' . $user->username);

				$attachments = $inboxModel->getAttachments($row);
				$readBy = $inboxModel->getReadBy($row->id);

				$tmpl = new MessagingTemplate();
				$htmlContent .= $tmpl->set('user', $user)
					->set('msg', $row)
					->set('isMine', 0) // TODO:
					->set('removeLink', $deleteLink)
					->set('authorLink', $authorLink)
					->set('attachments', $attachments)
					->set('readBy', $readBy)
					->fetch('inbox.message');
			}

			$myLink = JRoute::_('index.php?option=com_profile&view=display');

			$recipient = array();

			// Put the sender in the participant list
			$from = new stdClass();
			$from->to = $parentData->from;
			$recipient[] = $from;

			$recipient = array_merge($recipient, $inboxModel->getRecepientMessage($msgid));

			$recepientCount = count($recipient) - 1; // exclude the sender
			$textOther = $recepientCount > 1 ? 'COM_COMMUNITY_MSG_OTHER' : 'COM_COMMUNITY_MSG_OTHER_SINGULAR';

			$messageHeading = JText::sprintf('COM_COMMUNITY_MSG_BETWEEN_YOU_AND_USER', $myLink, '#', JText::sprintf($textOther, $recepientCount));
		} else {
			$html = '<div class="text">' . JText::_('COM_COMMUNITY_INBOX_MESSAGE_EMPTY') . '</div>';
		}

		$tmplMain = new MessagingTemplate();
		$html .= $tmplMain->set('messageHeading', $messageHeading)
			->set('msgid', $msgid)
			->set('recipient', $recipient)
			->set('messages', $data->messages)
			->set('parentData', $parentData)
			->set('htmlContent', $htmlContent)
			->set('my', $my)
			->fetch('inbox.read');

		return $html;
	}
}