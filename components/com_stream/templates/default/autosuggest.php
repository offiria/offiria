<ul>
	<?php
	foreach($users as $user){
		$name = $user->name;
		//@todo: highlight the search text
	?>
	<li class="eList" onclick="S.suggest.add('<?php echo StreamTemplate::escape($user->username) ;?> ');"><img src="<?php echo $user->getThumbAvatarURL();?>"><span><span class="eList-Bold"></span><?php echo StreamTemplate::escape($name) ;?></span></li>
	<?php } ?>
	<!--
	<li class="eList" onclick="S.suggest.add('joe');"><img src=""><span><span class="eList-Bold">Jo</span>hn Doe</span></li>
	-->
</ul>