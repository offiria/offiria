<?php
defined('_JEXEC') or die();

jimport('joomla.application.controller');

class StreamControllerCustomlist extends JController
{
	public function create()
	{
		$my = JXFactory::getUser();

		$customList = JTable::getInstance('Customlist', 'StreamTable');
		$customList->load(JRequest::getVar('customlist_id'));

		$tmpl = new StreamTemplate();
		$tmpl->set('customList', $customList);

		$data = $tmpl->fetch('customlist.edit');

		header('Content-Type: text/html; charset=UTF-8');
		echo $data;
		exit;
	}

	public function save()
	{
		$my = JXFactory::getUser();

		$customList = JTable::getInstance('Customlist', 'StreamTable');
		$customList->load(JRequest::getVar('customlist_id'));

		$customList->bind(JRequest::get('POST', JREQUEST_ALLOWRAW));

		if ($customList->id == 0) {
			$customList->user_id = $my->id;
		} else {
			// Update
		}

		$customList->store();

		$mainframe = JFactory::getApplication();
		$mainframe->redirect(JRoute::_('index.php?option=com_stream&view=customlist&list_id=' . $customList->id . '#edit', FALSE));
	}

	public function delete()
	{
		$my = JXFactory::getUser();
		$customlist_id = JRequest::getVar('customlist_id');

		$customList = JTable::getInstance('Customlist', 'StreamTable');
		$customList->load($customlist_id);

		$customList->delete();

		$data = array();
		$data['redirect'] = JRoute::_('index.php?option=com_stream&view=company', FALSE);

		echo json_encode($data);
		exit;
	}

	// TODO: rename this method for consistency
	public function saveCustomlist()
	{
		$customlist_id = JRequest::getVar('customlist_id');
		$post = JRequest::get('POST', JREQUEST_ALLOWRAW);

		$customListFilter = array();
		foreach($post as $filterEntity=>$values) {
			if(!empty($values)) {
				$customListFilter[$filterEntity] = explode(',', $values);
			}
		}

		$encodedFilter = json_encode($customListFilter);

		if($encodedFilter) {
			$customList = JTable::getInstance('Customlist', 'StreamTable');
			if($customList->load($customlist_id)) {
				$customList->filter = $encodedFilter;
				$customList->store();
			}
		}

		$mainframe = JFactory::getApplication();
		$mainframe->redirect(JRoute::_('index.php?option=com_stream&view=customlist&list_id=' .$customlist_id, FALSE));
	}
}