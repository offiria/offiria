<?php
$my   = JXFactory::getUser();
$user = JXFactory::getUser($stream->user_id);
$data = json_decode($stream->raw);
$date = new JDate( $stream->created );
?>			
<li id="message_<?php echo $stream->id; ?>" class="message-item compact-item">

	<span class="label-compact label-milestone">Milestone</span>
	
	<div class="message-content-compact milestone-compact">
		
		<div class="meta-compact">
			
			<span class="meta-title-compact">
				<?php if ($stream->created == $stream->updated ) { ?>
				<?php echo StreamTemplate::escape($user->name); ?> created Milestone: 
				<?php } else { ?>
				Milestone updated - 
				<?php } ?>
				<a href="<?php echo $stream->getUri();; ?>">
					<?php echo StreamTemplate::escape($stream->message); ?>
				</a>			
			</span>

			<span class="meta-date-compact">
				-
				<?php 
				//$date = new JDate( $stream->created );
				$startDate = new JDate( $stream->start_date );
				?>
				<a class="meta-date" href="<?php echo $stream->getUri();;?>"><?php echo $startDate->format( JText::_('JXLIB_DATE_SHORT_FORMAT'));//JXDate::formatLapse( $date ); ?></a>
			</span>

		</div>
	
		<div class="message-meta small" style="display: none;">
			• <a class="meta-like" href="#<?php echo (!$stream->isLike($my->id))? '' : 'un'; ?>like"><?php echo (!$stream->isLike($my->id)) ? JText::_('COM_STREAM_LIKE_LABEL') : JText::_('COM_STREAM_UNLIKE_LABEL'); ?></a>
			• <a class="meta-comment" href="#comment">Comment</a>
			<?php
			if( $my->authorise('stream.message.edit', $stream) ){ ?>
				• <a class="meta-edit" href="#edit">Edit</a>
				<?php
			}
			?>
		
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
	
	<?php
	// You can only delete your own message
	if( $my->authorise('stream.message.edit', $stream) ) {
	?>
	<div class="message-remove">
		<a href="javascript:void(0);" class="remove" original-title="Delete">Delete</a>
	</div>
	<?php } ?>
	<div class="clear"></div>
</li>