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

class StreamViewDirect extends StreamView
{
	function display($tpl = null)
	{
        $this->addPathway( JText::_('NAVIGATOR_LABEL_INBOX'), JRoute::_('index.php?option=com_stream&view=direct') );

		$this->_attachScripts();
        $my = JXFactory::getUser();
		$html = '';
		
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_("COM_STREAM_MY_PRIVATE_MESSAGES"));

		// Add attachment script
		$doc->addScript(JURI::root() . 'media/uploader/fileuploader.js');
		$doc->addStyleSheet(JURI::root() . 'media/uploader/fileuploader.css');

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

		$filter['type'] = 'direct';

		$model	= StreamFactory::getModel('stream');
		$data	= $model->getStream( $filter );
		$total	= $model->countStream( $filter );

		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jconfig->list_limit);

		$html = '';

		$tmpl	= new StreamTemplate();
		$tmpl->set('rows', $data);
		$tmpl->set('total', $total);
		$tmpl->set('pagination', $pagination);
		$html .= $tmpl->fetch('direct.list');

		echo $html;
	}

	/*public function getStreamDataHTML()
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
		
		$filter['type'] = 'direct';
		
		$model	= StreamFactory::getModel('stream');
		$data	= $model->getStream( $filter );
		$total	= $model->countStream( $filter );
		
		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jconfig->list_limit);
		
		$html = '';
		
		$tmpl	= new StreamTemplate();
		$tmpl->set('rows', $data);
		$tmpl->set('total', $total);
		$tmpl->set('pagination', $pagination);
		$html .= $tmpl->fetch('stream.data');

		return $html;
	}*/
}