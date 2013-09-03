<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.user.helper');
jimport('joomla.form.form');

class ProfileViewDetail extends ProfileView
{

	function display($tpl = null)
	{
		$user = JXFactory::getUser();
		$this->assignRef('user', $user);

		JForm::addFieldPath(JPATH_COMPONENT . DS . 'models' . DS . 'fields');

		$form = JForm::getInstance('form', JPATH_ROOT.DS.'components'.DS.'com_profile'.DS.'models'.DS.'forms'.DS.'details.xml');

		$detailModel = ProfileFactory::getModel('detail');
		$form->bind(array('params'=>$detailModel->getDetails($user->id)));

		$this->assignRef('form', $form);

		$document = JFactory::getDocument();
		$document->addScript(JURI::root().'media/jquery/jquery-1.7.min.js');
		$document->setTitle(JText::_('COM_PROFILE_LABEL_EDIT_DETAILS'));

		parent::display($tpl);
	}
}