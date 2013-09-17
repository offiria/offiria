<?php
$my   = JXFactory::getUser();
$user = JXFactory::getUser($stream->user_id);
$data = json_decode($stream->raw);
$date = new JDate( $stream->created );
?>
<li id="message_<?php echo $stream->id; ?>" class="message-item type_event">

	<div class="message-avatar">
		<a href="<?php echo $user->getURL();?>">
			<img class="cAvatar" border="0" author="85" alt="" src="<?php echo $user->getThumbAvatarURL(); ?>">
		</a>
	</div>			
	<div class="message-content message-content-milestone">
		<span class="label-compact label-milestone"><?php echo JText::_('COM_STREAM_LABEL_MILESTONE');?></span>
		
		<div class="message-content-compact milestone-compact">
			
			<div class="meta-compact">
				
				<span class="meta-title-compact">
					<?php if ($stream->created == $stream->updated ) { ?>
					<?php echo StreamTemplate::escape($user->name); ?> <?php echo JText::_('COM_STREAM_LABEL_CREATED_MILESTONE');?>: 
					<?php } else { ?>
					<?php echo JText::_('COM_STREAM_LABEL_MILESTONE_UPDATED');?> - 
					<?php } ?>
					<a href="<?php echo $stream->getUri();; ?>"><?php echo StreamTemplate::escape($stream->message); ?></a>			
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
				• <a class="meta-comment" href="#comment"><?php echo JText::_('COM_STREAM_LABEL_COMMENT1');?></a>
				<?php
				if( $my->authorise('stream.message.edit', $stream) ){ ?>
					• <a class="meta-edit" href="#edit"><?php echo JText::_('COM_STREAM_LABEL_EDIT');?></a>
					<?php
				}
				?>
			
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
		</div>
	</div>
	<?
	if(!empty($data->pinned)) :
		$tmpDate = date_parse($stream->created);
		$start = strtotime("now");
		if ($data->pinned == "1 month") {
			$end = strtotime("+1 month", mktime($tmpDate["hour"],$tmpDate["minute"],$tmpDate["second"],$tmpDate["month"],$tmpDate["day"], $tmpDate["year"]));	
		} elseif ($data->pinned == "1 week") {
			$end = strtotime("+1 week", mktime($tmpDate["hour"],$tmpDate["minute"],$tmpDate["second"],$tmpDate["month"],$tmpDate["day"], $tmpDate["year"]));	
		} else {
			$end = strtotime("+1 day", mktime($tmpDate["hour"],$tmpDate["minute"],$tmpDate["second"],$tmpDate["month"],$tmpDate["day"], $tmpDate["year"]));	
		}
		$seconds_diff = $end - $start;
		
		if (floor($seconds_diff/3600/24) >= 0) :
		?>
			<div class="pinned-message tips" title="<?php echo sprintf(JText::_('COM_STREAM_LABEL_PINNED_TIP'),floor($seconds_diff/3600/24)); ?>"></div>
	<?php endif;
	endif; ?>	
	
	<?php
	// You can only delete your own message
	if( $my->authorise('stream.message.edit', $stream) ) {
	?>
	<div class="message-remove">
		<a href="javascript:void(0);" class="remove" original-title="Delete"><?php echo JText::_('COM_STREAM_LABEL_DELETE');?></a>
	</div>
	<?php } ?>
	<div class="clear"></div>
</li>