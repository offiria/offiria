<?php if($group->isMember($my->id) || $my->isAdmin()) {
	if($group->archived){ ?>
		<div class="alert-message block-message warning group-filter">       
		<p>This group has been archived. You can no longer leave a message in this group. An achived group simply mean that this group is no longer active and has either completed its objectives</p>        
		</div>
	<?php } else { 
		
		// Allow only group members to see the stream.post box
		$userGroups = $my->getParam('groups_member');
		if (JXUtility::csvExist($userGroups, $group->id))
		{
			if (JRequest::getVar('template') != 'mobile') {
				$tmpl = new StreamTemplate();
				echo $tmpl->set('title', "title" )->set('group_id', $group->id)->fetch('stream.post');
			}
		}
	}
 } 
 ?>
<script type="text/javascript">

$(function() {
	/* Follow group */	
	$('a[href="#followGroup"]').click(function() {
		$.ajax({
			type: "POST",
			url: S.path['group.follow'],
			data: $('form[name="group-actions"]').serialize(),
			dataType: 'json',
			cache: false,
			success: function(data){
				// @todo: more awesome join effect
				window.location = data.redirect;
			}
		});
			
		return false;
	});
	
	/* Unfollow group */	
	$('a[href="#unfollowGroup"]').click(function() {
		$.ajax({
			type: "POST",
			url: S.path['group.unfollow'],
			data: $('form[name="group-actions"]').serialize(),
			dataType: 'json',
			cache: false,
			success: function(data){
				// @todo: more awesome join effect
				window.location = data.redirect;
			}
		});
			
		return false;
	});
	
	/* join group */
	$('a[href="#joinGroup"]').click(function() {
		$.ajax({
			type: "POST",
			url: S.path['group.join'],
			data: $('form[name="group-actions"]').serialize(),
			dataType: 'json',
			cache: false,
			success: function(data){
				window.location = data.redirect;
			}
		});
			
		return false;
	});
	
	/* leave group */
	$('a[href="#leaveGroup"]').click(function() {
		$.ajax({
			type: "POST",
			url: S.path['group.leave'],
			data: $('form[name="group-actions"]').serialize(),
			dataType: 'json',
			cache: false,
			success: function(data){
				window.location = data.redirect;
			}
		});
			
		return false;
	});
	
	/* delete group */
});	
</script>