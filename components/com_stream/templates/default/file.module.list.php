<div class="moduletable">
	<h3><?php echo $title; ?></h3>
	<?php if (!empty($files)) { ?>
	<ul class="reset file-list">
		<?php
		foreach($files as $file){
			$dlLink = JRoute::_('index.php?option=com_stream&view=system&task=download&file_id='.$file->id);
			echo '<li data-filename="'.$file->filename.'" class="message-content-file">';
			echo '<a  title="Click to download" href="'.$dlLink.'">'.StreamTemplate::escape( JHtmlString::truncate($file->filename, 24)).'</a>';
			echo ' <span class="small hint">('.StreamMessage::formatBytes($file->filesize, 1). ')</span>';
			echo '</li>';		
		}
		?>
	</ul>
		<?php if($total > count($files) ) {
			if(!empty($group)) {
				$allLink = JRoute::_('index.php?option=com_stream&view=groups&task=show_files&group_id='.$group->id );
			} else {
				$allLink = JRoute::_('index.php?option=com_stream&view=files&user_id='.$user->id );
			}
		?>
		<!-- more files than meet the eyes -->
		<span><a href="<?php echo $allLink; ?>"><?php echo JText::sprintf('Show all %1s files', $total); ?></a></span>
		<?php } ?>
	<?php } else { ?>
	<div class="alert-message block-message info">
		<p><?php echo JText::_('COM_STREAM_LABEL_NO_RELATED_FILE');?></p>
	</div>
	<?php } ?>
</div>
