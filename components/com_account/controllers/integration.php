<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// No direct access.
defined('_JEXEC') or die;

require_once(JPATH_ROOT.DS.'components'.DS.'com_account'.DS.'factory.php');
jimport('joomla.utilities.xconfig');


class AccountControllerIntegration extends JController
{
	
	public function display() 
	{		
		// Only admin can use this function to invite guests
		$jxConfig		= new JXConfig();
		$my				= JXFactory::getUser();
		$mainframe		= JFactory::getApplication();
		$accessHelper	= new AccountAccessHelper();
		
		if (!$my->authorise('stream.setting.edit', $accessHelper))
		{
			$mainframe->redirect(JURI::base(), JText::_('COM_ACCOUNT_ERRMSG_INVITATION_DENIED'), 'error');
		}
		
		if ($_POST)
		{
			jimport('joomla.utilities.xintegration');
			
			// Check if there is ad_integration posted and if the value is activedirectory 
			// by comparing with xintegration library for supported integrations
			$adIntegration = JRequest::getString('ad_integration', '');
			if ( !empty($adIntegration) && JXIntegration::isActiveDirectory($adIntegration) )
			{
				$integrationTbl = JTable::getInstance('integration', 'AccountTable');
				$integrationTbl->load(array('name' => $adIntegration));
				$integrationTbl->setParam('dc', JRequest::getString('ad_dc', ''));
				$integrationTbl->setParam('dm', JRequest::getString('ad_dm', ''));
				if (JRequest::getString('ad_pw', '') != '')
				{
					$integrationTbl->setParam('pw', JRequest::getString('ad_pw', ''));
				}
				$integrationTbl->setParam('un', JRequest::getString('ad_un', ''));
				$integrationTbl->setParam('hi', JRequest::getString('ad_hi', ''));
				
				if (!$integrationTbl->store())
				{
					$mainframe->redirect(JRoute::_('index.php?option=com_account&view=integration'), $integrationTbl->getError(), 'error');
				}			
				
				$mainframe->redirect(JRoute::_('index.php?option=com_account&view=integration'), JText::_('COM_ACCOUNT_ACTION_SAVE_INTEGRATION_SUCCESS!'));
			}
		}
		
		parent::display();
	}
}