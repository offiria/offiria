<?php
  /**
  * @version     1.0.0
  * @package     com_People
  * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
  * @license     GNU General Public License version 2 or later; see LICENSE.txt
  * @author      Offiria Team
  */

// no direct access
defined('_JEXEC') or die;
jimport('joomla.html.pagination');
?>

<h1 class="hidden-phone"><?php echo JText::_('COM_PEOPLE_LABEL_YOUR_MEMBERS');?></h1>


<?php
  $namefilter = JRequest::getVar('namefilter', 'all');
  $departmentId = JRequest::getVar('department_id');
  $positionId = JRequest::getVar('position_id');
  $get = JRequest::get('GET');
  $Category = new StreamCategory();
  $departments = $Category->getByCategory('department');
  $positions = $Category->getByCategory('position');

// Need to get rid of 'limitstart' since we should reset the limit when
// switching to a different view
if(isset($get['limitstart'])){
unset($get['limitstart']);
}

if(isset($get['start'])){
unset($get['start']);
}
?>

<ul class="nav nav-pills filter">
	<?php if (!empty($positions)): ?>
	<li <?php if(!$positionId) echo 'class="active"'; ?>>
		<a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('position_id' => '' )) ) ); ?>">
		<?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_ALL'); ?>
		</a>
	</li>
	<?php foreach($positions as $pos): ?>
	<li <?php if($pos->id == $positionId) echo 'class="active"'; ?>>
		<a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('position_id' => $pos->id )) ) ); ?>">
		<?php echo $pos->category; ?></a>
	</li>
	<?php endforeach; ?>
	<?php endif; ?>

	<?php if (!empty($departments)): ?>
	<li style="float:right"  class="dropdown <?php echo ($departmentId) ? 'active': ''; ?>">
	<?php if ($departmentId): ?>
	<a class="dropdown-toggle" data-toggle="dropdown" href="#">
	<?php echo JText::sprintf('COM_STREAM_LABEL_FILTER_BY_CATEGORY_PROVIDED', $Category->getCategoryName($departmentId)); ?><b class="caret"></b></a>
	<?php else: ?>
	<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo JText::_('COM_PEOPLE_FILTER_BY_DEPARTMENT'); ?> <b class="caret"></b></a>
	<?php endif; ?>
	<ul class="dropdown-menu">
	<?php foreach($departments as $dept): ?>
	<li <?php if($dept->id == $departmentId) echo 'class="active"'; ?>>
		<a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('department_id' => $dept->id )) ) ); ?>">
		<?php echo $dept->category; ?></a>
	</li>
	<?php endforeach; ?>
	<li>
		<a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('department_id' => '' )) ) ); ?>">
		<?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_ALL'); ?>
		</a>
	</li>
	</ul>
	</li>
	<?php endif ;?>
</ul>


<?php 
  $my = JXFactory::getUser();
  if ($my->isAdmin()):
?>
<!-- <ul class="menubar"> -->
<!-- 	<\!-- member management -\-> -->
<!-- 	<li class="action-activate"> <a href="javascript:void(0)"><?php echo JText::_('COM_PEOPLE_LABEL_ACTIVATE');?></a> </li> -->
<!-- 	<li class="action-set-admin"> <a href="javascript:void(0)"><?php echo JText::_('COM_PEOPLE_LABEL_SET_AS_ADMIN');?></a> </li> -->
<!-- 	<li class="action-unset-admin"> <a href="javascript:void(0)"><?php echo JText::_('COM_PEOPLE_LABEL_SET_AS_NORMAL_USER');?></a> </li> -->
<!-- </ul> -->
<?php endif; ?>

<!-- NEW PEOPLE LISTING -->
<?php /* comment out new search system. redundant search.
  <div class="form-search">	
  <input type="text" placeholder="Search name" class="inputbox">
  <input type="submit" class="btn" name="Search">
  </div>

<ul class="nav">
<li class="message-item">
<div class="message-avatar">
<a href="/e20/">
<img border="0" src="<?php echo JURI::root(); ?>images/avatar/user-thumb.png" alt="" author="85" class="cAvatar">
</a>
</div>
<div class="message-content">
	<div class="message-meta-top">
		<div class="message-content-actor">
			<a class="actor-link" target="_blank" href="<?php echo $result->href;?>"><strong>John Mayer</strong></a> 
			<span style="color:#999;">@John_May</span>
			<span class="btn-group" style="float:right;">
				<button onclick="window.open('<?php echo $result->href;?>');" data-toggle="dropdown" class="btn">
				View profile
			</button>
		</span>
	</div>
	<div class="message-content-text">
		Chief Executive Officer
	</div>
</div>
</div>
<div class="clear"></div>
</li>
</ul>
*/ ?>


<!-- END OF NEW LISTING -->

<form method="post" id="people-form" action="<?php echo JRoute::_('index.php?option=com_people&view=members') ?>">

<div class="people-management">
	<ul class="clearfix">
			<?php
				//$i = 1;
				foreach($this->members as $row):
					$user = JXFactory::getUser($row->id);
			?>
			
			<li>
				<div class="user-container <?php echo ($my->isAdmin()) ? 'admin' : ''; ?>">
					<div class="user-avatar">
						<a href="<?php echo $user->getURL();?>">
							<img class="cAvatar" border="0" author="85" alt="" src="<?php echo $user->getThumbAvatarURL(); ?>">
						</a>
					</div>
				
					<div class="user-content">
						<div class="user-name">
							<?php 
							  echo '<a href="' . JRoute::_('index.php?option=com_profile&view=display&user=' . $row->username) . '">' . $row->name . '</a>'; 
							?>
							<?php 
							  // different label color for deactivated admin
							  $classes = ($row->isAdmin()) ? 'label-warning' : 'label-important'; 
							?>
							<?php if ($row->isAdmin() && $row->block == 0) : ?>
							<span class="label label-warning">Admin</span>
							<?php endif; ?>
							<?php if ($row->block == 1): ?>
							<span class="label <?php echo $classes; ?>"><?php echo JText::_('COM_PEOPLE_LABEL_DEACTIVATED'); ?></span>
							<?php endif; ?>
							<div class="clear"></div>
						</div>
						
						<span class="user-email">
							<?php echo $row->email; ?>
						</span>
						
						<?php /*<em>@<?php echo $row->username; ?></em> */?>
					</div>
					
					<div class="clear"></div>
				</div>
				<?php if ($my->isAdmin()): ?>
					<div id="<?php echo $row->id; ?>" class="user-action">

						<?php if($row->block==1): ?>
						<button class="btn btn-success user-activate"><?php echo JText::_('COM_PEOPLE_LABEL_ACTIVATE'); ?></button>
						<?php else: ?>
						<button class="btn btn-danger user-deactivate"><?php echo JText::_('COM_PEOPLE_LABEL_DEACTIVATE'); ?></button>
						<?php if($row->isAdmin()): ?>
						<button class="btn user-admin user-unset-admin"><?php echo JText::_('COM_PEOPLE_LABEL_NORMAL'); ?></button>
						<?php else: ?>
						<button class="btn btn-warning user-admin user-set-admin"><?php echo JText::_('COM_PEOPLE_LABEL_ADMIN'); ?></button>
						<?php endif; ?>
						<?php endif; ?>
						
						<?php /*<a class="remove"><i class="icon-remove"></i></a>*/ ?>
						<span class="remove">&times;</span>
					</div>
				<?php endif; ?>
			</li>
			<?php
				//$i++;
				endforeach;
			?>
		</ul>
	</div>
	
	<?php /*
	<table class="table table-bordered table-striped table-novborder">
		<thead>
			<tr>
				<th>#</th>
				<?php if ($my->isAdmin()): ?>
				<th><input class="action-select-allcheckboxes" type="checkbox"/></th>
				<?php endif; ?>
				<th><?php echo JText::_('COM_PEOPLE_LABEL_NAME');?></th>
				<th><?php echo JText::_('COM_PEOPLE_LABEL_EMAIL');?></th>
				<th><?php echo JText::_('COM_PEOPLE_LABEL_DATE_JOINED');?></th>
			</tr>
		</thead>

		<tbody>
			<?php
				$i = 1;
				foreach($this->members as $row):
					$user = JXFactory::getUser($row->id);
			?>

			<tr>
				<td><?php echo $i; ?></td>
				<?php if ($my->isAdmin()): ?>
				<td> <input class="action-user-select" id="<?php echo $row->id; ?>" type="checkbox"/> </td>
				<?php endif; ?>
				<td>
					<div class="user-avatar" style="float:left;margin-right:8px">
						<a href="<?php echo $user->getURL();?>">
							<img class="cAvatar" border="0" author="85" alt="" src="<?php echo $user->getThumbAvatarURL(); ?>">
						</a>
					</div>
					<div class="user-avatar" style="float:left;margin-left:4px">
					<?php 
					  echo '<a href="' . JRoute::_('index.php?option=com_profile&view=display&user=' . $row->username) . '">' . $row->name . '</a>'; 
					if($row->isAdmin()){ echo ' <span class="label label-warning">Admin</span>';}?><br/>
					<em>@<?php echo $row->username; ?></em><br />
					<?php if($row->block==1){ echo ' <span class="label label-error">'. JText::_('COM_PEOPLE_LABEL_DEACTIVATED') . '</span>';}?><br/>
					</div>
				</td>
				<td><?php echo $row->email; ?></td>
				<td><?php echo JXDate::formatDate($row->registerDate, JXDate::SHORT_DATE_FORMAT); ?></td>
			</tr>
			<?php
				$i++;
				endforeach;
			?>
		</tbody>
	</table>
	*/?>
	
	<input type="hidden" name="action" value=""/>
</form>

<div class="pagination">
<?php echo $this->pagination->getPagesLinks(); ?>
</div>
<script type="text/javascript">
// add search event
$('#people-search input[type=submit]').click(function() {
	var $input = $('#people-search input[type=text]');
	S.people.search($input.val());
})
// realign height
var resizeHeight = function() {
var optimumHeight = $('body').outerHeight();
$('.main-content-inner').height(optimumHeight);
};
$(window).ready(resizeHeight);

$(document).ready(function(){
$('.user-activate,.user-deactivate').click(function(e) {
e.preventDefault();
var userId = $(this).parent('.user-action').attr('id');
$('input[name=action]').val('activate_' + userId)
$('#people-form').submit();
})

$('.user-set-admin').click(function(e){
e.preventDefault();
var userId = $(this).parent('.user-action').attr('id');
$('input[name=action]').val('setadmin_' + userId)
$('#people-form').submit();
});

$('.user-unset-admin').click(function(e){
e.preventDefault();
var userId = $(this).parent('.user-action').attr('id');
$('input[name=action]').val('unsetadmin_' + userId)
$('#people-form').submit();
});

var hideUserAction = function(e) {
e.stopImmediatePropagation();
$('.user-action.active').animate({
top: '+'+$('.user-action').outerHeight()
}, 250)
.removeClass('active');
}
$('#people-form').click(hideUserAction);

$('.user-container').click(function(e) {
if ($(e.target).is('a')) return true;
hideUserAction(e);
var userAction = $(this).next('.user-action');
userAction.animate({
top: 0
}, 250)
.addClass('active');
});
});
</script>
