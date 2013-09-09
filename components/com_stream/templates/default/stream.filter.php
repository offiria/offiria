<form>
<?php
$group_id = JRequest::getVar("group_id");
?>
<?php /*
<div id="stream-tab">
	<ul class="tabs">
		<li class="active"><a group_id="<?php echo $group_id; ?>" href="#filterAll"><?php echo JText::_('COM_STREAM_LABEL_ALL_ACTIVITIES');?></a></li>
		<?php 
		// If I am not a member of the group, maybe we should not show these filters
		if( JXUtility::csvExist($my->getParam('groups_member'), $group_id) || empty($group_id)) {
		?>
		<li><a group_id="<?php echo $group_id; ?>" user_id="<?php echo $my->id; ?>" href="#filterMine"><?php echo JText::_('COM_STREAM_LABEL_MY_ACTIVITIES');?></a></li>
		<li><a group_id="<?php echo $group_id; ?>" search="@<?php echo $this->escape($my->name); ?>" href="#filterMentions">@<?php echo JText::_('COM_STREAM_LABEL_MENTIONS');?></a></li>
		<li><a group_id="<?php echo $group_id; ?>" overdue="true" href="#filterDue"><?php echo JText::_('COM_STREAM_LABEL_TODAY_OVERDUE');?></a></li>
		<?php } ?>
	</ul>
</div>
*/?>
<?php
$baseLink		= ($group_id) ? 'index.php?option=com_stream&view=groups&task=show&group_id='.$group_id : 'index.php?option=com_stream&view=company';
$myLink			= JURI::base() . JRoute::_($baseLink. '&user_id='.$my->id);
$mentionLink	= JURI::base() . JRoute::_($baseLink. '&mention='.$this->escape($my->username) );
$overdueLink	= JURI::base() . JRoute::_($baseLink. '&overdue=true' );
$get			= JRequest::get('GET');

$myActive	= JRequest::getVar('user_id') ? ' class="active" ': '';
$mnActive	= JRequest::getVar('search') ? ' class="active" ': '';
$odActive	= JRequest::getVar('overdue') ? ' class="active" ': '';
$allActive	= (empty($myActive) && empty($mnActive) && empty($odActive)) ? ' class="active" ': '';


?>
<ul class="nav nav-tabs stream-filter">
  	<li <?php echo $allActive; ?>><a data-filter-data="group_id=<?php echo $group_id; ?>&uri=<?php echo urlencode( JURI::base() . JRoute::_($baseLink) );?>" href="#filterMassage"><?php echo JText::_('COM_STREAM_LABEL_ALL_ACTIVITIES');?></a></li>
	<li <?php echo $myActive; ?>><a data-filter-data="group_id=<?php echo $group_id; ?>&user_id=<?php echo $my->id; ?>&uri=<?php echo urlencode( $myLink );?>" href="#filterMassage" href="#filterMassage"><?php echo JText::_('COM_STREAM_LABEL_MY_ACTIVITIES');?></a></li>
	<li <?php echo $mnActive; ?>><a data-filter-data="group_id=<?php echo $group_id; ?>&uri=<?php echo urlencode( $mentionLink );?>&mention=<?php echo $this->escape($my->username); ?>" href="#filterMassage">@<?php echo JText::_('COM_STREAM_LABEL_MENTIONS');?></a></li>
	<li <?php echo $odActive; ?>><a data-group_id="<?php echo $group_id; ?>" data-filter-data="group_id=<?php echo $group_id; ?>&uri=<?php echo urlencode( $overdueLink );?>&overdue=true"  overdue="true" href="#filterMassage"><?php echo JText::_('COM_STREAM_LABEL_TODAY_OVERDUE');?></a></li>
	<?php if(empty($group_id)){ ?>
	<li class="dropdown pull-right">
		<a class="search-stream" href="#search_stream"><?php echo JText::_('COM_STREAM_SEARCH_UPDATES'); ?></a>
		<?php /*
		<a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_ALL'); ?> <b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a href="#"><?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_MY_GROUP'); ?></a></li>
			<li><a href="#"><?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_I_FOLLOW'); ?></a></li>
			<li><a href="#"><?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_IMPORTANT'); ?></a></li>
		</ul>
		*/?>
	</li>
	<?php } else { ?>
	<li class="dropdown pull-right">
		<a class="search-stream" href="#search_stream"><?php echo JText::_('COM_STREAM_SEARCH_UPDATES'); ?></a>
		<?php /*
		<a href="#search_stream"><?php echo JText::_('COM_STREAM_SEARCH_UPDATES'); ?></a>
		<a href="#" data-toggle="dropdown" class="dropdown-toggle"><?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_ALL'); ?> <b class="caret"></b></a>
		<ul class="dropdown-menu">
			<li><a href="#"><?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_ALL'); ?></a></li>
			<li><a href="#"><?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_IMPORTANT'); ?></a></li>
		</ul>
		*/?>
	</li>
	<?php } ?>
</ul>

</form>

<form class="form-filter well form-search" style="display:none" action="<?php echo JRoute::_('index.php?option=com_search'); //JRoute::_('index.php?' . http_build_query( $get ) ); ?>" method="get">
  <?php if(!empty($group_id)){ ?>	
  <input type="hidden" class="" name="areas" value="<?php echo $group_id;?>">
  <?php } else { ?>
  <input type="hidden" class="" name="searchphrase" value="streams">
  <?php } ?>
  <input type="text" class="input-medium search-query" name="searchword">
  <button class="btn" type="submit"><?php echo JText::_('PLG_SEARCH_LABEL_SEARCH');?></button>
</form>