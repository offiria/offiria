<div class="blue-button">
	<a class="btn btn-info" href="<?php echo JRoute::_('index.php?option=com_stream&view=company'); ?>#newTodo">
		<i class="icon-plus icon-white"></i><?php echo JText::_('COM_STREAM_BUTTON_LABEL_NEW_TODO'); ?>
	</a>
</div>
<?php
$byfilter 	= array('everyone' => JText::_('COM_STREAM_LABEL_DROPDOWN_EVERYONES'), 'mine' => JText::_('COM_STREAM_LABEL_DROPDOWN_MINE'), 'mygroups' => JText::_('COM_STREAM_LABEL_DROPDOWN_MY_GROUPS'));
$filetype 	= JRequest::getVar('filetype', 'all');
$by 		= JRequest::getVar('by', 'everyone');
$bylabel 	= $byfilter[$by]; 
$get 		= JRequest::get('GET');
$groupId	= JRequest::getVar('group_id', 0);

// Need to get rid of 'limitstart' since we should reset the limit when 
// switching to a different view
if(isset($get['limitstart'])){
	unset($get['limitstart']);
}

if(isset($get['start'])){
	unset($get['start']);
}

?>
<?php
$status = JRequest::getVar('status', '');
?>
<ul class="nav nav-pills filter group-filter">

	<!-- <input type="text" class="input-medium search-query" style="float: left; width: 82px; margin: 4px 10px 4px 0px;"> -->

	<li <?php if($status === '') echo 'class="active"'; ?>><a href="<?php echo JRoute::_('index.php?'. http_build_query( array_merge($get,
																																	array('by'	   => $by, 'status' => '' )) )); ?>"><?php echo JText::_('COM_STREAM_LABEL_TODO_ALL'); ?></a></li>
	<li <?php if($status === '0') echo 'class="active"'; ?>><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get,
																																	array('status' => '0' )) )); ?>"><?php echo JText::_('COM_STREAM_LABEL_TODO_PENDING'); ?></a></li>
	<li <?php if($status === '1') echo 'class="active"'; ?>><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get,
																																	array('status' => '1' )) )); ?>"><?php echo JText::_('COM_STREAM_LABEL_TODO_COMPLETED'); ?></a></li>
		<li style="float:right" class="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">     <?php echo $bylabel; ?>   <b class="caret"></b>
		</a>
		
		<ul class="dropdown-menu">
			<li><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('by' => 'everyone' )) ) ); ?>"><?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_EVERYONES'); ?></a></li>
			<li class="divider"></li>            
			<li><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('by' => 'mine' )) ) ); ?>"><?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_MINE'); ?></a></li>
			<?php if (!$groupId) { ?>
			<li><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('by' => 'mygroups' )) ) ); ?>"><?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_MY_GROUPS'); ?></a></li>	
			<?php } ?>
		</ul>
	</li>
</ul>