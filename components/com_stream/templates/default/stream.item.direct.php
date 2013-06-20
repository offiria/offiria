<?php
defined('_JEXEC') or die('Restricted access');

$my = JXFactory::getUser();
$user = JXFactory::getUser($stream->user_id);
$data = json_decode($stream->raw);
$date = new JDate( $stream->created );

$members = array();
if (!empty($stream->group_id)) {
	$group = $stream->getGroup();

	// Message sent to
	$memberIds = $group->members;
	$memberIds = explode(',', trim($memberIds, ','));
	$memberCount = JXUtility::csvCount($group->members);

	if($memberCount > 1) {
		foreach ($memberIds as $id) {
			if (intval($id) > 0 && $id != $group->creator) {
				$member = JXFactory::getUser($id);
				$members[] = '<a href="' . $member->getURL() . '">' . $this->escape($member->name) . '</a>';
			}
		}
	} else {
		$members[] = 'Yourself';
	}
}
?>

<li class="direct-list-item">
	<div class="direct-avatar">
		<a href="<?php echo $user->getURL();?>">
			<img class="cAvatar"border="0" author="85" alt="" src="<?php echo $user->getThumbAvatarURL(); ?>">
		</a>
	</div>
	<div class="direct-content">
		<div class="direct-content-to">
			<a href="<?php echo $user->getURL();?>" class="actor-link"><?php echo $this->escape($user->name); ?></a> <span style="font-size: 16px;">&nbsp;&#8250;&nbsp;</span>
			<?php echo implode($members, ', '); ?>
		</div>
		<div class="direct-content-message">
			<div class="autoShorten">
				<?php echo StreamMessage::format($stream->message); ?>
			</div>
			<div class="clear"></div>
			<div class="message-content">
			<?php echo StreamMessage::getAttachmentHTML($stream); ?>
			</div>
		</div>
	</div>
	<div class="direct-date">
		<?php echo JXDate::formatLapse($date); ?>
	</div>
	<div class="clear"></div>
	<div>
		<ul class="replies">
			<li id="message_<?php echo $stream->id; ?>" class="message-item type_<?php echo $stream->type; ?>">
				<div class="message-meta small" style="display: block;">
					<!--<a class="meta-like" href="#<?php echo (!$stream->isLike($my->id))? '' : 'un'; ?>like"><?php echo (!$stream->isLike($my->id)) ? JText::_('COM_STREAM_LIKE_LABEL') : JText::_('COM_STREAM_UNLIKE_LABEL'); ?></a>
							-->
					<a class="meta-comment" href="#comment" style="display: none;">Reply</a>
					<?php
					// TODO: disabled until edit callback output fixed
					if( $my->authorise('stream.message.edit', $stream) && false){ ?>
						• <a class="meta-edit" href="#edit">Edit</a>
						• <a class="meta-edit" href="#remove">Delete</a>
						<?php
					}
					?>
					<div class="clear"></div>
				</div>

				<div class="message-content-bottom">
					<?php echo StreamComment::getCommentSummaryHTML($stream); ?>
				</div>
			</li>
		</ul>
	</div>
</li>

<script type="text/javascript">
	function highlight(elemId){
		var elem = $(elemId);
		//elem.addClass('highlighted');
		elem.css('background-color', '#ffffaa');
		elem.animate({ backgroundColor: '#FFFFFF' }, 4000);
	}

	if (document.location.hash) {
		highlight(document.location.hash);
	}
</script>

