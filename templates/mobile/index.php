<?php
/**
 * @version					2.0.0
 * @package					mobile
 * @subpackage			Offiria Mobile View
 * @copyright				Copyright (C) Slashes n Dots Sdn Bhd. All rights reserved.
 * @license					Commercial
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.xfactory');
jimport('joomla.utilities.xconfig');
include_once(JPATH_ROOT.DS.'components'.DS.'com_stream'.DS.'factory.php');

$document = JFactory::getDocument();
$document->setGenerator('Offiria');

/* Global javascripts */
$document->addScript(JURI::root().'media/jquery/jquery-1.7.min.js');
$document->addScript(JURI::root().'media/jquery/js/jquery-ui-1.8.16.custom.min.js');
//$document->addScript(JURI::root().'media/stream/script.js');
$document->addScript(JURI::root().'/templates/mobile/js/library.js');
$document->addScript(JURI::root().'/templates/mobile/js/mobile.js');
$document->addScript(JRoute::_('index.php/component/scripts'));

// get params
$app 	= JFactory::getApplication();
$config = new JConfig();

$tParams = $app->getTemplate(true)->params;

// If user is not yet logged in, show login page
$my = JXFactory::getUser();
if( !$my ->id )
{
	include('guest.php');
	return;
}

$option = JRequest::getVar('option');
$view = JRequest::getVar('view');

//Should Be in Module
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

$groupIFollow 	= JXUtility::csvDiff($groupIFollow, $groupIJoin);
$myGroupsIds 	= JXUtility::csvMerge($groupIFollow, $groupIJoin);

class StreamGroupSorter {
	public static function sortLastActive($groupA, $groupB){
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

// If user hasn't yet follow of join any group, put a dummy groupid here
// otherwise, it will return all groups
if(empty($myGroupsIds)){
	$myGroupsIds = -1;
}
$myGroups			= $groupModel->getGroups(array('id' => $myGroupsIds), 100);
$myJoinedGroups		= $groupModel->getGroups(array('id' => $groupIJoin), 100);
$myFollowedGroups	= $groupModel->getGroups(array('id' => $groupIFollow), 100);

$otherGroups 		= array();

// If the user sign up to only a few groups, show other active groups
if(count($myGroups) < 10 ){
	$otherGroups = $groupModel->getGroups(array('!id' => $myGroupsIds), 10);
}

// Re-order based on last activity
usort($myGroups, array('StreamGroupSorter', 'sortLastActive'));
usort($myJoinedGroups, array('StreamGroupSorter', 'sortLastActive'));
usort($myFollowedGroups, array('StreamGroupSorter', 'sortLastActive'));

function offiria_list_groups($groups, $title, $groupIJoin, $groupIFollow)
{ 
	$group_id = JRequest::getVar('group_id');
	$my = JXFactory::getUser();
	$streamModel 	= StreamFactory::getModel('stream');
	?>
	<div class="groups-listing followed">
		<h3><?php echo $title; ?></h3>
		<?php
		// Only show list of groups if there is any
		if(!empty($groups)){ 
		?>
		<ul>
			<?php foreach($groups as $group) { ?>
			<li class="groups <?php if($group_id == $group->id){ echo 'active'; }?>">
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
	</div>
<?php
}

// Sort by activity

//@todo: reorder the groups based on last active per-user
//$myGroups = array_slice($myGroups, 0, 8);


?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />
		<meta name="apple-mobile-web-app-capable" content="yes" />
		<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/mobile/css/mobile.css" />
		<link rel="apple-touch-icon" href="<?php echo $this->baseurl ?>/templates/offiria/images/iphone-app-icon-114.png"/>
		<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $this->baseurl ?>/templates/offiria/images/ipad-app-icon-144.png"/>
		<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $this->baseurl ?>/templates/offiria/images/iphone-app-icon-114.png"/>
		<jdoc:include type="head" />
	</head>
	<body>
		
		<div id="offPopupWrap"><div id="offPopup"><div class="offPopupContent">a</div><a id="offPopupClose">x</a></div></div>
		
		<div id="mobileToolbars">
			<!-- Top Toolbar -->
			<div id="topToolbar">
				
				<?php
					// change main icon to reflect current section
					$pageIcon = '';
					
					if($option == 'com_stream' && ($view == 'blog' || $view == 'message')) {
						$pageIcon = 'iconBlog';
					} else if($option == 'com_stream' && $view == 'events' ) {
						$pageIcon = 'iconCalendar';
					} else if($option == 'com_stream' && $view == 'todo' ) {
						$pageIcon = 'iconTodo';
					} else if($option == 'com_stream' && $view == 'files' ) {
						$pageIcon = 'iconFiles';
					} else if($option == 'com_people' && $view == 'members' ) {
						$pageIcon = 'iconPeople';
					} else {
						// if others, set the icon to home
						$pageIcon = 'iconHome';
					}
				?>
				
				<div class="topIcons <?php echo $pageIcon; ?> iconLeft">
					<a href="#home" id="topMainButton">Home</a>
					<?php if ($pageIcon == 'iconHome' && $count > 0) { ?>
						<span class="notification"><?php echo $count; ?></span>
					<?php } ?>
				</div>
				
				<div class="topIcons iconGroups iconLeft">
					<a href="#groups" id="topGroupButton">Groups</a>
					<!--span class="notification">3</span-->
				</div>
				
				<div class="topIcons iconPost iconRight">
					<a href="#post" id="topPostButton">Post</a>
				</div>
				
				<div class="topIcons iconSearch iconRight">
					<a href="#search" id="topSearchButton">Search</a>
				</div>
				
				<?php

				// If page title is set, add it here. BUT, don't show it
				// if it is the same as site title (which mean, the title is not set)
				$docTitle = $document->getTitle();
				if(!empty($docTitle) && $docTitle != $config->sitename ) {
					echo '<div id="toolbarTitle"><h1>'. StreamTemplate::escape($docTitle).'</h1></div>';
				} else {
					echo '<div id="toolbarTitle"><h1>'. StreamTemplate::escape($config->sitename).'</h1></div>';
				}
				?>
				
			</div>
			<!-- /Top Toolbar -->
			
			<?php if($option == 'com_stream' && ($view == 'company' || $view == 'groups') ) { ?>
			<div class="secondary-bar">
				<div class="pull-left">
					<a href="#groups" class="btn" id="topGroupButton"><span></span>My Groups</a>
				</div>
				
				<div class="pull-right">
					<a href="#post" class="btn" id="topPostButton"><span></span>Post Status</a>
				</div>
				
				<div class="clear"></div>
			</div>
			<?php } ?>
			
			<!-- Secondary Toolbar -->
			<div id="secondToolbar" class="altToolbar">
				
				<ul>
					<!-- company stream -->
					<li class="iconHome iconSecondary<?php if($option == 'com_stream' && $view == 'company' ) { 
						echo ' active'; 

						$lastMessageId = StreamMessage::lastMessageId();
						$my->setParam('message_last_read', $lastMessageId);
						$my->save();

						} ?>">
						<a href="<?php echo JRoute::_('index.php?option=com_stream&view=company'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_HOME');?></a>
						<?php if ($count > 0) { ?>
						<span class="navigator-notice" id="company_updates"><?php echo $count; ?></span>
						<?php } ?>
					</li>

					<!-- articles -->
					<li class="iconBlog iconSecondary<?php if($option == 'com_stream' && $view == 'blog' ) { echo ' active'; } ?>">
						<a href="<?php echo JRoute::_('index.php?option=com_stream&view=blog'); ?>"><?php echo JText::_('Blog');?></a>
					</li>

					<!-- events -->
					<li class="iconCalendar iconSecondary<?php if($option == 'com_stream' && $view == 'events' ) { echo ' active'; } ?>">
						<a href="<?php echo JRoute::_('index.php?option=com_stream&view=events'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_EVENTS');?></a>
					</li>

					<!-- todo -->
					<li class="iconTodo iconSecondary<?php if($option == 'com_stream' && $view == 'todo' ) { echo ' active'; } ?>">
						<a data-content="hello" href="<?php echo JRoute::_('index.php?option=com_stream&view=todo'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_TODO');?></a>
					</li>

					<!-- files -->
					<li class="iconFiles iconSecondary<?php if($option == 'com_stream' && $view == 'files' ) { echo ' active'; } ?>">
						<a href="<?php echo JRoute::_('index.php?option=com_stream&view=files'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_FILE');?></a>
					</li>

					<!-- members -->
					<li class="iconPeople iconSecondary<?php if($option == 'com_people' && $view == 'members' ) { echo ' active'; } ?>">
						<a href="<?php echo JRoute::_('index.php?option=com_people&view=members'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_PEOPLE');?></a>
					</li>
				</ul>
				
					

			</div>
			<!-- /Secondary Toolbar -->
			
			<!-- Search Bar -->
			<div id="searchBar" class="altToolbar">
				<form id="searchform" action="<?php echo JRoute::_('index.php?option=com_search');?>">
					<input type="text" name="searchword" id="searchword" placeholder="enter keywords" />
					<input type="submit" />
					<div class="clear"></div>
				</form>
			</div>
			<!-- /Search bar -->
		</div>

		<!-- PopoverContainer - reusable-->
		<div id="popoverContainer" class="popOver">
			<div class="popovertip"></div>
			<div class="popoverTitle">Groups</div>
			<div class="popoverContents">
				<?php
				// Only show list of groups if there is any
				if(!empty($myJoinedGroups)){ 
				?>
				<ul>
					<?php foreach($myJoinedGroups as $group) { ?>
					<li class="groups <?php if($group_id == $group->id){ echo 'active'; }?>">
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

						<a class="<?php echo $statusClass; ?>" id="groups_<?php echo $group->id; ?>_link" href="<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='.$group->id); ?>">
							<span><?php echo StreamTemplate::escape($group->name); ?></span>
							<span class="comment-notice"></span>
						</a>
						
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
			</div>
		</div>
		<!-- /Popover Container -->
		
		<!-- composeScreen - mainly for posting stuff, reusable for anything -->
		<div id="composeScreen" class="composeScreen">
		<div id="stream-post">

			<div class="stream-post-tabs">
				<ul>
					<li class="li-text" id=""><a href="javascript:void(0)"><?php echo JText::_('COM_STREAM_LABEL_UPDATE');?><span></span>
					</a></li>
					<li class="tab active" id=""><a href="#stream-post-update"><?php echo JText::_('COM_STREAM_LABEL_STATUS');?><span></span>
					</a></li>
					<li class="tab" id=""><a href="#stream-post-event"><?php echo JText::_('COM_STREAM_LABEL_EVENT');?><span></span>
					</a></li>
					<li class="tab" id=""><a href="#stream-post-milestone"><?php echo JText::_('Milestone');?><span></span>
					</a></li>
					<li class="tab" id=""><a href="#stream-post-todo"><?php echo JText::_('COM_STREAM_LABEL_TODO');?><span></span>
					</a></li>
					<?php if( empty($group_id)) { ?>
					<li class="tab" id=""><a href="#stream-post-page"><?php echo JText::_('COM_STREAM_LABEL_BLOG');?><span></span>
					</a></li>
					<?php } ?>
					<!-- <li class="tab" id=""><a href="#stream-post-milestone"><?php echo JText::_('Milestone');?><span></span></a></li> -->
				</ul>
			</div>

			<div class="stream-post-message">

				<form id="stream-form">
					<!-- <input type="input" name="link[]" value=""> -->

					<!-- Updates -->
					<div class="stream-post-message-share tab-content"
						id="stream-post-update">
						<textarea id="message-box" name="message" class="stream-post"
							style="resize: vertical;" cols="63"
							placeholder="<?php echo JText::_('COM_STREAM_DEFAULT_LABEL_TYPE_YOUR_MESSAGE_HERE');?>"></textarea>
						<input type="hidden" name="type" value="update">
					</div>

					<!-- @mention -->
					<div class="stream-post-suggest" style="display: none"></div>

					<div class="stream-post-message-attach">
						<ul id="post-attachment-list"></ul>
						<!-- Link -->
						<div class="stream-post-link"></div>

						<!-- Todo -->
						<div class="stream-post-todo"></div>
					</div>
					<div class="stream-post-message-tabs">
						<ul>
							<li class="li-text"><?php echo JText::_('COM_STREAM_LABEL_ATTACH');?>
							</li>
							<li class="stream-post-link-add"><a href="#"
								onclick="return S.uploader.selectFile('post-file-uploader');"><?php echo JText::_('COM_STREAM_LABEL_UPLOAD');?>
							</a></li>
							<?php /* <li class="stream-post-topic-add active"><a href="#"><?php echo JText::_('COM_STREAM_LABEL_TOPIC');?></a></li> */?>
						</ul>

						<input type="hidden" name="group_id" value="<?php echo isset($group_id)? $group_id: ''; ?>" /> 
						<input type="submit" class="submit btn btn-info" value="<?php echo JText::_('COM_STREAM_LABEL_SHARE');?>" />
						<div id="stream-post-loading" class="stream-loading"></div>

						<div class="clear"></div>
					</div>

				</form>

			</div>

		</div>
		<!-- stream-post -->
	</div>
		<!-- /composeScreen -->
		<div class="contentContainer" id="contents">
		<?php
		// get system message to be outputted in custom container
		$app = JFactory::getApplication();
		$queue = $app->getMessageQueue();

		// store all messages
		$messages = array();
		// store all errors
		$errors = array();

		// get messages and error by type
		foreach ($queue as $message) {
			if ($message['type'] == 'message') {
				$messages[] = $message;
			}
			else if ($message['type'] == 'error') {
				$errors[] = $message;
			}
		}
		// start printing message
		?>
		<div id="system-message-container">

		<?php if (count($messages) > 0): ?>
			<div class="alert alert-success">
				<ul>
				<?php foreach($messages as $message) {
					echo '<li>' . $message['message'] . '</li>';
				}
				?>
				</ul>
			</div>
			<?php endif; ?>

			<?php if (count($errors) > 0): ?>
			<div class="alert alert-error">
				<ul>
				<?php foreach($errors as $error) {
					echo '<li>' . $error['message'] . '</li>';
				}
				?>
				</ul>
			</div>
			<?php endif; ?>

		</div>
		<!-- end system-message-container -->

		<jdoc:include type="modules" name="component_top" style="default"/>
			<jdoc:include type="component" />
			<jdoc:include type="modules" name="component_bottom" style="default"/>
		</div>
		
		<form id="logout-form" method="post" action="<?php echo JURI::root(); ?>">
		<div class="logout-button" style="display:none;">
			<input type="submit" value="Log out" class="button" name="Submit">
			<input type="hidden" value="com_users" name="option">
			<input type="hidden" value="user.logout" name="task">
			<?php echo JHtml::_('form.token'); ?>	
		</div>
		</form>
		
		<div class="offFooterLinks">
			Logged in as <a href="<?php echo JRoute::_('index.php?option=com_profile&view=display'); ?>"><?php echo $my->name; ?></a>. <a href="javascript:void(0);" onclick="document.getElementById('logout-form').submit();"><?php echo JText::_('JXLIB_LOGOUT');?></a>
		</div>
		
	</body>
</html>