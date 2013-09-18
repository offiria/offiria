<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');



$my = JXFactory::getUser();
$user = JXFactory::getUser($stream->user_id);
$data = json_decode($stream->raw);

$model = StreamFactory::getModel('stream');
$prevMsg = $model->getPrevMessage($stream);
$nextMsg = $model->getNextMessage($stream);

$task = JRequest::getVar('task');
$view = JRequest::getVar('view');

$fullpage = ($task == 'show') && ($view == 'message');

if($fullpage){
	// We are in fullpage view
	$doc = JFactory::getDocument();
	// set page title
	$doc->setTitle($data->title);
}

$likeCount = $stream->countLike();
$commentCount = $stream->getParam('comment_count', null);
$date = new JDate( $stream->created );
?>

<!-- new code -->

<li  id="message_<?php echo $stream->id; ?>" class="message-item type_<?php echo $stream->type; ?>">
	<?php
	// Do not display the title if we're on full page since there
	// will already be a page title at the top template 
	if(!$fullpage){ ?>
		<a href="<?php echo $stream->getUri();;?>">
			<h1><?php echo $this->escape($data->title); ?></h1>
		</a>
		<span class="author small">
			By <a href="<?php echo $user->getURL();?>" class="actor-link"><?php echo $this->escape($user->name); ?></a>
		</span>
	<?php } else { ?>
		<span class="author-full small">
			By <a href="<?php echo $user->getURL();?>" class="actor-link"><?php echo $this->escape($user->name); ?></a>
		</span>
	<?php } ?>
	<div class="blog-header">
		<div class="blog-header-avatar">
			<img src="<?php echo $user->getThumbAvatarURL();?>" />
		</div>
		<div class="blog-header-meta">
			<div class="pull-left">
				<span class="small">
					<?php echo JText::_('COM_STREAM_BLOG_LABEL_POSTED_ON'); ?> <?php echo $date->format(JText::_('JXLIB_DATE_SHORT_FORMAT')); ?>
					<?php
						if( $my->authorise('stream.message.edit', $stream) ){ ?>
						<div class="message-meta" style="border: none; display: inline; font-size: inherit; margin: inherit;">
						• <a class="meta-edit" href="#edit"><?php echo JText::_('COM_STREAM_LABEL_EDIT') ;?></a>
						• <a class="meta-edit" href="#remove"><?php echo JText::_('COM_STREAM_LABEL_DELETE') ;?></a>
						</div>
					<?php } ?>
				</span>
				<span class="small">
					<?php echo JText::_('COM_STREAM_BLOG_LABEL_IN_CATEGORY'); ?>: 
					<?php 
							$categoryTable = JTable::getInstance('Category', 'StreamTable');
							$categoryName = $categoryTable->getCategoryNameById($stream->category_id);
							if (!empty($categoryName)) {
								echo '<a href="' . JRoute::_('index.php?option=com_stream&view=blog&category_id=' . $stream->category_id) . '">' . $categoryName . '</a>';
							}
							else {
								echo JText::_('COM_STREAM_DEFAULT_LABEL_PAGE_DEFAULT_CATEGORY');
							}
					?>
				</span>
			</div>
			
			<div class="pull-right">
				<span class="small">

					<?php if($likeCount > 0 && false /*HIDE*/) : ?>
						<a class="meta-like" href="<?php echo JRoute::_('index.php?option=com_stream&view=message&task=show&message_id='.$stream->id); ?>"><?php echo $likeCount; ?></a>
						&bull;
					<?php endif; ?>
						<a class="meta-comment" href="<?php echo JRoute::_('index.php?option=com_stream&view=message&task=show&message_id='.$stream->id); ?>#comment-container"><?php echo $commentCount;?></a>
					
				</span>

			</div>
			
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
	
	<div class="blog-content">
		<?php
		   if ($fullpage) {
			   $message = $data->message;
		   }
		   else {
			   /**
				* Note
				* Joomla does offer truncation class JHtmlString::truncate();
				* But it works as finding the next pair (completing) which is not what we want
				* We want the message to be truncated and append correct closing tag without finding the next matching pairs
				* Example:
				* 		$str = '<p>this <b>is</b> a string </p>';
				* 		JHtmlString::truncate(str, 17)      // outputs: <p>this <b>is</b> a string </p>
				* We need the function to work like so:
				* 		truncate(str, 17)       			// outputs: <p>this <b>is</b></p>
				*/
			   

			   $BLOG_EXCERPT_WORD_LENGTH = 55;
			   // grab characters including HTML tags as Tidy will close them
			   if ( str_word_count($data->message) > $BLOG_EXCERPT_WORD_LENGTH) {
				   $str = preg_match('/((\w|<)+\W*\b){'.$BLOG_EXCERPT_WORD_LENGTH.'}/', $data->message, $match);
			   }

			   // but we still need to truncate leaving angle bracket(<)
			   if (!function_exists('removeTrails')) {
				   function removeTrails($str) {
					   // we need to remove trailing <br /> at the last few characters only
					   $lastLineToCheck = substr($str, -14);
					   // after replace and concat to keep the original formatting
					   $str = substr($str, 0, strlen($str) - 14) . str_replace(array('<br />', '<br/>', '<br>'), '', $lastLineToCheck);

					   // characters to remove
					   $INVALID_CHARS = '/([<.]|\s|\\\)/';
					   if (preg_match($INVALID_CHARS, substr($str, -1))) {
						   $str = substr($str, 0, strlen($str)-1);
						   removeTrails($str);
					   }
					   return $str;
				   }
			   }
			   $isExcerpt = (str_word_count($data->message) > $BLOG_EXCERPT_WORD_LENGTH) ? true : false;
			   $match = (isset($match[0]) || $isExcerpt) ? $match[0] : $data->message;
			   $message = removeTrails($match);
			   //$str = (substr(@$match[0], -1)) ? substr(@$match[0], 0, strlen(@$match[0])-1) : @$match[0];

			   // Specify configuration
			   $config = array(
							   'indent'         => false,
							   'output-html'    => true,
							   'wrap'           => 200,
							   'show-body-only' => true);

			   // append ellipsis
			   $message = ($isExcerpt) ? $message.'&hellip;' : $message;

			   // @url: http://www.php.net/manual/en/intro.tidy.php
			   if (class_exists('tidy')) { 
				   $tidy = new tidy();
				   $tidy->parseString($message, $config, 'utf8');
				   $tidy->cleanRepair();
				   $message = $tidy;
			   }
			   else {
				   $message = $data->message;
			   }
		   }
		   echo $message;
			
			?>
	</div>
	
	<?php echo StreamMessage::getAttachmentHTML($stream); ?>

	<?php echo StreamTag::getTagsHTML($stream); ?>
	
	<?php // match contains regex result so no READ MORE if regex unable to excerpt 
		if (isset($match) && class_exists('tidy') && $isExcerpt):?>
		<a class="btn btn-info" href="<?php echo JRoute::_('index.php?option=com_stream&view=message&task=show&message_id='.$stream->id); ?>"><?php echo JText::_('COM_STREAM_BLOG_READ_MORE'); ?> &raquo;</a>
	<?php endif; ?>
	
	<?php if($fullpage) : ?>
	<div class="comment-container" id="comment-container">
		
		<div class="message-meta small">
			<?php
			$date = new JDate( $stream->created );?>
				<a class="meta-date" href="<?php echo $stream->getUri();;?>"><?php echo JXDate::formatLapse( $date ); ?></a>
				• <a class="meta-like" href="#<?php echo (!$stream->isLike($my->id))? '' : 'un'; ?>like"><?php echo (!$stream->isLike($my->id)) ? JText::_('COM_STREAM_LIKE_LABEL') : JText::_('COM_STREAM_UNLIKE_LABEL'); ?></a>
				• <a class="meta-comment" href="#comment"><?php echo JText::_('COM_STREAM_LABEL_COMMENT') ;?></a>
			<div class="clear"></div>
		</div>
	
		<div class="message-content-bottom">
			<?php if($fullpage) { echo $comment; } ?>
		</div>
	
		<div class="clear"></div>
	</div>
	
	<?php endif; ?>
	
	<?php if($fullpage){ ?>
	<!-- Next/Prev -->
	<div class="prev_next">
		<?php if($prevMsg->id) { ?>
		<p class="previous pull-left">
			<a href="<?php echo $prevMsg->getUri(); ?>">← <?php echo JText::_('COM_STREAM_BLOG_PREVIOUS'); ?>: <?php echo StreamTemplate::escape(JHtmlString::truncate($prevMsg->getData()->title, 32)); ?></a>
		</p>
		<?php } ?>
		
		<?php if($nextMsg->id) { ?>
			<p class="next">
				<a href="<?php echo $nextMsg->getUri(); ?>"><?php echo JText::_('COM_STREAM_BLOG_NEXT'); ?>: <?php echo StreamTemplate::escape(JHtmlString::truncate($nextMsg->getData()->title, 32)); ?> →</a>
			</p>
		<?php } ?>
		<div class="clear"></div>	
	</div>
	<?php } ?>
	
	<div class="clear"></div>
	
</li>
<?php
return;

// old codes is removed to avoid confusion
// @see changeset:   3101:ed6a1161c900
?>
