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

class AccountControllerAccount extends JController
{
     public function display() {
	  parent::display(null);
     }

     /**
      * Open authorize window
      *	 Authorise the app to access user's resource
      */	 	
     public function authenticate() {
	  if ($_POST) {
	       $approved = JRequest::getVar('app_true');
	       $notApproved = JRequest::getVar('app_false');
	       
	       $model = AccountFactory::getModel('token');
	       $mainframe = JFactory::getApplication();
	       if ($approved) {
		    $user = JFactory::getUser();	  			
		    if ($model->generateToken($user->id)) {
			 $mainframe->redirect(JRoute::_('index.php?option=com_account&view=account'), JText::_('com_account_ACTION_APPROVED_PERFORMED'));
		    }
		    else {
			 $mainframe->redirect(JRoute::_('index.php?option=com_account&view=account'), JText::_('com_account_ACTION_ERROR'), 'error');
		    }
	       }
	       else if ($notApproved) {
		    /* Action for cancel */
		    echo 'Canceled';
	       }
	  }
	  parent::display();
     }
     
     public function addClient(){
     }
     
     public function removeClient(){
     }	
}