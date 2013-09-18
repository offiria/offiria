<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

$todoLink = (count($todos) > 0 && !empty($todos[0])) ? JRoute::_('index.php?option=com_stream&view=message&task=showMessages&ids='.implode(',',$todos)) : 'javascript: void(0);';
$eventLink = (count($events) > 0 && !empty($events[0])) ? JRoute::_('index.php?option=com_stream&view=message&task=showMessages&ids='.implode(',',$events)) : 'javascript: void(0);';
$milestoneLink = (count($milestones) > 0 && !empty($milestones[0])) ? JRoute::_('index.php?option=com_stream&view=message&task=showMessages&ids='.implode(',',$milestones)) : 'javascript: void(0);';
?>

<script type="text/javascript">
	var dailyOverview = {
		init: function() {
			/* bind action to edit action to rename/replace attachment */
			$('a[href="#view-daily"]').click(function (e) {	
				var container = $('div#daily-overview');
				container.modal({ backdrop:true, show:true });
			});
			
			$('.weekly-overview .close').click(function() {
				var container = $('div#daily-overview');
				container.modal('hide');
			})
		}
	};
	
	// Hide n Show Container (stream) for grid view
	$(document).ready(function() {		
		dailyOverview.init();
		<?php if ($showPopup) { ?>
			var container = $('div#daily-overview');
			container.modal({ backdrop:true, show:true });
		<?php } ?>
	});
</script>

<div class="weekly-overview-mini">
	<h3>Weekly Overview<a class="btn pull-right" href="#view-daily">View</a></h3>
	<ul class="nav">
		<li class="ov-milestone-mini tips" original-title="milestone">
			<a href="#view-daily"><span><?php echo $milestoneCount;?></span></a>
		</li>
		<li class="ov-todo-mini tips" original-title="todo">
			<a href="#view-daily"><span><?php echo $todoCount;?></span></a>
		</li>
		<li class="ov-event-mini tips" original-title="event">
			<a href="#view-daily"><span><?php echo $eventCount;?></span></a>
		</li>
	</ul>
</div>
	
<div class="modal weekly-overview" id="daily-overview" style="display:none;">	
	<h1>Weekly Overview:</h1>
	<h3>
		<span><?php echo JText::sprintf('COM_STREAM_LABEL_WEEKLY_OVERVIEW_DATE_RANGE', $weekNumber);?></span>
		<span class="pull-right"><?php echo '( '.JXDate::formatDate( $firstDay, JXDate::SHORT_DATE_FORMAT) .' - '. JXDate::formatDate( $lastDay, JXDate::SHORT_DATE_FORMAT).' )';?></span>
	</h3>
	<ul class="nav">
		<li class="ov-milestone">
			<a href="<?php echo $milestoneLink;?>"><span><?php echo $milestoneCount;?></span></a>
		</li>
		<li class="ov-todo">
			<a href="<?php echo $todoLink;?>"><span><?php echo $todoCount;?></span></a>
		</li>
		<li class="ov-event">
			<a href="<?php echo $eventLink;?>"><span><?php echo $eventCount;?></span></a>
		</li>
	</ul>
	<a class="close btn">Close</a>
</div>