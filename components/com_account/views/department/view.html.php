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

/**
 * HTML View class for the Account component
 */
class AccountViewDepartment extends AccountView
{
	function display($tpl = null)
	{
		$configHelper	= new JXConfig();
		
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_("COM_ACCOUNT_LABEL_MANAGE_DEPARTMENT"));
		$this->addPathway( JText::_('JXLIB_SETTINGS'), JRoute::_('index.php?option=com_account&view=account'));
		$this->addPathway(JText::_('COM_ACCOUNT_LABEL_MANAGE_DEPARTMENT'));
		parent::display($tpl);
	}
}