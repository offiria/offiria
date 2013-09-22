<?php
/**
 * @package		JomSocial
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

$my 		= JXFactory::getUser();
$user 		= JXFactory::getUser($stream->user_id);
$attendees 	= $stream->getFollowers();
$attendeesCount = count($attendees);

$model 		= StreamFactory::getModel('events');
$eventDate 	= $model->determinedEventDuration($stream->start_date, $stream->end_date);

$startDate 	= $eventDate['startDate'];
$endDate 	= $eventDate['endDate'];
$startTime 	= $eventDate['startTime'];
$endTime 	= $eventDate['endTime'];

$eventIsDue = $model->isEventPassed($endDate);
$isDueClass	= ($eventIsDue) ? 'past': 'onGoing';

$data 		= json_decode($stream->raw);
$typeClass 	= '';

$table 		= new StreamCategory();
$events 	= $table->getEvents();
$category	= '';
if (isset($data->category_id)) {
	foreach ($events as $event) {
		if ($event->id == $data->category_id) $category = $event->category;
	}
}
?>
<li id="message_<?php echo $stream->id; ?>" class="message-item type_<?php echo $stream->type; ?>">

<div class="message-avatar">
	<a href="<?php echo $user->getURL();?>">
	<img class="cAvatar" border="0" author="85" alt="" src="<?php echo $user->getThumbAvatarURL(); ?>">
	</a>
</div>

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
				
				<div class="message-event-item vanity-list events-<?php echo $isDueClass; ?>">
				
				<div class="vanity-col1">
					<div class="cal-box">
						<span class="cal-boxTop"></span>
						<span class="cal-date"><h2><?php echo $startDate->format('d'); ?></h2></span>
						<span class="cal-month"><?php echo $startDate->format('M'); ?></span>
						<span class="cal-boxBottom"></span>
					</div>
				</div>

				<div class="vanity-col2">
					<div class="cal-info">
						<div class="vanity-title">
							<?php 
							if ($category) { echo '<span class="label label-info">' . $category . '</span>'; }
							if (isset($data->title)) { echo '<b>' . $data->title . '</b><br/>'; }
							echo StreamMessage::format($stream->message); 
							?>
						</div>
						
						<div class="small">
							<?php echo $this->escape($stream->location); ?>
						</div>

						<div class="small">
							<?php
							// if start and end date is same, then show format 'Date: pp.kk.aaaa @ hh.mm - hh.mm
							if ($startDate->format( JText::_('JXLIB_DATE_SHORT_FORMAT')) == $endDate->format( JText::_('JXLIB_DATE_SHORT_FORMAT'))) {
								?>
								<span class="start-time">
									<?php echo JText::_('COM_STREAM_LABEL_DATE'); ?>: <?php echo $startDate->format( JText::_('JXLIB_DATE_SHORT_FORMAT')); ?>
									<?php if($startTime) { echo '&#64;&nbsp;' . $startTime; } ?>
									<?php if($endTime) { echo '&nbsp;-&nbsp;' . $endTime; } ?>
								</span>
								<?
							} else {
								// if start and end date are not the same, then show following format
								?>
								<span class="start-time">
									<?php echo JText::_('COM_STREAM_LABEL_START_DATE'); ?>: <?php echo $startDate->format( JText::_('JXLIB_DATE_SHORT_FORMAT')); ?>
									<?php if($startTime) { echo '&#64;&nbsp;' . $startTime; } ?>
								</span>
								
								<div class="clear"></div>
								<span class="end-time">
									<?php echo JText::_('COM_STREAM_LABEL_END_DATE'); ?>: <?php echo $endDate->format( JText::_('JXLIB_DATE_SHORT_FORMAT')); ?> 
									<?php if($endTime) { echo '&#64;&nbsp;' . $endTime; } ?>
								</span>
							<?php } ?>
						</div>
					</div>
							
					<?php if($attendeesCount): ?>
					<span><?php echo JText::_('COM_STREAM_LABEL_ATTENDEES'); ?>:</span>
					<div class="user-horizontal-list" style="height: 26px; overflow: hidden;">
						<?php
						$i = 0; // limit 8 attendees per row
						foreach($attendees as $attendee):
							$i++;
							?>
							<span class="user-list">
								<a href="<?php echo $attendee->getURL(); ?>">
									<img class="attendee-avatar tips" src="<?php echo $attendee->getThumbAvatarURL(); ?>"
									original-title="<?php echo $attendee->name; ?>" />
								</a>
							</span>

							<?php
							if (($i % 8) == 0) {
								echo '<div style="clear"></div>';
							}
							?>
						<?php endforeach; ?>
					</div>
						<?php if($attendeesCount > 8):  ?>
							<div>
								<span class="user-more" style="margin: 3px 0 0 5px;">
									<a href="#"><?php echo JText::_('COM_STREAM_LABEL_ATTENDEES_OTHERS'); ?></a>  <!-- show this if the list of attendees > 8 -->
								</span>
							</div>
						<?php endif; ?>
					<?php endif; ?>
				</div>
						
				<div class="vanity-col3">
					<?php if($eventIsDue == true): ?>
						<div class="btn"><?php echo JText::_('COM_STREAM_LABEL_PASSED');?></div>
					<?php elseif($stream->isFollowing($my->id)): ?>
						<div class="btn unfollow"><?php echo JText::_('COM_STREAM_LABEL_NOT_ATTEND');?></div>
					<?php else: ?>
						<div class="btn follow"><?php echo JText::_('COM_STREAM_LABEL_ATTEND');?></div>
					<?php endif; ?>
				</div>
				
				<div class="clear"></div>
			</div>
		</div>

		<!--div class="message-content-topic topic-container-parent" style="display:none">
			<ul class="topic-container">
				<?php 
				$topics = array();
				if (strlen($stream->topics) > 0) {
				$topics = explode(',', $stream->topics);
				}

				foreach ($topics as $topic) {
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

			<a href="javascript:void(0)" class="message-topic-edit topic-edit-change">Edit</a>

			<?php endif; ?>
		</div-->
		<?php
		// make sure location is valid
		if($stream->getParam('loc_valid') && ($stream->getParam('hide_map') != '1')){
			echo StreamMap::drawZoomableMap($this->escape($stream->location), 570, 150);
		}
		?>
		
		<?php echo StreamMessage::getAttachmentHTML($stream); ?>

		<?php echo StreamTag::getTagsHTML($stream); ?>

	</div>

		<div class="clear"></div>

		<!-- NEWS FEED DATE, ICON & ACTIONS -->
		<div class="message-meta small">
			<?php
			$date = new JDate( $stream->created );	?>
			<a class="meta-date" href="<?php echo $stream->getUri();;?>"><?php echo JXDate::formatLapse( $date ); ?></a>
			• <a class="meta-like" href="#<?php echo (!$stream->isLike($my->id))? '' : 'un'; ?>like"><?php echo (!$stream->isLike($my->id)) ? JText::_('COM_STREAM_LIKE_LABEL') : JText::_('COM_STREAM_UNLIKE_LABEL'); ?></a>
			• <a class="meta-comment" href="#comment"><?php echo JText::_('COM_STREAM_LABEL_COMMENT');?></a>
			<?php
			if( $my->authorise('stream.message.edit', $stream) ){ ?>
				• <a class="meta-edit" href="#edit"><?php echo JText::_('COM_STREAM_LABEL_EDIT');?></a>
				<?php
			}
			?>

			<div class="clear"></div>
		</div>
		<!-- /NEWS FEED DATE, ICON & ACTIONS -->
		
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

<?php
// You can only delete your own message
if( $my->authorise('stream.message.edit', $stream) ) {
?>
<div class="message-remove">
	<a href="javascript:void(0);" class="remove" original-title="Delete"><?php echo JText::_('COM_STREAM_LABEL_DELETE'); ?></a>
</div>
<?php } ?>

<div class="clear"></div>
</li>

