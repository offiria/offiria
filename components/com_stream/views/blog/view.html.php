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
class StreamViewBlog extends StreamView
{
	function display($tpl = null)
	{
		$doc = JFactory::getDocument();
		$title = JText::_("NAVIGATOR_LABEL_BLOG");
		$this->addPathway( JText::_('NAVIGATOR_LABEL_BLOG'), JRoute::_('index.php?option=com_stream&view=blog') );
		
		$html = '';
		
		// Build the proper blog title
		if( $month = JRequest::getVar('month', '') ){
			$monthList = array( 1 => 'JANUARY', 2=> 'FEBRUARY', 3 => 'MARCH', 
						4 => 'APRIL', 5 => 'MAY', 6 => 'JUNE', 
						7 => 'JULY', 8 => 'AUGUST', 9 => 'SEPTEMBER', 
						10 => 'OCTOBER', 11 => 'NOVEMBER', 12 => 'DECEMBER');
						
			$title .= ' - '. JText::_($monthList[$month]) . ' ';
		}
		
		if( $year = JRequest::getVar('year', '') ){
			$title .= $year;
		}
		
		$doc->setTitle($title);

		$tmpl = new StreamTemplate();
		$html .= $tmpl->fetch('blog.header');
		
		$my = JXFactory::getUser();
		if(! $my->getParam(ALERT_BLOG_INTRO)){
			$html .= '
				<div class="alert alert-success" data-alert_id="'.ALERT_BLOG_INTRO.'">
		        <a data-dismiss="alert" class="close">×</a>
				'.JText::_('COM_STREAM_HELPER_BLOG').'</div>';
	    }
		
		$html .= $this->getStreamDataHTML();
		
		echo $html;
	}

	/**
	 * Show editor's
	 */
	public function edit( $message )
	{
		$doc = JFactory::getDocument();
		$html = '';
		
		// Add attachment script
		$doc->addScript(JURI::root(). 'media/uploader/fileuploader.js');
		$doc->addStyleSheet(JURI::root().'media/uploader/fileuploader.css');
		
		
		$tmpl	= new StreamTemplate();
		$tmpl->set('stream', $message);
		$html = $tmpl->fetch('blog.edit');

		$this->addPathway( JText::_('NAVIGATOR_LABEL_BLOG'), JRoute::_('index.php?option=com_stream&view=blog'));
		$this->addPathway( JText::sprintf('COM_STREAM_BLOG_MESSAGE_ID', $message->id) );
		
		return $html;
	}
	
	public function getStreamDataHTML()
	{
		jimport('joomla.html.pagination');
		$jconfig = new JConfig();
		$filter = array();
		
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
		
		/* Month/year created */
		if( $month = JRequest::getVar('month', '') ){
			$filter['month'] = $month;
		}
		
		if( $year = JRequest::getVar('year', '') ){
			$filter['year'] = $year;
		}
		
		// to check if category is in valid type
		preg_match('/[0-9]+/', JRequest::getVar('category_id'), $match);
		if( isset($match[0]) ) {
			$filter['category_id'] = $match[0];
		}
		
		$filter['type'] = 'page';
	
		$tmpl	= new StreamTemplate();
		$model	= StreamFactory::getModel('stream');
		$data	= $model->getStream( $filter, $jconfig->list_limit, JRequest::getVar('limitstart', 0) );

		$total	= $model->countStream( $filter );
		
		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jconfig->list_limit);

		JXModule::addBuffer('right', $this->modTagsTrendingHTML());
		JXModule::addBuffer('right', $this->getArchiveHTML() );
		
		$html = '';
		
		//$tmpl	= new StreamTemplate();
		//$html = $tmpl->fetch('stream.filter');
		
		$tmpl	= new StreamTemplate();
		$tmpl->set('rows', $data);
		$tmpl->set('total', $total);
		$tmpl->set('pagination', $pagination);
		$html .= $tmpl->fetch('stream.data');
		
		return $html;
	}
	
	/**
	 * 
	 */	 	
	private function getArchiveHTML()
	{
		$model = StreamFactory::getModel('stream');
		$data = $model->getMessageStats();
		$html = '<div class="moduletable">
			<h3>Archives</h3>
			<ul class="archivelist">';
		
		$monthList = array( 1 => 'JANUARY', 2=> 'FEBRUARY', 3 => 'MARCH', 
							4 => 'APRIL', 5 => 'MAY', 6 => 'JUNE', 
							7 => 'JULY', 8 => 'AUGUST', 9 => 'SEPTEMBER', 
							10 => 'OCTOBER', 11 => 'NOVEMBER', 12 => 'DECEMBER');
							
		foreach($data as $row){
			$link = JRoute::_('index.php?option=com_stream&view=blog&month='.$row->month.'&year='.$row->year ,false);
			$html .= '<li><a href="'.$link.'">'. JText::_($monthList[$row->month]) .'&nbsp;' .$row->year.'</a>&nbsp;('.$row->count.')</li>';
		}
		
		$html .='</ul></div>';
		
		return $html;
	}
	
}