<?php
/**
 * @version     1.0.0
 * @package     Offiria
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// no direct access
defined('_JEXEC') or die;
$task = JRequest::getVar('task', 'activities');
$view = JRequest::getVar('view', 'display');
?>
<script type="text/javascript">
	var analytics = {
		fetchProfileAnalytic: function (type, user)
		{
			$.ajax({
				url: '<?php echo JRoute::_('index.php?option=com_profile&view=display&task=ajaxGenerateAnalytics', false);?>',
				dataType: 'html',
				data: 'group_type='+type+'&user='+user,
				type: 'post',
				beforeSend: function( xhr ) {
				},
				success: function( responseHtml ) {
					$('#analytic-container').html(responseHtml)
				}
			});
		}	
	};
	
	$(document).ready(function() {
		$('.tabs').button();		
		//$('.btn:eq(<?php echo $this->analyticIndex;?>)').button('toggle');
	});
</script>
<div class="profile-avatar">
	<div class="message-avatar">
		<img class="cAvatar" src="<?php echo $this->user->getAvatarURL();?>" />
	</div>
	<div class="message-content">
		<h1><?php echo $this->escape($this->user->name); ?>
		<?php echo '(@'.$this->user->get('username').')'; ?>
			<?php if($this->my->id != $this->user->id) : ?>
				<a href="<?php echo JRoute::_('index.php?option=com_messaging&view=inbox&to=' . $this->user->username); ?>" class="btn" style="font-weight: normal; margin: -4px 0 0 5px;">Send a Private Message</a>
				<!--<a href="#" class="compose-direct-message btn" style="font-weight: normal; margin: -4px 0 0 5px;" data-to="<?php echo $this->escape($this->user->username); ?>">Send a Private Message</a>-->
			<?php endif; ?>
		</h1>
		<span>
			<?php 
				echo $this->user->get('email');
			?>
		</span><br/>
		<span><b>
			<?php 
				echo (empty($this->user->work_title)) ? JText::_('COM_PROFILE_WORK_TITLE_DUMMY') : $this->escape($this->user->work_title);
			?>
		</b></span>
		<p>
		<?php
		  // add default about me data
		  $aboutMe = $this->user->getParam('about_me');
		  if (!empty($aboutMe)): ?>
		<span class="error">
		<?php echo $this->escape($aboutMe); ?>
		</span>
		<?php endif; ?>
		</p>
	</div>
	<div class="clear"></div>
	
</div>

<ul class="nav nav-pills filter">
	<li <?php if($task=='activities'){ echo 'class="active"';} ?> ><a href="<?php echo JRoute::_('index.php?option=com_profile&view=display&user='.$this->user->username); ?>">Activities</a></li>
	<li <?php if($task=='bio'){ echo 'class="active"';} ?>><a href="<?php echo JRoute::_('index.php?option=com_profile&view=display&task=bio&user='.$this->user->username); ?>">Bio</a></li>
	<li <?php if($task=='content'){ echo 'class="active"';} ?>><a href="<?php echo JRoute::_('index.php?option=com_profile&view=display&task=content&user='.$this->user->username); ?>">Content</a></li>
	<?php if($task=='bio' && ($this->my->id == $this->user->id)): ?>
	<li class="pull-right">
	<a class="btn" href="<?php echo JRoute::_('index.php?option=com_profile&view=edit'); ?>"><?php echo JText::_('COM_PROFILE_LABEL_EDIT_YOUR_PROFILE'); ?></a>
	<?php endif; ?>
	</li>
</ul>
	
<?php 
// Why not just use include $task.'.php' ? security issues
switch($task){
	case 'activities':
		include('activities.php');
		break;
	case 'bio':
		include('bio.php');
		break;
	case 'content':
		include('content.php');
		break;
}
?>

<!--TODO: TEMPORARY START-->
<script type="text/javascript">
	// Initialize Direct Messaging
	S.direct.init();
</script>
<!--TODO: TEMPORARY END-->