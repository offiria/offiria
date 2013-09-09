<?php
$byfilter 	= array('everyone' => JText::_('COM_STREAM_LABEL_DROPDOWN_EVERYONES'), 'mine' => JText::_('COM_STREAM_LABEL_DROPDOWN_MINE'), 'mygroups' => JText::_('COM_STREAM_LABEL_DROPDOWN_MY_GROUPS'));
$filetype 	= JRequest::getVar('filetype', 'all');
$by 		= JRequest::getVar('by', 'everyone');
$bylabel 	= $byfilter[$by]; 
$get 		= JRequest::get('GET');

// Need to get rid of 'limitstart' since we should reset the limit when 
// switching to a different view
if(isset($get['limitstart'])){
	unset($get['limitstart']);
}

if(isset($get['start'])){
	unset($get['start']);
}

?>

<script type="text/javascript">
	
$(document).ready(function() {	

	$('li.switch a').click(function() {
		$('li.switch a').removeClass('active');
		$(this).addClass('active');
	})

	var gridHover = function() {
		$('div.file-list-meta-content', this).show();
	};
	var gridLeave = function() {
		$('div.file-list-meta-content', this).hide();
	}

	$('li.switch a#grid').click(function() {
		$('#file-listing').hide()
			.removeClass('list')
			.addClass('grid')
			.show()
			.data('viewmode', 'grid');
		$("div.file-list-meta-content").hide();
		$(".grid li").bind('hover', gridHover);
		$(".grid li").bind('mouseleave', gridLeave);
	});
	$('li.switch a#list').click(function() {
		$('#file-listing').hide()
			.removeClass('grid')
			.addClass('list')
			.show()
			.data('viewmode', 'list');
		$("div.file-list-meta-content").show();
		$(".list li").unbind('hover');
		$(".list li").unbind('mouseleave');
	});

	// to make sure the view mode is passed through pagination
	$('.pagination li a').click(function(e) {
		e.preventDefault();
		var url = $(this).attr('href') + '#' + $('#file-listing').data('viewmode');
		console.log(url);
		window.location = url;
	});

	// monitor changes in the viewing attribute
	// #param might be passed from previous pagination
	var modeRetrieved = document.URL.match(/#([a-z]+)/)
	var viewMode = (modeRetrieved) ? modeRetrieved[1] : false;
	if (viewMode) {
		$('#file-listing').data('viewmode', viewMode);
		$('li.switch a#'+ viewMode).addClass('active').trigger('click');
	}
});
</script>
<ul class="nav nav-pills filter group-filter">
	<!-- <input type="text" class="input-medium search-query" style="float: left; width: 82px; margin: 4px 10px 4px 0px;"> -->
	
	<li <?php if($filetype == 'all') echo 'class="active"'; ?>>
		<a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('filetype' => 'all' )) ) ); ?>"><?php echo JText::_('COM_STREAM_LABEL_FILTER_ALL_TYPE'); ?></a>
	</li>
	<li <?php if($filetype == 'documents') echo 'class="active"'; ?>><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('filetype' => 'documents' )) ) ); ?>"><?php echo JText::_('COM_STREAM_LABEL_FILTER_DOCUMENT'); ?></a></li>
	<li <?php if($filetype == 'images') echo 'class="active"'; ?>><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('filetype' => 'images' )) ) ); ?>"><?php echo JText::_('COM_STREAM_LABEL_FILTER_IMAGES'); ?></a></li>
	
	<li class="switch pull-right">
		<div class="btn-group">
			<a id="list" class="btn btn-small active" href="javascript:void(0)"><span></span></a>
			<a id="grid" class="btn btn-small" href="javascript:void(0)"><span></span></a>
		</div>
	</li>
	
	<?php if($showOwnerFilter){ ?>
	<li class="dropdown pull-right">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">     <?php echo $bylabel; ?>   <b class="caret"></b>
		</a>
		
		<ul class="dropdown-menu">
			<li><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('by' => 'everyone' )) ) ); ?>"><?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_EVERYONES'); ?></a></li>
			<li class="divider"></li>            
			<li><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('by' => 'mine' )) ) ); ?>"><?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_MINE'); ?></a></li>
			<?php if (JRequest::getVar('group_id', '0') == '0') { ?>
			<li><a href="<?php echo JRoute::_('index.php?' . http_build_query( array_merge($get, array('by' => 'mygroups' )) ) ); ?>"><?php echo JText::_('COM_STREAM_LABEL_DROPDOWN_MY_GROUPS'); ?></a></li>	
			<?php } ?>
		</ul>
	</li>
	
	
	<?php } ?>
</ul>

