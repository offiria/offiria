<?php
// Placed in stream type when necessary
if (!defined("OVERDUE"))
{
	define("OVERDUE", 1);
	define("INCOMPLETE", 2);
	define("COMPLETED", 3);
}

	$filterStatus = JRequest::getVar('status', 'all');

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
<!-- COMPLETED -->
<li class="<?php echo $milestoneClass;?> <?php echo $addClass;?> message-item" id="message_<?php echo $milestone->id;?>">
	<ul>
		<li class="col1">
			<?php if ($milestoneStatus == COMPLETED) { ?>
			<div class="status-completed">
			</div>
			<?php } elseif ($milestoneStatus == INCOMPLETE) { ?>
			<div class="day-count">
				<?php echo $milestoneDays;?>
			</div>
			<div class="day-status">
				days left
			</div>
			<?php } elseif ($milestoneStatus == OVERDUE) { ?>
			<div class="day-count">
				<?php echo $milestoneDays;?>
			</div>
			<div class="day-status">
				days late
			</div>
			<?php } ?>
		</li>
		<li class="col2">
			<div class="title">
				<?php if( $my->authorise('stream.message.edit', $milestone) ){ ?>
				<input type="checkbox" <?php echo ($milestoneStatus == COMPLETED) ? 'checked' : ''; ?> class="complete-todo" id="complete_<?php echo $milestone->id;?>"  data-status="<?php echo $milestone->status;?>"/>
				<?php } ?>
				<span><a href="<?php echo $milestone->getUri();?>"><?php echo StreamMessage::format($milestone->message); ?></a></span>
			</div>
			<?php if ($milestoneStatus != COMPLETED) { ?>			
			<div class="progress progress-info progress-striped" milestone="<?php echo $milestone->id;?>">
				<div class="bar" style="width: <?php echo $milestone->getParam('progress', 0);?>%;"></div>
			</div>
			<?php } ?>
			<div class="content">
				<div class="small">
					<span>Due date: <?php echo $startDate->format( JText::_('JXLIB_DATE_SHORT_FORMAT')); ?></span>
				</div>
				<!-- TASK LIST PLACEHOLDER. REMOVE EVERYTHING IN BETWEEN THIS COMMENT ONCE COMPLETED. -->
				<?php 
				if($todoIds) {
					$tasks = StreamFactory::getModel('stream')->getStream(array('id' => $todoIds));
					foreach($tasks as $task) {
						echo $task->getTodoHtml($task);
					}
				} ?>
				<!--  -->
			</div>
				
			<?php if ($my->authorise('stream.message.edit', $milestone)) {?>
			<div class="milestone-action" data-id="<?php echo $milestone->id;?>">
				<div class="btn-group">
					<!--button class="btn btn-mini editMilestone">Edit</button-->
					<button data-toggle="dropdown" class="btn btn-mini dropdown-toggle"><span class="caret"></span></button>
					<ul class="dropdown-menu message-meta pull-right">
						<li><a href="#edit"  data-type="list">Edit</a></li>
						<li><a href="#deleteMilestone">Delete</a></li>
					</ul>
				</div>
			</div>
			<?php } ?>
		</li>
	</ul>
	<div class="clear"></div>
</li>

