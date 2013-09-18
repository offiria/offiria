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
// Tab index. Nasty!. We generate large enough number and the tab index will
// simply be large_number and large_number+1 to avoid collision
$tabIndex = rand ( 10000, 99000 );
?>
<div id="stream-cmt-<?php echo $stream_id; ?>" class="stream-comment">
	
	<?php if( $like_count > 0 ){ ?>
	<div class="stream-like">
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
		
		foreach($comment as $row){
			$lastCommentView = $my->getParam('group_'. $row->group_id.'_comment');	
			
			// Show at least, the last 2 comments
			// or all comments that are not yet viewed
			if($limit >= ( $count - 2) || ($row->id > $lastCommentView ) )
			{
				$commentHTML .= $tmpl->set('comment', $row)->fetch('comment.item');
				$shown++;
			}
			$limit++;
		}
		?>
	<?php if($count > 1 ) { ?>
		<?php if($count > $shown) {?>
		<div class="comment-more">
			<a href="#showallcomments" ><?php echo JText::sprintf('COM_STREAM_LABEL_SHOW_ALL_NUM_COMMENTS', $count); ?></a>
		</div>
		<?php } ?>
		<?php } ?>
		<?php
		echo $commentHTML;		
		?>
	<?php } ?>

	<div class="comment-form">
		<!-- post new comment form -->
		<form action="" style="display: none;">
			<textarea name="comment" rows="" cols="" style="resize:vertical;" tabindex="<?php echo $tabIndex; ?>"></textarea>
			<div class="comment-action clearfix">
				<ul>
					<?php
					if( !empty($jconfig->allow_anon)){ 
					?>
					<li class="left">
						<label class="checkbox"><input type="checkbox" name="anon" value="1"><?php echo JText::_('COM_PROFILE_NOTIFICATION_LABEL_ANONYMOUS_COMMENT');?></label>
					</li>
					<?php } ?>
								
					<li class="right">
						<a href="#cancelPostinComment" class="cancel"><?php echo JText::_('COM_STREAM_LABEL_CANCEL');?></a>
						<button type="submit" class="submit btn btn-mini"  tabindex="<?php echo ($tabIndex+1); ?>"><?php echo JText::_('COM_STREAM_LABEL_POST_COMMENT');?></button>
						<div class="clear"></div>
					</li>
				</ul>
			</div>
			<div class="clear"></div>
			<input type="hidden" name="stream_id" value="<?php echo $stream_id; ?>" />
		</form>
		<?php
		// If there are no comment, reply box should also start hidden
		?>
		<span class="comment-reply" <?php echo ($count > 0 )? '' : 'style="display: none"' ; ?>>
			<a href="#reply"><?php echo JText::_('COM_STREAM_LABEL_REPLY');?></a>
		</span>

	</div>
	
	<div class="clear"></div>
</div>