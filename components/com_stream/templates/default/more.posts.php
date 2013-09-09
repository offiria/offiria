<?php
//hardcoded
$msg_id = 7;
$lowest_msg_id = 6;
?>
<?php if($lowest_msg_id==$msg_id){ ?>

<div id="more<?php echo $msg_id; ?>" class="morebox"><?php echo JText::_('COM_STREAM_LABEL_NO_MORE_NEWS');?></div>

<?php } else { ?>

<div id="more<?php echo $msg_id; ?>" class="morebox">
	<a href="#" class="more" id="<?php echo $msg_id; ?>"><?php echo JText::_('COM_STREAM_LABEL_MORE');?></a>
</div>

<?php } ?>