<script type="text/javascript">
	$(document).ready(function() {
		$(document).bind('click', function(e){
			var triggeredObj = (e.target) ? e.target : e.eventSource;
			if ($(triggeredObj).hasClass('list-option') || // click on UL.list-option
					$(triggeredObj).parent('ul').hasClass('list-option')) // click on LI item in UL.list-option
			{
				return false; // do nothing if click on group option
			}
			else if ($(triggeredObj).hasClass('toggle'))
			{
				var show = ($(triggeredObj).hasClass('toggle-a')) ? false : true;
			} 
			
			// hide all group options
			$("ul.list-option").hide();
			$(".toggle").removeClass("toggle-a");

			// show group option if click on toggle with options hidden
			if (show)
			{
				$(triggeredObj).siblings("ul.list-option").show();
				$(triggeredObj).addClass("toggle-a");
			}
		});
	});
	
	$(function() {
	
		$('.group-follow').click(function() {
			var pdata = 'group_id='+$(this).siblings('input[name="group_id"]').val();
			var eventSrc = $(this);
			if ($(this).hasClass('selected')) /* Unfollow group */	
			{
				$.ajax({
					type: "POST",
					url: S.path['group.unfollow'],
					data: pdata,
					dataType: 'json',
					cache: false,
					success: function(data){
						eventSrc.toggleClass("selected");
					}
				});
			}
			else /* Follow group */	
			{
				$.ajax({
					type: "POST",
					url: S.path['group.follow'],
					data: pdata,
					dataType: 'json',
					cache: false,
					success: function(data){
						eventSrc.toggleClass("selected");
					}
				});
			}

			return false;
		});

		
		$('.group-join').click(function() {
			var pdata = 'group_id='+$(this).siblings('input[name="group_id"]').val();
			var eventSrc = $(this);
			if ($(this).hasClass('selected')) /* leave group */
			{
				$.ajax({
					type: "POST",
					url: S.path['group.leave'],
					data: pdata,
					dataType: 'json',
					cache: false,
					success: function(data){
						eventSrc.toggleClass("selected");
						eventSrc.siblings('.group-follow').removeClass('selected');
					}
				});
			}
			else /* join group */
			{
				$.ajax({
					type: "POST",
					url: S.path['group.join'],
					data: pdata,
					dataType: 'json',
					cache: false,
					success: function(data){
						eventSrc.toggleClass("selected");
						eventSrc.siblings('.group-follow').addClass('selected');
					}
				});			
			}

			return false;
		});
	});	
</script>

<div class="blue-button">
	<a class="btn btn-info" href="#createGroup" onclick="S.groups.create(this);return false;">
		<i class="icon-plus icon-white"></i>New Group
	</a>
</div>

<!--<div class="blue-button">
	<form name="group-actions">
		<a class="btn btn-info" onclick="S.groups.create(this);return false;" href="#createGroup"><?php /*echo JText::_('COM_STREAM_LABEL_CREATE_A_GROUP');*/?></a>
	</form>
</div>-->

<?php 
	$i			= 1; 
	$my			= JXFactory::getUser();
	$Category = new StreamCategory();

	foreach($groups as $group):
		$user = JXFactory::getUser($group->creator); 
		$members	= explode(',',$group->get('members'));
		$followers	= explode(',',$group->get('followers'));
		$categoryName = $Category->getCategoryName($group->category_id);
		// set default group name
		$categoryName = ($categoryName) ? $categoryName : JText::_('COM_STREAM_LABEL_GROUP_DEFAULT_NAME');
		$isFilteredByCategory = (JRequest::getVar('filter') == 'category');

?>

<div class="group-list">
	
	<div class="config-list-option">
		<span class="toggle"><?php echo JText::_('COM_STREAM_LABEL_TOGGLE');?></span>
		<ul class="list-option" style="display:none;">
			<li class="group-join <?php echo (in_array($my->id, $members)) ? 'selected' : '';?>"><?php echo JText::_('COM_STREAM_LABEL_JOIN');?></li>
			<li class="group-follow <?php echo (in_array($my->id, $followers)) ? 'selected' : '';?>"><?php echo JText::_('COM_STREAM_LABEL_FOLLOW');?></li>
			<input type="hidden" value="<?php echo $group->id;?>" name="group_id" />
		</ul>
	</div>

	<div class="group-list-title">
		<?php if (!$isFilteredByCategory): ?>
		<?php if ($Category->getCategoryName($group->category_id)): ?>
		<a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups&filter=category&category_id='.$group->category_id); ?>"><?php echo $categoryName; ?></a>
		<?php else: ?>
		<?php echo $categoryName; ?></a>
		<?php endif;?>
		<span class="message-in-groups">
			<a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='.$group->id); ?>"><?php echo $this->escape($group->name); ?></a>
		</span>
		<?php else: ?>
			<a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='.$group->id); ?>"><?php echo $this->escape($group->name); ?></a>
		<?php endif; ?>
	</div>
	<div class="group-list-desc">
		<?php echo StreamTemplate::escape($group->description); ?>
	</div>
	<div class="group-list-content">
		<div class="user-horizontal-list">
			<label class="bold">Members:</label>
			<?php
			foreach($members as $row){
				$user = JXFactory::getUser($row);
			?>
			<span class="user-list">
				<a href="<?php echo $user->getURL(); ?>">
				<img title="<?php echo $this->escape($user->name); ?>" src="<?php echo $user->getThumbAvatarURL(); ?>" class="tips">
				</a>
			</span>
			<?php } ?>
			<div class="clear"></div>
		</div>
		<div class="group-list-creator small">
			<?php $creator = JXFactory::getUser($group->creator); ?> 
			<?php echo JText::_('COM_STREAM_LABEL_CREATED_BY');?>&nbsp;<a href="<?php echo $creator->getURL(); ?>"><?php echo $this->escape($creator->name); ?></a>
		</div>
	</div>


</div>

<?php 
	$i++; 
	endforeach;
?>


<?php if (empty($groups)) { ?>
<div class="alert-message block-message info alert-empty-stream">
	<p><?php echo JText::_('COM_STREAM_LABEL_NO_NEW_GROUP');?></p>
</div>
<?php } ?>

<div class="pagination">
<?php echo $pagination->getPagesLinks(); ?>
</div>