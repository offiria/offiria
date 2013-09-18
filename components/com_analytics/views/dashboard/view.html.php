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
jimport('joomla.analytics.analytics');

/**
 * HTML View class for the Administrator component
 */
class AnalyticsViewDashboard extends AnalyticsView
{
	function display($tpl = null)
	{
		// Reset the Analytics view count every time we visit this page
		
		$doc = JFactory::getDocument();
		$my = JXFactory::getUser();
		
		$doc->setTitle(JText::_('COM_ANALYTICS_LABEL_COMMUNITY_ANALYTICS'));
		
		// this section is to get the group_type to set the twitter button's toggle
		$validGroupType = JAnalytics::getGroupType();
		$analyticsGroupBy = JRequest::getVar('group_type', 'day');
		$analyticIndex = array_search($analyticsGroupBy, $validGroupType);
		$analyticIndex = ($analyticIndex === false) ? 1 : $analyticIndex;
		
		$this->assign('analyticHtml', $this->generateAnalytics());
		$this->assign('analyticType', $validGroupType);		
		$this->assign('analyticIndex', $analyticIndex);
		$this->assign('activeUserList', $this->getActiveUser());
		$this->assign('activeGroupList', $this->getActiveGroup());
		parent::display($tpl);
		
	}
	
	function generateAnalytics()
	{
		require_once( JPATH_ROOT. DS. 'libraries/HighRoller/HighRoller.php');
		require_once(JPATH_ROOT. DS. 'libraries/HighRoller/HighRollerSeriesData.php');
		require_once(JPATH_ROOT. DS. 'libraries/HighRoller/HighRollerLineChart.php');
		require_once(JPATH_ROOT. DS. 'libraries/HighRoller/HighRollerColumnChart.php');
		
		$validGroupType		= JAnalytics::getGroupType();
		$analyticsGroupBy	= JRequest::getVar('group_type', 'day');
		$analyticsGroupBy	= (!in_array($analyticsGroupBy, $validGroupType)) ? 'day' : $analyticsGroupBy;
		
		
		$linechart = new HighRollerLineChart();
		$linechart->chart->renderTo = 'dashboard';
		$linechart->chart->type = 'area';
		//$linechart->tooltip->enabled = false;
		$linechart->yAxis = new StdClass;
		$linechart->xAxis = new StdClass;
		$linechart->yAxis->title = new StdClass;
		$linechart->yAxis->title->text = '';
		$linechart->yAxis->min = 0;
		$linechart->yAxis->labels = new StdClass;
		$linechart->xAxis->labels = new StdClass;
		$linechart->yAxis->labels->enabled = true;
		$linechart->xAxis->labels->enabled = true;
		
		$linechart->xAxis->categories =  JAnalytics::getXAxisCategory($analyticsGroupBy);
		$linechart->legend = new StdClass;
		$linechart->credits  = new StdClass;
		$linechart->legend->enabled = false;
		$linechart->credits->enabled = false;
		//$linechart->title->text = 'Line Chart';
		
		// Get the filter for log, default is all activities
		$filter				= JRequest::getVar('filter', '0');
		if ($filter == '0')
		{
			$chartData1 = JAnalytics::get('', null, null, '', $analyticsGroupBy);
			$series1 = new HighRollerSeriesData();
			$series1->addName(JText::_('COM_ANALYTICS_LABEL_ACTIVITY'))->addData($chartData1)->addColor('#41A317');		

			$linechart->addSeries($series1);
		}
		
		$chartData2 = JAnalytics::get(array('message.add'), null, null, '', $analyticsGroupBy);
		$series2 = new HighRollerSeriesData();
		$series2->addName(JText::_('COM_ANALYTICS_LABEL_NEW_POST'))->addData($chartData2)->addColor('#64E986');
		$linechart->addSeries($series2);	
		
		$html = '<div id="dashboard"></div><script type="text/javascript">'. $linechart->renderChart().'</script>';
		return $html;
	}
	
	public function getActiveUser()
	{
		return JAnalytics::getDbRecord('', null, null, '', 'active_user');
	}
	
	
	public function getActiveGroup()
	{
		return JAnalytics::getDbRecord('', null, null, '', 'active_group');
	}
}