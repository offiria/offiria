<?php
/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

jimport( 'joomla.application.component.view');
include_once('message.php');

/**
 * Templating system for JomSocial
 */
class StreamTodoType extends StreamType
{
   	const COMPLETED = 1;
	
	public function StreamTodoType($data)
	{
		$this->data = $data;
	}
	
	public function getItemHTML($format = null)
    {
    	$tmpl = new StreamTemplate();
		$tmpl->set( 'stream'	, $this->data)
				->set('comment', StreamComment::getCommentSummaryHTML($this->data));
		return $tmpl->fetch('stream.item.todo');
	}
	
	public function getTodoHtml($todoStreamObj)
	{
		$tmpl = new StreamTemplate();
		$tmpl->set( 'task', $todoStreamObj)
				->set( 'my', JXFactory::getUser());
		return $tmpl->fetch('todo.item');
	}
	
	public function setdone()
	{
		// Update user 'done' count
		$my = JXFactory::getUser();
		
		$state = JRequest::getVar('state');
		$item = JRequest::getVar('item');
		
		$this->setState( $item, $state );
		
		// If ALL task has been completed, mark status as 1 (completed)
		$totalTask = 0;
		$totalCompleted = 0;
		$data = $this->data->getData();
		foreach( $data->todo as $todo )
		{
			if(!empty($todo)) {
				$isDone = $this->data->getState($totalTask);
				$totalTask++;
				if($isDone){
					$totalCompleted ++;
				}
			}
		}
		
		if($totalTask == $totalCompleted && $totalTask != 0){
			$this->data->status = 1; // completed
		} else {
			$this->data->status = 0;
		}
		
		$this->data->store();
	}
	
	/**
	 *  Ajax call for 'done'
	 */	 	
	public function done(){
		// Update user 'done' count
		$my = JXFactory::getUser();
		$script = '';
		
		$state = JRequest::getVar('state');
		$item = JRequest::getVar('item');
		
		$this->setdone();
		
		// Update milestone stats
		$data = $this->data->getData();
		if(isset($data->milestone) && !empty($data->milestone)) {
			$milestone = JTable::getInstance( 'Stream' , 'StreamTable' );
			$milestone->load($data->milestone);
			$milestone->updateProgessStats();
			$milestone->store(true);
			$script .= '$(\'div.progress[milestone="'.$milestone->id.'"]\').find("div.bar").css("width", "'.$milestone->getParam('progress').'%")';
		}
		
		if (intval($state) === 1)
		{
			// Trigger Complete Todo item email notification
			StreamNotification::trigger( 'todo_complete_item', $this->data, $my, $item );
		}
		
		$todoDone = $my->getParam('todo_done');
		if($state){
			$todoDone++;	
		} else {
			$todoDone--;
		}
		
		$my->setParam('todo_done', $todoDone);
		$my->save();
		
		$data = array();
		if(isset($milestone)) 
		{
			$data['html2']			= $milestone->getMilestoneHTML($milestone);
			$data['milestone_id']	= $milestone->id;
		}
		$data['html'] 	= $this->data->getHTML();
		$data['id']		= $this->data->id;
		$data['script'] = $script;
		
		echo json_encode($data);
		exit;
	}
	
	/**
	 * Return true if the user can set the todo item as 'done'
	 */	 	
	public function allowDone($userid)
	{
		// If the userid is the stream owner, allow it
		if($this->data->user_id == $userid){
			return true;
		}
			
		// If stream belong to a group and userid is a member of the group
		// allow it
		if( !empty($this->data->group_id)){
			$user= JXFactory::getUser($userid);
			$userGroups = $user->getParam('groups_member');
	
			return JXUtility::csvExist($userGroups, $this->data->group_id);
		}
		
		return false;
	}
	
	/**
	 * Return true if the user can set the todo item as 'undone'
	 */	 	
	public function allowUndone($userid, $stream)
	{
	}
	
	/**
	 * Get todo done/not done state (1 = done, 0 = undone)
	 */	 	
	public function getState($todoIndex){
		return $this->data->getParam('state_'.$todoIndex, 0);	
	}
	
	/**
	 * Set todo done/not done state (1 = done, 0 = undone)
	 */	
	public function setState($todoIndex, $state){
		$this->data->setParam('state_'.$todoIndex, $state);// = $params->toString();
		
		$my = JXFactory::getUser();
		// Store who dunnit
		if($state == 1){
			$date = new JDate();
			$this->setDoneBy( $todoIndex, $my->id );
			$this->setDoneOn( $todoIndex, $date->toMySQL() );
		}else{
			$this->setDoneBy( $todoIndex, 0 );
			$this->setDoneOn( $todoIndex, '0000-00-00 00:00:00' );
		}
		
		
	}
	
	/**
	 * Get todo done/not done state (1 = done, 0 = undone)
	 */	 	
	public function getDoneBy($todoIndex){
		return $this->data->getParam('by_'.$todoIndex, 0);	
	}
	
	/**
	 * Set todo done/not done state (1 = done, 0 = undone)
	 */	
	public function setDoneBy($todoIndex, $userid){
		$this->data->setParam('by_'.$todoIndex, $userid);// = $params->toString();
	}
	
	/**
	 * Get todo done/not done state (1 = done, 0 = undone)
	 */	 	
	public function getDoneOn($todoIndex){
		return $this->data->getParam('on_'.$todoIndex, 0);	
	}
	
	/**
	 * Set todo done/not done state (1 = done, 0 = undone)
	 */	
	public function setDoneOn($todoIndex, $date){
		$this->data->setParam('on_'.$todoIndex, $date);// = $params->toString();
	}
	
	/**
	 * Called before Table::store() is called
	 */	 	
	public function onStore()
	{
		// If 'states' are available, in the POST data, reset the state param
		$states = JRequest::getVar('states');
		$doneby = JRequest::getVar('done_by');
		$doneon = JRequest::getVar('done_on');
		
		if(!empty($states)){
			$states = trim($states, ',');
			$states = explode(',', $states);
			
			$doneby = trim($doneby, ',');
			$doneby = explode(',', $doneby);
			
			$doneon = trim($doneon, ',');
			$doneon = explode(',', $doneon);
			
			$todoIndex = 0;
			foreach($states as $row){
				$this->data->setParam('state_'.$todoIndex, $row);
				$this->setDoneBy($todoIndex, $doneby[$todoIndex]);
				$this->setDoneOn($todoIndex, $doneon[$todoIndex]);
				$todoIndex++;
			}
		}
	}
}