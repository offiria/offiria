<div class="moduletable"><h3><?php echo JText::_('COM_STREAM_LABEL_MILESTONES'); ?></h3>

<?php if(count($milestones)) : ?>
	<span class="label milestone-complete"><?php echo JText::sprintf(JText::_('COM_STREAM_LABEL_MILESTONES_COMPLETED'), $completedCount); ?></span>
	<div class="clear"></div>
	
	<ul class="milestone-list">
	<?php
	$now 	= new JDate();
	$completedMilestonesHtml = '';
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
				$milestoneDaysText = $milestoneDays . ' ' . JText::_('COM_STREAM_LABEL_DAYS_LATE');
				$milestoneDaysClass = 'day-overdue';
				$milestoneLabel = 'label-important';
				break;
			case INCOMPLETE:
				$milestoneClass = 'incomplete';
				$milestoneDaysText = $milestoneDays . ' ' . JText::_('COM_STREAM_LABEL_DAYS_LEFT');
				$milestoneDaysClass = 'day-left';
				$milestoneLabel = 'label-warning';
				break;
			case COMPLETED:
				$milestoneClass = 'completed';
				break;
		}
		
		if ($milestoneStatus == COMPLETED) {
			$completedMilestonesHtml .= '<li>
			<a href="' . $milestone->getUri() . '">' . StreamMessage::format($milestone->message) . '</a>
			<div class="progress progress-info progress-striped tips" milestone="' . $milestone->id . '" title="' .  $milestone->getParam('progress', 0) . '%">
				<div class="bar" style="width: ' . $milestone->getParam('progress') . '%;"></div>
			</div>
			<div class="milestone-details vanity-list">
				<div class="small ' . $milestoneDaysClass . '">' .  $milestoneDaysText . '</div>
			</div>
		</li>';
		} else {
		?>	
		<li>
			<a href="<?php echo $milestone->getUri(); ?>"><?php echo StreamMessage::format($milestone->message); ?></a>
			<div class="progress progress-info progress-striped tips" milestone="<?php echo $milestone->id; ?>" title="<?php echo $milestone->getParam('progress', 0);?>%">
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
	<?php }
	}?>
	</ul>

	<div id="completed-milestone-list" style="display:none;">
		<ul class="milestone-list">	
			<?php echo $completedMilestonesHtml; ?>
		</ul>
		<div class="clear"></div>
	</div>

	<ul style="list-style-type: none; padding-bottom: 20px;">
		<li>
			<form class="edit" style="float: right !important;">
			<table border="0" cellpadding="0" width="100%">
				<tr>
					<td><span class="small"><?php echo JText::_('COM_STREAM_LABEL_MILESTONES_SHOW_FINISHED'); ?>:</span></td>
					<td>
						
						<div class="checkboxOne">
						<input onclick="javascript:showCompletedMilestones(this.checked);" type="checkbox" id="compl_milestones_show" name="compl_milestones_show" value="1" style="visibility: hidden;"/>
						<label for="compl_milestones_show"></label>
						</div>
					</td>
				</tr>
			</table>
			</form>		
		</li>
	</ul>
<?php else : ?>
	<div class="alert-message block-message info">
		<p><?php echo JText::_('COM_STREAM_LABEL_MILESTONES_NO'); ?></p>
	</div>
<?php endif; ?>
</div>

<script>
function showCompletedMilestones(value) {
	if (value === true) {
		document.getElementById("completed-milestone-list").style.display = "inline";
	} else {
		document.getElementById("completed-milestone-list").style.display = "none";
	}
	return false
}
</script>