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

/**
 * HTML View class for the Administrator component
 */
class StreamViewGroups extends StreamView
{
	function display($tpl = null) {
		/**
		 * Response values
		 * ======================
		 * 
		 // profile: {
		 // 	 name: string,
		 // 	 id: int
		 // },
		 // groups: {
		 // 	 group: {
		 // 		name: string,
		 // 		description: string,
		 // 	 },
		 // 	 params: json
		 // 	 followers: {
		 // 	 	follower: {
		 // 	 		name: string,
		 // 	 		email: string
		 // 	 	}
		 // 	 },
		 // 	 members: {
		 // 	 	member: {
		 // 	 		name: string,
		 // 	 		email: string
		 // 	 	}
		 // 	 },
		 // 	 archived: boolean
		 // 	 category: string
		 // }		
		 // 			
		 */

		header('Content-Type: application/json');		

		$my = JXFactory::getUser();
		$groupsModel = StreamFactory::getModel('groups');
		
		$filter = array();
		if (JRequest::getVar('filter', 'all') == 'joined'){
			$groupIJoin 	= $my->getParam('groups_member');
			$filter['id'] = $groupIJoin;
		}
		if (JRequest::getVar('filter', 'all') == 'followed'){
			$groupIJoin 	= $my->getParam('groups_follow');
			$filter['id'] = $groupIJoin;
		}
		if (JRequest::getVar('filter', 'all') == 'archived'){
			$filter['archived'] = 1;
		}
		if (JRequest::getVar('filter', 'all') == 'category'){
			$filter['category_id'] = JRequest::getVar('category_id', NULL);
		}
		if (JRequest::getVar('category_id')) {
			$filter['category_id'] = JRequest::getVar('category_id', NULL);
		}
		
		$groups = $groupsModel->getGroups( $filter, NULL, JRequest::getVar('limitstart', 0));
		$total = $groupsModel->getTotal($filter);
		
		$output['profile']['username'] = $my->username;
		$output['profile']['id'] = $my->id;
		$Category = new StreamCategory();

		foreach ($groups as $group) {
			// dont load private group
			if ($group->access == 1) {
				continue;
			}
			$followersCsv = json_encode($group->followers);
			$followers_id = explode(',', $followersCsv);
			foreach ($followers_id as $follower_id) {
				$usr = JXFactory::getUser(intval($follower_id));
				$followers['follower']['username'] = $usr->username;
				$followers['follower']['name'] = $usr->name;
				$followers['follower']['email'] = $usr->email;
			}

			$membersCsv = json_encode($group->members);
			$members_id = explode(',', $membersCsv);
			foreach ($members_id as $member_id) {
				$usr = JXFactory::getUser(intval($member_id));
				$members['member']['username'] = $usr->username;
				$members['member']['name'] = $usr->name;
				$members['member']['email'] = $usr->email;
			}

			$category = $Category->getCategoryName($group->category_id);

			$output['groups']['group'][] = 
				array(
					  'name' => $group->name,
					  'description' => $group->description,
					  'creator' => $group->creator,
					  'followers' => $followers,
					  'members' => $members,
					  'archived' => $group->archived,
					  'category' => $category
					  );
			echo json_encode($output);
		}
		exit;
	}
}