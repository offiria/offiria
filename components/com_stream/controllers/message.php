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

class StreamControllerMessage extends JController
{
	/**
	 *
	 */	 	
	public function display($cachable = false, $urlparams = false){
		parent::display( null );
	}	
	
	public function showMessages(){		
		$view = StreamFactory::getView('message');
		echo $view->showMessages();
	}
	
	/**
	 *  Add message
	 */	 	                       
	public function add()
	{
		$user	= JXFactory::getUser();
		if($user->id==0){
		    echo "Please login to post.";
		    exit;
		}  
		
		$streamModel = StreamFactory::getModel('stream');
		$fileModel  = StreamFactory::getModel('files');
		$activity = new StreamActivity();
		$activity->update($user->id, JRequest::getVar('type'));
		
		// Get group 
		$group = null;
		$customGroupNotification = null;
		
		if( JRequest::getVar('group_id') )
		{
			$group	= JTable::getInstance( 'Group' , 'StreamTable' );
			$group->load(JRequest::getVar('group_id'));
		}
		
		// Is this a 'direct'/private message type?
		if( JRequest::getVar('type') == 'direct' )
		{
			// create a private group and add members
			$group	= JTable::getInstance( 'Group' , 'StreamTable' );
			$group->members = $user->id;
			$group->creator	= $user->id;
			
			// Add more members
			$members = JRequest::getVar('members');
			foreach($members as $memberName)
			{
				$memberId = JUserHelper::getUserId($memberName);
				$group->members = JXUtility::csvInsert($group->members, $memberId);
			}
			
			$group->store();
			
			// After we have the group id, add it to each user's list
			foreach($members as $memberName)
			{
				$memberId = JUserHelper::getUserId($memberName);
				$memberObj = JXFactory::getUser($memberId);
				
				// Add the group id to user's group list
				$groupList = $memberObj->getParam('groups_member');
				$groupList = JXUtility::csvInsert($groupList, $group->id);
				$memberObj->setParam('groups_member', $groupList);
				$memberObj->save();
			}
			
			$groupList = $user->getParam('groups_member');
			$groupList = JXUtility::csvInsert($groupList, $group->id);
			$user->setParam('groups_member', $groupList);

			$user->save();

			// Direct message is a group but we don't want it displayed as a group in the notifications settings. Better alternative?
			$customGroupNotification = 'direct_message_new';
		}
		
		$postData = JRequest::get('POST', JREQUEST_ALLOWRAW);
		$postData = $this->_filterPostData($postData, JRequest::getVar('type'));
		// Store stream
		$stream	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->bind( $postData );
		
		
		// Set stream access level
		if( !is_null($group) ){
			$stream->access = $group->access;
			$stream->group_id = $group->id;
		} else {
			$stream->access = 0;
		}
		
		// Checking on invalid data type
		if ( JRequest::getVar('type') == 'event' ){
			/* this rarely happen but will do if somehow javascript validation is skipped */
			$eventModel = StreamFactory::getModel('events');
			$fallbackEventDuration = $eventModel->determinedEventDuration(JRequest::getVar('start_date'), JRequest::getVar('end_date'));
			$stream->start_date = (strpos(JRequest::getVar('start_date'), '0000-00-00 00:00') === false) 
				? JRequest::getVar('start_date') : $fallbackEventDuration['startDate']->format('Y-m-d h:i');
			$stream->end_date = (strpos(JRequest::getVar('end_date'), '0000-00-00 00:00') === false) 
				? JRequest::getVar('end_date') : $fallbackEventDuration['endDate']->format('Y-m-d h:i');;
		}
		
		$stream->raw = json_encode( $postData );
		$stream->user_id = $user->id;

		// grab selected category
		$categoryId = JRequest::getVar('blog_category', JText::_('COM_STREAM_DEFAULT_LABEL_PAGE_DEFAULT_CATEGORY'));
		$stream->category_id = $categoryId;

		// Setup video
		$this->_filterVideoURL($stream);
		$this->_filterSlideShare($stream);
		
		// if stream linkable link 
		if (JRequest::getVar('linkable_link', false)) {
			// mark the stream contains a linkable url
			$stream->setParam('linkable_link', JRequest::getVar('linkable_link'));
		}

		// If location is specified, validate them
		$stream->setParam('loc_valid', 0);
		if(JRequest::getVar('location')){
			jimport('joomla.utilities.map');
			if(JMap::validateAddress( JRequest::getVar('location'))){
				$stream->setParam('loc_valid', 1);
			}
			$stream->setParam('hide_map', JRequest::getVar('hide_map', '0'));
		} else {
			$rawData = json_decode($stream->raw);
			$rawData->location = "";
			$stream->raw = json_encode($rawData);
			$stream->store();
		}
		$rawData = json_decode($stream->raw);
		
		// Pin the stream item to the top and store the message
		$pinTill = JRequest::getString('pinned', null);

		if($pinTill) {
			$pinTillDate = new JDate();
			$pinTillDate->modify('+' . $pinTill);
			$stream->updated = $pinTillDate->toMySQL();
			$stream->store(true);
		} else {
			$stream->store();
		}
		
		// Any links in the message ?
		$links = StreamMessage::getLinks($stream->message);
		if(!empty($links)){

			$userLinks = $user->getParam('links');
			foreach($links as $row)
				{
					$link = JTable::getInstance( 'Link' , 'StreamTable' );
					
					// store only if 'load' allowed. It will return false for vides/slides etc.
					if( $link->load( array('link' => $row) ) ) {
						$link->addUser( $user->id );
						$link->store();

						$userLinks = JXUtility::csvInsert($userLinks, $link->id);
					}
				}

			// Update user links
			$user->setParam('links', $userLinks);
			$user->save();
		}

		// Any hashtags? Add them to hashtag table
		$trendingTag = array();
		$hashtags = StreamMessage::getHashtags($stream->message);
		foreach($hashtags as $tag){
			$hashtag = JTable::getInstance( 'Hashtag' , 'StreamTable' );
			$hashtag->load( array('hashtag' => $tag));
			$hashtag->hit();
			$hashtag->store();
			$trendingTag[] = trim($tag);
		}
		
		// add tagging to rawData
		if (!empty($trendingTag))
		{			
			foreach ($trendingTag as $index => $tag)
			{
				$unsupportedChars = array(',');
				$tag = str_replace($unsupportedChars, '', $tag);
				$tagsTrend = new StreamTag();
				$tagsTrend->updateTrending($tag, $stream->group_id, true);
				$trendingTag[$index] = '#'.$tag.'#';
			}
			$rawData = json_decode($stream->raw);
			$rawData->tags = implode(",", $trendingTag);

			$stream->raw = json_encode($rawData);
			$stream->store();
		}

		// Trigger Mentioned Name Notification
		$notificationType = 'profile_mention_'.$stream->type;
		StreamNotification::trigger( $notificationType, $stream, JRequest::getVar('group_id') );

		// Trigger Group Notification
		$groupNotification = ($customGroupNotification) ? $customGroupNotification : 'group_add_'.$stream->type;

		if( $group )
		{
			// Upgrade group stats if necessary
			$group->setParam('last_message', $stream->id);
			$group->setParam('message_count', $streamModel->countStream(array('group_id' => $group->id)));
			$group->setParam($stream->type . '_count', $streamModel->countStream(
				array('group_id' => $group->id, 'type' => $stream->type)
			));
			$group->store();

			// Trigger Group Wall Post Notification
			StreamNotification::trigger( $groupNotification, $group, $stream );
		}

		// If there is file uploaded, update the file
		$fileModel->updateOwner($stream);

		if ( JRequest::getVar('type') == 'todo' ){
			// Update milestone
			$this->_updateMilestone($stream);
		}

		// Get the HTML code to append
		$html = $stream->getHTML();
		$data = array();
		$data['html'] = $html;
		$data['group_id'] = JRequest::getVar('group_id');

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	/**
	 * Return list of new milestone select list
	 */	 	
	public function updateSelect()
	{
		$group_id = JRequest::getVar('group_id');
		require_once(JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'helpers'.DS.'milestones.php');
		$html = StreamMilestonesHelper::getSelectList($group_id);
		
		// Get the HTML code to append
		$data = array();
		$data['html'] = $html;

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	private function _updateMilestone($stream, $oldMilestone = null){
		// If milestone is specified, we need to update the actual milestone stats	
		if($milestoneId = JRequest::getVar('milestone') ){
			$milestone = JTable::getInstance( 'Stream' , 'StreamTable' );
			$milestone->load($milestoneId);
			
			// Should only be called if this is real milestone
			$milestone->attachTodo($stream);
			$milestone->updateProgessStats();
			$milestone->store(true);
		} //else {
			// remove any old milestone
			if($oldMilestone){
				$milestoneId = $oldMilestone;
				$milestone = JTable::getInstance( 'Stream' , 'StreamTable' );
				$milestone->load($milestoneId);
				
				// Should only be called if this is real milestone
				$milestone->detachTodo($stream);
				$milestone->updateProgessStats();
				$milestone->store(true);
			}
		//}
	}
	
	public function load() {
		$group_id = JRequest::getVar('group_id');
		$limit = JRequest::getVar('limit');
		
		$model = StreamFactory::getModel('stream');
		$data = $model->getStream(array('group_id' =>$group_id), $limit);
		$html = array();
		
		// Reverse the ordering
		$data = array_reverse($data);
		foreach($data as $row){
			$html[] = $row->getHTML();
		}
		
		$data = array();
		$data['html'] = $html;		
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;	
	}
	
	/**
	 *  Edit message
	 */	 	
	public function edit()
	{
		$message_id = JRequest::getVar('message_id');
		$edit_type = JRequest::getVar('edit_type', '');
		$stream	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->load($message_id);
		
		$tmpl = new StreamTemplate();
		$tmpl->set('stream', $stream);		
		$tmpl->set('edit_type', $edit_type);

		
		$data = array();
		$data['html'] 	 = $tmpl->fetch('stream.item.'.$stream->type.'.edit');		
		$data['title'] 	 = JText::sprintf('COM_STREAM_LABEL_EDITING_MAP', $stream->type);
		$data['script']  = "S.uploader.init('edit-file-uploader-".$stream->id."', 'edit-attachment-list-".$stream->id."'); ";
		$data['actions'] = '<span id="edit-file-uploader" class="qq-upload-button-edit"></span><a href="#" onclick="return S.uploader.selectFile(\'edit-file-uploader\');">'.JText::_('COM_STREAM_LABEL_UPLOAD').'</a><span><input type="button" class="fRight" value="'.JText::_('COM_STREAM_LABEL_SAVE').'" name="message-edit-save"></span>';
		
		// If this is 'Page' type, load the editor
		if($stream->type == 'page'){
			$data['script'] = "window.location = '". JRoute::_('index.php?option=com_stream&view=blog&task=edit&message_id='.$stream->id, false) ."'; ";
		}
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	/**
	 *  Save stream
	 */	 	
	public function save()
	{
		// Store stream
		$message_id = JRequest::getInt('message_id');
		$streamModel = StreamFactory::getModel('stream');
		$stream	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->load($message_id);

		$my = JXFactory::getUser();
		if( !$my->authorise('stream.message.edit', $stream) ){
			exit;
		}

		/* update the activity records */
		$activity = new StreamActivity();
		$activity->update($my->id, $stream->type);

		//	Update attachement there might be addition and removals
		$oldFiles = $stream->getFiles();
		$oldMilestone = isset($stream->getData()->milestone) ? $stream->getData()->milestone : null;

		$stream->bind( JRequest::get('POST', JREQUEST_ALLOWRAW) );

		// Checking on invalid data type
		if ( JRequest::getVar('type') == 'event' ){
			/* this rarely happen but will do if somehow javascript validation is skipped */
			$eventModel = StreamFactory::getModel('events');
			$fallbackEventDuration = $eventModel->determinedEventDuration(JRequest::getVar('start_date'), JRequest::getVar('end_date'));
			$stream->start_date = (strpos(JRequest::getVar('start_date'), '0000-00-00 00:00') === false) 
				? JRequest::getVar('start_date') : $fallbackEventDuration['startDate']->format('Y-m-d h:i');
			$stream->end_date = (strpos(JRequest::getVar('end_date'), '0000-00-00 00:00') === false) 
				? JRequest::getVar('end_date') : $fallbackEventDuration['endDate']->format('Y-m-d h:i');;
		}

		// edit should re-save the linkable link
		$stream->setParam('linkable_link', JRequest::getVar('linkable_link'));

		// Custom filtering
		$this->_filterVideoURL($stream);
		$this->_filterSlideShare($stream);
		
		// If location is specified, validate them
		$stream->setParam('loc_valid', 0);
		if(JRequest::getVar('location')){
			jimport('joomla.utilities.map');
			if(JMap::validateAddress( JRequest::getVar('location'))){
				$stream->setParam('loc_valid', 1);
			}
			$stream->setParam('hide_map', JRequest::getVar('hide_map', '0'));
		} else {
			$rawData = json_decode($stream->raw);
			$rawData->location = "";
			$stream->raw = json_encode($rawData);
			$stream->store();
		}
		
		
		// When edit the stream message, also need to process the tags
		$hashtags = StreamMessage::getHashtags($stream->message);
		$rawData = json_decode($stream->raw);
		foreach($hashtags as $tag)
		{
			$unsupportedChars = array(',');
			$tag = str_replace($unsupportedChars, '', $tag);
			$hashedTag = '#'.trim($tag).'#';
			if(!JXUtility::csvExist($rawData->tags, $hashedTag)) 
			{
				$tagsTrend = new StreamTag();
				$tagsTrend->updateTrending($tag, $stream->group_id, true);
				$rawData->tags = JXUtility::csvInsert($rawData->tags, $hashedTag);
				
				// only update the hit if it is a newly added tag
				$hashtag = JTable::getInstance( 'Hashtag' , 'StreamTable' );
				$hashtag->load( array('hashtag' => $tag));
				$hashtag->hit();
				$hashtag->store();
			}
		}
		$stream->raw = json_encode($rawData);

		$pinTill = JRequest::getString('pinned', 0);

		if ($pinTill) {
			// Update pin to top status
			$pinTillDate = new JDate($stream->created);
			$pinTillDate->modify('+' . $pinTill);
			$stream->updated = $pinTillDate->toMySQL();

			$stream->store(true);
		} else {
			// If save is done within 5 mins of last edit, do not update the 'updated' time
			$now 		= new JDate();
			$updated 	= new JDate($stream->updated);
			$timediff 	= JXDate::timeDifference( $updated->toUnix(), $now->toUnix() );

			$stream->pinned = 0;
			$stream->store(($timediff['days'] == 0) && ($timediff['hours'] == 0) && ($timediff['minutes'] < STREAM_EDIT_INTERVAL));
		}

		// Delete file attachment that are no longer used
		$newFiles		= $stream->getFiles();
		$requestFiles	= JRequest::getVar('attachment', array());
		foreach($oldFiles as $file){
			if(!in_array($file->id, $requestFiles) ){
				$file->delete();
			}
		}
		
		if( JRequest::getVar('group_id') )
		{
			$group	= JTable::getInstance( 'Group' , 'StreamTable' );
			$group->load(JRequest::getVar('group_id'));
			if( $group )
			{
				// the parameter need to be updated otherwise stream will be visible when moved to private group
				$stream->setParam('group_id', $group->id);
				$stream->access = $group->access;
				$stream->store(true);

				// Upgrade group stats if necessary
				$group->setParam('last_message', $stream->id);
				$group->setParam('message_count', $streamModel->countStream(array('group_id' => $group->id)));
				$group->setParam($stream->type . '_count', $streamModel->countStream(
					array('group_id' => $group->id, 'type' => $stream->type)
				));
				$group->store();
			}
		}
		
		// For all new attachment, we need to set their owner
		$fileModel  = StreamFactory::getModel('files');
		$fileModel->updateOwner($stream);
		
		// Update related milestone
		$this->_updateMilestone($stream, $oldMilestone);

		$data = array();
		$data['html'] 	= $stream->getHTML();
		$data['id']		= $message_id;

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}

	/**
	 *  AJAX Add Tag
	 */
	public function tagAdd() {
		$message_id = JRequest::getInt('message_id');
		$tag = JRequest::getVar('tag');

		// Filter unsupported characters. TODO: ajax error status/msg handling
		$unsupportedChars = array(',');
		$tag = str_replace($unsupportedChars, '', $tag);

		$hashedTag = '#' . trim($tag) . '#'; // Unlike message hash tags, we wrap them in hashes for specific searching
		$stream	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->load($message_id);

		$rawData = json_decode($stream->raw);
		$rawData->tags = (isset($rawData->tags)) ? $rawData->tags : '';

		/* TODO: TEMPORARY START: Wrap tags without them */
		if(!empty($rawData->tags)) {
			$tagsExploded = explode(',', $rawData->tags);
			$addHashesCallback = create_function('$value', 'if($value[0] != "#" && $value[strlen($value)-1] != "#") { return "#".$value."#"; } else { return $value; }');
			$tagsExploded = array_map($addHashesCallback, $tagsExploded);
			$rawData->tags = implode(',', $tagsExploded);
		}
		/* TEMPORARY END */

		if(!JXUtility::csvExist($rawData->tags, $hashedTag)) {
			$rawData->tags = JXUtility::csvInsert($rawData->tags, $hashedTag);

			$stream->raw = json_encode($rawData);
			$stream->store(true);

			$tagsTrend = new StreamTag();
			$tagsTrend->updateTrending($tag, $stream->group_id, true);
		}

		$tmpl = new StreamTemplate();
		$tmpl->set('stream', $stream);

		$data = array();
		$data['html'] = $tmpl->fetch('stream.tag');
		$data['id'] = $message_id;

		$group_id = JRequest::getVar('group_id');

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}

	/**
	 *  AJAX Tag Delete
	 */
	public function tagDelete() {
		$message_id = JRequest::getInt('message_id');
		$tag = JRequest::getVar('tag');
		$hashedTag = '#'.$tag.'#';
		$stream	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->load($message_id);

		$rawData = json_decode($stream->raw);
		$rawData->tags = (isset($rawData->tags)) ? $rawData->tags : '';
		$rawData->tags = JXUtility::csvRemove($rawData->tags, $hashedTag);

		$stream->raw = json_encode($rawData);
		$stream->store(true);

		$tmpl = new StreamTemplate();
		$tmpl->set('stream', $stream);

		$data = array();
		$data['html'] = $tmpl->fetch('stream.tag');
		$data['id'] = $message_id;

		$tagsTrend = new StreamTag();
		$tagsTrend->updateTrending($tag, $stream->group_id, false);

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}

	/**
	 *  AJAX Tag Lookup
	 */
	public function tagAutocomplete() {
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__stream_tags_trend GROUP BY `tag` LIMIT 1000";

		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		$tagsList = array();
		foreach($result as $row){
			$tagsList[] = $row->tag;
		}

		header('Content-Type: text/json');
		echo json_encode($tagsList);
		exit;
	}

	/**
	 *  AJAX People Lookup
	 */
	public function peopleAutocomplete() {
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__users"
				. " WHERE " . $db->nameQuote('username')
				. " NOT LIKE " . $db->quote('%admin%');

		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		$peopleList = array();
		foreach($result as $row){
			$peopleList[] = array('user_id'=>$row->id, 'user_fullname' => $row->name);
		}

		header('Content-Type: text/json');
		echo json_encode($peopleList);
		exit;
	}

	// START DEV
	public function peoplexAutocomplete() {
		$db = JFactory::getDbo();
		$query = "SELECT id FROM #__users"
			. " WHERE " . $db->nameQuote('username')
			. " NOT LIKE " . $db->quote('%admin%');

		$db->setQuery( $query );
		$results	= $db->loadObjectList();

		$peopleList = array();
		foreach($results as $row){
			$user = JXFactory::getUser($row->id);
			$peopleList[] = array('id'=>$user->id, 'value' => $user->name, 'username' => $user->username, 'thumb' => $user->getThumbAvatarURL());
		}

		header('Content-Type: text/json');
		echo json_encode($peopleList);
		exit;
	}

	public function groupxAutocomplete() {
		$my = JXFactory::getUser();
		$groupModel = StreamFactory::getModel('groups');

		$groupIJoin = $my->getParam('groups_member');
		$groupIFollow = $my->getParam('groups_follow');
		$myGroupsIds = JXUtility::csvMerge($groupIFollow, $groupIJoin);

		$myGroups = $groupModel->getGroups(array('id' => $myGroupsIds), 100);

		$groupsList = array();
		foreach($myGroups as $group){
			$peopleList[] = array('id'=>$group->id, 'value' => $group->name);
		}

		header('Content-Type: text/json');
		echo json_encode($peopleList);
		exit;
	}

	public function tagxAutocomplete() {
		$db = JFactory::getDbo();
		$query = "SELECT * FROM #__stream_tags_trend GROUP BY `tag` LIMIT 1000";

		$db->setQuery( $query );
		$result	= $db->loadObjectList();

		$tagsList = array();
		foreach($result as $row){
			$tagsList[] = array('id'=>$row->id, 'value' => $row->tag);
		}

		header('Content-Type: text/json');
		echo json_encode($tagsList);
		exit;
	}
	// END DEV
	
	/**
	 *  Save stream
	 */	 	
	public function fetch()
	{
		// Store stream
		$message_id = JRequest::getInt('message_id');
		$stream	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->load($message_id);
		
		$data = array();
		$data['html'] 	= $stream->getHTML();
		$data['id']		= $message_id;

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	/*public function direct()
	{
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_("COM_STREAM_LABEL_COMPOSE_PRIVATE_MESSAGE"));
		
		$tmpl = new StreamTemplate();
		$html = $tmpl->fetch('stream.post.direct');
		echo $html;	
	}*/
	
	/**
	 * Delete a message
	 */
	public function delete(){
		$my = JXFactory::getUser();
		$message_id = JRequest::getVar('message_id');
		$stream = JTable::getInstance('Stream', 'StreamTable');
		$stream->load($message_id);

		if (!$my->authorise('stream.message.delete', $stream)) {
			// No reason this code would ever get here!
			exit;
		}

		// Remove trending tags data
		$tagsTrend = new StreamTag();
		$rawData = json_decode($stream->raw);
		$rawData->tags = (isset($rawData->tags)) ? $rawData->tags : '';

		$tagsArray = explode(',', trim($rawData->tags, ','));

		foreach ($tagsArray as $tag) {
			// trim hashes
			$tag = trim($tag,'#');
			// remove trending data
			$tagsTrend->updateTrending($tag, $stream->group_id, false);
		}

		$stream->delete();

		// Delete related comments
		StreamComment::deleteComments($message_id);

		// Unattach all files
		$user = JXFactory::getUser($stream->user_id);
		$links = StreamMessage::getLinks($stream->message);
		if (!empty($links)) {
			$userLinks = $user->getParam('links');
			foreach ($links as $row) {
				$link = JTable::getInstance('Link', 'StreamTable');
				$link->load(array('link' => $row));
				$link->removeUser($user->id);
				$link->store();

				$userLinks = JXUtility::csvRemove($userLinks, $link->id);
			}

			$user->setParam('links', $userLinks);
			$user->save();
		}

		// @todo: delete attachments

		// Upgrade group stats if necessary
		if (JRequest::getVar('group_id')) {
			$group = JTable::getInstance('Group', 'StreamTable');
			$group->load(JRequest::getVar('group_id'));
			$group->setParam('message_count', $streamModel->countStream(array('group_id' => $group->id)));
			$group->setParam($stream->type . '_count', $streamModel->countStream(
				array('group_id' => $group->id, 'type' => $stream->type)
			));
			$group->store();
		}

		$data = array();
		$data['id'] = $message_id;

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	/**
	 *
	 */	 	
	private function _filterSlideShare(&$stream)
	{
		StreamFactory::load('libraries.slideshare');
		$rawData = json_decode($stream->raw);	
		$ssLib = new StreamSlideshare();
		$slideShares = $ssLib->getURL($stream->message);
		$rawData->slideshare = array();
		
		//print_r($videos); exit;
		if( $slideShares ){
			// Youtube link found    		
			foreach($slideShares as $slideShareLink ){
				$ss = JTable::getInstance( 'Slideshare' , 'StreamTable' );
				$ss->load( array('source' => $slideShareLink) );
				$ss->store();
				$rawData->slideshare[] = $ss->id;
			}
		}
		
		$stream->raw = json_encode($rawData);
	}
	
	/**
	 * If there are videos, store it
	 */	 	
	private function _filterVideoURL(&$stream){
		StreamFactory::load('libraries.video');
		$rawData = json_decode($stream->raw);	
		$videoLib = new StreamVideo();
		$videos = $videoLib->getURL($stream->message);
		$rawData->video = array();
		
		//print_r($videos); exit;
		if( $videos ){
			// Youtube link found    		
			foreach($videos as $youtubeLink ){
				$video = JTable::getInstance( 'Video' , 'StreamTable' );
				$video->load( array('source' => $youtubeLink) );
				$video->store();
				$rawData->video[] = $video->id;
			}
		}
		
		$stream->raw = json_encode($rawData);
	}
	
	/**
	 * filter out the unused raw data, to save space and to avoid unexpected rendering result
	 */	 
	private function _filterPostData($postData, $type)
	{
		$checkVar = array();
		switch($type) {
			case 'milestone': 
				$checkVar = array('end_date', 'event_category', 'milestone', 'start_time', 'end_time', 'blog_category');
				break;
			case 'todo': 
				$checkVar = array('start_date', 'event_category', 'start_time', 'blog_category');
				break;
			case 'event':
				$checkVar = array('milestone', 'blog_category');
				break;
			case 'page':
				$checkVar = array('milestone', 'start_time', 'end_time', 'event_category', 'start_date', 'end_date');
				break;
			case 'update':
				$checkVar = array('milestone', 'start_time', 'end_time', 'event_category', 'start_date', 'end_date', 'blog_category');
				break;
		}
		
		foreach ($checkVar as $var)
		if (isset($postData[$var]))
		{
			unset($postData[$var]);
		}
		
		return $postData;
	}
	
	
	/**
	 *
	 *
	 */	 	 	
	private function _streamShowLikes( $stream )
	{
		$my = JXFactory::getUser();
		
		$canUnlike = false;
		$likeHTML = '';
		$likeUsers = array();
		$likeCount = $stream->countLike();
		
		if( $likeCount == 0) {
			$likeHTML = JText::_('COM_STREAM_LIKE_THIS_NONE');
		} 
		else {
			$likes = explode(',', $stream->likes );
			foreach( $likes as $key => $val) {
				//@todo: need to make sure the user is valid and does exist
				$likes[$key] = JXFactory::getUser( $val );
			}		
			
			foreach($likes as $user) {
				$likeUsers[] = '<a href="'. $user->getURL() .'">'.$user->name.'</a>';
			}
			
			$likeHTML = implode(", ", $likeUsers);
			$likeHTML = JXString::isPlural( count($likeUsers) ) ? JText::sprintf('COM_STREAM_LIKE_THIS_MANY_LIST', $likeHTML) : JText::sprintf('COM_STREAM_LIKE_THIS_LIST', $likeHTML);

		}
		
		$doILike = $stream->isLike( $my->id );
		
		$likeResponse = array();
		$likeResponse['html'] = $likeHTML;
		$likeResponse['label'] = $doILike ? JText::_('COM_STREAM_UNLIKE_LABEL') : JText::_('COM_STREAM_LIKE_LABEL');
		
		return $likeResponse;
	}
	
	/**
	 *  Like the message
	 */	 		
	public function like()
	{
		$user = JXFactory::getUser();
		$stream_id = JRequest::getVar('message_id');
		$stream	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->load($stream_id);
		
		// People need to be able to read the message to add comment
		if( !$user->authorise('stream.message.read', $stream) ){
			// No reason this code would ever get here!
			exit;
		}
		
		$stream->like();
		
		$now 		= new JDate();
		$updated 	= new JDate($stream->updated);
		$timediff 	= JXDate::timeDifference( $updated->toUnix(), $now->toUnix() );

		$preventUpdate = (($updated->toUnix() > $now->toUnix()) || (($timediff['days'] == 0) && ($timediff['hours'] == 0) && ($timediff['minutes'] < STREAM_EDIT_INTERVAL))) ? true : false;

		$stream->store($preventUpdate);

		// Trigger Like Post Notification
		StreamNotification::trigger( 'profile_like_post', $stream );
		
		$data = $this->_streamShowLikes($stream);
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	/**
	 * Unlike a message
	 */	 	
	public function unlike()
	{
		$user = JXFactory::getUser();
		$stream_id = JRequest::getVar('message_id');
		$stream	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->load($stream_id);
		
		// People need to be able to read the message to add comment
		if( !$user->authorise('stream.message.read', $stream) ){
			// No reason this code would ever get here!
			exit;
		}
		
		$stream->unlike();
		
		$now 		= new JDate();
		$updated 	= new JDate($stream->updated);
		$timediff 	= JXDate::timeDifference( $updated->toUnix(), $now->toUnix() );

		$preventUpdate = (($updated->toUnix() > $now->toUnix()) || (($timediff['days'] == 0) && ($timediff['hours'] == 0) && ($timediff['minutes'] < STREAM_EDIT_INTERVAL))) ? true : false;

		$stream->store($preventUpdate);
		
		$data = $this->_streamShowLikes($stream);
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	
	/**
	 * Show all current likes
	 */	 	
	public function showlikes()
	{
		$my = JXFactory::getUser();
		
		$stream_id = JRequest::getVar('message_id');
		$stream	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->load($stream_id);
		
		$data = $this->_streamShowLikes($stream);
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	
	/**
	 *  Add user to the item follower
	 */ 	 	
	public function follow()
	{
		$stream_id = JRequest::getVar('stream_id');
		$stream	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->load($stream_id);
		$stream->follow();
		$stream->store();
		
		// Trigger Event Join Notification
		StreamNotification::trigger( 'event_join', $stream );
		
		$data = array();
		$data['html'] 	= $stream->getHTML('full');
		$data['id']		= $stream_id;
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	/**
	 *  Add user to the item follower
	 */ 	 	
	public function unfollow()
	{
		$stream_id = JRequest::getVar('stream_id');
		$stream	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->load($stream_id);
		$stream->unfollow();
		$stream->store();
		
		$data = array();
		$data['html'] 	= $stream->getHTML('full');
		$data['id']		= $stream_id;
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	/**
	 *  Add user to the item follower
	 */ 	 	
	public function setStatus()
	{
		$my = JXFactory::getUser();
		$now = new JDate();
		
		$message_id = JRequest::getVar('message_id');
		$stream	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->load($message_id);
		
		// Check for permissiion first
		if( $my->authorise('stream.message.edit', $stream) ){
			$stream->status = JRequest::getVar('status');
			
			// keep track of who change the status and when
			$stream->setParam('status_changed_by', $my->id);
			$stream->setParam('status_changed_on', $now->toMySQL() );
			
			$stream->store();
		}
		$data = array();
		$data['html'] 	= $stream->getHTML();
		$data['id']		= $message_id;
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	/**
	 *  These are actions done to the message. It varies from each message type.
	 *  We simply re-route this to the message type library	 
	 */	 	
	public function action()
	{
		$action = JRequest::getVar('action');
		
		$stream_id = JRequest::getVar('message_id');
		$stream	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->load($stream_id);
		
		// Each message type should implement whatever the function is
		$stream->$action();
	}
	
	
	/**
	 *  Show individual message
	 *
	 */	 	 	
	public function show()
	{
		$my = JXFactory::getUser();

		$message_id = JRequest::getVar( 'message_id' );
		$stream		= JTable::getInstance( 'Stream' , 'StreamTable' );
		$stream->load( $message_id );
		
		if (!$stream->id)
		{
			$app	= JFactory::getApplication();
		    $app->enqueueMessage( JText::_('COM_STREAM_ERROR_NO_POST_NOT_FOUND' ) , 'error' );
		    return;
		}
		
		if( !$my->authorise('stream.message.read', $stream) ){
			$app	= JFactory::getApplication();
		    $app->enqueueMessage( JText::_('COM_STREAM_ERROR_NO_ACCESS' ) , 'error' );
		    return;
		}
		
		// mark the blog view as an action taken by the user
		if ($stream->type == 'page') {
			$stream->actionIsTaken(NULL, 'blog_'.$stream->id);
			$stream->store(true);
		}

		$document 	= JFactory::getDocument();
		$viewType	= $document->getType();	
 		$viewName	= JRequest::getCmd('view', 'message');
		
		$view 		= StreamFactory::getView( $viewName, '', $viewType);
		
		echo $view->show($stream);
	}
	
	/***
	 * Called when the tabs in stream is called
	 */	 	
	public function filter()
	{
		$view = StreamFactory::getView('company');
		$html = $view->getStreamDataHTML();
		
		$data = array();
		$data['html'] 	= $html;
		$data['type']	= JRequest::getVar('type');
		
		header('Content-Type: text/json');
		echo json_encode($data);
		
		exit;
	} 	

	/**
	 * Whenever a link is shared, user will be able to post an excerpt from the url itself
	 */
	public function grabLinks() {
		$url = JRequest::getVar('web_url');
		$result = StreamLinks::grab($url);

		$table = JTable::getInstance('Link', 'StreamTable');
		$table->load(array('link'=>$url));

		$table->params = $result;
		if ($table->store() && $result) {
			header('Content-type: application/json');
			echo $result;
		}
		exit;
	}

}