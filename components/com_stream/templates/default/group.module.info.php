<?php
// Get followers
$followersHtml = '<ul><li class="nolink">No one is following the group</li></ul>';
$followers = JXUtility::csvDiff($group->followers, $group->members);

if(JXUtility::csvCount($followers) > 0){
	$followersHtml = '<ul>';
	foreach(explode(',', $followers) as $id)
	{
		if($id == 42) {
			// exclude the super user
			continue;
		}
		$user = JXFactory::getUser($id);
		$followersHtml .= '<li><a href="' . $user->getURL() . '">'.$user->name.'</a></li>';
	}
	$followersHtml .= '</ul>';
}
?>
<div class="moduletable group-module">
		
	<h3><?php echo JText::_('COM_STREAM_LABEL_GROUP_INFO');?></h3>
	
	<div class="group-module-info">
		<?php
		require_once( JPATH_ROOT. DS. 'libraries/HighRoller/HighRoller.php');
		require_once(JPATH_ROOT. DS. 'libraries/HighRoller/HighRollerSeriesData.php');
		require_once(JPATH_ROOT. DS. 'libraries/HighRoller/HighRollerLineChart.php');
		require_once(JPATH_ROOT. DS. 'libraries/HighRoller/HighRollerColumnChart.php');
		
		$chartData1 = JAnalytics::get('', null, $group->id, '', 'day');

		$linechart = new HighRollerLineChart();

		$linechart->legend = new stdClass();
		$linechart->credits = new stdClass();
		
		$linechart->yAxis = new stdClass();
		$linechart->yAxis->title = new stdClass();
		$linechart->yAxis->labels = new stdClass();

		$linechart->xAxis = new stdClass();
		$linechart->xAxis->title = new stdClass();
		$linechart->xAxis->labels = new stdClass();

		$linechart->chart->renderTo = 'linechart';
		//$linechart->tooltip->enabled = false;
		$linechart->yAxis->title->text = '';
		$linechart->yAxis->min = 0;
		$linechart->yAxis->labels->enabled = false;
		$linechart->xAxis->labels->enabled = false;
		
		//$linechart->xAxis->categories =  array('Sun', 'Mon', 'Tue', 'Wed', 'Fri', 'Sat') ;
		$linechart->legend->enabled = false;
		$linechart->credits->enabled = false;
		//$linechart->title->text = 'Line Chart';
		
		$series1 = new HighRollerSeriesData();
		$series1->addName('Activity')->addData($chartData1);
		
		$linechart->addSeries($series1);
		?>
		
		<span class="small">Activity: Last 14 days</span>
		<div id="linechart" style="height:80px"></div>

		<script type="text/javascript">
		  <?php echo $linechart->renderChart();?>
		</script>

		<div class="group-module-description">
			<p>
				<?php echo $this->escape($group->description); ?>
			</p>
		</div>

		<div class="group-module-members">
			<span class="count-members"><strong><?php echo JXUtility::csvCount($group->members); ?></strong>&nbsp;<?php echo JText::_('COM_STREAM_LABEL_MEMBERS');?></span> 
			<span class="count-followers"><a data-group_id="<?php echo $group->id; ?>" data-content="<?php echo StreamTemplate::escape($followersHtml); ?>" href="#showFollowers"><strong><?php echo JXUtility::csvCount(JXUtility::csvDiff($group->followers, $group->members)); ?></strong>&nbsp;<?php echo JText::_('COM_STREAM_LABEL_FOLLOWERS');?></a></span>
		</div>
	</div>
	
	<?php /*?>
	<div class="group-module-actions">
		<form name="group-actions">
			
			<?php if(!$group->isMember($my->id)) { ?>
				<?php if(!$group->isFollower($my->id)) { ?>
			<a href="#followGroup" class="btn"><?php echo JText::_('COM_STREAM_LABEL_FOLLOW');?></a>
				<?php } else { ?>
			<a href="#unfollowGroup" class="btn"><?php echo JText::_('COM_STREAM_LABEL_UNFOLLOW');?></a>	 
				<?php }  ?>
			<a href="#joinGroup" class="btn btn-info"><?php echo JText::_('COM_STREAM_LABEL_JOIN_GROUP');?></a>
			<?php } else { 
			// if you're already a member, you don't need to follow it right?
			?>
			<a href="#leaveGroup" class="btn btn-warning"><?php echo JText::_('COM_STREAM_LABEL_LEAVE_GROUP');?></a>
			<?php }  ?>
			<input type="hidden" name="group_id" value="<?php echo $group->id; ?>"/>
		</form>
		<div class="clear"></div>
	</div>
	<!--
	<img src="<?php echo JURI::root(); ?>components/com_stream/assets/images/analytics.png" width="240"/>
	-->
	
	</php */?>
</div>
