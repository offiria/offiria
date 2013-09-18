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

class AccountView extends JView
{
	/* Will be removed if javascript will be used for tabbing navigation */
	public function showNavBar()
	{
		$task = JRequest::getCmd('task', 'display');
		$view = JRequest::getVar('view');
?>
		<ul class="menubar">
		  <li <?php echo ($task == 'display' && $view == 'account') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_account&view=account');?>"><?php echo JText::_('COM_ACCOUNT_LABEL_ACCOUNT_SETTING');?></a></li>
		  <li <?php echo ($task == 'advance' && $view == 'account') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_account&view=account&task=advance');?>"><?php echo JText::_('COM_ACCOUNT_LABEL_ACCOUNT_ADVANCE_SETTING');?></a></li>
		  <!--li <?php echo ($task == 'integrations') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_account&view=integration');?>"><?php echo JText::_('COM_ACCOUNT_LABEL_INTEGRATIONS');?></a></li-->
		  <li <?php echo ($task == 'categories') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_account&view=account&task=categories');?>"><?php echo JText::_('COM_ACCOUNT_LABEL_CATEGORIES');?></a></li>
		  <li <?php echo ($task == 'manageDepartment') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_account&view=account&task=manageDepartment');?>"><?php echo JText::_('COM_ACCOUNT_LABEL_MANAGE_DEPARTMENT');?></a></li>
		  <li <?php echo ($task == 'manageTheme') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_account&view=account&task=manageTheme');?>"><?php echo JText::_('COM_ACCOUNT_LABEL_MANAGE_THEME');?></a></li>
		  <!--li <?php echo ($task == 'billing') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_account&view=account&task=billing');?>"><?php echo JText::_('COM_ACCOUNT_LABEL_BILLING');?></a></li-->
		  <li <?php echo ($task == 'display' && $view == 'invite') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_account&view=invite');?>"><?php echo JText::_('COM_ACCOUNT_LABEL_INVITE_USERS');?></a></li>	
		  <?php ##CUTLINEBEGIN ?>  <!--	  <?php ##CUTLINEEND ?>
		  <li <?php echo ($task == 'getUpdate' && $view == 'account') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_account&view=account&task=getUpdate');?>"><?php echo JText::_('COM_ACCOUNT_LABEL_UPDATES');?></a></li>
		  <?php ##CUTLINEBEGIN ?>  -->	  <?php ##CUTLINEEND ?>
		</ul>
<?php
	}
	
	public function addPathway( $text , $link = '' )
	{
		// Set pathways
		$mainframe		= JFactory::getApplication();
		$pathway		=& $mainframe->getPathway();
		
		$pathwayNames	= $pathway->getPathwayNames();
		
		// Test for duplicates before adding the pathway
		if( !in_array( $text , $pathwayNames ) )
		{
			$pathway->addItem( $text , $link );
		}
	}

}