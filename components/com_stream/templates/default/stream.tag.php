<?php
$uri = JURI::getInstance();
$uriBase = $uri->toString( array('scheme', 'host', 'port'));

$data = json_decode($stream->raw);

$tags = array();
if (isset($data->tags) && strlen($data->tags) > 0) {
	$tags = explode(',', $data->tags);

	$removeHashesCallback = create_function('$value', 'if($value[0] == "#" && $value[strlen($value)-1] == "#") { return substr($value,1,strlen($value)-2); } else { return $value; }');
	$tags = array_map($removeHashesCallback, $tags);
}
?>
<div class="message-content-tag-error"></div>
<div class="message-content-tag-parent">
	<div class="message-content-tag small">
		<i class="icon-tag"></i>
		
		<div class="content-tag-container">
			<?php
				$tagsOutput = array();
				$groupId = JRequest::getInt('group_id', 0);

				foreach ($tags as $tag) {
					$tagLink = $uriBase . JRoute::_('index.php?option=com_stream&view=company&task=tagFilter&tag=') . urlencode($tag);
					//$tagLink .= ($groupId > 0) ? '&tagGroupId=' . $groupId : '';
					$tagsOutput[] = '<span class="label"><a href="' . $tagLink . '">' . $tag . '</a></span>';
				}

				echo implode("", $tagsOutput);
			?>

			<?php
				if(count($tags)) {
					echo '<span class="edit-tags"><a href="#editTags">'.JText::_('COM_STREAM_LABEL_EDIT_TAGS').'</a></span>';
				} else {
					echo '<span class="edit-tags"><a href="#editTags">'.JText::_('COM_STREAM_LABEL_ADD_TAGS').'</a></span>';
				}
			?>
		
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>

	<?php // TODO: ajax/dynamic ?>
	<div class="message-content-tag-edit tag-container-parent" style="display: none;">
		<i class="icon-tag"></i>
		<div class="content-tag-container">
			
			<div>
			<ol class="tag-container clearfix">
			<?php
				foreach($tags as $tag) {
				echo '<li class="tag-element">
					<span title=""><a href="#">' . $tag . '</a></span>
					<span class="tag-remove close small">x</span>
				</li>';
				}
			?>
			</ol>
			</div>
			
			<div class="clear"></div>
			
			<div class="form-tags">
				<form>
					<input type="text" class="tag-input tag-typeahead" maxlength="20"/>
					<div class="btn btn-primary tag-add"><?php echo JText::_('COM_STREAM_LABEL_ADD_TAG'); ?></div>
					<div class="btn tag-cancel"><?php echo JText::_('COM_STREAM_LABEL_FINISH_TAGGING'); ?></div>
				</form>
				
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
		</div>
		
		<div class="clear"></div>
	</div>
</div>
<div class="clear"></div>