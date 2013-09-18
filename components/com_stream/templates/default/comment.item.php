<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');
$my = JXFactory::getUser();
$user = JXFactory::getUser($comment->user_id);
$commentDate = new JDate($comment->created);
$lastCommentView = $my->getParam('group_'. $comment->group_id.'_comment');
$commentLikeCount = $comment->getLikeCount();
?>

<div id="comment-<?php echo $comment->id; ?>" class="comment-item">
	<a href="<?php echo $user->getURL();?>">
		<span class="comment-avatar-container"><img class="comment-avatar" alt="" src="<?php echo $user->getThumbAvatarURL(); ?>"></span>
	</a>
	<?php if($comment->id > $lastCommentView && $comment->user_id != $my->id){ echo '<span class="label">'. JText::_('COM_STREAM_LABEL_NEW') .'</span>'; $my->setParam('group_'. $comment->group_id.'_comment', $comment->id); $my->save(); } ?>
	<?php if($comment->id > $lastCommentView ){ $my->setParam('group_'. $comment->group_id.'_comment', $comment->id); $my->save(); } ?>
	<a href="<?php echo $user->getURL();?>"><?php echo $this->escape($user->name); ?></a> <?php echo StreamMessage::format($comment->comment); ?>
	<div class="comment-meta">
		<span><?php echo JXDate::formatLapse($commentDate); ?></span>
		<span style="<?php if($commentLikeCount <= 0) echo 'display: none;'; ?>">• <span class="comment-like"><?php echo $commentLikeCount; ?></span></span>
		<span class="comment-option" style="display:none;">
			<span>• <a href="#comment<?php echo (!$comment->isLike($my->id))? '' : 'un'; ?>like"><?php echo (!$comment->isLike($my->id)) ? JText::_('COM_STREAM_LIKE_LABEL') : JText::_('COM_STREAM_UNLIKE_LABEL'); ?></a> </span>
			<span style="<?php echo $my->authorise('stream.comment.delete', $comment) ? '' : 'display: none;' ; ?>">
				• <a href="#delete"><?php echo JText::_('COM_STREAM_LABEL_REMOVE');?></a>
			</span>
		</span>
	</div>
</div>