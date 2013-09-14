<style type="text/css">
	.toggle-item {
		display: none;
	}
</style>

<!-- NEW MILESTONE TEMPLATE -->

<div class="timeline">
	<!-- OVERDUE -->
	<div class="milestone">
		<?php $count = 0; ?>
		<?php if (count($milestones) > 0): ?>
		<ul>
		<?php
		foreach($milestones as $index => $milestone) 
		{ 
			//need to add first-complete class to milestone
			if ($milestone->status == StreamMilestoneType::COMPLETED && ($index == 0 || ($milestones[$index-1]->status != StreamMilestoneType::COMPLETED)))
			{
				?>
		</ul>
		<span class="label label-success">
			Completed
		</span>
		<ul>
				<?php
				$addClass = 'first-complete';
			}
			else
			{
				$addClass = '';
			}
			$html = $milestone->getMilestoneHTML($milestone, $addClass);
			if (!empty($html))
			{
				echo $html;
				$count = 1;
			}
		} ?>
		</ul>
		<?php endif; ?>
	</div>
</div>

<?php if ($count === 0) {?>
	<br/>
	<div class="alert-message block-message info alert-empty-stream">   
		<p><?php echo JText::_('COM_STREAM_NO_MILESTONE');?></p>        
	</div>
<?php }	?>

<div class="pagination">
	<?php echo $pagination->getPagesLinks(); ?>
</div>
<!-- END OF NEW MILESTONE -->

<link rel="stylesheet" href="<?php echo JURI::root();?>media/uploader/fileuploader.css" type="text/css" />
<script src="<?php echo JURI::root();?>media/uploader/fileuploader.js" type="text/javascript"></script>
<script type="text/javascript">	
	var milestoneAction = {
		init: function() {
			/* bind action to delete action to remove attachment */
			$('div.milestone-action a[href="#deleteMilestone"]').live("click",function(e)
			{
				e.preventDefault();
				if (!confirm(S.text['confirm.message.delete']))
				{
					return false;
				}

				var milestone_id = $(this).parents('.milestone-action').data('id');

				if(milestone_id != 'undefined'){		    	
					$.ajax({
						type: "POST",
						url: S.path['message.delete'],
						data: {message_id: milestone_id},
						cache: false,
						dataType: 'json',
						success: function(data){
							$('li#message_' + milestone_id ).remove();
						}
					});
				}

				return false;	
			});
		}
	}


	// TOGGLE VIEW ALL MILESTONE TASK LIST
	$(document).ready(function(){
		$('a[href="#toggleTasks"]').live('click', function(e){
			e.preventDefault();
			$(this).parents('.milestone-task').find('ul.todolist').fadeToggle("fast", "linear");
		});
		
		$('ul.todolist').toggle();	
	
		milestoneAction.init();
	})
</script>

<?php /*
<!-- OLD MILESTONE TEMPLATE -->
<div class="vanity-list">
	<ul class="milestones-list">
		<!-- LISTING TITLE -->
		<li>
			<div class="vanity-col1">Milestone</div>
			<div class="vanity-col2">Status</div>
			<div class="vanity-col3">Due</div>
			<div class="clear"></div>
		</li>
		<?php
		$count = 0;
		foreach($milestones as $milestone) { 
			$html = $milestone->getMilestoneHTML($milestone);
			if (!empty($html))
			{
				echo $html;
				$count = 1;
			}
		} 
		if ($count === 0)
		{?>
		<br />
		<div class="alert-message block-message info alert-empty-stream">   
			<p><?php echo JText::_('COM_STREAM_NO_MILESTONE');?></p>        
		</div>
		<?php }	?>

		<script type="text/javascript">	
			// TOGGLE VIEW ALL MILESTONE TASK LIST
			$(document).ready(function(){
				$('a[href="#toggleTasks"]').click(function(e){
					e.preventDefault();
					$(this).parents('.milestone-item').find('ul#stream .milestone-task.toggle-item').fadeToggle("fast", "linear");
				});
			})
		</script>

	</ul>
</div>
<div class="pagination">
	<?php echo $pagination->getPagesLinks(); ?>
</div>
*/ ?>