<?php

$byfilter 	= array('everyone' => JText::_('COM_STREAM_FILTER_LABEL_EVERYONE'), 'mine' => JText::_('COM_STREAM_LABEL_DROPDOWN_MINE'), 'mygroups' => JText::_('COM_STREAM_LABEL_DROPDOWN_MY_GROUPS'));
$filter 	= JRequest::getVar('filter', 'all');
$by 		= JRequest::getVar('by', 'everyone');
$bylabel 	= $byfilter[$by]; 
$get 		= JRequest::get('GET');
$Category = new StreamCategory();
$categories = $Category->getGroups();

?>
<ul class="nav nav-pills filter">
	<!-- <input type="text" class="input-medium search-query" style="float: left; width: 82px; margin: 4px 10px 4px 0px;"> -->
	
	<li <?php if($filter == 'all') echo 'class="active"'; ?>><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('filter' => 'all' )) ) ); ?>"><?php echo JText::_('COM_STREAM_FILTER_LABEL_ALL_GROUPS'); ?></a></li>
	<li <?php if($filter == 'joined') echo 'class="active"'; ?>><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get,array('filter' => 'joined' )) ) ); ?>"><?php echo JText::_('COM_STREAM_FILTER_LABEL_JOINED');?></a></li>
	<li <?php if($filter == 'followed') echo 'class="active"'; ?>><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('filter' => 'followed' )) ) ); ?>"><?php echo JText::_('COM_STREAM_FILTER_LABEL_FOLLOWED');?></a></li>
	<li <?php if($filter == 'archived') echo 'class="active"'; ?>><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('filter' => 'archived' )) ) ); ?>"><?php echo JText::_('COM_STREAM_FILTER_LABEL_ARCHIVED');?></a></li>

	<?php if (count($categories) > 0) : ?>
	<?php // active class is added to nav-pills if this is the user is filtering category ?>
	<li class="pull-right dropdown <?php echo (JRequest::getVar('filter') == 'category') ? 'active' : ''?>">
	<a class="dropdown-toggle" data-toggle="dropdown" href="#">
		<?php 
		if (JRequest::getVar('filter') == 'category') {
			echo JText::sprintf('COM_STREAM_LABEL_FILTER_BY_CATEGORY_PROVIDED', $Category->getCategoryName(JRequest::getVar('category_id')));
		}
		else {
			echo JText::_('COM_STREAM_LABEL_FILTER_BY_CATEGORY');
		}
		?>
		<b class="caret"></b>
		</a>
		<ul class="dropdown-menu pull-right">
			<?php 
			  foreach ($categories as $category) { ?>
			<li><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('filter' => 'category', 'category_id'=>$category->id )) ) ); ?>"><?php echo $category->category; ?></a></li>
			
			<?php }	?>
		</ul>
	</li>
	<?php endif; ?>
</ul>

