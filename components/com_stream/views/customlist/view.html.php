<?php
// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

class StreamViewCustomlist extends StreamView
{
	private $_filter;

	function display($tpl = null)
	{
		jimport('joomla.html.pagination');
		$my = JXFactory::getUser();

		$this->_attachScripts();
		$html = '';
		$jconfig = new JConfig();
		$doc = JFactory::getDocument();

		$customlist_id = JRequest::getVar('list_id', '', 'GET');

		$customList = JTable::getInstance('Customlist', 'StreamTable');
		$customList->load($customlist_id);

		$doc->setTitle($customList->title);
		$this->addPathway(JText::_('COM_TEMPLATE_CUSTOMLIST'));

		$buildConditionFilter = array();
		$filterArr = array();
		$preloadedSource = array();

		$group = JTable::getInstance('Group', 'StreamTable');

		if ($customList->filter) {
			$filterArr = json_decode($customList->filter, true);

			// Build the filter conditions
			foreach ($filterArr as $filterEntity => $arrvalue) {
				$buildConditionFilter[$filterEntity] = implode(',', $arrvalue);

				//TODO: Temporary preloaded source - this should be returned from a model
				foreach ($arrvalue as $value) {
					if ($filterEntity == 'group_ids') {
						$group->load($value);
						$preloadedSource[$filterEntity][] = array('id' => $group->id, 'value' => $group->name, 'selected' => '1');
					} elseif ($filterEntity == 'user_ids') {
						$user = JXFactory::getUser($value);
						$preloadedSource[$filterEntity][] = array('id' => $value, 'value' => $user->name, 'thumb' => $user->getThumbAvatarURL(), 'selected' => '1');
					} elseif ($filterEntity == 'tags') {
						$preloadedSource[$filterEntity][] = array('value' => $value, 'selected' => '1');
					}
				}
			}
		}

		$tmpl = new StreamTemplate();
		$tmpl->set('my', $my);
		$tmpl->set('customList', $customList); // current list
		$tmpl->set('filterArr', $filterArr); // array of list filters
		$tmpl->set('preloadedSource', $preloadedSource); // array of preloaded list selections
		$html .= $tmpl->fetch('customlist.header');

		$html .= '<br />';

		$options = array();
		$options['hide_filter'] = 1; // Hide filter tabs

		// Side bar
		if (isset($filterArr['group_ids'])) {
			JXModule::addBuffer('right', $this->getFilteredGroupsHTML($filterArr['group_ids']));
		}
		if (isset($filterArr['user_ids'])) {
			JXModule::addBuffer('right', $this->getFilteredUsersHTML($filterArr['user_ids']));
		}
		if (isset($filterArr['tags'])) {
			JXModule::addBuffer('right', $this->getFilteredTagsHTML($filterArr['tags']));
		}

		if (!empty($buildConditionFilter)) {
			$companyView = StreamFactory::getView('company');
			$html .= $companyView->getStreamDataHTML($buildConditionFilter, $options);
		} else {
			$html .= '<div class="alert-message block-message info">'
				// <a data-dismiss="alert" class="close">×</a>
				. JText::_('COM_STREAM_LABEL_NOFILTERSAPPLIED_LONG') . '</div>';
		}

		echo $html;
	}

	public function getFilteredGroupsHTML($groupids)
	{
		$filter = array();
		$filter['id'] = implode(',', $groupids);

		$model = StreamFactory::getModel('groups');
		$data = $model->getGroups($filter);

		$tmpl = new StreamTemplate();
		$tmpl->set('title', JText::_('Groups'));
		$tmpl->set('groups', $data);

		$html = $tmpl->fetch('group.module.groups');

		return $html;
	}

	public function getFilteredUsersHTML($user_ids)
	{
		$users = array();
		foreach ($user_ids as $id) {
			if (intval($id) > 0) {
				$users[] = JXFactory::getUser($id);
			}
		}
		$tmpl = new StreamTemplate();
		$tmpl->set('title', JText::_('People'));
		$tmpl->set('members', $users);

		$html = $tmpl->fetch('group.module.memberlist');

		return $html;
	}

	public function getFilteredTagsHTML($tags)
	{
		$tagObjs = array();

		foreach ($tags as $tag) {
			$obj = new stdClass();
			$obj->tag = $tag;
			$tagObjs[] = $obj;
		}

		$tmpl = new StreamTemplate();
		$tmpl->set('title', JText::_('Tags'));
		$tmpl->set('trendingTags', $tagObjs);

		$html = $tmpl->fetch('stream.tag.trending');

		return $html;
	}
}