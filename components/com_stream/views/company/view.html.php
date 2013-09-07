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
class StreamViewCompany extends StreamView
{
	function display($tpl = null)
	{
		// Reset the stream view count every time we visit this page		
		$doc = JFactory::getDocument();
		$my  = JXFactory::getUser();
		
		$lastMessageId = StreamMessage::lastMessageId();

		$groupView = StreamFactory::getView('groups');
		$eventView = StreamFactory::getView('events');

		// Side bar modules
		JXModule::addBuffer('right', $this->modTagsTrendingHTML());

		if(!$my->isExtranetMember()) {
			JXModule::addBuffer('right', $groupView->getNewGroupsHTML($my->getMergedGroupIDs()));
		}
		JXModule::addBuffer('right', $eventView->getUpcomingHTML());

		echo $this->getStreamPostHTML(); // Post box
		echo $this->getStreamDataHTML(); // Stream items
	}

	function tagFilter($tpl = null)
	{
		$doc = JFactory::getDocument();
		
		$search = JRequest::getVar('search', ''); // Hashtags
		$tag    = JRequest::getVar('tag', '', 'GET', 'STRING'); // Tags
		
		$options = array();
		$options['hide_filter'] = 1; // Hide filter tabs
		
		$filter = array();
		$html   = '';

		if (!empty($search)) {
			$doc->setTitle(JText::sprintf("COM_STREAM_SEARCH_TEXT", $search));
		} else if (!empty($tag)) {
			$filter['tag'] = $tag;

			if ($tagGroupId = JRequest::getVar('tagGroupId', '')) {
				$group = JTable::getInstance('Group', 'StreamTable');
				$group->load($tagGroupId);
				$doc->setTitle(JText::sprintf("COM_STREAM_SEARCH_GROUP_TAG", $tag));

				$this->addPathway( JText::_('NAVIGATOR_LABEL_GROUPS'), JRoute::_('index.php?option=com_stream&view=groups') );
				$this->addPathway($group->name, JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id=' . $group->id));

				$filter['group_id'] = $tagGroupId;
			} else {
				$doc->setTitle(JText::sprintf("COM_STREAM_SEARCH_TAG", $tag));
			}
			$this->addPathway('Tag Filter');
		} else {
			return $html;
		}

		// Trending tags at the right sidebar
		JXModule::addBuffer('right', $this->modTagsTrendingHTML((isset($group)) ? $group : null));

		$html .= '<hr />';
		$html .= $this->getStreamDataHTML($filter, $options);

		return $html;
	}
	
	/**
	 * Keyword-based search
	 */	 	
	public function search()
	{	
	}

	public function getStreamPostHTML()
	{
		if (JRequest::getVar('template') == 'mobile') {
			return false;
		}
		$title = JText::_("COM_STREAM_LABEL_RECENT_ACTIVITIES");
		if( $search = JRequest::getVar('search', '') ){
			$title = JText::_('COM_STREAM_LABEL_SEARCHING FOR').": " . $search;
		}
		
		$html = '';                                  
		$tmpl = new StreamTemplate();
		$html = $tmpl->set('title', $title )->set('group_id', '')->fetch('stream.post');
		
		return $html;
	}

	public function getStreamDataHTML($filter = array() , $options = array())
	{
		jimport('joomla.html.pagination');
		$app	= JFactory::getApplication();
		$jconfig = new JConfig();
		$html = '';
		
		$user = JXFactory::getUser();
		
		if( $mention = JRequest::getVar('mention', '') ){
			$filter['mention'] = '@'.$mention;
		}
		
		if( $user_id = JRequest::getVar('user_id', '') ){
			$filter['user_id'] = $user_id;
		}
		
		if( $search = JRequest::getVar('search', '') ){
			$filter['search'] = $search;
		}
		
		if( $group_id = JRequest::getVar('group_id', '') ){
			$filter['group_id'] = $group_id;
		}
		
		if( $limit_start = JRequest::getVar('limitstart', '') ){
			$filter['limitstart'] = $limit_start;
		}
		
		if( $overdue = JRequest::getVar('overdue', '') ){
			$date = new JDate();
			$filter['end_date'] = $date->toMySQL();
		}

		// Order by 'updated'
		$filter['order_by_desc'] = 'updated';

		$model	= StreamFactory::getModel('stream');
		$data	= $model->getStream($filter, $jconfig->list_limit, JRequest::getVar('limitstart', 0));
		
		$total	= $model->countStream( $filter );
		
		// Set $user if user_id filter is specified
		if(isset($filter['user_id'])){
			$user = JXFactory::getUser( $filter['user_id'] );
		}
		
		$tmpl	= new StreamTemplate();
		$tmpl->set('user', $user);
		if(isset($options['filter'])){
			$html = $tmpl->fetch($options['filter']);
		} else if(!isset($options['hide_filter'])) {
			$html = $tmpl->fetch('stream.filter');
		}
		
		// Pagination
		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jconfig->list_limit);

		// If URI is defined, we need to set the pagination link properly
		if( $uri = JRequest::getVar('uri', '') ){
	
			$uri = new JURI($uri);
	
			$router = $app->getRouter();
			$result = $router->parse($uri);
	
			foreach($result as $key => $val) {
				$pagination->setAdditionalUrlParam($key, $val);
			}
		}
		
		$tmpl	= new StreamTemplate();
		$tmpl->set('rows', $data);
		
		$tmpl->set('total', $total);
		$tmpl->set('pagination', $pagination);
		$tmpl->set('options', $options);
		$html .= $tmpl->fetch('stream.data');
		return $html;
	}
	
	public function modGetDailyOverviewHtml()
	{
		$my = JXFactory::getUser();
		$showPopup = false;
		$dailyOverview = $my->getParam('overview', '');
		$dayOfWeek = date('w');
		$weekOfYear = date('W');
		$today = strtotime(date('Y-m-d'));		
		// use 6 minus as dayOfWeek starts at 0
		$lastDateofWeek = $today + ((6 - $dayOfWeek) * 24 * 60 * 60);
		$firstDateofWeek = $today - ($dayOfWeek * 24 * 60 * 60);
		
		// Need to check user param for popup require on each day
		$save = false;
		if (empty($dailyOverview))
		{
			$dailyOverview = new stdClass();
			$dailyOverview->date = $today;
			$save = true;
		}
		else
		{
			$dailyOverview = json_decode($dailyOverview);
			if ($dailyOverview->date < $today)
			{
				$dailyOverview->date = $today;
				$save = true;
			}
		}
		
		// save if require to popup to avoid subsequent popup on viewing homepage
		if ($save)
		{
			$my->setParam('overview', json_encode($dailyOverview));
			$my->save();
			$showPopup = true;
		}
		// display popup
		$yesterday = $today - (60*60*24);
		$streamModel = JModel::getInstance('Stream', 'StreamModel');

		// find all incomplete milestone
		$milestones = $streamModel->getStreamIds(array('type' => 'milestone', 
														'user_id' => $my->id, 
														'status' => 0, 
														'custom' => '(start_date >= "'.date('Y-m-d',$today).'" AND start_date <= "'.date('Y-m-d',$lastDateofWeek).'")'));
		//var_dump($milestones);

		// find all upcoming events
		$events = $streamModel->getStreamIds(array('type' => 'event', 
													'user_id' => $my->id, 
													'custom' => '(start_date <= "'.date('Y-m-d', $lastDateofWeek).'" AND (end_date >= "'.date('Y-m-d', $today).'" AND end_date <= "'.date('Y-m-d', $lastDateofWeek).'") OR 
																 (start_date >= "'.date('Y-m-d', $today).'" AND start_date <= "'.date('Y-m-d', $lastDateofWeek).'")) ' ));
		//var_dump($events);

		// find all incomplete todos
		$todo = $streamModel->getStreamIds(array('type' => 'todo', 
												'user_id' => $my->id, 
												'custom' => '(params REGEXP \'"state_[0-9]+":"0","by_[0-9]+":"0"\' OR params NOT REGEXP \'"state_[0-9]+"\') AND (end_date >= "'.date('Y-m-d',$today).'" AND end_date <= "'.date('Y-m-d',$lastDateofWeek).'")'));
		//var_dump($todo);
		
		
		$tmpl	= new StreamTemplate();
		$firstDay = new JDate($firstDateofWeek);
		$lastDay = new JDate($lastDateofWeek);
		$tmpl->set('firstDay', $firstDay)->set('lastDay', $lastDay)->set('today', $today)->set('weekNumber', $weekOfYear);
		$tmpl->set('todos', $todo)->set('events', $events)->set('milestones', $milestones);
		$tmpl->set('todoCount', count($todo))->set('eventCount', count($events))->set('milestoneCount', count($milestones))->set('showPopup', $showPopup);
		return $tmpl->fetch('stream.module.dailyoverview');
	}
}