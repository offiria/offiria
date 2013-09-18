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
jimport('joomla.html.pagination');
JModel::addIncludePath(JPATH_ROOT . DS . 'components' . DS . 'com_account' . DS . 'models');

/**
 * HTML View class for the Account component
 */
class AccountViewBilling extends AccountView
{
	function display($tpl = null)
	{
		$jxConfig	= new JXConfig();
		$doc		= JFactory::getDocument();
		$doc->setTitle(JText::_("COM_ACCOUNT_LABEL_BILLING"));
		
		$total = 0;
		/*$payments = JModel::getInstance('payments', 'AccountModel');
		$total	 = $payments->getTotal();
		$payments->getList(null, $jxConfig->get('list_limit'), JRequest::getVar('limitstart', 0));		
		*/
		$pagination = new JPagination($total,  JRequest::getVar('limitstart', 0) , $jxConfig->get('list_limit'));
		
		$this->assignRef('availablePlans', $jxConfig->getAvailablePlans());
		$this->assignRef('plan', $jxConfig->getCurrentPlan());
		$this->assignRef('jxConfig', $jxConfig);
		
		$this->assignRef('payments', $payments);
		$this->assignRef('pagination', $pagination);
		
		parent::display($tpl);
	}
}