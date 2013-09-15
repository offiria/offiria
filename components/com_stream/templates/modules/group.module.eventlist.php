<div class="moduletable">
<h3><?php echo $title; ?></h3>
<?php if(!empty($events)){ ?>
<ul class="reset eventlist">

	<?php 
	$streamModel = StreamFactory::getModel('stream');
	
	foreach($events as $event):

		$data = json_decode($event->raw);
		$data->location = (!empty($data->location)) ? $data->location : '';

		$startDate = new JDate($event->start_date);

		$attendeesCount = count($event->getFollowers());
		$likeCount = $event->countLike();
		$commentCount = $streamModel->countComments( array('stream_id' => $event->id) );
	?>
	<li>
		<div class="cal-col1">
			<div class="cal-box">
				<span class="cal-boxTop"></span>
				<span class="cal-date"><h2><?php echo $startDate->format('d'); ?></h2></span>
				<span class="cal-month"><?php echo $startDate->format('M'); ?></span>
				<span class="cal-boxBottom"></span>
			</div>
		</div>

		<div class="cal-col2">
			<div class="cal-info">
				<div class="cal-title">
					<a href="<?php echo $event->getUri(); ?>"><?php echo $this->escape( JHtmlString::truncate($data->message, 54)); ?></a>
				</div>
				<div class="cal-venue">
					<?php echo $this->escape($data->location); ?>
				</div>
				<div class="compact-like-comment">
					
					<div class="meta-like">
						<span class="meta-count"><a href="<?php echo $event->getUri(); ?>"><?php echo $likeCount; ?></a></span>
					</div>
					
					<div class="meta-comment">
						<span class="meta-count"><a href="<?php echo $event->getUri(); ?>"><?php echo $commentCount; ?></a></span>
					</div>
					
					<div class="clear"></div>
				</div>
					
			</div>
		</div>

		<div class="clear"></div>
	</li>
	
	<?php endforeach; ?>
</ul>
<?php } else {?>
<div class="alert-message block-message info">       
	<p><?php echo JText::_('COM_STREAM_LABEL_NO_UPCOMING_EVENT');?></p>        
</div>
<?php } ?>
</div>