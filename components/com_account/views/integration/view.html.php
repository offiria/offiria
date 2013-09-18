<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
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
		$this->assignRef('row', $row);
		
		parent::display($tpl);
	}
}