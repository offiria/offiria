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
class StreamViewFiles extends StreamView
{
	function display($tpl = null)
	{
		$jconfig = new JConfig();
		jimport('joomla.html.pagination');
		include_once(JPATH_LIBRARIES.DS.'joomla'.DS.'html'.DS.'html'.DS.'string.php');
        $this->addPathway( JText::_('NAVIGATOR_LABEL_FILE'), JRoute::_('index.php?option=com_stream&view=groups') );
		$title = JText::_("COM_STREAM_LABEL_FILE_LISTING");
		$this->_attachScripts();
		
		// Reset the stream view count every time we visit this page


		$my = JXFactory::getUser();
		
		$filter = array();
		
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

		// If user is limited to certain group, filter it
		$limitGroup = $my->getParam('groups_member_limited');
		if($limitGroup) {
			$filter['group_id'] = $limitGroup;
		}

		
		$fileModel = StreamFactory::getModel('files');
		$files = $fileModel->getFiles( $filter, $jconfig->list_limit,JRequest::getVar('limitstart', 0) );
		$total = $fileModel->getTotal( $filter );
		
		// Show storage stats
		// JXModule::addBuffer('right', $this->getStorageStatsHTML() );
		
		$doc = JFactory::getDocument();
		$doc->setTitle($title);
		
		// Pagination
		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jconfig->list_limit);
		
		$html = '';          
		
		/* if user has not close the alert below or this is not user`s file preview */
		if(! ($my->getParam(ALERT_FILE_INTRO) || JRequest::getVar('user_id', false)) ){
			$html .= '
				<div class="alert alert-success" data-alert_id="'.ALERT_FILE_INTRO.'">
		        <a data-dismiss="alert" class="close">×</a>
		        These are the files uploaded by everyone. You can filter the files by the file type as well
		        as the file ownership.
		      </div>';
	    }
		$tmpl = new StreamTemplate();
		
		// Do not show owner filter if we're viewing specific user's file
		$tmpl->set('showOwnerFilter', (JRequest::getVar('user_id', 0) == 0 ));
		$html .= $tmpl->fetch('file.header');
		
		
        
		                        
		$tmpl = new StreamTemplate();
		$streamTbl = JTable::getInstance('Stream', 'StreamTable');		
		$tmpl->set('streamTbl', $streamTbl)->set('my', $my);
		$tmpl->set('files', $files)->set('pagination', $pagination);
		$html .= $tmpl->fetch('files.list');
		
		return $html;		
					
		//JXModule::addBuffer('right', $groupView->getNewGroupsHTML($myGroupsIds) );
		//JXModule::addBuffer('right', $eventView->getUpcomingHTML() );
		//echo $this->getStreamPostHTML();
		//echo $this->getStreamDataHTML();
	}
	
	/**
	 * Keyword-based search
	 */	 	
	public function search()
	{
		
	}
	
	/**
	 * Show files of a group
	 */	 	
	public function modGroupFilesHTML($group, $limit = 10)
	{
		$fileModel = StreamFactory::getModel('files');
		$files = $fileModel->getFiles( array('group_id' => $group->id), $limit );
		$total = $group->count('file');
		
		$tmpl = new StreamTemplate();
		$tmpl->set('title', JText::_('COM_STREAM_LABEL_RELATED_FILES'));
		$tmpl->set('files', $files)->set('total', $total)->set('group', $group);
		$html = $tmpl->fetch('file.module.list');
		return $html;
	}
	
	/**
	 * 
	 */	 	
	public function getStorageStatsHTML()
	{
		$jxConfig = new JXConfig();
		$current_plan = $jxConfig->getCurrentPlan();
		$planPackage = $jxConfig->getAvailablePlans( $current_plan );
		// Change from Gb to bytes
		$packSize = (isset($planPackage[2]) && (int)$planPackage[2] > 0) ? $planPackage[2] : 0;
		$total = $packSize * 1000 * 1000 * 1000;
		
		$fileModel = StreamFactory::getModel('files');
		$used = $fileModel->getTotalStorage( JRequest::getVar('user_id', null) );
		$tmpl = new StreamTemplate();
		$tmpl->set('title', JText::_('COM_STREAM_LABEL_STORAGE_USAGE'));
		$tmpl->set('used', $used)->set('total', $total);
		$html = $tmpl->fetch('file.module.storagestats');
		return $html;
	}

	public function modUserFilesHTML($user, $limit = 10)
	{
		$fileModel = StreamFactory::getModel('files');
		$files = $fileModel->getFiles( array('user_id' => $user->id), $limit );
		$total = $fileModel->countFiles( array('user_id' => $user->id));

		$tmpl = new StreamTemplate();
		$tmpl->set('title', JText::_('COM_STREAM_LABEL_FILES'));
		$tmpl->set('files', $files)->set('total', $total);
		$tmpl->set('user', $user);
		$html = $tmpl->fetch('file.module.list');
		return $html;
	}
}