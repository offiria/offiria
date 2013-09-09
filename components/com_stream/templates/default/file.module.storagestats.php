<div class="moduletable">
<h3><?php echo $title; ?></h3>
	<!-- Progress bar start -->
	<!--
	<div class="ui-progress-bar ui-container" id="progress_bar">
		  <div style="display: block; width: <?php echo ($used/$total); ?>%;" class="ui-progress">
	    	<span style="display: none;" class="ui-label">Done</span>
	  	</div>
	</div>
	-->
	<!-- Progress bar end -->
	<?php if ((int)$total > 0) { ?>
	<div id="storage-limit-progress" class="progress progress-info progress-striped">
    	<div class="bar" style="width: <?php echo ($used/$total)*100; ?>%;"></div>
    </div>
	<?php } ?>

	<div class="storage-limit-desc">
		<span class="small"><?php echo ((int)$total > 0) ? JText::sprintf('COM_STREAM_LABEL_STORAGE_USAGE_OUT_OF', StreamMessage::formatBytes($used), StreamMessage::formatBytes($total)) : StreamMessage::formatBytes($used).' used.'; ?></span>
	</div>
	
</div>
