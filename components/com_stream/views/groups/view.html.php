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
class StreamViewGroups extends StreamView
{
	function display($tpl = null)
	{
		jimport('joomla.html.pagination');
		$this->addPathway( JText::_('NAVIGATOR_LABEL_GROUPS'), JRoute::_('index.php?option=com_stream&view=groups') );
		$my = JXFactory::getUser();
		
        $this->_attachScripts();
		$html = '';
		$jconfig = new JConfig();
		$doc = JFactory::getDocument();
		$groupsModel = StreamFactory::getModel('groups');
		
		$filter = array();
		if(JRequest::getVar('filter', 'all') == 'joined'){
			$groupIJoin 	= $my->getParam('groups_member');
			if(empty($groupIJoin)){
				// force the system to seach for dummy group since it
				// will skip seach for empty value
				$filter['id'] = '-1';
			} else {
				$filter['id'] = $groupIJoin;
			}
			
		}
		if(JRequest::getVar('filter', 'all') == 'followed'){
			$groupIJoin 	= $my->getParam('groups_follow');
			if(empty($groupIJoin)){
				// force the system to seach for dummy group since it
				// will skip seach for empty value
				$filter['id'] = '-1';
			} else {
				$filter['id'] = $groupIJoin;
			}
		}
		if(JRequest::getVar('filter', 'all') == 'archived'){
			$filter['archived'] = 1;
		}
		if(JRequest::getVar('filter', 'all') == 'category'){
			$filter['category_id'] = JRequest::getVar('category_id', NULL);
		}
		
		$groups = $groupsModel->getGroups( $filter, $jconfig->list_limit, JRequest::getVar('limitstart', 0));
		$total = $groupsModel->getTotal($filter);
		
		$doc->setTitle(JText::_("COM_STREAM_LABEL_GROUP_LISTING"));
		
		// Pagination
		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jconfig->list_limit);
		
		$tmpl = new StreamTemplate();
		$html .= $tmpl->fetch('groups.header');
		
		$tmpl = new StreamTemplate();
		$html .= $tmpl->set('groups', $groups)->set('pagination', $pagination)->fetch('groups.list');
		
		echo $html;
	}
	
	/**
	 * Show individual group
	 */	 	
	function show($group)
	{
		jimport('joomla.html.pagination');
		$my = JXFactory::getUser();
		$jconfig = new JConfig();
		$html = '';
		$this->_addPathway($group);
		
		$document = JFactory::getDocument();
		$this->_attachScripts();
		$document->setTitle($group->name);
		
		//$tmpl	= new StreamTemplate();
		//$html 	.= $tmpl->fetch('groups.tab');
		$tmpl = new StreamTemplate();
		$tmpl->set('group', $group)->set('show_back', false);
		$html .= $tmpl->fetch('group.header');
				
		$messageId = JRequest::getInt('message_id', 0);
		if ( intval($messageId) > 0 )
		{			
			$message_id = JRequest::getVar( 'message_id' );
			$stream		= JTable::getInstance( 'Stream' , 'StreamTable' );
			$stream->load( $message_id );
			$view 		= StreamFactory::getView( 'message' );
			//JRequest::setVar('view', 'message');

			$html .= $view->show($stream);
		}
		else
		{
			$tmpl->set('group', $group);
			$html .= $tmpl->fetch('groups.show');

			$filter = array();		
			$filter['group_id'] =$group->id;

			// Order by 'updated'
			$filter['order_by_desc'] = 'updated';

			$model	= StreamFactory::getModel('stream');
			$data	= $model->getStream($filter, $jconfig->list_limit, JRequest::getVar('limitstart', 0));
			$total	= $model->countStream($filter);
			$html 	.= $tmpl->fetch('stream.filter');
			
			/*if ($total <= 0 && $my->getParam('first_grp_create', 0) == 0)
			{
				$my->setParam('first_grp_create', 1);
				$my->save();
				$html 	.= $tmpl->fetch('group.first.create');
			}
			else
			{*/
				$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jconfig->list_limit);

				$tmpl->set('rows', $data);
				$tmpl->set('total', $total);
				$tmpl->set('pagination', $pagination);
				$html .= $tmpl->fetch('stream.data');
			//}
		}
		
		$fileView = StreamFactory::getView('files');
		
		// Sidebar
		$todoView = StreamFactory::getView('todo');
		$eventView = StreamFactory::getView('events');
		//$analyticsHTML = '<div class="moduletable"><h3>'.JText::_('COM_STREAM_LABEL_GROUP_ACTIVITY').'</h3><img src="'. JURI::root().'components/com_stream/assets/images/analytics.png" width="240" height="38"/></div>';
		//JXModule::addBuffer('right', $analyticsHTML);

			
		// Add group header to the sidebar
		JXModule::addBuffer('right', $tmpl->fetch('group.module.info') );

		JXModule::addBuffer('right', $this->modTagsTrendingHTML($group));
		JXModule::addBuffer('right', $this->modGetMilestonesHTML($group));
		JXModule::addBuffer('right', $todoView->modGetPendingTask(array('group_id' => $group->id)));
		JXModule::addBuffer('right', $eventView->getUpcomingHTML(array('group_id'=>$group->id)));
		JXModule::addBuffer('right', $this->modGetMembersHTML($group) );
		//JXModule::addBuffer('right', $fileView->modGroupFilesHTML($group));

		return $html;
	}


	/**
	 * Show group events
	 */
	public function show_events($group)
	{
		$document = JFactory::getDocument();
		$this->_addPathway($group);
		$this->_attachScripts();
		$document->setTitle($group->name);
		$this->addPathway( JText::_('NAVIGATOR_LABEL_EVENTS') );

		$html = '';

		$tmpl = new StreamTemplate();
		$tmpl->set('group', $group)->set('show_back', true);
		$html .= $tmpl->fetch('group.header');

		$eventView = StreamFactory::getView('events');
		$html .= $eventView->getGroupEventHTML(array('group_id' => $group->id));

		return $html;
	}


	/**
	 * Show group events
	 */
	public function show_todos($group)
	{
		jimport('joomla.html.pagination');
		$jconfig = new JConfig();
		
		$document = JFactory::getDocument();
		$this->_addPathway($group);
		$this->_attachScripts();
		$document->setTitle($group->name);
		$this->addPathway( JText::_('NAVIGATOR_LABEL_TODO') );

		$html = '';

		$tmpl = new StreamTemplate();
		$tmpl->set('group', $group)->set('show_back', true);
		$html .= $tmpl->fetch('group.header');
		
		$html .= $tmpl->fetch('todo.header');

		$filter = array('type' => 'todo', 'group_id' => $group->id);
		
		$status = JRequest::getVar('status', '');
		if( $status != '' ){
			$filter['status'] = $status;
		}
		
		$filter['type'] = 'todo';
		
		$my = JXFactory::getUser();
		$by = JRequest::getVar('by', '');
		if( $by == 'mine' )
		{
			$filter['user_id'] = $my->id;
		}
		
		$model	= StreamFactory::getModel('stream');
		$data	= $model->getStream( $filter );
		$total	= $model->countStream( $filter );
		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jconfig->list_limit);
		
		$tmpl->set('rows', $data);
		$tmpl->set('total', $total);
		$tmpl->set('pagination', $pagination);
		$html .= $tmpl->fetch('stream.data');
		return $html;

		return $html;
	}
	
	/**
	 * Show group files
	 */
	public function show_files($group){
		$jconfig = new JConfig();
		jimport('joomla.html.pagination');
		
		$document = JFactory::getDocument();
		$this->_addPathway($group);
		$this->_attachScripts();
		$document->setTitle($group->name);
		$this->addPathway( JText::_('COM_STREAM_LABEL_FILES') );
		
		$html = '';
		
		$tmpl = new StreamTemplate();
		$tmpl->set('group', $group)->set('show_back', true);
		$html .= $tmpl->fetch('group.header');

		$filter = array();
		$filter['group_id'] = $group->id;
				
		
		// Filter by filetype
		if( $filetype = JRequest::getVar('filetype', '') ){
			$filter['filetype'] = $filetype;
		}
		
		// Filter by "by/creator"
		if( $by = JRequest::getVar('by', '') ){
			$filter['by'] = $by;
		}
		
		// Filter by user_id (cannot be used along with 'by' filter)
		else if( $user_id = JRequest::getVar('user_id', '') ){
			$filter['user_id'] = $user_id;
			$user = JXFactory::getUser($user_id);
			$title = JText::sprintf("%1s's files", $user->name);
		}
		
		$fileModel = StreamFactory::getModel('files');
		$files = $fileModel->getFiles( $filter, $jconfig->list_limit,JRequest::getVar('limitstart', 0) );
		$total = $fileModel->getTotal( $filter );
		
		// Pagination
		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jconfig->list_limit);
		
		$streamTbl = JTable::getInstance('Stream', 'StreamTable');
		$my = JXFactory::getUser();
		
		$tmpl = new StreamTemplate();		
		$tmpl->set('streamTbl', $streamTbl)->set('my', $my);
		$tmpl->set('files', $files)->set('pagination', $pagination);
		$tmpl->set('showOwnerFilter', (JRequest::getVar('user_id', 0) == 0 ));
		$html .= $tmpl->fetch('file.header');
		$html .= $tmpl->fetch('files.list');
		
		return $html;
	}	 
	
	/**
	 * Show group files
	 */
	public function show_members($group){
		$jconfig = new JConfig();
		jimport('joomla.html.pagination');
		
		$document = JFactory::getDocument();
		$this->_addPathway($group);
		$this->_attachScripts();
		$document->setTitle($group->name);
		
		$this->addPathway( JText::_('COM_STREAM_LABEL_MEMBERS') );
		
		$html = '';
		
		$tmpl = new StreamTemplate();
		$tmpl->set('group', $group)->set('show_back', true);
		$html .= $tmpl->fetch('group.header');
		
		$memberIds = $group->members;
		$memberIds =  explode( ',', trim( $memberIds, ',' ) );
		
		$members = array();
		foreach($memberIds as $id)
		{
			if (intval($id) > 0)
			{
				$members[] = JXFactory::getUser($id);
			}
		}

		$total = count($members);

		// TODO: create a get group member method in member/group model
		$members = array_slice($members, JRequest::getVar('limitstart', 0));

		// Pagination
		$pagination = new JPagination($total, JRequest::getVar('limitstart', 0), $jconfig->list_limit);

		$tmpl = new StreamTemplate();
		$tmpl->set('group', $group)->set('members', $members)
			->set('total', $total)->set('pagination', $pagination);
		$html .= $tmpl->fetch('group.members.list');

		return $html;
	}	 
	
	
	/**
	 *
	 */	
	public function show_milestones($group)
	{
		include_once(JPATH_ROOT.DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'messages'.DS.'milestone.php');
		include_once(JPATH_ROOT.DS.'components'.DS.'com_stream'.DS.'libraries'.DS.'messages'.DS.'todo.php');

		$jconfig = new JConfig();
		jimport('joomla.html.pagination');

		$html = '';
		$document = JFactory::getDocument();
		$this->_addPathway($group);
		$this->_attachScripts();
		$document->setTitle($group->name);
		$this->addPathway(JText::_('COM_STREAM_LABEL_MILESTONE'));
		
		$tmpl = new StreamTemplate();
		$tmpl->set('group', $group)->set('show_back', true);
		$html .= $tmpl->fetch('group.header');
				
		$filter = array();
		$filter['group_id'] = $group->id;
		$filter['type'] 	= 'milestone';
		//$filter['order_by_desc'] = 'start_date';
		$filter['order_by'] = '`status` ASC, start_date ASC';
		
		$filterStatus = JRequest::getVar('status', 'upcoming');
		
		// Filter by "by/creator"
		$by = JRequest::getVar('by', '');
		if( $by == 'mine' ){
			$my = JXFactory::getUser();
			$title = JText::sprintf("%1s's files", $my->name);
			$filter['user_id'] = $my->id;
		}		
		// Filter by user_id (cannot be used along with 'by' filter)
		else if( $user_id = JRequest::getVar('user_id', '') )
		{			
			$user = JXFactory::getUser($user_id);
			$title = JText::sprintf("%1s's milestones", $user->name);
			$filter['user_id'] = $user->id;
		}
		
		$streamModel = StreamFactory::getModel('stream');
		$milestones = $streamModel->getStream($filter, $jconfig->list_limit, JRequest::getVar('limitstart', 0));
		$total	= $streamModel->countStream($filter);

		// Pagination
		$pagination = new JPagination($total, JRequest::getVar('limitstart', 0), $jconfig->list_limit);
		
		$tmpl = new StreamTemplate();
		$tmpl->set('milestones', $milestones)
			->set('pagination', $pagination)
			->set('showOwnerFilter', (JRequest::getVar('user_id', 0) == 0 ))
			->set('filterStatus', $filterStatus);
		//$html .= $tmpl->fetch('milestone.header');
		$html .= $tmpl->fetch('milestones.list');

		return $html;
	}	
	
	/**
	 *
	 */	 	
	public function modGetMembersHTML($group)
	{
		$memberIds = $group->members;
		$memberIds =  explode( ',', trim( $memberIds, ',' ) );
		
		$members = array();
		foreach($memberIds as $id)
		{
			if (intval($id) > 0)
			{
				$members[] = JXFactory::getUser($id);
			}
		}
		
		$total = count($members);
		
		$tmpl = new StreamTemplate();
		$tmpl->set('title', JText::_('COM_STREAM_LABEL_GROUP_MEMBERS'))
			->set('group', $group)
			->set('members', $members)
			->set('total', $total);
		$html = $tmpl->fetch('group.module.memberlist');

		return $html;
	}
	
	/**
	 * Show list of group milestone
	 */	 	
	public function modGetMilestonesHTML($group){
		$html = '<div class="moduletable"><h3>'.JText::_('Milestones').'</h3>';
		
		$filter = array();
		$filter['group_id'] = $group->id;
		$filter['type'] 	= 'milestone';
		$filter['order_by_asc'] = 'start_date';
		$filter['status'] = 0;
		
		$streamModel = StreamFactory::getModel('stream');
		$milestones = $streamModel->getStream( $filter );
		
		// Count how many milestone has been completed
		$filter['status'] = 1;
		$completedCount = $streamModel->countStream( $filter );
		 
		$tmpl = new StreamTemplate();
		$tmpl->set('milestones', $milestones);
		$tmpl->set('completedCount', $completedCount);
		$html .= $tmpl->fetch('group.module.milestoneslist');
		
		$html .= '</div>';
		
		return $html;
	}
	
	/**
	 * List down new groups excluding the gives groups
	 */	 	
	public function getNewGroupsHTML($exclude_ids = null, $limit = 6)
	{
		$filter = array();		
		$filter['!id'] = $exclude_ids;
		$filter['access'] = 0;
		
		$model	= StreamFactory::getModel('groups');
		$data	= $model->getGroups( $filter, $limit );
		
		$tmpl = new StreamTemplate();
		$tmpl->set('title', JText::_('COM_STREAM_LABEL_NEW_GROUPS'));
		$tmpl->set('groups', $data);
		$html = $tmpl->fetch('group.module.groups');

		return $html;
	}

	/**
	 * Return list of active groups
	 */	 	
	public function getActiveGroupsHTML($exclude_ids = null, $limit = 6)
	{
		$filter = array();		
		$filter['!id'] = $exclude_ids;
		
		$model	= StreamFactory::getModel('groups');
		$data	= $model->getGroups( $filter, $limit );
		
		$tmpl = new StreamTemplate();
		$tmpl->set('title', JText::_('COM_STREAM_LABEL_ACTIVE_GROUPS'));
		$tmpl->set('groups', $data);
		$html = $tmpl->fetch('group.module.groups');

		return $html;
	}
	
	/**
	 * Return list groups by the given user
	 */	 	
	public function getUserGroupsHTML($userid = null, $limit = 6)
	{
		$my = JXFactory::getUser($userid);
		$ids = $my->getParam('groups_member');
		$filter = array();		
		$filter['id'] = $ids;
		
		$model	= StreamFactory::getModel('groups');
		$data	= $model->getGroups( $filter, $limit );
		
		$tmpl = new StreamTemplate();
		$tmpl->set('title', JText::sprintf('COM_STREAM_LABEL_OWNER_GROUPS', $my->name ));
		$tmpl->set('groups', $data);
		$html = $tmpl->fetch('group.module.groups');

		return $html;
	}
	
	/**
	 * Return list groups the user is most involed in lately
	 */	 	
	public function getUserActiveGroupsHTML($userid = null, $limit = 6)
	{
		$my = JXFactory::getUser($userid);
		$ids = $my->getParam('groups_member');
		
		$model	= StreamFactory::getModel('groups');
		$data	= $model->getActiveGroups( $userid, $limit );
			
		$tmpl = new StreamTemplate();
		$tmpl->set('title', JText::sprintf("COM_STREAM_LABEL_GROUP_FAVORITE", $my->name ));
		$tmpl->set('groups', $data);
		$html = $tmpl->fetch('group.module.groups');

		return $html;
	}
	
	private function _addPathway($group){
		$this->addPathway( JText::_('NAVIGATOR_LABEL_GROUPS'), JRoute::_('index.php?option=com_stream&view=groups') );
		$this->addPathway( $group->name, JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='.$group->id) );
	}
}