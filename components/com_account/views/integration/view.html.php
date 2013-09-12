<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.utilities.xconfig');

JModel::addIncludePath( JPATH_ROOT.DS.'components'.DS.'com_account'.DS.'models' );
JTable::addIncludePath( JPATH_ROOT.DS.'components'.DS.'com_account'.DS.'tables' );

/**
 * HTML View class for the Account component
 */
class AccountViewIntegration extends AccountView
{
	function display($tpl = null)
	{
		
		$integrationModel = JModel::getInstance('integrations', 'AccountModel');
		$row = $integrationModel->getList();
		
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_("COM_ACCOUNT_LABEL_THIRD_PARTY_INTEGRATION"));
		$this->addPathway( JText::_('JXLIB_SETTINGS'), JRoute::_('index.php?option=com_account&view=account'));
		$this->addPathway(JText::_('COM_ACCOUNT_LABEL_THIRD_PARTY_INTEGRATION'));
		$this->assignRef('row', $row);
		
		parent::display($tpl);
	}
}