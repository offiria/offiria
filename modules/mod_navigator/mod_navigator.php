<?php
/**
 * @version		$Id: mod_stats.php 20806 2011-02-21 19:44:59Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	mod_stats
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.xfactory');

include_once( JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'factory.php');

$option = JRequest::getVar('option');
$view = JRequest::getVar('view');
$task = JRequest::getVar('task');

$group_id = JRequest::getVar('group_id');

$my = JXFactory::getUser();
$lastMessageRead = $my->getParam('message_last_read');
$count = StreamMessage::countMessageSince($lastMessageRead);
$streamModel 	= StreamFactory::getModel('stream');

// Groups
// Get the list
$groupModel 	= StreamFactory::getModel('groups');
$groupIJoin 	= $my->getParam('groups_member');
$groupIFollow 	= $my->getParam('groups_follow');
$myGroupsIds 	= JXUtility::csvMerge($groupIFollow, $groupIJoin);


if(!class_exists('StreamGroupSorter')){
	class StreamGroupSorter {
		public function sortLastActive($groupA, $groupB){
			$my = JXFactory::getUser();
			$diffA = $groupA->getParam('last_message') - $my->getParam('group_'.$groupA->id.'_last');
			$diffB = $groupB->getParam('last_message') - $my->getParam('group_'.$groupB->id.'_last');
			
			if( $diffA < $diffB )
				return 1;
			if( $diffA > $diffB )
				return -1;
				
			return 0; 
		}
	}
}


// If user hasn't yet follow of join any group, put a dummy groupid here
// otherwise, it will return all groups
if(empty($myGroupsIds)){
	$myGroupsIds = -1;
}
$myGroups		= $groupModel->getGroups(array('id' => $myGroupsIds), 100);

// Re-order based on last activity
usort($myGroups, array('StreamGroupSorter', 'sortLastActive'));



// Sort by activity

//@todo: reorder the groups based on last active per-user
//$myGroups = array_slice($myGroups, 0, 8);

?>

<div class="navigation">
	<ul>
		<!-- company stream -->
		<li class="home<?php if($option == 'com_stream' && $view == 'company' ) { 
			echo ' active'; 
			
			$lastMessageId = StreamMessage::lastMessageId();
			$my->setParam('message_last_read', $lastMessageId);
			$my->save();
		
			} ?>">
			<a href="<?php echo JRoute::_('index.php?option=com_stream&view=company'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_HOME');?></a>
			<span class="navigator-notice" id="company_updates"><?php echo ($count > 0)? $count: '' ; ?></span>
		</li>

		<!-- groups -->
		<li class="groups<?php if($option == 'com_stream' && $view == 'groups' ) { echo ' active'; } ?>">
			<a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_GROUPS');?></a>
			<?php
			// Only show list of groups if there is any
			if(!empty($myGroups)){ 
			?>
			<ul>
				<?php foreach($myGroups as $group) { ?>
				<li class="<?php if($group_id == $group->id){ echo 'active'; }?>">
					<?php
					$lastReadId 	= $my->getParam('group_'.$group->id.'_read');
					$statusClass	= (JXUtility::csvExist($groupIJoin, $group->id)) ? 'joined' : 'followed';
					$groupLastMsg 	= $group->getParam('last_message');
					$groupNewMsg	= 0;
					
					// Only calculate if there is a diff here
					if($lastReadId != $groupLastMsg){
						$groupNewMsg 	= $streamModel->countStream(array('!user_id' => $my->id, 'id_more' => $lastReadId, 'group_id' => $group->id));
					}
					$styleHide = '';
					?>
					
					<a class="<?php echo $statusClass; ?>" id="groups_<?php echo $group->id; ?>_link" href="<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='.$group->id); ?>"><?php echo StreamTemplate::escape($group->name); ?></a>
					
					<?php
					if( intval($groupNewMsg) == 0)
					{
						$styleHide = 'display:none';
					}
					
					{
						echo '<span style="'.$styleHide.'" class="navigator-notice" id="groups_'.$group->id.'">'.intval($groupNewMsg).'</span>';
					}
					?>
					<!-- <span class="navigator-notice"></span> -->
				</li>
				<?php } ?>
			</ul>
			<?php } ?>
		</li>
		
		<!-- articles -->
		<li class="articles<?php if($option == 'com_stream' && $view == 'articles' ) { echo ' active'; } ?>">
			<a href="<?php echo JRoute::_('index.php?option=com_stream&view=articles'); ?>"><?php echo JText::_('Articles');?></a>
		</li>
		
		<!-- events -->
		<li class="events<?php if($option == 'com_stream' && $view == 'events' ) { echo ' active'; } ?>">
			<a href="<?php echo JRoute::_('index.php?option=com_stream&view=events'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_EVENTS');?> <span class="stat">3 today</span></a>
		</li>
		
		<!-- todo -->
		<li class="todo<?php if($option == 'com_stream' && $view == 'todo' ) { echo ' active'; } ?>">
			<a data-content="hello" href="<?php echo JRoute::_('index.php?option=com_stream&view=todo'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_TODO');?> <span class="stat">14 pending</span></a>
		</li>
	
		<!-- files -->
		<li class="files<?php if($option == 'com_stream' && $view == 'files' ) { echo ' active'; } ?>">
			<a href="<?php echo JRoute::_('index.php?option=com_stream&view=files'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_FILE');?></a>
		</li>
	
		<!-- members -->
		<li class="members<?php if($option == 'com_people' && $view == 'members' ) { echo ' active'; } ?>">
			<a href="<?php echo JRoute::_('index.php?option=com_people&view=members'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_PEOPLE');?></a>
		</li>
	
		
	</ul>
</div>

<!-- who's online module-->
<?php
$db = JFactory::getDBO();
$query = 'SELECT DISTINCT(userid) FROM `#__session` WHERE userid != 0 AND client_id != 1 AND `username` != "admin"';
$db->setQuery($query);
$result = $db->loadObjectList();
if($db->getErrorNum())
{
	JError::raiseError( 500, $db->stderr());
}
?>
<div class="moduletable">
	<div class="whoonline">
		<h3><?php echo JText::_('NAVIGATOR_WHO_ONLINE');?></h3>
		<ul class="user-info-compact-sidebar">
			<?php
			foreach($result as $row){
			$user = JXFactory::getUser($row->userid); ?>
			<li>
				<div class="user-avatar">
					<a href="<?php echo $user->getURL(); ?>"><img class="tips" title="<?php echo StreamTemplate::escape($user->name); ?>" src="<?php echo $user->getThumbAvatarURL(); ?>"></a>
				</div>
			</li>
			
			<?php } ?>
		</ul>
	</div>
</div>
