<script type="text/javascript">

	$(function() {
		$(".searchMembers").keypress( function(event) {

			if(!S.suggestMember.active){
				S.suggestMember.start('member');
			} else {
				S.suggestMember.update(event);
			}
		});

		$('#addGroupMember').click(function() {
			$('#addGroupMemberField').toggle();
		});
	});

</script>
<?php $streamTemplate = new StreamTemplate();?>
<div class="groupMember" id="groups_<?php echo $group->id; ?>">

	<?php if($my->authorise('stream.group.edit', $group)): ?>

	<?php /* <div class="btn" id="addGroupMember"><i class="icon-plus"></i>Add People</div> 
	<div class="clear"></div>*/?>
	
	<div id="addGroupMemberField">
		<form action="" class="well form-search">
			<input type="text" class="searchMembers" placeholder="<?php echo JText::_('COM_STREAM_GROUP_SEARCH_MEMBERS');?>"/>
			<div class="stream-post-suggest" style="display: none"></div>
		</form>
		<div class="clear"></div>
	</div>

	<?php endif; ?>

	<!--<span class="older-stream-separator" id="updated-stream-separator"><span>List Of Members</span></span>-->

	<ul class="groupMemberList">
	 <li id="updated-stream-separator" class="older-stream-separator newGroupMember-separator" style="display: none"><span><?php echo JText::_('COM_STREAM_LABEL_NEW_GROUP_MEMBERS'); ?></span></li>
		 <li id="updated-stream-separator" class="older-stream-separator"><span><?php echo JText::_('COM_STREAM_LABEL_GROUP_MEMBERS'); ?></span></li>
		<?php foreach($members as $user){ ?>
		<li>
			<?php $streamTemplate->set('group', $group)->set('my', $my)->set('user', $user)->load('group.members.list.add');?>
		</li>
		<?php } ?>
	</ul>
	
	<div class="clear"></div>
</div>
<div class="pagination">
	<?php echo $pagination->getPagesLinks(); ?>
</div>
