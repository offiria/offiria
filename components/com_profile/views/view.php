<?php

jimport('joomla.application.component.view');
require_once(JPATH_ROOT .DS.'components'.DS.'com_profile'.DS.'factory.php');
require_once(JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'factory.php');
jimport('joomla.html.parameter');
jimport('joomla.user.helper');

class ProfileView extends JView
{
	/* Will be removed if javascript will be used for tabbing navigation */
	public function showNavBar()
	{
		$task = JRequest::getCmd('task', 'display');
?>
		<ul class="menubar">
		  <li <?php echo ($task == 'display') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_profile&view=edit');?>"><?php echo JText::_('COM_PROFILE_LABEL_EDIT_PROFILE');?></a></li>
		  <li <?php echo ($task == 'details') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=details');?>"><?php echo JText::_('COM_PROFILE_LABEL_EDIT_DETAILS');?></a></li>
		  <li <?php echo ($task == 'changeAvatar') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=changeAvatar');?>"><?php echo JText::_('COM_PROFILE_LABEL_PROFILE_AVATAR');?></a></li>
		  <li <?php echo ($task == 'notification') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=notification');?>"><?php echo JText::_('COM_PROFILE_LABEL_NOTIFICATION');?></a></li>
		  <!--li <?php //echo ($task == 'preference') ? 'class="active"' : '';?>><a href="<?php //echo JRoute::_('index.php?option=com_profile&view=edit&task=preference');?>"><?php //echo JText::_('COM_PROFILE_LABEL_PREFERENCE');?></a></li-->
  		  <!-- <li <?php echo ($task == 'applications') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=applications');?>"><?php echo JText::_('COM_PROFILE_LABEL_APPLICATIONS');?></a></li> -->
		</ul>
<?php
	}
	
	public static function showSideBar()
	{	
		return false;
		// $name	= JRequest::getString( 'user' );
		// $userId	= JUserHelper::getUserId( $name );
		
		// $view = StreamFactory::getView('todo', '', 'html');
		// $user = JXFactory::getUser($userId);
		// return $view->modGetPendingTask(array('user_id' => $user->id));
	}

}
?>
