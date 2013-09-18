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
jimport('joomla.application.component.view');
jimport('joomla.user.helper');
jimport('joomla.form.form');
jimport('joomla.utilities.xconfig');
jimport('joomla.xfactory');


/**
 * HTML View class for the Account component
 */
class AccountViewUpdate extends AccountView
{
	function display($renderData)
	{		
		$doc = JFactory::getDocument();
		$doc->setTitle(JText::_("COM_ACCOUNT_LABEL_UPDATES"));
		$this->assignRef('renderData', $renderData);
		parent::display(null);
	}
}