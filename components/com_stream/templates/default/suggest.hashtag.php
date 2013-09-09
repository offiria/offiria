<ul>
	<?php
	foreach($hashtags as $tag){
		$name = $tag;
		//@todo: highlight the search text
	?>
	<li class="eList" onclick="S.suggest.add('<?php echo StreamTemplate::escape($tag) ;?> ');"><span><span class="eList-Bold">#</span><?php echo StreamTemplate::escape($name) ;?></span></li>
	<?php } ?>
</ul>