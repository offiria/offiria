<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.controller');
jimport('joomla.utilities.xutility');

class StreamControllerGroups extends JController
{
	/**
	 *
	 */	 	
	public function display($cachable = false, $urlparams = false)
	{
		$view 	= StreamFactory::getView( 'groups', '', 'html');
		echo $view->display();
		
	}
	
	/**
	 * Show group members
	 */	 	
	public function show_members(){
		$my = JXFactory::getUser();
		$group_id = JRequest::getVar('group_id');
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load($group_id);
		
		// People need to be able to read the group
		if( !$my->authorise('stream.group.read', $group) ){
			$app	= JFactory::getApplication();
		    $app->enqueueMessage( JText::_('COM_STREAM_ERROR_NO_ACCESS' ) , 'error' );
		    return;
		}
		
		$view 	= StreamFactory::getView( 'groups', '', 'html');
		echo $view->show_members($group);
	}
	
	/**
	 * SHow group events
	 */	 	
	public function show_events(){
		$my = JXFactory::getUser();
		$group_id = JRequest::getVar('group_id');
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load($group_id);
		
		// People need to be able to read the group
		if( !$my->authorise('stream.group.read', $group) ){
			$app	= JFactory::getApplication();
		    $app->enqueueMessage( JText::_('COM_STREAM_ERROR_NO_ACCESS' ) , 'error' );
		    return;
		}
		
		$view 	= StreamFactory::getView( 'groups', '', 'html');
		echo $view->show_events($group);
	}
	
	/**
	 * SHow group files
	 */	 	
	public function show_files(){
		$my = JXFactory::getUser();
		$group_id = JRequest::getVar('group_id');
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load($group_id);
		
		// People need to be able to read the group
		if( !$my->authorise('stream.group.read', $group) ){
			$app	= JFactory::getApplication();
		    $app->enqueueMessage( JText::_('COM_STREAM_ERROR_NO_ACCESS' ) , 'error' );
		    return;
		}
		
		$view 	= StreamFactory::getView( 'groups', '', 'html');
		echo $view->show_files($group);
	}
	
	/**
	 * Show group milestone
	 */	 	
	public function show_milestones(){
		$my = JXFactory::getUser();
		$group_id = JRequest::getVar('group_id');
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load($group_id);
		
		// People need to be able to read the group
		if( !$my->authorise('stream.group.read', $group) ){
			$app	= JFactory::getApplication();
		    $app->enqueueMessage( JText::_('COM_STREAM_ERROR_NO_ACCESS' ) , 'error' );
		    return;
		}
		
		$view 	= StreamFactory::getView( 'groups', '', 'html');
		echo $view->show_milestones($group);
	}
	
	/**
	 * Show group todos
	 */	 	
	public function show_todos(){
		$my = JXFactory::getUser();
		$group_id = JRequest::getVar('group_id');
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load($group_id);
		
		// People need to be able to read the group
		if( !$my->authorise('stream.group.read', $group) ){
			$app	= JFactory::getApplication();
		    $app->enqueueMessage( JText::_('COM_STREAM_ERROR_NO_ACCESS' ) , 'error' );
		    return;
		}
		
		$view 	= StreamFactory::getView( 'groups', '', 'html');
		echo $view->show_todos($group);
	}
	
	/**
	 * Show individual group
	 */	 	
	public function show()
	{
		$my = JXFactory::getUser();
		
		$group_id = JRequest::getVar('group_id');
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load($group_id);
		
		// People need to be able to read the group
		// allow also siteadmin and superuser to view the private group content
		if( !$my->isAdmin() && !$my->authorise('stream.group.read', $group) ){
			$app	= JFactory::getApplication();
		    $app->enqueueMessage( JText::_('COM_STREAM_ERROR_NO_ACCESS' ) , 'error' );
		    return;
		}
		
		$view 	= StreamFactory::getView( 'groups', '', 'html');
		echo $view->show($group);
		
		// Update view stats
		$lastReadId 	= $my->getParam('group_'.$group->id.'_read', -1);
		$lastCommentId 	= $my->getParam('group_'. $group->id.'_comment', -1);
		
		$groupLastMsg 		= $group->getParam('last_message');
		$groupLastComment 	= $group->getParam('last_comment');
		
		if($lastReadId != $groupLastMsg || $lastCommentId != $groupLastComment){
			$my->setParam('group_'.$group->id.'_read', $groupLastMsg);
			//$my->setParam('group_'. $group->id.'_comment', $groupLastComment);
			$my->save();
		}
	}
	
	/**
	 * Edit/Add via ajax
	 */	 	
	public function create()
	{
		$my = JXFactory::getUser();
		
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load(JRequest::getVar('group_id'));

		if( !$my->authorise('stream.group.edit', $group) ){
			// No reason this code would ever get here!
		    exit;
		}

		/* update activity table */
		$activity = new StreamActivity();
		$activity->update($my->id, 'groups');

		$data = array();

		// Template start

		/*if($group->id)
			$data['title'] = JText::sprintf('COM_STREAM_LABEL_EDIT_GROUP');
		else {
			$data['title'] = JText::sprintf('COM_STREAM_LABEL_ADD_NEW_GROUP');
			$group->access = 0;
		}
		$data['actions'] = '<span><input type="button" class="btn btn-primary fRight" value="'.JText::_('COM_STREAM_LABEL_SAVE').'" name="groups-edit-save"></span>';*/
		
		$tmpl = new StreamTemplate();
		$tmpl->set('group', $group);
		$data = $tmpl->fetch('groups.edit');
		header('Content-Type: text/html; charset=UTF-8');
		echo $data;
		exit;
	}
	
	/**
	 * Save groupss
	 */	 	
	public function save()
	{
		$my = JXFactory::getUser();
		
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load(JRequest::getVar('group_id'));
		
		if( !$my->authorise('stream.group.edit', $group) ){
			// No reason this code would ever get here!
		    exit;
		}
		
		/* update activity table */
		$activity = new StreamActivity();
		$activity->update($my->id, 'groups');

		$group->access = 0; // set to 0 and if user select the checkbox, it will get binded again
		$group->bind( JRequest::get('POST', JREQUEST_ALLOWRAW) );
		
		// New group. Initialize creator and members
		if($group->id == 0){
			$group->creator = $my->id;
			$group->members = JXUtility::csvInsert($group->members, $my->id);		
			$group->id = JRequest::getVar('group_id');

			// Set group join getting started helper task as completed
			$my->setGettingStartedCompletion(JXUser::GSTARTED_JOIN_GROUP, 100);
		}
		else {
			// this is an existing group, if a user tries to convert public to private group
			// remove all existing follower besides the current user whos editing this group
			$group->followers = $my->id;
		}

		$group->store();
		
		// Update stream data access
		$groupModel = StreamFactory::getModel('groups');
		$groupModel->updateAccess($group->id, $group->access);
		
		// Store user cache
		$groupList = $my->getParam('groups_member');
		$groupList = JXUtility::csvInsert($groupList, $group->id);
		$my->setParam('groups_member', $groupList);
		$my->save();
		
		$data = array();
		$data['redirect'] = JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='. $group->id, FALSE);
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	
	/**
	 * Ajax join
	 */	 	
	public function join()
	{
		$my = JXFactory::getUser();
		
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load(JRequest::getVar('group_id'));
		
		if( !$my->authorise('stream.group.join', $group) ){
			// No reason this code would ever get here!
		    exit;
		}

		/* update activity table */
		$activity = new StreamActivity();
		$activity->update($my->id, 'groups');

		// If you join, you'd also follow it
		$group->members = JXUtility::csvInsert($group->members, $my->id);
		$group->followers = JXUtility::csvInsert($group->followers, $my->id);		
		$group->store();
		
		// Store user cache
		$groupList = $my->getParam('groups_member');
		$groupList = JXUtility::csvInsert($groupList, $group->id);
		$my->setParam('groups_member', $groupList);
		$my->save();
		
		// Trigger Group Join Notification
		StreamNotification::trigger( 'group_join', $group, $my );

		// Set group join getting started helper task as completed
		$my->setGettingStartedCompletion(JXUser::GSTARTED_JOIN_GROUP, 100);
		
		$data = array();
		$data['redirect'] = JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='. $group->id, FALSE);
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	/**
	 * Ajax leave
	 */	 	
	public function leave()
	{
		$my = JXFactory::getUser();
		
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load(JRequest::getVar('group_id'));
		
		/* update activity table */
		$activity = new StreamActivity();
		$activity->update($my->id, 'groups');

		// If you leave, you also unfollow a group
		$group->followers = JXUtility::csvRemove($group->followers, $my->id);
		$group->members = JXUtility::csvRemove($group->members, $my->id);		
		$group->store();
		
		// Store user cache
		$groupList = $my->getParam('groups_member');
		$groupList = JXUtility::csvRemove($groupList, $group->id);
		$my->setParam('groups_member', $groupList);
		$my->save();
		
		$data = array();
		// leaving private group should redirect to group list page to avoid error msg
		if ($group->access)
		{
			$data['redirect'] = JRoute::_('index.php?option=com_stream&view=groups', FALSE);
		}
		else
		{
			$data['redirect'] = JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='. $group->id, FALSE);
		}
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}

	/**
	 * Ajax: remove member from group
	 */
	public function memberRemove()
	{
		$user = JXFactory::getUser(JRequest::getVar('user_id'));

		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load(JRequest::getVar('group_id'));

		// If you leave, you also unfollow a group
		$group->followers = JXUtility::csvRemove($group->followers, $user->id);
		$group->members = JXUtility::csvRemove($group->members, $user->id);
		$group->store();

		// Store user cache
		$groupList = $user->getParam('groups_member');
		$groupList = JXUtility::csvRemove($groupList, $group->id);
		$user->setParam('groups_member', $groupList);
		$user->save();

		$data = array();
		$data['redirect'] = JRoute::_('index.php?option=com_stream&view=groups&task=show_members&group_id='. $group->id, FALSE);

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}

	/**
	 * Ajax: add member to group
	 */
	public function memberAdd()
	{
		$my = JXFactory::getUser();
		$user = JXFactory::getUser(JRequest::getVar('user_id'));

		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load(JRequest::getVar('group_id'));

		// If you join, you'd also follow it
		$group->members = JXUtility::csvInsert($group->members, $user->id);
		$group->followers = JXUtility::csvInsert($group->followers, $user->id);
		$group->store();

		// Store user cache
		$groupList = $user->getParam('groups_member');
		$groupList = JXUtility::csvInsert($groupList, $group->id);
		$user->setParam('groups_member', $groupList);
		$user->save();

		$newUser = new JXUser($user->id); //TODO: JXFactory::getUser causes user params to sync to their session when the user is still logged in

		$tmpl = new StreamTemplate();
		$tmpl->set('user', $newUser);
		$tmpl->set('my', $my);
		$tmpl->set('group', $group);

		$data = array();
		$data['html'] = $tmpl->fetch('group.members.list.add');

		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	/**
	 * Ajax join
	 */	 	
	public function follow()
	{
		$my = JXFactory::getUser();
		
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load(JRequest::getVar('group_id'));
		
		$group->followers = JXUtility::csvInsert($group->followers, $my->id);		
		$group->store();
		
		// Store user cache
		$groupList = $my->getParam('groups_follow');
		$groupList = JXUtility::csvInsert($groupList,$group->id);
		
		$my->setParam('groups_follow', $groupList);
		$my->save();
		
		// Trigger Group Follow Notification
		StreamNotification::trigger( 'group_follow', $group, $my );
		
		$data = array();
		$data['redirect'] = JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='. $group->id, FALSE);
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	/**
	 * Ajax join
	 */	 	
	public function unfollow()
	{
		$my = JXFactory::getUser();
		
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load(JRequest::getVar('group_id'));
		
		$group->followers = JXUtility::csvRemove($group->followers, $my->id);		
		$group->store();
		
		// Store user cache
		$groupList = $my->getParam('groups_follow');
		$groupList = JXUtility::csvRemove($groupList,$group->id);
		
		$my->setParam('groups_follow', $groupList);
		$my->save();
		
		$data = array();
		$data['redirect'] = JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='. $group->id, FALSE);
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	/**
	 * Show popup list of all followers
	 */	 	
	public function followers()
	{
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load(JRequest::getVar('group_id'));
		$followers = array();
		$html = '<p>No one is following the group</p>';
		$followers = JXUtility::csvDiff($group->followers, $group->members);
		
		if(  JXUtility::csvCount($followers) > 0){
			$html = '<ul>';
			foreach($items	=   explode( ',', $followers ) as $id)
			{
				$user = JXFactory::getUser($id);
				$html .= '<li>'.$user->name.'</li>';
			}
			$html .= '</ul>';
		}
		
		
		$data = array();
		$data['html'] =$html;
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	
	/**
	 * Archive a group
	 */	 	
	public function archive()
	{
		$this->_setArchive(1);
	}
	
	/**
	 * reopen the group
	 */	 	
	public function unarchive()
	{
		$this->_setArchive(0);
	}
	
	private function _setArchive( $state )
	{
		$my = JXFactory::getUser();
		
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load(JRequest::getVar('group_id'));
		
		if($my->authorise('stream.group.archive', $group))
		{
			$group->archived = $state;
			$group->store();
		}
		
		// redirect
		$data = array();
		$data['redirect'] = JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='.$group->id, FALSE);
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}
	/**
	 * Delete a group
	 */	 	
	public function delete()
	{
		$my = JXFactory::getUser();
		
		$group	= JTable::getInstance( 'Group' , 'StreamTable' );
		$group->load(JRequest::getVar('group_id'));
		
		if($my->authorise('stream.group.delete', $group))
		{
			// Delete all related stream as well
			$streamModel = StreamFactory::getModel('stream');
			$streamModel->delete(array('group_id' => $group->id));
			
			// Delete all related files
			$fileModel = StreamFactory::getModel('files');
			$fileModel->delete(array('group_id' => $group->id));
			
			$group->delete();
		}
		// Delete related comments
		// @todo: delete all related messages
		//StreamComment::deleteComments($stream_id);
		
		// redirect
		$data = array();
		$data['redirect'] = JRoute::_('index.php?option=com_stream&view=groups', FALSE);
		
		header('Content-Type: text/json');
		echo json_encode($data);
		exit;
	}	

	/**
	 * Return a groups assign for user
	 * @return JSON name, description of the group assigned
	 */
	public function listByUser() {
	     $user = JXFactory::getUser();
	     $table = JTable::getInstance( 'Group' , 'StreamTable' );
	     $groupsJoined = $table->listGroupsByUser($user->id);
	     
		 header('Content-Type: text/json');
	     // display output if there is a group joined
	     if (count($groupsJoined) > 0) {
	     	// result will contains the name and description of the groups;
	     	$results = array();
	     	foreach ($groupsJoined as $groupId) {
	     		$table->load($groupId);
	     		$result['id'] = $groupId;
	     		$result['name'] = $table->name;
	     		$result['description'] = $table->description;
	     		$results[] = $result;
	     	}
	     	echo json_encode($results);
	     }
	     exit;
	}
}