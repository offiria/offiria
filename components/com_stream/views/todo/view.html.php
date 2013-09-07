<?php
/**
 * @version     1.0.0
 * @package     com_administrator
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Administrator component
 */
class StreamViewTodo extends StreamView
{
	function display($tpl = null)
	{
        $this->addPathway( JText::_('NAVIGATOR_LABEL_TODO'), JRoute::_('index.php?option=com_stream&view=todo') );
		
		$this->_attachScripts();
        $my = JXFactory::getUser();
		$html = '';
		
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_("COM_STREAM_LABEL_TODO_LIST"));
		
		$tmpl = new StreamTemplate();
		$html .= $tmpl->fetch('todo.header');
		
		// Add attachment script
		$doc->addScript(JURI::root(). 'media/uploader/fileuploader.js');
		$doc->addStyleSheet(JURI::root().'media/uploader/fileuploader.css');
		
		//JXModule::addBuffer('right', '<div><strong>I am working on...</strong></div>');
		JXModule::addBuffer('right', $this->modGetPendingTask(array('user_id' => $my->id)));
		$html .= $this->getStreamDataHTML();
		
		echo $html;
	}
	
	/**
	 * Keyword-based search
	 */	 	
	public function search()
	{
		
	}
	
	/**
	 *
	 */	 	
	public function getStreamDataHTML()
	{
		jimport('joomla.html.pagination');
		$jconfig = new JConfig();
		$filter = array();
		if( $mention = JRequest::getVar('mention', '') ){
			$filter['mention'] = '@'.$mention;
		}
		
		if( $search = JRequest::getVar('search', '') ){
			$filter['search'] = $search;
		}
		
		$status = JRequest::getVar('status', '');
		if( $status != '' ){
			$filter['status'] = $status;
		}
		
		$filter['type'] = 'todo';
		
		$my = JXFactory::getUser();
		$by = JRequest::getVar('by', '');
		if( $by == 'mygroups' ){
			$filter['group_ids'] = $my->getParam('groups_member');
		}
		elseif( $by == 'mine' )
		{
			$filter['user_id'] = $my->id;
		}
		
		$model	= StreamFactory::getModel('stream');
		$data	= $model->getStream( $filter );
		$total	= $model->countStream( $filter );
		
		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jconfig->list_limit);
		
		$tmpl	= new StreamTemplate();
		$html = $tmpl->fetch('stream.filter');
		
		$tmpl	= new StreamTemplate();
		$tmpl->set('rows', $data);
		$tmpl->set('total', $total);
		$tmpl->set('pagination', $pagination);
		$html .= $tmpl->fetch('stream.data');
		return $html;
	}
	
	/**
	 *  Return
	 */	 	
	public function modGetPendingTask($inFilter)
	{
		$tasks = '';
		$tmpl	= new StreamTemplate();
		$model	= StreamFactory::getModel('stream');
		$my = JXFactory::getUser();
				
		// Get todo list with due date
		$filter = array();
		$filter['type'] = 'todo';
		$filter['has_end_date'] = true;
		$filter['order_by_asc'] = 'end_date';
		
		$filter = array_merge($filter, $inFilter); 

		$data	= $model->getStream( $filter );
		
		// calculate total task
		$hasWarnDue = false;
		$hasWarnToday = false;
		$hasWarnThisWeek = false;
		$hasWarnLater = false;
		$username = '';
		$showName = (JRequest::getVar('option') == 'com_stream');
		
		$today = new JXDate();
		//echo count($data); exit;
		foreach($data as $stream)
		{
			$dueDate = new JDate($stream->end_date);
			$raw = json_decode($stream->raw);
			$todoIndex = 0;
			
			$class = ($my->authorise('stream.todo.done',  $stream)) ? '' : 'readonly';
			
			foreach( $raw->todo as $todo )
			{
		
				if(!empty($todo)) {
					if(!$stream->getState($todoIndex))  
					{
						
						
						// Put the due labels
						if( $today->isOverdue($dueDate) ){
							if( !$hasWarnDue )
							{ 
								$tasks .= '<li class="todo-overdue"><span class="label label-important">' . JText::_('COM_PROFILE_LABEL_OVERDUE') . '</span></li>';
								$hasWarnDue = true;
							}
						} 
						elseif( $today->isToday($dueDate) ){
							if( !$hasWarnToday )
							{
								$tasks .= '<li class="todo-today"><span class="label label-success">' . JText::_('COM_PROFILE_LABEL_TODAY') . '</span></li>';
								$hasWarnToday = true;
							}
						}
						
						elseif( $today->isThisWeek($dueDate) ){
							if( !$hasWarnThisWeek )
							{
								$tasks .= '<li class="todo-thisweek"><span class="label label-warning">' . JText::_('COM_PROFILE_LABEL_THIS_WEEK') . '</span></li>';
								$hasWarnThisWeek = true;
							}
						}
						
						elseif(!$hasWarnLater){
							$tasks .= '<li class="todo-later"><span class="label">' . JText::_('COM_PROFILE_LABEL_LATER') . '</span></li>';
							$hasWarnLater = true;
						}
						// end due labels
						if($showName)
							$username = ' <span class="small hint">- '.StreamTemplate::escape(JXFactory::getUser($stream->user_id)->name ).'</span>';
						$tasks .= '<li class="clearfix todo-item"><a class="done-todo-item '.$class.'" data-message_id="'.$stream->id.'" data-todo_index="'.$todoIndex.'" href="javascript: void(0);"></a><span>'.StreamMessage::format($todo).$username.'</span></li>';
					}
					$todoIndex++;
				}
			}			
		}
		
		// Get todo with NO due date
		unset($filter);
		$filter = array();
		$filter['type'] = 'todo';
		$filter['!has_end_date'] = true;
		$filter['order_by_asc'] = 'id';
		
		$filter = array_merge($filter, $inFilter); 
		$data	= $model->getStream( $filter );
		
		$hasWarnAnytime = false;
		foreach($data as $stream)
		{
			$raw = json_decode($stream->raw);
			$todoIndex = 0;
			
			foreach( $raw->todo as $todo )
			{
				if(!empty($todo)) {
					$class = ($my->authorise('stream.todo.done',  $stream)) ? '' : 'readonly';
					if(!$stream->getState($todoIndex))  
					{
						if(!$hasWarnAnytime)
						{
							$tasks .= '<li class="todo-anytime"><span class="label">' . JText::_('COM_PROFILE_LABEL_ANYTIME') . '</span></li>';
							$hasWarnAnytime = true;
						}
						
						if($showName)
							$username = ' <span class="small hint">- '.StreamTemplate::escape(JXFactory::getUser($stream->user_id)->name ).'</span>';
						$tasks .= '<li class="clearfix todo-item"><a class="done-todo-item '.$class.'" data-message_id="'.$stream->id.'" data-todo_index="'.$todoIndex.'" href="javascript:void(0);"></a><span>'.StreamMessage::format($todo).$username.'</span></li>';
					}
					$todoIndex++;
				}
			}
		}
		
		if (empty($tasks))
		{
			$tasks = '<div class="alert-message block-message info"><p>'.JText::_('COM_STREAM_LABEL_NO_PENDING_TASK').'</p></div>';
		}
		$tmpl->set('tasks', $tasks);
		return $tmpl->fetch('todo.module.pending');
	}
}