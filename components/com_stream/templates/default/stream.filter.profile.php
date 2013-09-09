<form>
<?php
//print_r($_SERVER); exit;
$group_id = JRequest::getVar("group_id", 0);
$my = JXFactory::getUser();
?>
<ul class="nav nav-tabs">
	<li class="active"><a data-filter-data="user_id=<?php echo $user->id; ?>&uri=<?php echo urlencode( JURI::current() );?>"  href="#filterMassage">
	<?php if ($my->id == $user->id): ?>
	<?php echo JText::_('COM_STREAM_LABEL_MY_ACTIVITIES');?>
	<?php else: ?>
	<?php echo JText::_('COM_STREAM_LABEL_THEIR_ACTIVITIES');?>
	<?php endif;?>
</a></li>
<li><a data-filter-data="search=@<?php echo $this->escape($user->username); ?>&uri=<?php echo urlencode(JURI::current());?>" href="#filterMassage">@<?php echo JText::_('COM_STREAM_LABEL_MENTIONS');?></a></li>
	<li><a data-group_id="<?php echo $group_id; ?>" overdue="true" href="#filterDue"><?php echo JText::_('COM_STREAM_LABEL_TODAY_OVERDUE');?></a></li>
</ul>
</form>