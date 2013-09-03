<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.application.component.helper');
jimport('joomla.user.helper');

JTable::addIncludePath(JPATH_ROOT . DS . 'components' . DS . 'com_messaging' . DS . 'tables');

class MessagingModelInbox extends JModel
{
	var $_data = null;
	var $_pagination = null;

	/**
	 * Return the conversation list
	 */
	public function &getInbox($_isread = true)
	{
		jimport('joomla.html.pagination');
		$my = JFactory::getUser();
		$to = $my->id;

		if (empty($this->_data)) {
			$this->_data = array();

			$db = $this->getDBO();

			$sql = 'SELECT MAX(b.' . $db->nameQuote('id') . ') AS ' . $db->nameQuote('bid');
			$sql .= ' FROM ' . $db->nameQuote('#__msg_recepient') . ' as a, ' . $db->nameQuote('#__msg') . ' as b';
			$sql .= ' WHERE (a.' . $db->nameQuote('to') . ' = ' . $db->Quote($to) . ' OR a.' . $db->nameQuote('msg_from') . ' = ' . $db->Quote($to) . ')';
			$sql .= ' AND b.' . $db->nameQuote('id') . ' = a.' . $db->nameQuote('msg_id');
			$sql .= ' AND (a.' . $db->nameQuote('deleted') . '=' . $db->Quote(0) . ' || (a.' . $db->nameQuote('deleted') . '=' . $db->Quote(1) . ' && b.from =' . $to . '))';
			$sql .= ' AND (b.' . $db->nameQuote('deleted') . '=' . $db->Quote(0) . ' || (b.' . $db->nameQuote('deleted') . '=' . $db->Quote(1) . ' && b.from !=' . $to . '))';
			$sql .= ' GROUP BY b.' . $db->nameQuote('parent');
			$db->setQuery($sql);
			$tmpResult = $db->loadObjectList();
			$strId = '';

			foreach ($tmpResult as $tmp) {
				if (empty($strId)) $strId = $tmp->bid;
				else $strId = $strId . ',' . $tmp->bid;
			}

			$result = null;

			if (!empty($strId)) {
				$sql = 'SELECT b.' . $db->nameQuote('id') . ', b.' . $db->nameQuote('from') . ', b.' . $db->nameQuote('parent') . ', b.' . $db->nameQuote('from_name') . ', b.' . $db->nameQuote('posted_on') . ', b.' . $db->nameQuote('subject') . ', b.' . $db->nameQuote('body') . ', a.' . $db->nameQuote('to');
				$sql .= ' FROM ' . $db->nameQuote('#__msg') . ' as b, ' . $db->nameQuote('#__msg_recepient') . ' as a ';
				$sql .= ' WHERE b.' . $db->nameQuote('id') . ' in (' . $strId . ')';
				$sql .= ' AND b.' . $db->nameQuote('id') . ' = a.' . $db->nameQuote('msg_id');
				if (!$_isread) {
					$sql .= ' AND a.' . $db->nameQuote('is_read') . ' = ' . $db->Quote('0');
				}
				$sql .= ' AND (a.' . $db->nameQuote('deleted') . '=' . $db->Quote(0) . ' || (a.' . $db->nameQuote('deleted') . '=' . $db->Quote(1) . ' && b.from =' . $to . '))';
				$sql .= ' AND (b.' . $db->nameQuote('deleted') . '=' . $db->Quote(0) . ' || (b.' . $db->nameQuote('deleted') . '=' . $db->Quote(1) . ' && b.from !=' . $to . '))';

				$sql .= ' ORDER BY b.' . $db->nameQuote('posted_on') . ' DESC';

				$db->setQuery($sql);
				$result = $db->loadObjectList();
				if ($db->getErrorNum()) {
					JError::raiseError(500, $db->stderr());
				}
			}

			// For each message, find the parent+from, group them together 
			$inboxResult = array();
			if (!empty($result)) {
				foreach ($result as $row) {
					$inboxResult[$row->parent] = $row;
				}
			}

			$limit = $this->getState('limit');
			$limitstart = $this->getState('limitstart');
			if (empty($this->_pagination)) {
				$this->_pagination = new JPagination(count($inboxResult), $limitstart, $limit);
				$inboxResult = array_slice($inboxResult, $limitstart, $limit);
			}

			return $inboxResult;
		}

		return null;
	}

	/**
	 * get Parent
	 */
	public function getParent($msgId)
	{
		$db = $this->getDBO();

		if (empty($msgId))
			return 0;

		$sql = 'select parent';
		$sql .= ' from ' . $db->nameQuote('#__msg');
		$sql .= ' where ' . $db->nameQuote('id') . ' = ' . $db->Quote($msgId);
		$db->setQuery($sql);
		$result = $db->loadObject();

		if ($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr() . 'Messsage not found');
		}

		return $result->parent;
	}

	/**
	 * Get pagination
	 */
	public function &getPagination()
	{
		return $this->_pagination;
	}

	/**
	 * Return list of sent items
	 */
	public function &getSent()
	{
		jimport('joomla.html.pagination');
		$my = JFactory::getUser();
		$from = $my->id;

		$limit = $this->getState('limit');
		$limitstart = $this->getState('limitstart');

		if (empty($this->_data)) {
			$this->_data = array();

			$db = $this->getDBO();

			$sql = 'SELECT b.*, a.' . $db->nameQuote('to') . ', c.' . $db->nameQuote('name') . ' as ' . $db->nameQuote('to_name')
				. ' FROM ' . $db->nameQuote('#__msg_recepient') . ' as a, '
				. $db->nameQuote('#__msg') . ' as b, ' . $db->nameQuote('#__users') . ' c '
				. ' WHERE '
				. " b.`from` = {$from} AND "
				. ' b.' . $db->nameQuote('deleted') . '=' . $db->Quote(0)
				. ' AND b.' . $db->nameQuote('id') . ' = a.' . $db->nameQuote('msg_id')
				. ' AND a.' . $db->nameQuote('to') . ' = c.' . $db->nameQuote('id')
				. ' ORDER BY b.' . $db->nameQuote('posted_on') . ' DESC LIMIT ' . $limitstart . ',' . $limit;

			$db->setQuery($sql);
			$result = $db->loadObjectList();

			if ($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}

			$sql = 'SELECT COUNT(*) as numresult'
				. ' FROM ' . $db->nameQuote('#__msg_recepient') . ' as a, '
				. $db->nameQuote('#__msg') . ' as b, ' . $db->nameQuote('#__users') . ' c '
				. ' WHERE '
				. " b.`from` = {$from} AND "
				. ' b.' . $db->nameQuote('deleted') . '=' . $db->Quote(0)
				. ' AND b.' . $db->nameQuote('id') . ' = a.' . $db->nameQuote('msg_id')
				. ' AND a.' . $db->nameQuote('to') . ' = c.' . $db->nameQuote('id')
				. ' ORDER BY b.' . $db->nameQuote('posted_on') . ' DESC ';


			$db->setQuery($sql);
			$res = $db->loadObjectList();
			$numresult = $res[0]->numresult;

			// For each message, find the parent+from, group them together
			$inboxResult = array();
			$inToName = array();
			$inToId = array();

			if (!empty($result)) {
				foreach ($result as $row) {
					if (!isset($inboxResult[$row->parent])) {
						$inToName[$row->parent][$row->to_name] = $row->to_name;
						$inToId[$row->parent][$row->to] = $row->to;
						$inboxResult[$row->parent] = $row;
					}
				}
			}

			//now rewrite back the to / to_name
			foreach ($inboxResult as $row) {
				$inboxResult[$row->parent]->to = $inToId[$row->parent];
				$inboxResult[$row->parent]->to_name = $inToName[$row->parent];
			}

			if (empty($this->_pagination)) {
				$this->_pagination = new JPagination($numresult, $limitstart, $limit);
				$inboxResult = array_values($inboxResult);
			}

			return $inboxResult;
		}

		return null;
	}

	/**
	 * Return the full messages
	 */
	public function getFullMessages($id)
	{
		$db = $this->getDBO();

		$parentmsgid = $this->getParent($id);

		$query = 'SELECT * FROM ' . $db->nameQuote('#__msg')
			. ' WHERE ' . $db->nameQuote('parent') . '=' . $db->Quote($parentmsgid);
		$query .= ' ORDER BY ' . $db->nameQuote('id');
		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;
	}

	/**
	 * Return the sent messages for later removal.
	 */
	public function getSentMessages($id)
	{
		$db = $this->getDBO();
		$my = CFactory::getUser();

		$parentmsgid = $this->getParent($id);

		$query = 'SELECT * FROM ' . $db->nameQuote('#__msg')
			. ' WHERE ' . $db->nameQuote('parent') . '=' . $db->Quote($parentmsgid);
		$query .= ' AND ' . $db->nameQuote('from') . ' = ' . $db->Quote($my->id);
		$query .= ' ORDER BY ' . $db->nameQuote('id');
		$db->setQuery($query);

		$result = $db->loadObjectList();

		return $result;
	}

	/**
	 * Return the users who have read the message.
	 */
	public function getReadBy($id)
	{
		$db = $this->getDBO();

		$query = 'SELECT DISTINCT(' . $db->nameQuote('to') . ') FROM ' . $db->nameQuote('#__msg_recepient')
			. ' WHERE ' . $db->nameQuote('msg_id') . '=' . $db->Quote($id)
			. ' AND ' . $db->nameQuote('is_read') . '=' . $db->Quote(1);

		$db->setQuery($query);

		$result = $db->loadResultArray();

		return $result;
	}

	/**
	 * Return the message
	 */
	public function &getMessage($id)
	{
		$db = $this->getDBO();
		$sql = 'SELECT * FROM ' . $db->nameQuote('#__msg')
			. ' WHERE ' . $db->nameQuote('id') . '=' . $db->Quote($id);

		$db->setQuery($sql);
		$result = $db->loadObject();

		if ($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr() . 'Messsage not found');
		}

		return $result;
	}

	/**
	 * Return the recipient message
	 */
	public function &getRecepientMessage($id)
	{
		$db = $this->getDBO();
		$sql = 'SELECT * FROM ' . $db->nameQuote('#__msg_recepient')
			. ' WHERE ' . $db->nameQuote('msg_id') . '=' . $db->Quote($id);

		$db->setQuery($sql);
		$result = $db->loadObjectList();

		if ($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr() . 'Messsage not found');
		}

		return $result;
	}

	/**
	 * Return the time the given user send the last message
	 */
	public function getLastSentTime($id)
	{
		$user = JXFactory::getUser($id);
		$db = $this->getDBO();
		$sql = 'SELECT ' . $db->nameQuote('posted_on')
			. ' FROM ' . $db->nameQuote('#__msg')
			. ' WHERE ' . $db->nameQuote('from') . '=' . $db->Quote($id)
			. ' ORDER BY ' . $db->nameQuote('posted_on') . ' DESC';

		$db->setQuery($sql);
		$postedOn = $db->loadResult();

		if (empty($postedOn)) {
			// set to a far distance past to indicate last sent time was
			// very far away in the past
			return new JDate('1990-01-01 10:00:00');
		} else {
			return new JDate($postedOn);
		}
	}

	/**
	 * Return the latest recipient message based on parent message id.
	 */
	public function &getUserMessage($id)
	{
		$my = JFactory::getUser();
		$to = $my->id;

		$db = $this->getDBO();

		$sql = 'select a.* from ' . $db->nameQuote('#__msg_recepient') . ' a';
		$sql .= " where a." . $db->nameQuote('to') . " = {$to} and a." . $db->nameQuote('msg_parent') . " = (select distinct b." . $db->nameQuote('msg_parent');
		$sql .= ' from ' . $db->nameQuote('#__msg_recepient') . ' b where b.' . $db->nameQuote('msg_id') . ' = ' . $db->Quote($id) . ')';
		$sql .= ' order by ' . $db->nameQuote('msg_id') . ' desc limit 1';

		$db->setQuery($sql);
		$result = $db->loadObject();

		if ($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr() . 'Messsage not found');
		}

		return $result;
	}

	public function &getMessages($filter = array(), $read = false)
	{
		$my = JFactory::getUser();
		$db = $this->getDBO();

		if (empty($this->_data)) {
			$this->_data = array();

			$isRead = "";
			if ($read) {
				$isRead = ' AND b.is_read=0';
			}

			$sql = 'SELECT a.*, b.' . $db->nameQuote('to') . ', b.' . $db->nameQuote('deleted') . ' as ' . $db->nameQuote('to_deleted') . ', b.' . $db->nameQuote('is_read')
				. ' FROM ' . $db->nameQuote('#__msg') . ' a, ' . $db->nameQuote('#__msg_recepient') . ' b'
				. ' where a.' . $db->nameQuote('parent') . ' = ' . $db->Quote($filter['msgId'])
				. ' and  b.' . $db->nameQuote('msg_parent') . ' = ' . $db->Quote($filter['msgId'])
				. ' and  a.' . $db->nameQuote('id') . ' = b.' . $db->nameQuote('msg_id') . $isRead
				. ' order by a.' . $db->nameQuote('id') . ' desc, a.' . $db->nameQuote('deleted') . ' desc, b.' . $db->nameQuote('deleted') . ' desc';

			$db->setQuery($sql);
			if ($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}

			// Now, we get all the conversation within this discussion
			$allMsgFromMe = $db->loadObjectList();

			// perform further filtering
			$prev_id = 0;
			foreach ($allMsgFromMe as $row) {
				$showMsg = true;

				if ($row->to == $my->id) { //message for me.
					$showMsg = ($row->to_deleted == 0);
				} else if ($row->from == $my->id) { // message from me
					$showMsg = ($row->deleted == 0);
				}

				// check whether this message id is the same as previous one or not.
				// if yes...mean the message send to multiple users. We need to show
				// only one time.
				if ($showMsg) {
					$showMsg = ($row->id != $prev_id);
				}

				//update the flag for next checking.
				$prev_id = $row->id;

				if ($showMsg) {
					//append message into array object
					$this->_data[] = $row;
				}
			}

			//reverse the array so that it show the old to latest.
			$this->_data = array_reverse($this->_data);
		}

		return $this->_data;
	}


	public function send($vars)
	{
		$db = $this->getDBO();
		$my = JFactory::getUser();

		$date = JFactory::getDate(); //get the time without any offset!
		$cDate = $date->toMySQL();

		$obj = new stdClass();
		$obj->id = null;
		$obj->from = $my->id;
		$obj->posted_on = $date->toMySQL();
		$obj->from_name = $my->name;
		$obj->subject = $vars['subject'];
		$obj->body = $vars['body'];
		$obj->attachment = implode(', ', $vars['attachment']);

		// Don't add message if user is sending message to themselves
		if ($vars['to'] != $my->id) {
			$db->insertObject('#__msg', $obj, 'id');

			// Update the parent
			$obj->parent = $obj->id;
			$db->updateObject('#__msg', $obj, 'id');
		}

		if (is_array($vars['to'])) {
			//multiple recepint
			foreach ($vars['to'] as $sToId) {
				if ($vars['to'] != $my->id)
					$this->addReceipient($obj, $sToId);
			}
		} else {
			//single recepient
			if ($vars['to'] != $my->id)
				$this->addReceipient($obj, $vars['to']);
		}

		return $obj->id;
	}

	/**
	 *
	 */
	public function sendReply($obj, $replyMsgId)
	{
		$db = $this->getDBO();
		$my = JFactory::getUser();

		$originalMsg = JTable::getInstance('Message', 'MessagingTable');

		// get original sender from obj
		$originalMsg->load($replyMsgId);

		$recepientMsg = $this->getRecepientMessage($replyMsgId);
		$parentId = $originalMsg->parent;

		$db->insertObject('#__msg', $obj, 'id');

		// Update the parent
		$obj->parent = $parentId;
		$db->updateObject('#__msg', $obj, 'id');

		if (is_array($recepientMsg)) {
			$recepientId = $this->getParticipantsID($replyMsgId, $my->id);

			foreach ($recepientId as $sToId) {
				$this->addReceipient($obj, $sToId);
			}
		} else {
			// add receipient, get the 'to' address from the original
			// sender. BUT, in some case where user try to post two message in
			// a row, the 'from' will failed. instead, we need to use 'to' from 
			// the original message.
			$recepientId = $originalMsg->from;
			if ($my->id == $originalMsg->from) {
				$recepientId = $recepientMsg->to;
			}
			$this->addReceipient($obj, $recepientId);
		}

		return true;
	}

	/**
	 * Add recipient
	 */
	public function addReceipient($msgObj, $recepientId)
	{
		$db = $this->getDBO();
		$my = JFactory::getUser();

		$recepient = new stdClass();
		$recepient->msg_id = $msgObj->id;
		$recepient->msg_parent = $msgObj->parent;
		$recepient->msg_from = $msgObj->from;
		$recepient->to = $recepientId;

		if ($my->id != $recepientId)
			$db->insertObject('#__msg_recepient', $recepient);

		if ($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		return $this;
	}

	/**
	 * Remove recipient
	 */
	public function removeReceipient($parentMsgId, $recepientId)
	{
		$db = $this->getDBO();

		/*$sql = 'UPDATE ' . $db->nameQuote('#__msg_recepient')
			. ' SET ' . $db->nameQuote('deleted') . '=' . $db->Quote('1')
			. ' WHERE ' . $db->nameQuote('msg_parent') . '=' . $db->Quote($parentMsgId) . ' AND ' . $db->nameQuote('to') . '=' . $db->Quote($recepientId);*/

		$sql = 'DELETE FROM ' . $db->nameQuote('#__msg_recepient')
			. ' WHERE ' . $db->nameQuote('msg_parent') . '=' . $db->Quote($parentMsgId) . ' AND ' . $db->nameQuote('to') . '=' . $db->Quote($recepientId);

		$db->setQuery($sql);
		$db->query();

		if ($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		return $this;
	}

	/**
	 * Remove the received message
	 */
	public function removeReceivedMsg($msgId, $userid)
	{
		$db = $this->getDBO();

		// get original sender and recepient
		$originalMsg = JTable::getInstance('Message', 'MessagingTable');
		$originalMsg->load($msgId);

		$recepientMsg = $this->getRecepientMessage($msgId);

		// we need to determine which table we needed for message removal.
		// we 1st check on the original message 'from', current user id matched,
		// then we remove from master table.
		// ELSE, we remove from child table.

		$sql = "";
		$delFrom = false;
		$delTo = false;
		if ($originalMsg->from == $userid) {
			$sql = 'UPDATE ' . $db->nameQuote('#__msg')
				. ' SET ' . $db->nameQuote('deleted') . '=' . $db->Quote('1')
				. ' WHERE ' . $db->nameQuote('id') . '=' . $db->Quote($msgId) . ' AND ' . $db->nameQuote('from') . '=' . $db->Quote($userid);

			//executing update query
			$db->setQuery($sql);
			$db->query();
			$delFrom = true;
		}

		if (is_array($recepientMsg)) {
			//multi recepient
			//echo "array";

			foreach ($recepientMsg as $row) {
				if ($row->to == $userid) {
					$sql = 'UPDATE ' . $db->nameQuote('#__msg_recepient')
						. ' SET ' . $db->nameQuote('deleted') . '=' . $db->Quote('1')
						. ' WHERE ' . $db->nameQuote('msg_id') . '=' . $db->Quote($msgId) . ' AND ' . $db->nameQuote('to') . '=' . $db->Quote($userid);
					//executing update query
					$db->setQuery($sql);
					$db->query();
					$delTo = true;
				}
			}
		} else {
			if ($recepientMsg->to == $userid) {
				$sql = 'UPDATE ' . $db->nameQuote('#__msg_recepient')
					. ' SET ' . $db->nameQuote('deleted') . '=' . $db->Quote('1')
					. ' WHERE ' . $db->nameQuote('msg_id') . '=' . $db->Quote($msgId) . ' AND ' . $db->nameQuote('to') . '=' . $db->Quote($userid);
				//executing update query
				$db->setQuery($sql);
				$db->query();
				$delTo = true;
			}
		}

		if ($delFrom == false && $delTo == false) {
			//both oso not matched. return false.
			return false;
		}

		return true;
	}

	public function &getUserId($param = array())
	{
		$db = $this->getDBO();
		$userId = 0;
		$sql = "";

		if (!empty($param['name'])) {
			// get from users table
			$sql = 'select ' . $db->nameQuote('id')
				. ' from ' . $db->nameQuote('#__users')
				. ' where ' . $db->nameQuote('username') . ' = ' . $db->Quote($param['name']);
		} else {
			// get from community_message table
			$sql = 'select ' . $db->nameQuote('from') . ' as ' . $db->nameQuote('id')
				. ' from ' . $db->nameQuote('#__community_message')
				. ' where ' . $db->nameQuote('id') . ' = ' . $db->Quote($param['id']);
		}

		$db->setQuery($sql);
		if ($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		$result = $db->loadResult();

		if (!empty($result)) $userId = $result;

		return $userId;
		// JError::raiseError( 500, 'Receiver id not found.');
	}

	/**
	 * Mark a message as "read" (opened)
	 * @param    object         parent message id
	 * @param    object         current user id
	 */
	public function markMessageAsRead($filter)
	{
		$db = $this->getDBO();
		$my = JFactory::getUser();

		// update all the messages that belong to current user.
		$sql = 'UPDATE ' . $db->nameQuote('#__msg_recepient')
			. ' SET ' . $db->nameQuote('is_read') . '= ' . $db->Quote('1')
			. ' WHERE ' . $db->nameQuote('msg_parent') . '=' . $db->Quote($filter['parent']) . ' AND ' . $db->nameQuote('to') . '=' . $db->Quote($filter['user_id'])
			. ' AND ' . $db->nameQuote('is_read') . '= ' . $db->Quote('0');

		//executing update query
		$db->setQuery($sql);
		$db->query();

		return true;
	}

	/**
	 * Mark a message as "new"
	 * @param    object         parent message id
	 * @param    object         current user id
	 */
	public function markMessageAsUnread($filter)
	{
		$db = $this->getDBO();
		$my = JFactory::getUser();

		// update all the messages that belong to current user.
		$sql = 'UPDATE ' . $db->nameQuote('#__msg_recepient')
			. ' SET ' . $db->nameQuote('is_read') . '=' . $db->Quote('0')
			. ' WHERE ' . $db->nameQuote('msg_parent') . '=' . $db->Quote($filter['parent']) . ' AND ' . $db->nameQuote('to') . '=' . $db->Quote($filter['user_id'])
			. ' AND ' . $db->nameQuote('is_read') . '= ' . $db->Quote('1');

		//executing update query
		$db->setQuery($sql);
		$db->query();

		return true;
	}

	/**
	 * Mark a message as "read" (opened) from Inbox page
	 * @param    object         message id
	 * @param    object         current user id
	 */
	public function markAsRead($filter)
	{
		$db = $this->getDBO();
		$my =& JXFactory::getUser();

		// update all the messages that belong to current user.
		$sql = 'UPDATE ' . $db->nameQuote('#__msg_recepient')
			. ' SET ' . $db->nameQuote('is_read') . '= ' . $db->Quote('1')
			. ' WHERE ' . $db->nameQuote('msg_id') . '=' . $db->Quote($filter['parent']) . ' AND ' . $db->nameQuote('to') . '=' . $db->Quote($filter['user_id'])
			. ' AND ' . $db->nameQuote('is_read') . '= ' . $db->Quote('0');

		//executing update query
		$db->setQuery($sql);
		$db->query();

		return true;
	}

	/**
	 * Mark a message as "read" (opened) from Inbox page
	 * @param    object         message id
	 * @param    object         current user id
	 */
	public function markAsUnread($filter)
	{
		$db = $this->getDBO();
		$my =& JXFactory::getUser();

		// update all the messages that belong to current user.
		$sql = 'UPDATE ' . $db->nameQuote('#__msg_recepient')
			. ' SET ' . $db->nameQuote('is_read') . '= ' . $db->Quote('0')
			. ' WHERE ' . $db->nameQuote('msg_id') . '=' . $db->Quote($filter['parent']) . ' AND ' . $db->nameQuote('to') . '=' . $db->Quote($filter['user_id'])
			. ' AND ' . $db->nameQuote('is_read') . '= ' . $db->Quote('1');

		//executing update query
		$db->setQuery($sql);
		$db->query();

		return true;
	}

	/**
	 * Check if the user can reply to this message thread
	 */
	public function canReply($userid, $msgId)
	{
		$db = $this->getDBO();
		$sql = 'SELECT COUNT(*) FROM ' . $db->nameQuote('#__msg_recepient')
			. ' WHERE (' . $db->nameQuote('msg_parent') . '=' . $db->Quote($msgId) . ' OR ' . $db->nameQuote('msg_id') . '=' . $db->Quote($msgId) . ' ) '
			. ' AND ( ' . $db->nameQuote('to') . '=' . $db->Quote($userid) . ' OR ' . $db->nameQuote('msg_from') . '=' . $db->Quote($userid) . ' )';

		$db->setQuery($sql);
		//echo $db->getQuery(); 

		return $db->loadResult();
	}

	/**
	 * Check if user can read this message.
	 *
	 * @param    string     userid
	 * @param     string    msgID : should be the parent message
	 */
	public function canRead($userid, $msgId)
	{
		// really, if the user can reply to this message, then he can read it
		return $this->canReply($userid, $msgId);
	}

	public function getTotalMessageSent($userId)
	{
		CFactory::load('helpers', 'time');
		$date = CTimeHelper::getDate();
		$db = $this->getDBO();

		//Joomla 1.6 JDate::getOffset returns in second while in J1.5 it's in hours
		$query = 'SELECT COUNT(*) FROM ' . $db->nameQuote('#__msg') . ' AS a '
			. 'WHERE a.' . $db->nameQuote('from') . '=' . $db->Quote($userId)
			. ' AND TO_DAYS(' . $db->Quote($date->toMySQL(true)) . ') - TO_DAYS( DATE_ADD( a.' . $db->nameQuote('posted_on')
			. ' , INTERVAL ' . ((C_JOOMLA_15) ? $date->getOffset() : $date->getOffset() / 3600) . ' HOUR ) ) = ' . $db->Quote('0')
			. ' AND a.' . $db->nameQuote('parent') . '=a.' . $db->nameQuote('id');
		$db->setQuery($query);

		$count = $db->loadResult();

		return $count;
	}

	/**
	 * Get unread message count for current user
	 * @param    int        parent message id
	 * @param    int        current user id
	 * @return  int     unread message count
	 */
	public function countUnRead($filter)
	{
		$db = $this->getDBO();
		$unRead = 0;

		// Skip the whole db query if no user specified
		if (empty($filter['user_id']))
			return 0;

		$sql = 'select count(' . $db->Quote('1') . ') as ' . $db->nameQuote('unread_count');
		$sql .= ' from ' . $db->nameQuote('#__msg_recepient');
		$sql .= ' where ' . $db->nameQuote('is_read') . ' = ' . $db->Quote('0');
		if (!empty($filter['parent']))
			$sql .= ' and ' . $db->nameQuote('msg_parent') . ' =' . $db->Quote($filter['parent']);
		if (!empty($filter['user_id']))
			$sql .= ' and ' . $db->nameQuote('to') . ' =' . $db->Quote($filter['user_id']);

		$sql .= ' and ' . $db->nameQuote('deleted') . ' = ' . $db->Quote('0');
		$db->setQuery($sql);
		$result = $db->loadObject();

		if (!empty($result)) {
			$unRead = $result->unread_count;
		}

		return $unRead;
	}

	/**
	 * Get total recepient conversation message count for a message.
	 */
	public function getRecepientCount($filter)
	{
		$db = $this->getDBO();
		$msgCnt = 0;

		$sql = 'select count(' . $db->Quote('1') . ') as ' . $db->nameQuote('recepient_count');
		$sql .= ' from ' . $db->nameQuote('#__msg_recepient');
		$sql .= ' where ' . $db->nameQuote('msg_parent') . ' = ' . $db->Quote($filter['parent']);
		if (!empty($filter['user_id']))
			$sql .= " and `to` !=" . $db->Quote($filter['user_id']);

		$db->setQuery($sql);
		$result = $db->loadObject();

		if (!empty($result)) {
			$msgCnt = $result->unread_count;
		}

		return $msgCnt;
	}

	/* Count the number of replies in a thread */
	public function countReplies($msgid)
	{
		$db = $this->getDBO();

		// with the given msgid, get the parent.
		$sql = 'SELECT ' . $db->nameQuote('id');
		$sql .= ' FROM ' . $db->nameQuote('#__msg');
		$sql .= ' WHERE ' . $db->nameQuote('parent') . ' = ' . $db->Quote($msgid);

		$db->setQuery($sql);
		$results = $db->loadObjectList();

		if ($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		return count($results);
	}

	/**
	 * Given any message id, return an array of userid that are involved in the
	 * conversation, be it recipient or sender.
	 *
	 */
	public function getParticipantsID($msgid, $exclusion = 0)
	{
		$getParticipantsIDs = array();
		$db = $this->getDBO();

		// with the given msgid, get the parent.
		$sql = 'SELECT ' . $db->nameQuote('parent');
		$sql .= ' FROM ' . $db->nameQuote('#__msg');
		$sql .= ' WHERE ' . $db->nameQuote('id') . ' = ' . $db->Quote($msgid);

		$db->setQuery($sql);
		$parentId = $db->loadResult();
		if ($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}


		// with the parentid, get all the recipient and the senderid
		$sql = 'SELECT ' . $db->nameQuote('msg_from') . ', ' . $db->nameQuote('to');
		$sql .= ' FROM ' . $db->nameQuote('#__msg_recepient');
		$sql .= ' WHERE ' . $db->nameQuote('msg_parent') . ' = ' . $db->Quote($parentId);
		$db->setQuery($sql);
		$result = $db->loadObjectList();
		if ($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		if ($result) {
			foreach ($result as $row) {
				if ($exclusion != $row->to) {
					$getParticipantsIDs[] = $row->to;
				}

				if ($exclusion != $row->msg_from) {
					$getParticipantsIDs[] = $row->msg_from;
				}
			}

			$getParticipantsIDs = array_unique($getParticipantsIDs);
		}

		return $getParticipantsIDs;
	}

	/**
	 * Get all recepient user id for a message except the current userid.
	 *
	 * @depreciated, use getParticipantsID instead
	 */
	public function &getMultiRecepientID($filter = array())
	{
		$db = $this->getDBO();
		$my = JFactory::getUser();

		$originalMsg = new CTableMessage($db);
		$originalMsg->load($filter['reply_id']);

		$RecepientMsg = $this->getRecepientMessage($filter['reply_id']);

		$recepient = array();

		if ($my->id != $originalMsg->from) {
			$recepient[] = $originalMsg->from; // the original sender
		}

		foreach ($RecepientMsg as $row) {
			if ($my->id != $row->to) {
				$recepient[] = $row->to; // the original sender
			}
		}

		return $recepient;
	}


	/**
	 * Get current user all the unread messages
	 * param user_id
	 */
	public function &getUnReadInbox()
	{
		$db = $this->getDBO();
		$my = CFactory::getUser();

		$sql = 'SELECT b.' . $db->nameQuote('id') . ', b.' . $db->nameQuote('from') . ', b.' . $db->nameQuote('parent') . ', b.' . $db->nameQuote('from_name') . ', b.' . $db->nameQuote('posted_on') . ', b.' . $db->nameQuote('subject');
		$sql .= ' FROM ' . $db->nameQuote('#__msg_recepient') . ' as a, ' . $db->nameQuote('#__msg') . ' as b';
		$sql .= ' WHERE a.' . $db->nameQuote('to') . ' = ' . $db->Quote($my->id);
		$sql .= ' AND ' . $db->nameQuote('is_read') . ' = ' . $db->Quote('0');
		$sql .= ' AND a.' . $db->nameQuote('deleted') . ' = ' . $db->Quote('0');
		$sql .= ' AND b.' . $db->nameQuote('id') . ' = a.' . $db->nameQuote('msg_id');
		$sql .= ' ORDER BY b.' . $db->nameQuote('posted_on') . ' DESC';

		$db->setQuery($sql);
		$result = $db->loadObjectList();

		return $result;
	}


	/**
	 * Get current user latest messages
	 * param user_id
	 * param limit (optional)
	 */
	public function &getLatestMessage($filter = array(), $limit = 5)
	{
		$db = $this->getDBO();
		$my = JFactory::getUser();

		$user_id = (empty($filter['user_id'])) ? $my->id : $filter['user_id'];

		$sql = 'select a.' . $db->nameQuote('msg_id') . ', a.' . $db->nameQuote('msg_parent') . ' , b.' . $db->nameQuote('from') . ', b.' . $db->nameQuote('from_name') . ',';
		$sql .= ' b.' . $db->nameQuote('posted_on') . ', b.' . $db->nameQuote('body');
		$sql .= ' from ' . $db->nameQuote('#__msg_recepient') . ' a, ' . $db->nameQuote('#__msg') . ' b';
		$sql .= ' where a.' . $db->nameQuote('to') . ' =' . $db->Quote($user_id);
		$sql .= ' and a.' . $db->nameQuote('deleted') . ' = ' . $db->Quote('0');
		$sql .= ' and a.' . $db->nameQuote('msg_id') . ' = b.' . $db->nameQuote('id');
		$sql .= ' order by ' . $db->nameQuote('msg_id') . ' desc';
		$sql .= ' limit {$limit}';

		$db->setQuery($sql);
		if ($db->getErrorNum()) {
			JError::raiseError(500, $db->stderr());
		}

		$result = $db->loadObjectList();

		return $result;
	}

	public function getUserInboxCount()
	{
		$db = $this->getDBO();
		$my = JFactory::getUser();
		$inboxResult = array();

		// Select all recent message to the user
		$sql = 'SELECT MAX(b.' . $db->nameQuote('id') . ') AS ' . $db->nameQuote('bid');
		$sql .= ' FROM ' . $db->nameQuote('#__msg_recepient') . ' as a, ' . $db->nameQuote('#__msg') . ' as b';
		$sql .= ' WHERE a.' . $db->nameQuote('to') . ' = ' . $db->Quote($my->id);
		$sql .= ' AND b.' . $db->nameQuote('id') . ' = a.' . $db->nameQuote('msg_id');
		$sql .= ' AND a.' . $db->nameQuote('deleted') . '=' . $db->Quote('0');
		$sql .= ' GROUP BY b.' . $db->nameQuote('parent');
		$db->setQuery($sql);
		$tmpResult = $db->loadObjectList();

		$strId = '';
		foreach ($tmpResult as $tmp) {
			if (empty($strId)) $strId = $tmp->bid;
			else $strId = $strId . ',' . $tmp->bid;
		}

		$result = null;
		if (!empty($strId)) {
			$sql = 'SELECT b.' . $db->nameQuote('id') . ', b.' . $db->nameQuote('parent') . ', b.' . $db->nameQuote('posted_on');
			$sql .= ' FROM ' . $db->nameQuote('#__msg') . ' as b';
			$sql .= ' WHERE b.' . $db->nameQuote('id') . ' in (' . $strId . ')';
			$sql .= ' ORDER BY b.' . $db->nameQuote('posted_on') . ' DESC';

			$db->setQuery($sql);
			$result = $db->loadObjectList();
			if ($db->getErrorNum()) {
				JError::raiseError(500, $db->stderr());
			}
		}

		// For each message, find the parent+from, group them together 			
		if (!empty($result)) {
			foreach ($result as $row) {
				$inboxResult[$row->parent] = $row;
			}
		}

		return count($inboxResult);
	}

	/**
	 * Returns a list of unread or notifications for the users inbox
	 *
	 **/
	public function getTotalNotifications($userId)
	{
		return (int)$this->countUnRead(array('user_id' => $userId));
	}

	/**
     * Return an array of file attachments (if there are any)
     */	     
    public function getAttachments($message)
	{
		$attachmentIds = explode(',', $message->attachment);
		
		$files = array();

		if(!empty($attachmentIds)){
			foreach($attachmentIds as $fileid)
			{
				$file = JTable::getInstance('File', 'MessagingTable');
				if( !empty($fileid) && $file->load($fileid) )
				{	
					$files[] = $file;
				}
			}
		}
		
		return $files;
	}
}