<?php
/**
 * @version                $Id: index.php 21097 2011-04-07 15:38:03Z jomsocial $
 * @package                Joomla.Site
 * @subpackage			   e20 - Enterprise 2.0
 * @copyright        	   Copyright (C) Slashes n Dots Sdn Bhd. All rights reserved.
 * @license                GPL
 */
// No direct access.
defined('_JEXEC') or die;

jimport('joomla.xfactory');
jimport('joomla.utilities.xconfig');
include_once(JPATH_ROOT.DS.'components'.DS.'com_stream'.DS.'factory.php');
include_once(JPATH_ROOT.DS.'components'.DS.'com_account'.DS.'factory.php');

$document = JFactory::getDocument();
$document->setGenerator(JText::_('CUSTOM_SITE_NAME'));
$my = JXFactory::getUser();

$option = JRequest::getVar('option');
$view = JRequest::getVar('view');

// Global javascripts
$document->addScript(JURI::root().'media/jquery/jquery-1.7.min.js');
$document->addScript(JURI::root().'media/jquery/js/jquery-ui-1.8.16.custom.min.js');

// Don't load notification and libraries when not logged in
if($my->id && ($option != 'com_users' && $view != 'reset' && $view !='remind')) {
	$document->addScript(JURI::root().'media/jquery/bootstrap-button.min.js');
	$document->addScript(JURI::root().'media/jquery/autogrow.min.js');
	$document->addScript(JURI::root().'media/jquery/bootstrap-twipsy.min.js');
	$document->addScript(JURI::root().'media/jquery/bootstrap-popover.min.js');
	$document->addScript(JURI::root().'media/jquery/bootstrap-dropdown.min.js');
	$document->addScript(JURI::root().'media/jquery/bootstrap-transition.min.js');
	$document->addScript(JURI::root().'media/jquery/bootstrap-collapse.min.js');
	$document->addScript(JURI::root().'media/jquery/bootstrap-modal.min.js');
	$document->addScript(JURI::root().'media/jquery/bootstrap-tab.min.js');
	$document->addScript(JURI::root().'media/jquery/bootstrap-typeahead-hacked.min.js');
	$document->addScript(JURI::root().'media/jquery/jquery.tools.min.js');
	$document->addScript(JURI::root().'media/jquery/jquery.pageslide.min.js');
	$document->addScript(JURI::root().'media/jquery/scrollbar.min.js');
	$document->addScript(JURI::root().'media/tipsy2/src/javascripts/jquery.tipsy.min.js');
	$document->addScript(JURI::root().'media/editors/tinymce/jscripts/tiny_mce/tiny_mce.js');
	$document->addScript(JURI::root().'media/stream/script.pack.js?t='.filemtime(JPATH_ROOT.DS.'media'.DS.'stream'.DS.'script.pack.js'));
 	$document->addScript(JURI::root().'media/highchart/js/highcharts.js');
	$document->addScript(JRoute::_('index.php?option=com_scripts', false));

	$document->addStyleSheet(JURI::root().'media/jquery/css/flick/jquery-ui-1.8.16.custom.css');
}

// Get params
$app 	= JFactory::getApplication();
$config = new JConfig();

$tParams = $app->getTemplate(true)->params;

//JHtml::_('behavior.framework', true);

// If user is not yet logged in, show login page
if(!$my->id)
{
	include('guest.php');
	return;
}

// Should Be in Module
#include_once( JPATH_ROOT .DS.'components'.DS.'com_stream'.DS.'factory.php');

$option 	= JRequest::getVar('option');
$view 		= JRequest::getVar('view');
$task 		= JRequest::getVar('task');
$group_id 	= JRequest::getVar('group_id');

$my = JXFactory::getUser();
$lastMessageRead = $my->getParam('message_last_read');
$count = StreamMessage::countMessageSince($lastMessageRead);
$inboxUnreadCount1 = MessagingNotification::getUserNotification($my->id);
$streamModel 	= StreamFactory::getModel('stream');

// Groups
// Get the list
$groupModel 	= StreamFactory::getModel('groups');
$groupIJoin 	= ($my->isExtranetMember()) ? $my->getParam('groups_member_limited') : $my->getParam('groups_member');
$groupIFollow 	= $my->getParam('groups_follow');

// Profile completeness
$profileDetailsUnfilled = $my->getParam('profile_details_unfilled', null);
$profileDetailsUnfilled = ($profileDetailsUnfilled === null) ? 100 : $profileDetailsUnfilled;

$groupIFollow 	= JXUtility::csvDiff($groupIFollow, $groupIJoin);
$myGroupsIds 	= JXUtility::csvMerge($groupIFollow, $groupIJoin);

class StreamGroupSorter {
	public static function sortLastActive($groupA, $groupB){
		$my = JXFactory::getUser();
		$diffA = $groupA->getParam('last_message') - $my->getParam('group_'.$groupA->id.'_last');
		$diffB = $groupB->getParam('last_message') - $my->getParam('group_'.$groupB->id.'_last');
		
		// If a group has been archived, always push it down
		if( $groupA->archived && !$groupB->archived){
			return 1;
		}
		
		if( !$groupA->archived && $groupB->archived){
			return -1;
		}
		
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
if(empty($groupIJoin)){
	$groupIJoin = -1;
}

$myGroups			= $groupModel->getGroups(array('id' => $myGroupsIds), 100);
$myJoinedGroups		= $groupModel->getGroups(array('id' => $groupIJoin), 100);
$myFollowedGroups	= $groupModel->getGroups(array('id' => $groupIFollow), 100);

$otherGroups 		= array();

// If the user sign up to only a few groups, show other active groups
if(count($myGroups) < 10 ){
	$otherGroups = $groupModel->getGroups(array('!id' => $myGroupsIds, 'access'=>0), 10);
}

// Re-order based on last activity
usort($myGroups, array('StreamGroupSorter', 'sortLastActive'));
usort($myJoinedGroups, array('StreamGroupSorter', 'sortLastActive'));
usort($myFollowedGroups, array('StreamGroupSorter', 'sortLastActive'));
usort($otherGroups, array('StreamGroupSorter', 'sortLastActive'));

// Get number of people in the site
$memberCount = offiria_get_membercount();

function offiria_get_membercount(){
	$db		= JFactory::getDBO();
	$query	=  'SELECT count(*)
				FROM ' . $db->nameQuote( '#__users' );

	$db->setQuery( $query );
	$result = $db->loadResult();
	return ($result - 1);
}

function offiria_get_pendingInvitation()
{
	require_once(JPATH_ROOT . DS . 'components' . DS . 'com_account' . DS . 'controllers' . DS . 'invite.php');
	$inviteController = new AccountControllerInvite();
	return ($inviteController->getPendingEmailCount() > 0);
}
/**
 * @param $opt Array value:
 * 	['ignore'] = group,to,ignore (comma-separated value)
 */
function offiria_list_groups($groups, $title, $groupIJoin, $groupIFollow, $opt=array())
{ 
	$group_id = JRequest::getVar('group_id');
	$my = JXFactory::getUser();
	$streamModel 	= StreamFactory::getModel('stream');
	?>
	<div class="groups-listing followed">
		<h3><?php echo $title; ?></h3>
		<?php
		// Only show list of groups if there is any
		if(!empty($groups)):
		?>
		<ul>
			<?php foreach($groups as $group) { ?>
			<li class="groups <?php if($group_id == $group->id){ echo 'active'; }?>">
				<?php
				$lastReadId 	= $my->getParam('group_'.$group->id.'_read');
				$statusClass	= (JXUtility::csvExist($groupIJoin, $group->id)) ? 'joined' : 'followed';
				$groupLastMsg 	= $group->getParam('last_message');
				$groupNewMsg	= 0;

				/* ignore groups provided in parameter */
				$ignores = (isset($opt['ignore'])) ? explode(',', $opt['ignore']) : array();
				if (count($ignores) > 0) {
					/* ignoring archived group */
					if (in_array('archived', $ignores)) {
						if ($group->archived) {
							break;
						}
					}
					/* ignoring followed group */
					if (in_array('followed', $ignores)) {
						if (JXUtility::csvExist($groupIJoin, $group->id)) {
							break;
						}
					}
				}

				// Only calculate if there is a diff here
				if($lastReadId != $groupLastMsg){
					$groupNewMsg 	= $streamModel->countStream(array('!user_id' => $my->id, 'id_more' => $lastReadId, 'group_id' => $group->id));
				}
				$styleHide = '';
				?>

				<?php /*<a class="<?php echo $statusClass; ?>" id="groups_<?php echo $group->id; ?>_link" href="<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='.$group->id); ?>">
					<?php if($group->access) {
						// Should not be seeing this really
						echo '<i class="icon-lock icon-white"></i>&nbsp;&nbsp;';										
					}
					
					
					if($group->archived ){
						echo '<i class="icon-remove-sign icon-white"></i>&nbsp;&nbsp;';
					}
					
					?>
					<?php echo StreamTemplate::escape($group->name); ?>
				</a>*/?>
				
				<a class="<?php echo $statusClass; ?>
					
					<?php if($group->access) {
						echo 'private-group';
						}
				
					if($group->archived ){
						echo 'archived-group';
					}
					?>
					
					" id="groups_<?php echo $group->id; ?>_link" href="<?php echo $group->getUri(); ?>">
					<span>
					
					<?php echo StreamTemplate::escape($group->name); ?></span>
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
		<?php else: ?>
	    <div class="alert alert-info">
			<?php echo JText::_('COM_STREAM_LABEL_NO_GROUP_FOLLOWED'); ?><a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups'); ?>">
			<?php echo JText::_('COM_STREAM_LABEL_VIEW_LIST_OF_GROUP_TO_FOLLOW'); ?></a>
		</div>
		<?php endif; ?>
	</div>
<?php
}

// Sort by activity

//@todo: reorder the groups based on last active per-user
//$myGroups = array_slice($myGroups, 0, 8);

?>

<!DOCTYPE html>
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >

<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
	<meta name="apple-mobile-web-app-capable" content="yes" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo JText::_('CUSTOM_TEMPLATE');?>/css/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/templates/<?php echo JText::_('CUSTOM_TEMPLATE');?>/css/offiria.css?t=<?php echo filemtime(JPATH_ROOT.DS.'templates'.DS.JText::_('CUSTOM_TEMPLATE').DS.'css'.DS.'offiria.css');?>" />
	<link rel="icon" type="image/ico" href="<?php echo $this->baseurl ?>/templates/<?php echo JText::_('CUSTOM_TEMPLATE');?>/images/favicon.ico"/>
	<link rel="apple-touch-icon" href="<?php echo $this->baseurl ?>/templates/<?php echo JText::_('CUSTOM_TEMPLATE');?>/images/iphone-app-icon-114.png"/>
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo $this->baseurl ?>/templates/<?php echo JText::_('CUSTOM_TEMPLATE');?>/images/ipad-app-icon-144.png"/>
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo $this->baseurl ?>/templates/<?php echo JText::_('CUSTOM_TEMPLATE');?>/images/iphone-app-icon-114.png"/>
	<?php
	// Get per-user styling
	$style = $my->getParam('style');
	$jxConfig = new JXConfig();
	$style = ($style) ? $style : $jxConfig->get(JXConfig::STYLE);
	if($style){
		echo '<link rel="stylesheet" type="text/css" href="'.$this->baseurl.'/templates/'.JText::_('CUSTOM_TEMPLATE').'/css/style_'.$style.'.css" />';
	}
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo $this->baseurl ?>/media/tipsy2/src/stylesheets/tipsy.css" />
	
	<!-- BROWSER SPECIFIC -->
	<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/offiria/js/browser-specific.js"></script>

	<!-- PIE CHART -->
	<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/offiria/js/piecanvas.min.js"></script>
	
	<!-- SCRIBD PLUGIN -->
	<script type="text/javascript" src='http://www.scribd.com/javascripts/scribd_api.js'></script>

	<!-- DROPBOX CHOOSER -->
	<script type="text/javascript" src="https://www.dropbox.com/static/api/1/dropbox.js" id="dropboxjs" data-app-key="gt3pkmkihv85tca"></script>
	
	<!--[if IE]><script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/e20/js/excanvas.js"></script><![endif]-->
	<jdoc:include type="head" />

	<!--[if IE]> 
	<script type="text/javascript" src="<?php echo JURI::root().'media/jquery/jquery-placeholder.js'; ?>"></script>
	<script type="text/javascript" src="<?php echo JURI::root().'media/jquery/modernizr.custom.js'; ?>"></script>
	<![endif]-->
	<!--
	<script language='javascript' type='text/javascript' src='http://cdn.walkme.com/users/1348/walkme_1348.js'></script>
	-->
	
	<script type="text/javascript">
		
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-670908-18']);
	_gaq.push(['_setDomainName', 'offiria.com']);
	_gaq.push(['_trackPageview']);
	
	(function() {
	  var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	  ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	
	</script>
</head>

<body>
	<!-- #eHeader -->
	<div id="oHeader" class="navbar">
		<?php if (StreamMobile::isMobile()): ?>
		<?php include_once(JPATH_ROOT . "/templates/" . $this->template . '/mobile.header.php'); ?>
		<?php else: ?>
		<?php include_once(JPATH_ROOT . "/templates/" . $this->template . '/header.php'); ?>
		<?php endif; ?>
	</div>
	<!-- #eHeader -->

	<?php
	if( $my->isAdmin() && $memberCount < 5){ 
	?>
	<!-- Invite -->
	<div class="container admin-message">
		<div class="alert alert-warning clearfix">
			<div class="admin-text pull-left"><?php echo sprintf(JText::_('COM_STREAM_INVITATION_ALERT'), JText::_('CUSTOM_SITE_NAME'), JText::_('CUSTOM_SITE_NAME'));?></div>
			<div class="pull-right"><a href="<?php echo JRoute::_('index.php?option=com_account&view=invite'); ?>" class="btn btn-inverse"><?php echo JText::_('COM_STREAM_INVITATION_ALERT_BTN_INVITE');?> &raquo;</a></div>
		</div>
	</div>
	<?php } ?>
	
	<?php
	if( $my->isAdmin() && offiria_get_pendingInvitation()){ 
	?>
	<!-- Invite -->
	<div class="container admin-message pending-invite">
		<div class="alert alert-warning clearfix">
			<div class="admin-text pull-left">
				<?php echo JText::_('COM_STREAM_LABEL_INVITATION_PENDING'); ?>
			</div>
			<div class="pull-right"><a href="<?php echo JRoute::_('index.php?option=com_account&view=invite&task=resendPending'); ?>" class="btn btn-inverse"><?php echo JText::_('COM_STREAM_LABEL_INVITATION_RESEND'); ?> &raquo;</a></div>
		</div>
	</div>
	<?php } ?>
	
	<!-- Invite End -->
	<!-- BODY CONTENT-->
	<div id="oContent">
		<div id="oContent-inner" class="container">
		
			<div class="row">
			
				<!-- Modules -->
				<div id="sidebar-left" class="span3">
					
					<div class="sidebar-left-inner blocks">
						
						<div class="side-notification">
							<?php if (intval($count) > 0): ?>
							<a href="javascript:void(0)">
							<?php echo JText::_('COM_STREAM_NOTIFICATION_LABEL_NEW_NOTIFICATION')  ?>
							</a>
							<span class="navigator-notice" style="display:block"><?php echo $count; ?></span>
							<?php else: ?>
							<a href="javascript:void(0)">
							<?php echo JText::_('COM_STREAM_NOTIFICATION_LABEL_NO_NEW_NOTIFICATION'); ?>
							</a>
							<span class="navigator-notice"><?php echo $count; ?></span>
							<?php endif; ?>
						</div>
						
						<div id="mini-profile-content" class="user-profile">
							<div class="user-avatar">
								<a href="<?php echo JRoute::_('index.php?option=com_profile&view=display'); ?>"><img src="<?php echo $my->getThumbAvatarURL(); ?>" /></a>
							</div>

							<div class="user-details">
								<h3><a href="<?php echo JRoute::_('index.php?option=com_profile&view=display'); ?>"><?php echo $my->name; ?></a></h3>

								<div class="btn-group">
									
								    <a href="<?php echo JRoute::_('index.php?option=com_messaging'); ?>"><?php echo JText::_('NAVIGATOR_LABEL_INBOX');?></a>
							    	<?php if($inboxUnreadCount1): ?>
								    <span style="font-weight: bold; color: #FB4C40; display: inline;">(<?php echo $inboxUnreadCount1; ?>)</span>
									<?php endif; ?>
									&nbsp;&bull;&nbsp;
									
								    <a class="dropdown-toggler" data-toggle="dropdown" href="#"><?php echo JText::_('COM_PROFILE_LABEL_PROFILE_SETTINGS');?><span class="caret"></span></a>
									
								    <ul class="dropdown-menu">
								      <li><a href="<?php echo JRoute::_('index.php?option=com_profile&view=edit'); ?>">&diams;&nbsp;<?php echo JText::_('COM_PROFILE_LABEL_EDIT_PROFILE');?></a></li>
									  <li><a href="<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=details'); ?>">&diams;&nbsp;<?php echo JText::_('COM_PROFILE_LABEL_EDIT_DETAILS');?></a></li>
								      <li><a href="<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=changeAvatar'); ?>">&diams;&nbsp;<?php echo JText::_('COM_PROFILE_LABEL_PROFILE_AVATAR');?></a></li>
								      <li><a href="<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=notification'); ?>">&diams;&nbsp;<?php echo JText::_('COM_PROFILE_LABEL_NOTIFICATION');?></a></li>
								    </ul>
								</div>
							</div>

							<form id="logout-form" method="post" action="<?php echo JURI::root(); ?>">
							<div class="logout-button" style="display:none;">
								<input type="submit" value="Log out" class="button" name="Submit">
								<input type="hidden" value="com_users" name="option">
								<input type="hidden" value="user.logout" name="task">
								<input type="hidden" value="<?php echo base64_encode(JURI::base());?>" name="return" />
								<?php echo JHtml::_('form.token'); ?>	
							</div>
							</form>

							<div class="clear"></div>

							<!--GETTING STARTED-->
							<?php
							//$showHelper = JRequest::getVar('showHelper');
							if($my->getGettingStartedCompletion() < 100): ?>
								
								<div id="getting-started" class="user-details-progress">

									<span class="user-profile-completeness"><?php echo JText::_('COM_TEMPLATE_GETTING_STARTED'); ?></span>
									
									<div class="progress progress-success progress-striped">
										<div class="bar" style="width: <?php echo $my->getGettingStartedCompletion(); ?>%;"></div>
									</div>

									<ul class="get-started-list">
										<?php
										foreach($my->getGettingStartedTask() as $gstask=>$completion) :

											// If the value isn't 0 or 100 it must be a task with stages
											$completionOutput = ($completion > 0 && $completion < 100) ? '&nbsp;(' . $completion . '&#37;)' : '';
											?>
											<li class="clearfix">
												<?php if($completion >= 100) : ?>
												<span class="get-started-list-task-completed pull-left"><?php echo JText::_('COM_PROFILE_GSTARTED_' . $gstask) . $completionOutput; ?></span>
												<span class="get-started-list-check pull-right"></span>
												<?php else : ?>
												<span class="get-started-list-task pull-left"><a href="<?php echo $my->getGettingStartedTaskLink($gstask); ?>"><?php echo JText::_('COM_PROFILE_GSTARTED_' . $gstask) . $completionOutput; ?></a></span>
												<span class="get-started-list-uncheck pull-right"></span>
												<?php endif; ?>
											</li>
											<?php endforeach; ?>
									</ul>
									
									<div class="clear"></div>
								</div>
								<?php endif; ?>
							<!--GETTING STARTED END-->
						</div>
						
						<!-- DAILY OVERVIEW START -->
						<div>
							<?php 						
							$companyView = StreamFactory::getView('company', '', 'html');
							echo $companyView->modGetDailyOverviewHtml();
							?>
						</div>
						<!-- DAILY OVERVIEW END -->
						
						<?php if (true/*JRequest::getVar('cl') == 1*/) : ?>
						<div class="custom-listing joined">
							<h3 class="clearfix"><span><?php echo JText::_('COM_TEMPLATE_CUSTOMLIST'); ?></span><a id="group-create" class="btn" href="#" onclick="S.customlist.create(this);return false;"><span><?php echo JText::_('COM_STREAM_LABEL_CUSTOMLIST_NEW');?></span></a></h3>
							<ul>
								<?php
								$userCustomlists = $streamModel->getCustomlist($my->id);
								if(count($userCustomlists)):
									foreach($userCustomlists as $userCustomlist):
									?>
									<li class="custom <?php if(JRequest::getVar('list_id') == $userCustomlist->id) { echo 'active'; } ?>"><a class="customlist" href="<?php echo JRoute::_('index.php?option=com_stream&view=customlist&list_id=' . $userCustomlist->id); ?>"><?php echo StreamTemplate::escape($userCustomlist->title); ?></a></li>
									<?php
									endforeach;
								else:
								?>
									<div class="alert alert-info" style="margin-bottom: 8px;"><?php echo JText::_('COM_STREAM_LABEL_STREAM_INFO');?> <a href="#" onclick="S.customlist.create(this);return false;"><?php echo JText::_('COM_STREAM_LABEL_STREAM_CREATE');?></a>.</div>
								<?php
								endif;
								?>
							</ul>
						</div>
						<?php endif; ?>
				
						<div class="groups-listing joined">
							<h3 class="clearfix"><span><?php echo JText::_('COM_TEMPLATE_MY_GROUPS'); ?></span>
								<?php if (!$my->isExtranetMember()) : ?>
								<a id="group-create" class="btn" href="#" onclick="S.groups.create(this);return false;"><span><?php echo JText::_('COM_STREAM_LABEL_CREATE_A_GROUP');?></span></a>
								<?php endif; ?>
							</h3>
							<?php
							// Only show list of groups if there is any
							if(!empty($myJoinedGroups)):
							?>
							<ul>
								<?php foreach($myJoinedGroups as $idx=>$group) { ?>
								<?php
								  $GROUP_SIDEBAR_LIMIT = 10;
								  $groupIsLimited = ($idx >= $GROUP_SIDEBAR_LIMIT) ? true : false;
								  $groupHiddenStyle = ($groupIsLimited) ? 'style="display:none"' : '';
								  ?>
								<li <?php echo $groupHiddenStyle; ?>
								class="groups <?php if($group_id == $group->id){ echo 'active'; }?>">
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
									<?php /*
									<a class="<?php echo $statusClass; ?>" id="groups_<?php echo $group->id; ?>_link" href="<?php echo $group->getUri(); ?>">
										<span>
										<?php if($group->access) {
											echo '<i class="icon-lock icon-white"></i>&nbsp;&nbsp;';
											}
									
										if($group->archived ){
											echo '<i class="icon-remove-sign icon-white"></i>&nbsp;&nbsp;';
										}
										?>
										<?php echo StreamTemplate::escape($group->name); ?></span>
										<span class="comment-notice"></span>
									</a>
									*/?>
									
									<a class="<?php echo $statusClass; ?>
										
										<?php if($group->access) {
											echo 'private-group';
											}
									
										if($group->archived ){
											echo 'archived-group';
										}
										?>
										
										" id="groups_<?php echo $group->id; ?>_link" href="<?php echo $group->getUri(); ?>">
										<span>
										
										<?php echo StreamTemplate::escape($group->name); ?></span>
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
								<?php if ($groupIsLimited): ?>
								<li class="moreless">
									<a class="more" href="#showAllGroups"><?php echo JText::_('COM_STREAM_LABEL_SHOW_MORE_GROUP'); ?><span></span></a>
									<a class="less" <?php echo $groupHiddenStyle; ?> data-limit="<?php echo $GROUP_SIDEBAR_LIMIT; ?>"href="#showLessGroups"><?php echo JText::_('COM_STREAM_LABEL_SHOW_LESS_GROUP'); ?><span></span></a>
								<li>
								<?php endif; ?>
							</ul>
							<?php else: ?>
							<div class="alert alert-info">
								<?php echo JText::_('COM_STREAM_LABEL_NO_GROUP_JOINED'); ?>
								<a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups'); ?>">
								<?php echo JText::_('COM_STREAM_LABEL_VIEW_LIST_OF_GROUP_TO_JOIN'); ?>
								</a>
							</div>
							<?php endif;?>
						</div>
				
						<?php
						if(!$my->isExtranetMember()) {
							offiria_list_groups($myFollowedGroups, JText::_('COM_TEMPLATE_FOLLOWED_GROUPS'),$groupIJoin, $groupIFollow);

							if(!empty($otherGroups)) { 
								offiria_list_groups($otherGroups, JText::_('COM_TEMPLATE_OTHER_GROUPS'),$groupIJoin, $groupIFollow, array('ignore'=>'archived'));
							}
						}
						?>

						<jdoc:include type="modules" name="left" style="default"/>
					</div><!--end .sidebar-left-inner-->
					
					<div class="clear"></div>
				</div>
				
				<!-- Components -->
				<div id="main-content-right" class="pull-left">
					<div id="main-content" class="span6">
						<div class="main-content-inner">
							<jdoc:include type="modules" name="breadcrumbs" style="none"/>

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
							</div> <!-- end system-message-container -->
				
							<jdoc:include type="modules" name="component_top" style="default"/>
							<?php
							// If page title is set, add it here. BUT, don't show it
							// if it is the same as site title (which mean, the title is not set)
							$docTitle = $document->getTitle();
							if(!empty($docTitle) && $docTitle != $config->sitename ) {

								/* if(strlen($docTitle) > GROUP_NAME_LENGTH && $group_id !== null) { */
								/* 	$docTitle = rtrim(substr($docTitle, 0, GROUP_NAME_LENGTH)) . "..."; */
								/* } */

								/* title for groups is rendered at stream template to allow reposition */
								if (!$group_id && $view != 'customlist') {
									echo '<h1>'. StreamTemplate::escape($docTitle).'</h1>';
								}
							}
							?>
				
							<?php
							$hideWelcome = $my->getParam('swl');
							if($hideWelcome || $my->isSuperAdmin()){ ?>
				
							<jdoc:include type="component" />
				
							<?php 
							} else {
								$tmpl = new StreamTemplate();
								$tmpl->set('my', $my);
								echo $tmpl->fetch('user.welcome');
							}
							?>
				

							<jdoc:include type="modules" name="component_bottom" style="default"/>
		
							<div class="clear"></div>
						</div><!--end .main-content-left-inner-->
				
					</div><!-- end #main-content -->
					
					<div id="sidebar-right" class="span3">
						
						<div class="pageslide-header visible-tablet">
							<div class="pageslide-header-inner">
								<a href="javascript:$.pageslide.close()" class="btn btn-navbar"><i class="icon-remove icon-white"></i>Close Sidebar</a>
							</div>
						</div>
						
						<div class="search-content">
							<form id="searchform" action="<?php echo JRoute::_('index.php?option=com_search');?>">
								<input type="text" name="searchword" placeholder="<?php echo JText::_('PLG_SEARCH_LABEL_SEARCH'); ?>..." />
								<input type="submit">
								<div class="clear"></div>
							</form>
						</div>		
						<div id="<?php if (($view == 'groups' && preg_match("/^show(_.+)?$/", $task) && $group_id > 0) || $view == 'customlist'): ?>group-pages<?php endif; ?>" class="sidebar-right-inner blocks">
							<?php
							$accountView = AccountFactory::getView('');
							if ($view == 'groups') {
								// if groups page are opened, then move the birthday module at the last
								JXModule::addBuffer('right', $accountView->modMembersBirthday(), 'module.members.birthday');
							} else {
								// adding birthday as a first module
								JXModule::addBuffer('right_bday', $accountView->modMembersBirthday(), 'module.members.birthday');
								$birthday =& JXModule::getBuffer('right_bday');
								if (is_array($birthday) && count($birthday)) echo $birthday[0];
							}
							JXModule::addBuffer('right', $accountView->modWeather(), 'weatherenable');
							
							// Add guest invite right module
							$accountView = AccountFactory::getView('invite');
							JXModule::addBuffer('right', $accountView->modMemberInvite(), 'module.invite.guest');
							// Add weather module
							
							$buffer =& JXModule::getBuffer('right');
							foreach($buffer as $buff) {
								echo $buff;
							}
							
							
							
							?>
							<?php /* if($option == 'com_stream' && $view == 'company') { ?>
							<img src="<?php echo JURI::root().'components/com_stream/assets/images/hot_topics.png'; ?>" />
							<?php } */?>
							
						</div><!-- end .sidebar-right-inner -->
					</div><!-- end #sidebar-right -->
					
				<div class="clear"></div>
				
				</div>
			</div>
			

		</div>

	</div><!-- #eContent -->

	<div class="clear"></div>

	<!-- #eFooter -->
	<div id="oFooter">
		<?php if (StreamMobile::isMobile()): ?>
		<?php include_once(JPATH_ROOT . "/templates/" . $this->template . '/mobile.footer.php'); ?>
		<?php else: ?>
		<?php include_once(JPATH_ROOT . "/templates/" . $this->template . '/footer.php'); ?>
		<?php endif; ?>
	</div>
	<!-- #eFooter -->

<!-- tipsy -->
<script type='text/javascript'>
$(function() {
	$('.tips').tipsy({live: true, gravity: 's'});
	//Image Preview Wrapper
	$(".slide-nav").pageslide({ direction: "left", modal: true });
});
</script>
<?php ##CUTSTART ?>
<!-- uservoice -->
<script type="text/javascript">
  var uvOptions = {};
  (function() {
    var uv = document.createElement('script'); uv.type = 'text/javascript'; uv.async = true;
    uv.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'widget.uservoice.com/yxQEHUXVPLvK5kQDwmd7XQ.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(uv, s);
  })();
</script>
<?php ##CUTSTOP ?>
<jdoc:include type="modules" name="analytics" style="default"/>
</body>
</html>

