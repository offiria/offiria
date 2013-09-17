<?php
  /**
  * @version     1.0.0
  * @package     com_People
  * @copyright   Copyright (C) 2011. All rights reserved.
  * @license     GNU General Public License version 2 or later; see LICENSE.txt
  * @author      Created by com_combuilder - http://www.notwebdesign.com
  */

// no direct access
defined('_JEXEC') or die;
jimport('joomla.html.pagination');
?>
<h1 class="hidden-phone"><?php echo JText::_('COM_PEOPLE_LABEL_YOUR_MEMBERS');?></h1>

<?php
$lview			= JRequest::getVar('lview', 'grid');
$namefilter 	= JRequest::getVar('namefilter', 'all');
$departmentId 	= JRequest::getVar('department_id');
$positionId 	= JRequest::getVar('position_id');
$get 			= JRequest::get('GET');
$Category 		= new StreamCategory();
$departments 	= $Category->getByCategory('department');
$positions 		= $Category->getByCategory('position');
$my 			= JXFactory::getUser();
// Need to get rid of 'limitstart' since we should reset the limit when
// switching to a different view
if(isset($get['limitstart'])) unset($get['limitstart']);
if(isset($get['start'])) unset($get['start']);
?>
<ul class="nav nav-pills filter">
	<?php 
	// Positions
	if (!empty($positions)): ?>
	<li class="dropdown <?php echo ($positionId) ? 'active': ''; ?>">
		<?php if ($positionId): ?>
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			<?php echo JText::sprintf('COM_STREAM_LABEL_FILTER_BY_POSITION_PROVIDED', $Category->getCategoryName($positionId)); ?><b class="caret"></b></a>
		<?php else: ?>
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo JText::_('COM_PEOPLE_FILTER_BY_POSITION'); ?> <b class="caret"></b></a>
		<?php endif; ?>
		<ul class="dropdown-menu">
			<li>
			<a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('position_id' => '' )) ) ); ?>">
			<?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_ALL'); ?>
			</a>
			</li>
			<li class="divider"></li>
			<?php foreach($positions as $pos): ?>
				<li <?php if($pos->id == $positionId) echo 'class="active"'; ?>>
				<a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('position_id' => $pos->id )) ) ); ?>">
				&diams;&nbsp;<?php echo $pos->category; ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</li>
	<?php endif;
	
	// Departments
	if (!empty($departments)): ?>
	<li class="dropdown <?php echo ($departmentId) ? 'active': ''; ?>">
		<?php if ($departmentId): ?>
			<a class="dropdown-toggle" data-toggle="dropdown" href="#">
			<?php echo JText::sprintf('COM_STREAM_LABEL_FILTER_BY_CATEGORY_PROVIDED', $Category->getCategoryName($departmentId)); ?><b class="caret"></b></a>
		<?php else: ?>
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo JText::_('COM_PEOPLE_FILTER_BY_DEPARTMENT'); ?> <b class="caret"></b></a>
		<?php endif; ?>
		<ul class="dropdown-menu">
			<li>
			<a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('department_id' => '' )) ) ); ?>">
			<?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_ALL'); ?>
			</a>
			</li>
			<li class="divider"></li>
			<?php foreach($departments as $dept): ?>
				<li <?php if($dept->id == $departmentId) echo 'class="active"'; ?>>
				<a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('department_id' => $dept->id )) ) ); ?>">
				&diams;&nbsp;<?php echo $dept->category; ?></a>
				</li>
			<?php endforeach; ?>
		</ul>
	</li>
	<?php endif; ?>
	
	<?php
	// Toggle grid/list
	?>
	<li style="float:right" class="switch pull-right">
		<div class="btn-group">
			<a id="list" class="btn btn-small <?php echo ($lview == 'list') ? 'active': ''; ?>" href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('lview' => 'list' )) ) ); ?>"><span></span></a>
			<a id="grid" class="btn btn-small <?php echo ($lview == 'grid') ? 'active': ''; ?>" href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('lview' => 'grid' )) ) ); ?>"><span></span></a>
		</div>
	</li>	
</ul>

<form method="post" id="people-form" action="<?php echo JRoute::_('index.php?option=com_people&view=members&lview='.$lview) ?>">
<?php if ($lview == 'list') { 
	// listview
	?>
	<table class="table table-bordered table-striped table-novborder">
		<thead>
			<tr>
				<th>#</th>
				<th><?php echo JText::_('COM_PEOPLE_LABEL_NAME');?></th>
				<th><?php echo JText::_('COM_PEOPLE_LABEL_EMAIL');?></th>
				<th><?php echo JText::_('COM_PEOPLE_LABEL_DATE_JOINED');?></th>
				<?php if ($my->isAdmin()): ?>
				<th><?php echo JText::_('COM_PEOPLE_LABEL_ACTION');?></th>
				<?php endif; ?>
			</tr>
		</thead>

		<tbody class="user-container">
			<?php
			$i = 1;
			foreach($this->members as $row):
				$user = JXFactory::getUser($row->id);
			?>
			<tr>
				<td><?php echo $i; ?></td>
				<td class="user-content">
					<div class="user-avatar" style="float:left;margin-right:8px">
						<a href="<?php echo $user->getURL();?>">
							<img class="cAvatar" border="0" author="85" alt="" src="<?php echo $user->getThumbAvatarURL(); ?>">
						</a>
					</div>
					<div class="user-name" style="float:left;margin-left:4px">
						<?php 
						echo '<a href="' . JRoute::_('index.php?option=com_profile&view=display&user=' . $row->username) . '"><b>' . $row->name . '</b></a>'; 
						if($row->isAdmin()) {
							$classes = 'label-warning';
							echo ' <span class="label label-warning">Admin</span><br/>';
						} else {
							$classes = 'label-important';
						} ?>
						<em>@<?php echo $row->username; ?></em><br />
						<?php if($row->block==1){ echo ' <span class="label ' . $classes . '">'. JText::_('COM_PEOPLE_LABEL_DEACTIVATED') . '</span>';}?><br/>
					</div>
				</td>
				<td><?php echo $row->email; ?></td>
				<td><?php echo JXDate::formatDate($row->registerDate, JXDate::SHORT_DATE_FORMAT); ?></td>
				<?php if ($my->isAdmin()): ?>
					<td id="<?php echo $row->id; ?>" class="user-action"> <!--input class="action-user-select" id="<?php echo $row->id; ?>" type="checkbox"/--> 
					<?php if($row->block==1): ?>
						<button class="btn btn-success user-activate tips" title="<?php echo JText::_('COM_PEOPLE_LABEL_ACTIVATE'); ?>"></button>
					<?php else: ?>
						<button class="btn btn-danger user-deactivate tips" title="<?php echo JText::_('COM_PEOPLE_LABEL_DEACTIVATE'); ?>"></button>
						<?php if($row->isAdmin()): ?>
							<button class="btn user-admin user-unset-admin tips" title="<?php echo JText::_('COM_PEOPLE_LABEL_NORMAL'); ?>"></button>
						<?php else: ?>
							<button class="btn btn-warning user-admin user-set-admin tips" title="<?php echo JText::_('COM_PEOPLE_LABEL_SET_AS_ADMIN'); ?>"></button>
						<?php endif; ?>
					<?php endif; ?>
					</td >				
				<?php endif; ?>			
			</tr>
			<?php
				$i++;
				endforeach;
			?>
		</tbody>
	</table>	
<?php } else {
	// default: grid view ?>
<div class="people-management">
	<ul class="clearfix">
			<?php
			foreach($this->members as $row):
				$user = JXFactory::getUser($row->id);
				if ($user->lastvisitDate != '0000-00-00 00:00:00') {
					$lastvisitDate = new JDate($user->lastvisitDate);
					$lvDate = JText::_('COM_PEOPLE_LABEL_VISITED') . ": " . $lastvisitDate->format( JText::_('JXLIB_DATE_FORMAT'));
				} else {
					$lvDate = JText::_('COM_PEOPLE_LABEL_NOT_VISITED');
				}
			?>
			<li>
				<div class="user-container <?php echo ($my->isAdmin()) ? 'admin' : ''; ?> tips" title="<?php echo $lvDate; ?>">
					<div class="user-avatar">
						<a href="<?php echo $user->getURL();?>">
							<img class="cAvatar" border="0" author="85" alt="" src="<?php echo $user->getThumbAvatarURL(); ?>">
						</a>
					</div>
				
					<div class="user-content">
						<div class="user-name">
							<?php 
							  echo '<a href="' . JRoute::_('index.php?option=com_profile&view=display&user=' . $row->username) . '">' . $row->name . '</a> <em>@' . $row->username . '</em>'; 
							  // different label color for deactivated admin
							  $classes = ($row->isAdmin()) ? 'label-warning' : 'label-important'; 
							?>
							<?php if ($row->isAdmin() && $row->block == 0) : ?>
							<span class="label label-warning"><?php echo JText::_('COM_PEOPLE_LABEL_ADMIN'); ?></span>
							<?php endif; ?>
							<?php if ($row->block == 1): ?>
							<span class="label <?php echo $classes; ?>"><?php echo JText::_('COM_PEOPLE_LABEL_DEACTIVATED'); ?></span>
							<?php endif; ?>
							<div class="clear"></div>
						</div>
						
						<span class="user-email">
							<?php echo $row->email; ?>
						</span>
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
						<button class="btn btn-warning user-admin user-set-admin"><?php echo JText::_('COM_PEOPLE_LABEL_SET_AS_ADMIN'); ?></button>
						<?php endif; ?>
						<?php endif; ?>
						
						<span class="remove">&times;</span>
					</div>
				<?php endif; ?>
			</li>
			<?php
				endforeach;
			?>
		</ul>
	</div>
<?php } ?>	
	<input type="hidden" name="action" value=""/>
</form>

<?php if (count($this->members) == 0) {?>
<div class="alert-message block-message info alert-empty-stream">   
	<p><?php echo JText::_('COM_PEOPLE_LABEL_NO_MEMBERS');?></p>        
</div>
<?php }	?>

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
