<?php
$document = JFactory::getDocument();
$docTitle = $document->getTitle();
$task = JRequest::getVar('task');
?>

<div class="customlist-page-title clearfix">
	<form method="post" action="<?php echo JRoute::_('index.php?option=com_stream&view=customlist&task=saveCustomlist&customlist_id=' . $customList->id); ?>">
		<div class="customlist-page-title-inner pull-left">
			<h1><?php echo StreamTemplate::escape($customList->title); ?></h1>

			<?php if(empty($filterArr)): ?>

			<div class="customlist-filter clearfix" id="cl-view-show" style="display: block">
				<span><?php echo JText::_('COM_STREAM_LABEL_NOFILTERSAPPLIED'); ?></span>
			</div>

			<?php else: ?>

			<div class="customlist-filter clearfix" id="cl-view-show" style="display: block">
				<span><?php echo JText::_('COM_STREAM_LABEL_STREAM_MESSAGES'); ?></span>
				<?php if(isset($filterArr['group_ids'])): ?>
				<span class="filtered-item groups">in <strong><?php echo count($filterArr['group_ids']); ?> Group</strong></span>
				<?php endif; ?>
				<?php if(isset($filterArr['user_ids'])): ?>
				<span class="filtered-item people">by <strong><?php echo count($filterArr['user_ids']); ?> People</strong></span>
				<?php endif; ?>
				<?php if(isset($filterArr['tags'])): ?>
				<span class="filtered-item tags">with <strong><?php echo count($filterArr['tags']); ?> Tag</strong></span>
				<?php endif; ?>
				<?php if(isset($filterArr['create_start'])): ?>
				<span class="filtered-item tags">from <strong><?php echo StreamTemplate::escape($filterArr['create_start'][0]); ?></strong></span>
				<?php endif; ?>
				<?php if(isset($filterArr['create_end'])): ?>
				<span class="filtered-item tags">to <strong><?php echo StreamTemplate::escape($filterArr['create_end'][0]); ?></strong></span>
				<?php endif; ?>
			</div>

			<?php endif; ?>

			<div class="customlist-filter clearfix" id="cl-view-edit" style="display: none">
				<div class="cl-text pull-left">Messages</div>
				<ul class="customlist-list clearfix pull-left">
					<li class="filter-item groups">
						<span class="pull-left">in</span>
						<div class="btn-group">
							<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
								<span class="filter-count"><?php echo (isset($filterArr['group_ids'])) ? count($filterArr['group_ids']) : ''; ?></span> Group
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li class="cl-search">
									<input type="text" placeholder="Search Groups" id="tfk-input-groups"/>
									<input type="hidden" class="cl-selected-input" name="group_ids" />
								</li>
								<li class="cl-list-item tfk-selected" style="display: none"></li>
								<li class="cl-list-item tfk-suggested" style="display: none"></li>
							</ul>
						</div>
					</li>

					<li class="filter-item people">
						<span class="pull-left">by</span>
						<div class="btn-group">
							<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
								<span class="filter-count"><?php echo (isset($filterArr['user_ids'])) ? count($filterArr['user_ids']) : ''; ?></span> People
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li class="cl-search">
									<input type="text" placeholder="Search People" id="tfk-input-people"/>
									<input type="hidden" class="cl-selected-input" name="user_ids" />
								</li>
								<li class="cl-list-item tfk-selected" style="display: none"></li>
								<li class="cl-list-item tfk-suggested" style="display: none"></li>
							</ul>
						</div>
					</li>

					<li class="filter-item tags">
						<span class="pull-left">with</span>
						<div class="btn-group">
							<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
								<span class="filter-count"><?php echo (isset($filterArr['tags'])) ? count($filterArr['tags']) : ''; ?></span> Tag
								<span class="caret"></span>
							</a>
							<ul class="dropdown-menu">
								<li class="cl-search">
									<input type="text" placeholder="Search Tags" id="tfk-input-tags"/>
									<input type="hidden" class="cl-selected-input" name="tags" />
								</li>
								<li class="cl-list-item tfk-selected" style="display: none"></li>
								<li class="cl-list-item tfk-suggested" style="display: none"></li>
							</ul>
						</div>
					</li>

					<li class="filter-item date">
						<span class="pull-left">from</span>
						<input type="text" name="create_start" style="width:80px;" class="hasdatepicker" value="<?php echo (isset($filterArr['create_start'])) ? $filterArr['create_start'][0] : ''; ?>" placeholder="from" id="tfk-input-from"/>
					</li>

					<li class="filter-item tags">
						<span class="pull-left">to</span>
						<input type="text" name="create_end" style="width:80px;" class="hasdatepicker" value="<?php echo (isset($filterArr['create_end'])) ? $filterArr['create_end'][0] : ''; ?>" placeholder="To" id="tfk-input-to"/>
					</li>
				</ul>
				<div class="clear"></div>
			</div>
		</div>

		<div class="customlist-page-action pull-right">
			<div class="btn-group pull-right" id="cl-menu" style="display: block;">
				<button class="btn dropdown-toggle" data-toggle="dropdown"><span class="cl"><span class="caret"></span></span></button>
				<ul class="dropdown-menu">
					<li><a href="#edit">Edit List</a></li>
					<li><a href="#rename" data-customlist_id="<?php echo $customList->id; ?>">Rename List</a></li>
					<li><a href="#delete" data-customlist_id="<?php echo $customList->id; ?>">Delete List</a></li>
				</ul>
			</div>
			<div id="cl-btn-editcancel" style="display: none;">
				<a class="btn" href="#cancel"><?php echo JText::_('Cancel'); ?></a>
				<button class="btn btn-primary" type="submit"><?php echo JText::_('Save'); ?></button>
			</div>
			<div class="clear"></div>
		</div>
	</form>
</div>

<script src="<?php echo JURI::root();?>media/jquery/bootstrap-xtypeahead.js" type="text/javascript"></script>
<script type="text/javascript">
$(function() {
	$('.dropdown-menu li').on('click', function(e) {
		e.stopPropagation();
	});

	var toggleEdit = function() {
		$('#cl-view-edit, #cl-btn-editcancel').toggle();
		$('#cl-view-show, #cl-menu').toggle();
	};

	$('.customlist-page-title a[href="#cancel"], .customlist-page-title a[href="#edit"]').on('click', function(e) {
		e.preventDefault();
		toggleEdit();
	});

	$('.customlist-page-title a[href="#rename"]').on('click', function(e) {
		e.preventDefault();
		S.customlist.create(this);
	});

	$('.customlist-page-title a[href="#delete"]').on('click', function (e) {
		e.preventDefault();

		var customlist_id = $(this).data('customlist_id');

		if (confirm('Are you sure you want to delete this list?')) {
			$.ajax({
				type:"POST",
				url:S.path['customlist.delete'],
				data:{customlist_id:customlist_id},
				cache:false,
				dataType:'json',
				success:function (data) {
					window.location = (data.redirect);
				}
			});
		}
	});

	if(document.location.hash == '#edit') {
		toggleEdit();
	}

	$('#tfk-input-groups').xtypeahead({
		source:function(typeahead, query) {
			$.ajax({
				type:"POST",
				url:S.path['group.xautocomplete'],
				data:{query:query},
				cache:false,
				dataType:'json',
				success:function (data) {
					typeahead.process(data);
				}
			});
		}
		,preloadedsource: <?php echo (isset($preloadedSource['group_ids'])) ? json_encode($preloadedSource['group_ids']) : '[]'; ?>
	});

	$('#customlist-edit-message-modal').live('hidden', function () {
		$(this).remove();
	});

	$('#tfk-input-people').xtypeahead({
		source:function(typeahead, query) {
			$.ajax({
				type:"POST",
				url:S.path['people.xautocomplete'],
				data:{query:query},
				cache:false,
				dataType:'json',
				success:function (data) {
					typeahead.process(data);
				}
			});
		}
		,thumbsonly:true
		,preloadedsource: <?php echo (isset($preloadedSource['user_ids'])) ? json_encode($preloadedSource['user_ids']) : '[]'; ?>
	});

	$('#tfk-input-tags').xtypeahead({
		source:function(typeahead, query) {
			$.ajax({
				type:"POST",
				url:S.path['tag.xautocomplete'],
				data:{query:query},
				cache:false,
				dataType:'json',
				success:function (data) {
					typeahead.process(data);
				}
			});
		}
		,preloadedsource: <?php echo (isset($preloadedSource['tags'])) ? json_encode($preloadedSource['tags']) : '[]'; ?>
		,selectedproperty: 'value'
	});

	$( "input.hasdatepicker" ).datepicker({ 
		dateFormat: 'yy-mm-dd'
	});
});
</script>