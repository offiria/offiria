<div class="moduletable">
	<h3><?php echo $title; ?></h3>
	<ul class="grouplist">
		<?php foreach($groups as $group) {
			$user = JXFactory::getUser($group->creator); 	
		?>
		<li class="">
			<a href="<?php echo JRoute::_('index.php?option=com_stream&view=groups&task=show&group_id='.$group->id); ?>" ><?php echo $this->escape($group->name); ?></a> by <em><?php echo $this->escape($user->name); ?></em>
		</li>
		<?php } 
		if (empty($groups)) { 
		?>
		<div class="alert-message block-message info">       
			<p><?php echo JText::_('COM_STREAM_LABEL_NO_NEW_GROUP');?></p>        
		</div>
		<?php } ?>
	</ul>
</div>