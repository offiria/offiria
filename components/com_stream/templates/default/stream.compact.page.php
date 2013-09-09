<?php
$my   = JXFactory::getUser();
$user = JXFactory::getUser($stream->user_id);
$data = json_decode($stream->raw);
$date = new JDate( $stream->created );
$userWhoViewed = $stream->whoMakesAction($stream->id, 'blog');

?>			
<li id="message_<?php echo $stream->id; ?>" class="message-item compact-item">
	<div class="message-avatar-compact">
		<a href="#">
			<img class="cAvatar"border="0" author="85" alt="" src="<?php echo $user->getThumbAvatarURL(); ?>">
		</a>
	</div>
	
	<span class="label-compact label-blog"><?php echo JText::_('COM_STREAM_LABEL_BLOG'); ?></span>
	
	<div class="message-content-compact blog-compact">
		
		<div class="meta-compact">
			<span class="meta-title-compact">
				<?php if ($stream->created == $stream->updated ) { ?>
				<?php echo StreamTemplate::escape($user->name); ?> <?php echo JText::_('COM_STREAM_BLOG_CREATE_NEW_BLOG'); ?>
          		<?php } else { ?>
				<?php echo JText::_('COM_STREAM_BLOG_POST_UPDATED'); ?>
				<?php } ?>
				<a href="<?php echo $stream->getUri();; ?>">
					<?php echo StreamTemplate::escape($data->title); ?>
				</a>
			</span>
		
			<span class="meta-dateblog-compact">
				<?php 
				$date = new JDate( $stream->created );?>
				<a class="meta-date" href="<?php echo $stream->getUri();;?>"><?php echo JXDate::formatLapse( $date ); ?></a>
			</span>

			<?php if ($userWhoViewed && count($userWhoViewed) > 0) : ?>
			<div class="user-horizontal-list">
				<div class="small">
				<?php echo JText::_('COM_STREAM_LABEL_READ_BY'); ?>
					<?php
					  $avatarListWhoMakeAction = '';
					  $avatarListWhoMakeAction .= '<a href="#showReaders" data-content="<ul>';
					  foreach($userWhoViewed as $user_id) {
						  // there will be 0 as user which in return will load current user
						  if ($user_id != 0 && $user_id != NULL) {
							  $user = JXFactory::getUser($user_id);
							  $avatarListWhoMakeAction .= StreamTemplate::escape('<li><a href="'.$user->getURL().'">'.$user->name.'</a></li>');
						  }
					  }
					  $avatarListWhoMakeAction .= '</ul>">';
					  $label = (count($userWhoViewed) > 1) ? JText::_('COM_STREAM_LABEL_USERS') : JText::_('COM_STREAM_LABEL_USER');
					  $avatarListWhoMakeAction .= count($userWhoViewed)." $label</a>";
					  echo $avatarListWhoMakeAction;
					?>
				</div>
			 </div>
			<?php endif; ?>
		</div>
		
		<div class="message-meta-compact" style="display: none;">
			
			<a class="meta-like" href="#<?php echo (!$stream->isLike($my->id))? '' : 'un'; ?>like"><?php echo (!$stream->isLike($my->id)) ? JText::_('COM_STREAM_LIKE_LABEL') : JText::_('COM_STREAM_UNLIKE_LABEL'); ?></a>
			<a class="meta-comment" href="#comment">Comment</a>
			<?php
			if( $my->authorise('stream.message.edit', $stream) ){ ?>
				<a class="meta-edit" href="#edit">Edit</a>
				<?php
			}
			?>
		
			<div class="clear"></div>
		</div>
	
		<div clas="clear"></div>
	</div>
	
	<div class="clear"></div>
</li>