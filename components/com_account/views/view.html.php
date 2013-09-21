<?php
/**
 * @version     1.0.0
 * @package     com_administrator
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
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
		  <li <?php echo ($task == 'display' && $view == 'account') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_account&view=account&task=display');?>"><?php echo JText::_('COM_ACCOUNT_LABEL_ACCOUNT_SETTING');?></a></li>
		  <li <?php echo ($task == 'advance') ? 'class="active"' : '';?>><a href="<?php echo JRoute::_('index.php?option=com_account&view=account&task=advance');?>"><?php echo JText::_('COM_ACCOUNT_LABEL_ACCOUNT_ADVANCE_SETTING');?></a></li>
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
		$pathway		= $mainframe->getPathway();
		
		$pathwayNames	= $pathway->getPathwayNames();
		
		// Test for duplicates before adding the pathway
		if( !in_array( $text , $pathwayNames ) )
		{
			$pathway->addItem( $text , $link );
		}
	}

	
	public function modMembersBirthday() {
		$html 		= '';
		$members 	= ''; 
		$i 			= 0;

		$birthdayHelper = new AccountBirthdayHelper();
		$birthdaymember = $birthdayHelper->getBirthdayMembest();
		
		$numOfMembers = count($birthdaymember);
		if ($numOfMembers > 0) {
			foreach ($birthdaymember as $key => $value) {
				$i++;
				$members .= '<a href="' . JRoute::_('index.php?option=com_messaging&view=inbox&to=' . $key) . '"><b>' . $value . '</b></a>';
				if ($i == $numOfMembers) {	// last member, don't add separator
				} elseif ($i == $numOfMembers - 1) { // last but one member, add 'and' separator	
					$members .= ' ' . JText::_('COM_ACCOUNT_LABEL_MEMBERS_BIRTHDAY_AND') . ' ';		
				} else {
					$members .= ', ';		
				}
			}

			ob_start();
			require_once(JPATH_ROOT .DS.'components'.DS.'com_account'.DS.'templates'.DS.'modules'.DS.'module.members.birthday.php');
			$html = ob_get_contents();
			ob_end_clean();
		}
		return $html;;
	}
	
	public function modWeather() {
		$html 		= '';
		$document 	= JFactory::getDocument();
		$params 	= new JXConfig();
		$my 		= JXFactory::getUser();
		
		// @todo: usage of advanced options in weather module (moduleclass_sfx, cache, useCache, cacheTime)
		if ($my->getParam('timezone')) {
			$helper = new modSPWeatherHelper($params, $my->getParam('timezone'));
			$location   = substr($my->getParam('timezone'), strpos($my->getParam('timezone'), "/")+1, strlen($my->getParam('timezone')));
		} else {
			$helper = new modSPWeatherHelper($params, $params->get('default_timezone'));
			$location   = substr($params->get('weather_location'), strpos($params->get('weather_location'), "/")+1, strlen($params->get('weather_location')));
		}

		if (is_array($helper->error())) {
			JFactory::getApplication()->enqueueMessage( implode('<br /><br />', $helper->error()) , 'error');
		} else {
			$document->addStylesheet(JURI::base(true) . '/templates/'.$document->template.'/css/weather.css');
			$forecast 		= $helper->getForecastData();
			$weather_datax	= $helper->getData();
			$weather_data	= $weather_datax['query']['results']['rss']['channel'];
			ob_start();
			require_once(JPATH_ROOT .DS.'components'.DS.'com_account'.DS.'templates'.DS.'modules'.DS.'module.weather.php');
			$html = ob_get_contents();
			ob_end_clean();	
		}
		return $html;
	}
}