<?php
/**
 * @version     1.0.0
 * @package     Offiria
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.user.helper');
jimport('joomla.xfactory');
jimport('joomla.form.form');

/**
 * HTML View class for the Administrator component
 */
class ProfileViewDisplay extends ProfileView
{
	function display($tpl = null)
	{
		include_once(JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'html'.DS.'string.php');

		$name	= JRequest::getString( 'user' );
		$userId	= JUserHelper::getUserId( $name );
		$user	= JXFactory::getUser( $userId );
		$my		= JXFactory::getUser();
		$lastStatus	= $user->getStatus();
		$task = JRequest::getVar('task', 'activities');

		$this->addPathway( JText::_('NAVIGATOR_LABEL_PEOPLE'), JRoute::_('index.php?option=com_people&view=members'));
		$this->addPathway( $user->name);

		$doc = JFactory::getDocument();
		
		// Add attachment script
		$doc->addScript(JURI::root() . 'media/uploader/fileuploader.js');
		$doc->addStyleSheet(JURI::root() . 'media/uploader/fileuploader.css');
		
		$groupsView = StreamFactory::getView('groups');
		// $html = $groupsView->getUserGroupsHTML($userId);
		// JXModule::addBuffer('right', $html );
		
		// this section is to get the group_type to set the twitter button's toggle
		$validGroupType = JAnalytics::getGroupType();
		$analyticsGroupBy = JRequest::getVar('group_type', 'day');
		$analyticIndex = array_search($analyticsGroupBy, $validGroupType);
		$analyticIndex = ($analyticIndex === false) ? 1 : $analyticIndex;
		
		$this->assignRef('user', $user);
		$this->assignRef('my', $my);
		$this->assignRef('analyticIndex', $analyticIndex);
		$this->assignRef('analyticType', $validGroupType);
		$this->assign('lastStatus', $lastStatus[0]->message);
		$this->assign('analyticHtml', $this->generateAnalytics($user));
		
		switch($task){
			case 'activities':
				$companyView = StreamFactory::getView('company');
				
				$filter = array();
				$filter['user_id'] = $user->id; // Show only the user's stream
				
				$options = array();
				$options['filter'] = 'stream.filter.profile';
				
				$activities = $companyView->getStreamDataHTML( $filter , $options);
				
				$this->assignRef('activities', $activities);
				break;
				
			case 'bio':
				// Bio
				JForm::addFieldPath(JPATH_COMPONENT . DS . 'models' . DS . 'fields');
		
				$form = JForm::getInstance('form', JPATH_ROOT.DS.'components'.DS.'com_profile'.DS.'models'.DS.'forms'.DS.'details.xml');
				$detailModel = ProfileFactory::getModel('detail');
				$form->bind(array('params'=>$detailModel->getDetails($user->id)));
				$this->assignRef('form', $form);
				$this->assignRef('user', $user);
				break;
				
			case 'content':
				$this->assignRef('user', $user);
				
				$streamModel = StreamFactory::getModel('stream');
				$blogs = $streamModel->getStream( array( 'type' => 'page', 'user_id' => $user->id), 10);
				$this->assign('blogs', $blogs);
				$this->assign('blogCount', $streamModel->countStream(array( 'type' => 'page', 'user_id' => $user->id)));
				
				
				$links = $user->getParam('links', '');
				$linkModel = StreamFactory::getModel('links');
				$links = $linkModel->getLinks( array( 'id' => $links, '!link' => ''), 10);
				$this->assignRef('links', $links);
				$this->assign('linkCount', JXUtility::csvCount($user->getParam('links')));
				
				$fileModel = StreamFactory::getModel('files');
				$files = $fileModel->getFiles( array( 'user_id' => $user->id), 10);
				$fileView = StreamFactory::getView('files');
				$this->assignRef('fileView', $fileView);
				$this->assignRef('files', $files);
				$this->assign('fileCount', $fileModel->countFiles(array( 'user_id' => $user->id)));
				break;
		}

		//$document = JFactory::getDocument();
		// $document->setTitle(JText::_('COM_PROFILE_LABEL_PROFILE_PAGE').': '.$user->name);
		$groupView = StreamFactory::getView('groups');
		JXModule::addBuffer('right', $groupView->getUserActiveGroupsHTML($user->id));
		
		// development
		$fileView = StreamFactory::getView('files');
		JXModule::addBuffer('right', $fileView->modUserFilesHTML($user));

		parent::display($tpl);
	}
	
	public function generateAnalytics($user)
	{
		require_once( JPATH_ROOT. DS. 'libraries/HighRoller/HighRoller.php');
		require_once(JPATH_ROOT. DS. 'libraries/HighRoller/HighRollerSeriesData.php');
		require_once(JPATH_ROOT. DS. 'libraries/HighRoller/HighRollerLineChart.php');
		require_once(JPATH_ROOT. DS. 'libraries/HighRoller/HighRollerColumnChart.php');
		
		$validGroupType = JAnalytics::getGroupType();
		$analyticsGroupBy = JRequest::getVar('group_type', 'day');
		$analyticsGroupBy = (!in_array($analyticsGroupBy, $validGroupType)) ? 'day' : $analyticsGroupBy;
		
		$chartData1 = JAnalytics::get('', $user->id, null, '', $analyticsGroupBy);
		
		$chartData2 = JAnalytics::get(array('message.like', 'comment.like'), $user->id, null, '', $analyticsGroupBy);
		
		$linechart = new HighRollerLineChart();
		$linechart->legend = new stdClass();
		$linechart->credits = new stdClass();
		$linechart->chart->renderTo = 'linechart';
		$linechart->chart->type = 'area';
		
		$linechart->yAxis = new stdClass();
		$linechart->yAxis->title = new stdClass();
		$linechart->yAxis->labels = new stdClass();

		$linechart->xAxis = new stdClass();
		$linechart->xAxis->title = new stdClass();
		$linechart->xAxis->labels = new stdClass();

		$linechart->yAxis->title->text = '';
		$linechart->yAxis->min = 0;
		$linechart->yAxis->labels->enabled = true;
		$linechart->xAxis->labels->enabled = true;
		
		$linechart->xAxis->categories =  JAnalytics::getXAxisCategory($analyticsGroupBy);
		$linechart->legend->enabled = false;
		$linechart->credits->enabled = false;
		//$linechart->title->text = 'Line Chart';
		
		$series1 = new HighRollerSeriesData();
		$series1->addName('Activity')->addData($chartData1)->addColor('#82CAFA');
		
		$series2 = new HighRollerSeriesData();
		$series2->addName('Comments/Like')->addData($chartData2)->addColor('#6698FF');
		
		$linechart->addSeries($series1);
		$linechart->addSeries($series2);
		
		$html = '<div id="linechart" style="height:160px"></div><script type="text/javascript">'. $linechart->renderChart().'</script>';
		return $html;
	}


	/**
	 * is a method to set up the bread crumbs
	 */
	public function addPathway( $text , $link = '' )
	{
		// Set pathways
		$mainframe		= JFactory::getApplication();
		$pathway		= $mainframe->getPathway();
		
		$pathwayNames	= $pathway->getPathwayNames();
		
		// Test for duplicates before adding the pathway
		if( !in_array( $text , $pathwayNames ) )
		{
			$pathway->addItem( $text , $link );
		}
	}
}