<?php
$uri = JURI::getInstance();
$uriBase = $uri->toString( array('scheme', 'host', 'port'));
?>

<div class="moduletable">
	<h3><?php echo $title; ?></h3>
	<?php if(count($trendingTags)) : ?>
		<ul class="trend-tags clearfix">
			<?php
			foreach ($trendingTags as $tag) :
				$tagLink = $uriBase . JRoute::_('index.php?option=com_stream&view=company&task=tagFilter&tag=') . urlencode($tag->tag);
				$tagLink .= (isset($groupId) && $groupId > 0) ? '&tagGroupId=' . $groupId : '';
				?>
				<li><a href="<?php echo $tagLink; ?>"><?php echo $tag->tag; ?></a></li>
			<?php endforeach; ?>
		</ul>
	<?php else : ?>
		<div class="alert-message block-message info">
			<p><?php echo JText::_('COM_STREAM_LABEL_NO_TAGS_ASSIGNED'); ?></p>
		</div>
	<?php endif; ?>
</div>