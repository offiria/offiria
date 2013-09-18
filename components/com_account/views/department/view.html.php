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
		parent::display($tpl);
	}
}