<?php
$my = JXFactory::getUser();
$user = JXFactory::getUser($stream->user_id);
$data = json_decode($stream->raw);
$date = new JDate($stream->created);

$commentCount = $stream->getParam('comment_count', null);

// TODO: method
if($commentCount) {
	$model = StreamFactory::getModel('stream');
	$recentComments = $model->getComments(array('message_id' => $stream->id, 'order_by_desc' => 'id'), 1);
	$recentPosterId = $recentComments[0]->user_id;
	$recentMessage = $recentComments[0]->comment;
	$messageCreated = $recentComments[0]->created;
	$recentMessageElId = '#comment-' . $recentComments[0]->id; // used for message highlighting
} else {
	$recentPosterId = $stream->user_id;
	$recentMessage = $stream->message;
	$messageCreated = $stream->created;
	$recentMessageElId = ''; // doesn't highlight first message
}

$messageURL = JRoute::_('index.php?option=com_stream&view=direct&message_id=' . $stream->id) . $recentMessageElId;;

$members = array();
if (!empty($stream->group_id)) {
	$group = $stream->getGroup();

	// Message sent to
	$memberIds = $group->members;
	$memberIds = explode(',', trim($memberIds, ','));

	// Message sent to
	$memberIds = $group->members;
	$memberIds = explode(',', trim($memberIds, ','));
	$memberCount = JXUtility::csvCount($group->members);

	if($memberCount > 1) {
		foreach ($memberIds as $id) {
			if (intval($id) > 0 && $id != $my->id) {
				$member = JXFactory::getUser($id);
				$members[] = '<a href="' . $member->getURL() . '">' . $this->escape($member->name) . '</a>';
			}
		}
	} else {
		$members[] = 'yourself';
	}
}

$recentPoster = JXFactory::getUser($recentPosterId);
?>

<li id="message_<?php echo $stream->id; ?>" class="message-item compact-item direct-item">
	<div class="message-avatar-compact">
		<a href="<?php echo $recentPoster->getURL(); ?>">
			<img class="cAvatar"border="0" author="85" alt="" src="<?php echo $recentPoster->getThumbAvatarURL(); ?>">
		</a>
	</div>
	<?php /* <span class="label-compact label-blog" style="background-color: #C43C35;">Message</span> */?>
	<div class="message-content-compact direct-compact">
		<div class="meta-compact">
			<span class="meta-title-compact">
				<?php if($recentPoster->id == $my->id) : ?>
				<span class="meta-direct-compact">
					You sent a private message to <?php echo implode($members, ', '); ?>
				</span>
				<div class="well"><a href="<?php echo $messageURL ?>"><?php echo $this->escape(JXString::truncateWords($recentMessage, 20)); ?></a></div>
				<?php else : ?>
				<span class="meta-direct-compact">
					Private message from <a href="<?php echo $recentPoster->getURL(); ?>"><?php echo $this->escape($recentPoster->name); ?></a>
				</span>
				<div class="well">
					<a href="<?php echo $messageURL; ?>"><?php echo $this->escape(JXString::truncateWords($recentMessage, 20)); ?></a>
				</div>
				<?php endif; ?>
			</span>
		
			<span class="meta-dateblog-compact">
				<?php $date = new JDate($messageCreated); ?>
				<a class="meta-date" href="<?php echo $messageURL; ?>"><?php echo JXDate::formatLapse( $date ); ?></a>
			</span>
		</div>
	</div>
	<div class="clear"></div>
</li>