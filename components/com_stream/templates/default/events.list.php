<div class="vanity-list">

	<ul class="events-list">

		<li>
			<div class="vanity-col1">Date</div>
			<div class="vanity-col2">Events</div>
			<div class="vanity-col3">Status</div>
			<div class="clear"></div>
		</li>

		<?php
		foreach($events as $key=>$event):

		$data = json_decode($event->raw);
		$data->location = (!empty($data->location)) ? $data->location : '';

		$model = StreamFactory::getModel('events');
		$eventDate = $model->determinedEventDuration($event->start_date, $event->end_date);

		$startDate = $eventDate['startDate'];
		$endDate = $eventDate['endDate'];
		$startTime = $eventDate['startTime'];
		$endTime = $eventDate['endTime'];

		$eventIsDue = $model->isEventPassed($endDate);
		$isDueClass	= ($eventIsDue) ? 'past': 'onGoing';

		$attendees 	= $event->getFollowers();
		$attendeesCount = count($attendees);
		?>

		<!-- ONGOING EVENTS -->
		<li class="events-<?php echo $isDueClass; ?>">
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
						<?php echo $this->escape($data->message); ?>
					</div>
					<div class="small">
						<?php echo $this->escape($data->location); ?>
					</div>
					<div class="small">
						<span class="start-time">
							Start: <?php echo $startDate->format( JText::_('JXLIB_DATE_SHORT_FORMAT')); ?> <!-- Only show the date if the event held in >= 2 days -->
							<?php if($startTime != '00:00:00') { echo '&#64;&nbsp;' . $startTime; } ?>
						</span>

						<?php if($endDate !== null): ?>
							<div class="clear"></div>
							<span class="end-time">
								End: <?php echo $endDate->format( JText::_('JXLIB_DATE_SHORT_FORMAT')); ?>  <!-- Only show the date if the event held in >= 2 days -->
								<?php if($endTime != '00:00:00') { echo '&#64;&nbsp;' . $endTime; } ?>
							</span>
						<?php endif; ?>
					</div>
				</div>
				<?php if(!empty($attendees)): ?>
				<div class="user-horizontal-list">
					<span>Attendees:</span>
						<?php foreach($attendees as $attendee): ?>
						<span class="user-list">
							<a href="<?php echo $attendee->getURL(); ?>">
								<img class="attendee-avatar" src="<?php echo $attendee->getThumbAvatarURL(); ?>" />
							</a>
						</span>
						<?php endforeach; ?>
						<?php if($attendeesCount > 8):  ?>
						<span class="user-more">
							<a href="#">and others ...</a>  <!-- show this if the list of attendees > 8 -->
						</span>
						<?php endif; ?>
				</div>
				<?php endif; ?>
			</div>
			<div class="vanity-col3">
				<?php if($eventIsDue == true): ?>
				<div class="btn">
					<?php echo JText::_('Past');?>
				</div>
				<?php elseif($event->isFollowing($my->id)): ?>
				<div class="btn unfollow" data-message_id="<?php echo $event->id; ?>">
					<?php echo JText::_('COM_STREAM_LABEL_NOT_ATTEND');?>
				</div>
				<?php else: ?>
				<div class="btn follow" data-message_id="<?php echo $event->id; ?>">
					<?php echo JText::_('COM_STREAM_LABEL_ATTEND');?>
				</div>
				<?php endif; ?>
			</div>
			<div class="clear"></div>
			<div>
				<ul>
					<li id="message_<?php echo $event->id; ?>" class="message-item type_<?php echo $event->type; ?>">
						<div class="message-meta small">
							<?php
							$date = new JDate( $event->created );?>
							<a class="meta-date" href="<?php echo $event->getUri();;?>"><?php echo JXDate::formatLapse( $date ); ?></a>
							• <a class="meta-like" href="#<?php echo (!$event->isLike($my->id))? '' : 'un'; ?>like"><?php echo (!$event->isLike($my->id)) ? JText::_('COM_STREAM_LIKE_LABEL') : JText::_('COM_STREAM_UNLIKE_LABEL'); ?></a>
							• <a class="meta-comment" href="#comment">Comment</a>
							<?php
							if( false && $my->authorise('stream.message.edit', $event) ){ ?>
								• <a class="meta-edit" href="#edit"><?php echo JText::_('COM_STREAM_LABEL_EDIT'); ?></a>
								• <a class="meta-edit" href="#remove"><?php echo JText::_('COM_STREAM_LABEL_DELETE'); ?></a>
								<?php
							}
							?>

							<div class="clear"></div>
						</div>

						<div class="message-content-bottom">
							<?php echo StreamComment::getCommentSummaryHTML($event); ?>
						</div>
					</li>
				</ul>
			</div>
			<div class="clear"></div>
		</li>

		<?php endforeach; ?>
		
	</ul>
		<?php 
		if (count($events) == 0)
		{?>
		<br />
		<div class="alert-message block-message info alert-empty-stream">   
			<p><?php echo JText::_('COM_STREAM_NO_EVENT');?></p>        
		</div>
		<?php }	?>
</div>
<div id="sandbox"></div>
<div class="pagination">
	<?php echo $pagination->getPagesLinks(); ?>
</div>

<script type="text/javascript">
	//TODO: This is a temporary solution as we have timeline-style events in plan
	var Event = {
		init:function () {
			$('.events-list .btn.follow').live("click", function () {
				var stream_id = S.utils.getMessageId(this);

				$.ajax({
					type:"POST",
					url:S.path['event.follow'],
					data:'stream_id=' + stream_id,
					dataType:'json',
					cache:false,
					success:function (data) {
						location.reload();
					}
				});

				return false;
			});

			$('.events-list .btn.unfollow').live("click", function () {
				var stream_id = S.utils.getMessageId(this);

				$.ajax({
					type:"POST",
					url:S.path['event.unfollow'],
					data:'stream_id=' + stream_id,
					dataType:'json',
					cache:false,
					success:function (data) {
						location.reload();
					}
				});

				return false;
			});
		}
	};

	Event.init();
</script>