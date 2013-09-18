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
 * Params
 * - completed_on
 * - completed_by
 */   
class StreamMilestoneType extends StreamType
{
	const COMPLETED = 1;
	
	public function StreamMilestoneType($data)
	{
		$this->data = $data;
	}
	
	/**
	 * link the given task to current message.
	 */
	public function attachTodo($message)
	{
		$todoList = $this->data->getParam('todo'); 
		$this->data->setParam( 'todo', JXUtility::csvInsert($todoList, $message->id));
	}
	
	/**
	 * link the given task to current message.
	 */
	public function detachTodo($message)
	{
		$todoList = $this->data->getParam('todo'); 
		$this->data->setParam( 'todo', JXUtility::csvRemove($todoList, $message->id));
	}
	
	public function completeTodo()
	{
		$my = JXFactory::getUser();
		if (!$this->allowComplete($my->id))
		{
			$data = array();	
			$data['script'] = '';
			echo json_encode($data);
			exit;
		}
		
		$todoList		= $this->data->getParam('todo'); 
		$streamTable	= JTable::getInstance( 'Stream' , 'StreamTable' );
		$todoList		= explode(',', $todoList);
		$status			= JRequest::getVar('status');
		
		$returnHTML		= '';
		
		// set the state to 1 to allow todo to perform complete task
		/*JRequest::setVar('state', 1);
		for ($i = 0; $i < count($todoList); $i++)
		{
			$streamTable->load($todoList[$i]);
			$rawData = $streamTable->raw;			
			$todoItems = json_decode($rawData);
			
			if (isset($todoItems->todo))
			{
				for( $j = 0; $j < count($todoItems->todo); $j++ )
				{
					if ((int)$streamTable->getState($j) === 0)
					{
						JRequest::setVar('item', $j);
						$streamTable->setdone();
					}
				}
				//$returnHTML .= $streamTable->getTodoHtml($streamTable);
			}
		}
		
		$streamTable->load($this->data->id);
		$streamTable->updateProgessStats();
		$streamTable->store(true);*/
		
		$streamTable->load($this->data->id);
		if ($status == self::COMPLETED)
		{
			$streamTable->setParam('progress', 0);
			$streamTable->status = 0;
		}
		else
		{
			$streamTable->setParam('progress', 100);
			$streamTable->status = self::COMPLETED;
		}
		$streamTable->store();
		
		
		$data = array();
		$data['html'] 	= $streamTable->getMilestoneHTML($streamTable);		
		$data['script'] = '$("#message_'.$this->data->id.'").replaceWith(data.html); $("#complete_'.$this->data->id.'").data("status", '.$streamTable->status.'); $(\'a[href="#toggleTasks"]\').unbind("click"); $(\'a[href="#toggleTasks"]\').click(function(e){ e.preventDefault(); $(this).parents(\'.milestone-item\').find(\'ul#stream .milestone-task.toggle-item\').fadeToggle("fast", "linear"); });';//  '$(\'div.progress[milestone="'.$this->data->id.'"]\').find("div.bar").css("width", "100%")';
		echo json_encode($data);
		exit;
	}
	
	/**
	 * Return true if the user belongs to the group or the owner of the milestone
	 */	 	
	public function allowComplete($userid)
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
	
	public function getTaskCount() {
		$todoIds = $this->data->getParam('todo');
		return JXUtility::csvCount($todoIds);
	}

	public function getTaskCompletedCount() {
		$todoIds = $this->data->getParam('todo');

		if($todoIds == null) {
			return 0;
		}

		$filter = array();
		$filter['id'] = $todoIds;

		$streamModel = StreamFactory::getModel('stream');
		$tasks = $streamModel->getStream($filter);

		// Count how many tasks has been completed
		$filter['status'] = 1;
		$streamModel->countStream($filter);

		return $streamModel->countStream($filter);
	}

	/**
	 * Load all current task and update the progress rate
	 */	 	
	public function updateProgessStats()
	{
		$todoList = $this->data->getParam('todo');
		$todoMessages = explode( ',', trim( $todoList, ',' ) );
		$totalTask = 0;
		$totalCompleted = 0;
	
		foreach($todoMessages as $stream_id) {
			$stream = JTable::getInstance( 'Stream' , 'StreamTable' );
			$stream->load($stream_id);
			
			// $sream might have been deleted, check for valid todo
			if($stream->id) {	
				$data = json_decode($stream->raw);
				$todoIndex 	= 0;
				$numTodo 	= count($data->todo);
				$doneTodo 	= 0;
				
				foreach( $data->todo as $todo )
				{
					if(!empty($todo)) {
						$isDone = $stream->getState($todoIndex);
						$totalTask++;
						if($isDone){
							$totalCompleted ++;
						}
						
						$todoIndex++;
					}
				}
			}
		}

		// Must be stored later
		if ($totalTask)
		{
			$this->data->setParam('progress', round(($totalCompleted / $totalTask) * 100)); // in %
		}
		else
		{
			$this->data->setParam('progress', 100); // in %
		}
		$this->data->status = ($totalCompleted == $totalTask) ? self::COMPLETED : 0;
	}
	
	/**
	 * Return object HTML
	 */	 	
	public function getItemHTML($format = null)
    {
    	// If were not in 'article' view, just show short summary
    	$view = JRequest::getVar('view');
    	$task = JRequest::getVar('task');
    	$editType = JRequest::getVar('edit_type', '');
    	$messageId = JRequest::getVar('message_id',0);
		//var_dump($messageId);
		
		if ($editType == 'list')
		{
			return $this->getMilestoneHTML($this->data);
		}

		if ((($view == 'message' && ($task == 'show' || $task == 'save' || $task == 'fetch')) || ($view == 'milestones') || $task == 'showMessages') || ($messageId == $this->data->id)) {
			$tmpl = new StreamTemplate();
			$tmpl->set( 'stream'	, $this->data)
				->set('comment', StreamComment::getCommentSummaryHTML($this->data));
			return $tmpl->fetch('stream.item.milestone');
		} else{
			$tmpl = new StreamTemplate();
			$tmpl->set( 'stream'	, $this->data)
				 ->set('comment', StreamComment::getCommentSummaryHTML($this->data));
			return $tmpl->fetch('stream.compact.milestone');
		}
		
    	/*if($task != 'show'){
			$data = json_decode($this->data->raw);
			$date = new JDate( $this->data->created );
			$user = JXFactory::getUser($this->data->user_id);
			
			$html = '<li style="border:none; background:#F2F2F2; padding:8px;list-style-type: none;margin: 0 0 15px;backgroun:#ddd"><span style="margin-right:6px;" class="label info">New Milestone</span>
				'. StreamMessage::format($this->data->message) .'
				<span class="small hint">- '.StreamTemplate::escape($user->name).' , '.JXDate::formatLapse( $date ).'</span>
				</li>';
			return $html;
		}
		
		$tmpl = new StreamTemplate();
		$tmpl->set( 'stream'	, $this->data)
				->set('comment', StreamComment::getCommentSummaryHTML($this->data));
		return $tmpl->fetch('stream.item.milestone');*/
	}
	
	public function getMilestoneHTML($milestoneObj, $addClass='')
	{
		$tmpl = new StreamTemplate();
		$tmpl->set( 'milestone', $milestoneObj)
				->set( 'addClass', $addClass)
				->set( 'my', JXFactory::getUser());
		return $tmpl->fetch('milestone.item');
	}
	
	/**
	 *
	 */	 	
	public function onStore(){
		// If this is a milestone, set the end_date for away into the future
	}
	
	/**
	 * Called during create. Can pass an array to the javascript
	 */	 	
	public function onCreate(){
		require_once(JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'helpers'.DS.'milestones.php');
		$milestoneSelect = StreamMilestonesHelper::getSelectList($this->data->group_id);
		$response = array();
		$response['script'] = '$("select[name\"milestone\"]").html('.$milestoneSelect.');';
		return $response;
	}
	
}