<?php
/**
 * @package		Offiria
 * @subpackage	Core
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

$my = JXFactory::getUser();
$lastComment = $my->getParam('comment_last_read', 0);
$commentHTML = '';
$jconfig = new JConfig();
$task = JRequest::getVar('task', null);
// Tab index. Nasty!. We generate large enough number and the tab index will
// simply be large_number and large_number+1 to avoid collision
$tabIndex = rand ( 10000, 99000 );
?>
<div id="stream-cmt-<?php echo $stream_id; ?>" class="stream-comment">

	<?php if( $like_count > 0 ){ ?>
	<div class="stream-like" style="display: none;">
		<a href="#showLikes"><?php echo JText::sprintf('COM_STREAM_LABEL_NUM_PEOPLE_LIKES_THIS', $like_count); ?></a>
	</div>
	<?php } else {
	// Even when there are no like data, add the div structure since
	// it is needed when people actually 'like' something
	?>
	<div class="stream-like" style="display:none;"></div>
	<?php	} ?>

	<?php if($count > 0 ) {
	// If there are comment, we show all the comment since our last visit
	// We need to caculate it here since we need to know if we need to show
	// the show all link or not
	$tmpl = new StreamTemplate();
	$limit = 0;
	$shown = 0;	// number of comment actually shown
	$unread = 0;	// number of unread comments

	foreach($comment as $row){
		$lastCommentView = $my->getParam('group_'. $row->group_id.'_comment');

		if($row->id > $lastCommentView)
		{
			$unread++;
		}

		if($task == 'show') {
			$commentHTML .= $tmpl->set('comment', $row)->fetch('comment.item');
		}
		$limit++;
	}
	?>
	<?php if($count > 0 && $task != 'show' ) : ?>
			<div class="comment-more">
				<a href="#showallcomments" ><?php echo JText::sprintf('Show all <span class="wall-cmt-count">%1$s</span> replies', ($count > 1) ? $count : ''); ?></a>
				<?php if($unread > 0) : ?>
				<span style="vertical-align: inherit;" class="label"><?php echo $unread; ?> New</span>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php
	if($task == 'show') {
		echo $commentHTML;
	}
	?>
	<?php } ?>

	<div class="comment-form">
		<!-- post new comment form -->
		<form action="" style="display: none;">
			<textarea name="comment" rows="" cols="" style="resize:vertical;" tabindex="<?php echo $tabIndex; ?>" <?php if($task == 'show') { echo 'class="noautogrow" placeholder="type your message here..."'; } ?> ></textarea>
			<div class="comment-action clearfix">
				<ul>
					<?php
					if( !empty($jconfig->allow_anon) && false ){
						?>
						<li class="left">
							<label class="checkbox"><input type="checkbox" name="anon" value="1"><?php echo JText::_('COM_PROFILE_NOTIFICATION_LABEL_ANONYMOUS_COMMENT');?></label>
						</li>
					<?php } ?>

					<li class="right">
						<a href="#cancelPostinComment" class="cancel"><?php echo JText::_('COM_STREAM_LABEL_CANCEL');?></a>
						<button type="submit" class="submit btn btn-mini" tabindex="<?php echo ($tabIndex+1); ?>"><?php echo JText::_('Post Reply');?></button>
						<div class="clear"></div>
					</li>
				</ul>
			</div>
			<div class="clear"></div>
			<input type="hidden" name="stream_id" value="<?php echo $stream_id; ?>" />
		</form>
		<?php
		// If there are no comments, reply box should also start hidden
		?>
		<span class="comment-reply" style="display: <?php echo ($task == 'show') ? 'block' : 'none'; ?>">
		<a href="#reply"><?php echo JText::_('COM_STREAM_LABEL_REPLY');?></a>
	</span>
	</div>

	<div class="clear"></div>
</div>