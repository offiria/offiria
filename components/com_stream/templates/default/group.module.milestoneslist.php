<span class="label milestone-complete"><?php echo JText::sprintf('%1s milestones completed', $completedCount); ?></span>
<div class="clear"></div>
<ul class="milestone-list">
<?php
$now 	= new JDate();
foreach($milestones as $milestone){

	$data 	= $milestone->getData();
	
	$now = new JDate();
	$data = $milestone->getData();
	$todoIds = $milestone->getParam('todo');
	$startDate = new JDate($milestone->start_date);
	$dateDiff = JXDate::timeDifference($startDate->toUnix(), $now->toUnix());

	$milestoneTaskCompletedCount = $milestone->getTaskCompletedCount();
	$milestoneTaskCount = $milestone->getTaskCount();

	$milestoneStatus = (!empty($dateDiff['days']) && ($dateDiff['days'] > 0) ) ? OVERDUE : INCOMPLETE;
	$milestoneStatus = ($milestone->status == StreamMilestoneType::COMPLETED) ? COMPLETED : $milestoneStatus;
	$milestoneDays = abs($dateDiff['days']);

	$milestoneClass = '';
	$milestoneDaysText = '';
	$milestoneDaysClass = '';
	$milestoneLabel = '';

	switch($milestoneStatus) {
		case OVERDUE:
			$milestoneClass = 'overdue';
			$milestoneDaysText = $milestoneDays . ' days late';
			$milestoneDaysClass = 'day-overdue';
			$milestoneLabel = 'label-important';
			break;
		case INCOMPLETE:
			$milestoneClass = 'incomplete';
			$milestoneDaysText = $milestoneDays . ' days left';
			$milestoneDaysClass = 'day-left';
			$milestoneLabel = 'label-warning';
			break;
		case COMPLETED:
			$milestoneClass = 'completed';
			break;
	}
?>	
	<li>
		<a href="<?php echo $milestone->getUri(); ?>"><?php echo StreamMessage::format($milestone->message); ?></a>
		<div class="progress progress-info progress-striped" milestone="<?php echo $milestone->id; ?>">
	    	<div class="bar" style="width: <?php echo $milestone->getParam('progress'); ?>%;"></div>
	    </div>
	    <div class="milestone-details vanity-list">
	    	<div class="small <?php echo $milestoneDaysClass; ?>"><?php echo $milestoneDaysText; ?></div>
	    	<!--
	    	<?php $todoIds = $milestone->getParam('todo'); ?>
	    	<span class="small hint"><?php echo $milestone->getParam('progress'); ?>% completed. 
				<?php echo count($todoIds); ?> task list.
			</span>
			-->
	    </div>
	</li>

<?php } ?>
</ul>