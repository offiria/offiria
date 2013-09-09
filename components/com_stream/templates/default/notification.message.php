<?php 
$date = new JDate( $stream->created );
$user = JXFactory::getUser( $stream->user_id );
?>
<li class="<?php echo ($stream->group_id) ? 'notification-group' : 'notification-public' ?>">
	<?php echo (JHtmlString::truncate($stream->message, 40)); ?><br/>
	<span class="hint small"><?php echo StreamTemplate::escape($user->name); ?>, <a href="<?php echo $stream->getUri();?>"><?php echo JXDate::formatLapse( $date ); ?></a></span>
	<div class="clear"></div>
</li>