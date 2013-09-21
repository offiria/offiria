<?php

/**
 * @package		JomSocial
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

$my = JXFactory::getUser();
$user = JXFactory::getUser($stream->user_id);
$data = json_decode($stream->raw);
?>
<li id="message_<?php echo $stream->id; ?>" class="message-item type_<?php echo $stream->type; ?>">
	
	<div class="message-avatar">
		<a href="<?php echo $user->getURL();?>">
			<img class="cAvatar"border="0" author="85" alt="" src="<?php echo $user->getThumbAvatarURL(); ?>">
		</a>
	</div><!-- Added - 17JAN2012 -->

 	<div class="message-content">
		<div class="message-content-top">
			<div class="message-meta-top">
				<div class="message-content-actor">
					<strong>
						<a href="<?php echo $user->getURL();?>" class="actor-link"><?php echo $this->escape($user->name); ?></a>
					</strong>
					<?php if ( !empty( $stream->group_id ) ) {
						$group	= $stream->getGroup();
						echo ' <span class="message-in-groups">';
						if($group->access)
							echo '<i class="icon-lock"></i> ';
						echo '<a href="'.  StreamMessage::getGroupUri( $group->id ).'">'. $this->escape($group->name). '</a></span>'; 
					} ?>
				</div>
     
				<div class="message-content-text">
					<?php echo StreamMessage::format($stream->message); ?>
				</div>
		
				<?php echo StreamMessage::getAttachmentHTML($stream); ?>

				<?php echo StreamTag::getTagsHTML($stream); ?>

				<div class="message-content-topic topic-container-parent" style="display: none;">
					<ul class="topic-container">
						<?php
						$topics = array();
						if ( strlen( $stream->topics ) > 0 ) {
							$topics = explode(',', $stream->topics);
						}

						foreach ( $topics as $topic ) {
							?>
							<li class="stream-topics-element">
								<a href="#"><?php echo $topic; ?></a>
								<span class="stream-topics-close">x</span>
							</li>
							<?php } ?>
					</ul>

					<?php if (isset($topics)): ?>
					<div>
						<input type="text" class="topic-input" />
						<input type="button" class="topic-add stream-form-topic-add" name="" value="Add Topic" />
					</div>
					<a href="javascript:void(0)" class="message-topic-edit topic-edit-change"><?php echo JText::_('COM_STREAM_LABEL_EDIT');?></a>
					<?php endif; ?>
				</div>

			</div>
			
			<div class="clear"></div><!-- Added - 17JAN2012 -->
			
			<div class="message-meta small">
				<?php 
				$date = new JDate( $stream->created );?>
				<a class="meta-date" href="<?php echo $stream->getUri();;?>"><?php echo JXDate::formatLapse( $date ); ?></a>
				&bull; <a class="meta-like" href="#<?php echo (!$stream->isLike($my->id))? '' : 'un'; ?>like"><?php echo (!$stream->isLike($my->id)) ? JText::_('COM_STREAM_LIKE_LABEL') : JText::_('COM_STREAM_UNLIKE_LABEL'); ?></a>
				&bull; <a class="meta-comment" href="#comment"><?php echo JText::_('COM_STREAM_LABEL_COMMENT');?></a>
				<?php
				if( $my->authorise('stream.message.edit', $stream) ){ ?>
				&bull; <a class="meta-edit" href="#edit"><?php echo JText::_('COM_STREAM_LABEL_EDIT');?></a>
				<?php
				}
				?>
				
				<div class="clear"></div>
			</div><!-- Added - 17JAN2012 -->
				
			<div class="clear"></div>
		</div>

		<div class="message-content-bottom">
			<?php echo $comment; ?>
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
		
	<?php	// You can only delete your own message
	if( $my->authorise('stream.message.edit', $stream) ) {
		?>
		<div class="message-remove">
			<a href="javascript:void(0);" class="remove" original-title="Delete">Delete</a>
		</div>
		<?php } ?>
	<div class="clear"></div>
</li>
