<ul>
	<?php
	foreach($users as $user){
		$name = $user->name;
	?>
	<li class="eList" onclick="S.suggestMember.add(this, '<?php echo $user->id; ?>');"><img src="<?php echo $user->getThumbAvatarURL();?>"><span><span class="eList-Bold"></span><?php echo StreamTemplate::escape($name) ;?></span></li>
	<?php } ?>
</ul>