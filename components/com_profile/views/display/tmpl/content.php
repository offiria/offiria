<div class="data-grid">
	
	<div class="content-title">
		<h3>Blogs</h3>
		<a href="<?php echo JRoute::_('index.php?option=com_stream&view=blog&user_id='.$this->user->id);?>">Show All (<?php echo $this->blogCount; ?>)</a>		
	</div>
	
	<ol class="content-list">
		<?php foreach($this->blogs as $blog){
			$date = new JDate($blog->created); 
		?>
			<li><a href="<?php echo $blog->getUri(); ?>"><?php echo StreamMessage::format($blog->getData()->title); ?></a> 
				<span class="small hint">(<?php echo $date->format(JText::_('JXLIB_DATE_SHORT_FORMAT')); ?>	)</span>
			</li>
		<?php } ?>
	</ol>

</div>

<div class="data-grid">
	
	<div class="content-title">
		<h3>Files</h3>
		<a href="<?php echo JRoute::_('index.php?option=com_stream&view=files&user_id='.$this->user->id);?>">Show All (<?php echo $this->fileCount; ?>)</a>		
	</div>
	
	<ol class="content-list">
		<?php
		foreach($this->files as $file){
			$dlLink = JRoute::_('index.php?option=com_stream&view=system&task=download&file_id='.$file->id);
			echo '<li class="message-content-file">';
			echo '<a  title="Click to download" href="'.$dlLink.'">'.StreamTemplate::escape( JHtmlString::truncate($file->filename, 24)).'</a>';
			echo ' <span class="small hint">('.StreamMessage::formatBytes($file->filesize, 1). ')</span>';
			echo '</li>';		
		}
		?>
	</ol>

</div>

<div class="data-grid">
	
	<div class="content-title">
		<h3>Links</h3>
		<a href="<?php echo JRoute::_('index.php?option=com_stream&view=links&user_id='.$this->user->id);?>">Show All (<?php echo $this->linkCount; ?>)</a>		
	</div>
	
	<ol class="content-list">
		<?php foreach($this->links as $link){ ?>
			<li><?php echo StreamMessage::format($link->link); ?></li>
		<?php } ?>
	</ol>

</div>