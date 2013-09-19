<?php

defined('_JEXEC') or die('Restricted access');

class StreamTag
{
	public static function getTagsHTML($stream) {
		$tmpl = new StreamTemplate();
		$tmpl->set('stream', $stream);

		return $tmpl->fetch('stream.tag');
	}

	public function getTrending($group = null) {
		$db = JFactory::getDbo();
		$my = JXFactory::getUser();
		$groupModel = StreamFactory::getModel('groups');
		$now = new JXDate();
		$nowDate = $now->format('Y-m-d');

		// Get the week's top trending tags
		//$where  = 'WHERE YEARWEEK(' . $db->nameQuote('occurrence_date') . ') = YEARWEEK(' . $db->Quote($nowDate) . ')' . ' '

		// Get the trending tags for the past n weeks
		$where  = 'WHERE ' . $db->nameQuote('occurrence_date') . ' BETWEEN DATE_SUB(' . $db->Quote($nowDate) . ', INTERVAL 2 WEEK) AND ' . $db->Quote($nowDate) . ' '
				. ' AND ' . $db->nameQuote('frequency') . ' > 0' . ' ';

		// Filter tags in group
		if(!is_null($group)) {
			$where .= 'AND ' . $db->nameQuote('group_id') . ' = ' . $db->Quote($group->id) . ' ';
		} else {
			// Get the groups joined (including private ones)
			$groupIJoinCsv = $my->getParam('groups_member', '-1');

			// Get the other public groups
			$otherGroups = $groupModel->getGroups(array('!id' => $groupIJoinCsv, 'access' => 0), 100);

			$otherGroupsCsv = '0';
			if(count($otherGroups)) {
				foreach($otherGroups as $group) {
					$otherGroupsCsv = JXUtility::csvInsert($otherGroupsCsv, $group->id);
				}
			} else {
				$otherGroupsCsv = '-1';
			}

			$myGroupsCsv = JXUtility::csvMerge($otherGroupsCsv, $groupIJoinCsv);

			$where .= 'AND ' . $db->nameQuote('group_id') . ' IN (' . $myGroupsCsv .')' . ' ';
		}

		// Build the query
		$query =  'SELECT * FROM ' . $db->nameQuote('#__stream_tags_trend') . ' '
			. $where
			. 'GROUP BY ' . $db->nameQuote('tag') . ' '
			. 'ORDER BY ' . $db->nameQuote('frequency') . ' DESC, ' . $db->nameQuote('tag') . ' ASC' . ' '
			. 'LIMIT 10';

		$db->setQuery($query);
		$result = $db->loadObjectList();
		return $result;
	}

	public function updateTrending($tag, $group_id, $add=true) {
		$db = JFactory::getDbo();
		$now = new JXDate();
		$nowDate = $now->format('Y-m-d');

		$tagTrending = JTable::getInstance('TagTrend', 'StreamTable');
		$exist = $tagTrending->load(array('tag'=>$tag, 'group_id'=>$group_id, 'occurrence_date'=>$nowDate));

		if($add) {
			if($exist) {
				// Record's there, just update it
				$tagTrending->frequency++;
			} else {
				// Create a new record
				$tagTrending->bind(array('tag'=>$tag, 'frequency'=>1, 'group_id'=>$group_id, 'occurrence_date'=>$nowDate));
			}
			$tagTrending->store();
		} else {
			if($exist) {
				// If it's the last frequency count, delete the record
				if($tagTrending->frequency > 1) {
					$tagTrending->frequency--;
				} else {
					$tagTrending->delete();
				}
				$tagTrending->store();
			}
		}
	}
}