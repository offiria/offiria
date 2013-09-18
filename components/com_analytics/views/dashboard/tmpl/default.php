<?php
/**
 * @version     1.0.0
 * @package     com_administrator
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// no direct access
defined('_JEXEC') or die;
?>

<script type="text/javascript">
	var analytics = {
		fetchProfileAnalytic: function ()
		{
			$.ajax({
				url: '<?php echo JRoute::_('index.php?option=com_analytics&view=dashboard&task=ajaxGenerateAnalytics', false);?>',
				dataType: 'html',
				data: 'group_type='+$('#analytic_group_type').val()+'&filter='+$('#analytic_filter_type').val(),
				type: 'post',
				beforeSend: function( xhr ) {
				},
				success: function( responseHtml ) {
					$('#dashboard-overview').html(responseHtml)
				}
			});
		},
		fetchFilterAnalytic: function(index)
		{
			var selection = ['All', 'New Post'];
			$('#dropdown-label').html(selection[index]);
			$('#analytic_filter_type').val(index);
			analytics.fetchProfileAnalytic();
		}
	}
	
	
	
	
	$(document).ready(function() {
		$('.dropdown-toggle').dropdown();
		$('.tabs').button();		
		$('button.btn:eq(<?php echo $this->analyticIndex;?>)').button('toggle');
		
		$('button.btn').click(function(event) { 
			var srcObj = event.src || event.target;
			$('#analytic_group_type').val($(srcObj).attr('data'));
			analytics.fetchProfileAnalytic(); 
		});
	});
</script>
<input type="hidden" name="analytic_group_type" id="analytic_group_type" value="<?php echo $this->analyticType[$this->analyticIndex];?>" />
<input type="hidden" name="analytic_filter_type" id="analytic_filter_type" value="0" />

<div class="btn-group" style="float:left">
	<a class="btn" href="#" id="dropdown-label"><?php echo JText::_('COM_STREAM_LABEL_FILTER_ALL_TYPE'); ?></a>
	<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
		<span class="caret"></span>
	</a>
	<ul class="dropdown-menu">
		<li><a href="#" onclick="analytics.fetchFilterAnalytic('0');"><?php echo JText::_('COM_ANALYTICS_LABEL_ALL');?></a></li>
		<li><a href="#" onclick="analytics.fetchFilterAnalytic('1');"><?php echo JText::_('COM_ANALYTICS_LABEL_NEW_POST');?></a></li>
	</ul>
</div>

<div class="btn-group" data-toggle="buttons-radio" style="float:right">
	<?php foreach ($this->analyticType as $analyticType) { ?>
	<?php
	  /* make the button translateable */
	  switch ($analyticType) {
	  case 'hour':
	  $jtext = JText::_('COM_ANALYTICS_LABEL_BUTTON_HOUR');
	  break;
	  case 'week':
	  $jtext = JText::_('COM_ANALYTICS_LABEL_BUTTON_WEEK');
	  break;
	  case 'day':
	  $jtext = JText::_('COM_ANALYTICS_LABEL_BUTTON_DAY');
	  break;
	  case 'month':
	  $jtext = JText::_('COM_ANALYTICS_LABEL_BUTTON_MONTH');
	  break;
	  case 'year':
	  $jtext = JText::_('COM_ANALYTICS_LABEL_BUTTON_YEAR');
	  break;
	  }
	?>

	<button class="btn" data="<?php echo $analyticType;?>"><?php echo $jtext;?></button>
	<?php } ?>
</div>
<div class="clear"></div>
<br /><br />
<div id="dashboard-overview">	
	<?php echo $this->analyticHtml;?>
</div>
<?php
//echo '<img src="'. JURI::root(). '/components/com_analytics/assets/dashboard_overview.png" >';
//echo '<h3>Engagement</h3>';
//echo '<img src="'. JURI::root(). '/components/com_analytics/assets/dashboard_engagement.png" >';
?>

<?php if ($this->activeUserList) {?>
<h3><?php echo JText::sprintf('COM_ANALYTICS_LABEL_ACTIVE_USER_MONTH', date('F'));?></h3>
<table class="table table-striped table-bordered table-condensed">
<thead>
  <tr>
	<th>#</th>
	<th class="yellow"><?php echo JText::_('COM_ANALYTICS_LABEL_NAME');?></th>
	<th class="blue"><?php echo JText::_('COM_ANALYTICS_LABEL_EMAIL');?></th>
	<th class="green"><?php echo JText::_('COM_ANALYTICS_LABEL_SKILLS');?></th>
	<th class="green"><?php echo JText::_('COM_ANALYTICS_LABEL_WORK_TITLE');?></th>
  </tr>
</thead>
<tbody>
<?php 
$numRow = count($this->activeUserList);
$no = 1;
for ($i = 0; $i < $numRow; $i++) {
	if ($no == 11)
	{
		break;
	}
	
	$user = JXFactory::getUser($this->activeUserList[$i]->user_id);	
	if ($user->id) {
?>
  <tr>
	<td><?php echo $no;?></td>
	<td><a href="<?php echo $user->getURL();?>"><?php echo $this->escape($user->get('name'));?></a></td>
	<td><?php echo $user->get('email');?></td>
	<td><?php echo strip_tags($user->get('work_skills'));?></td>
	<td><?php echo strip_tags($user->get('work_title'));?></td>
  </tr>
<?php 
		$no++;
	} // user->id
}?>
</tbody>
</table>
<?php } ?>

<?php if ($this->activeGroupList) {?>
<h3><?php echo JText::sprintf('COM_ANALYTICS_LABEL_ACTIVE_GROUP_MONTH', date('F'));?></h3>
<table class="table table-striped table-bordered table-condensed">
<thead>
  <tr>
	<th>#</th>
	<th class="yellow"><?php echo JText::_('COM_ANALYTICS_LABEL_GROUP_NAME');?></th>
	<th class="blue"><?php echo JText::_('COM_ANALYTICS_LABEL_GROUP_PARTICIPANT');?></th>
  </tr>
</thead>
<tbody>
<?php 
JTable::addIncludePath(JPATH_ROOT . DS . 'components' . DS . 'com_stream' . DS . 'tables');
$groupTable = JTable::getInstance('Group', 'StreamTable');
$numRow = count($this->activeGroupList);
$no = 1;
for ($i = 0; $i < $numRow; $i++) {
	if ($no == 11)
	{
		break;
	}
	$groupTable->load($this->activeGroupList[$i]->group_id);
	if ($groupTable->id) {
		$memberArr = explode(',', $groupTable->members);
?>
  <tr>
	<td><?php echo $no;?></td>
	<td><a href="<?php echo $groupTable->getUri();?>"><?php echo $this->escape($groupTable->get('name'));?></a></td>
	<td><?php echo count($memberArr);?></td>
  </tr>
<?php 
		$no++;
	} //$groupTable->id
}?>
</tbody>
</table>
<?php } ?>