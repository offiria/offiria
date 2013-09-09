<style type="text/css">
/* Newsfeed */

/* stream topics */
.message-content-topic {
margin:6px 0;
}

.message-content-topic > span {
	background-color: #F9F9F9;
	border:1px solid #ddd;
    border-radius: 4px 4px 4px 4px;
    font-size: 80%;
    margin-right: 2px;
    padding: 2px 4px;
}
<!-- Temporary Scripts -->
</style>
<?php
// Public stream should only be on 'company' view 
$view  		= JRequest::getVar('view', 'company');
$task  		= JRequest::getVar('task', 'display');
$limitstart = JRequest::getVar('limitstart', 0);
$viewOld 	= JRequest::getVar('limitstart', FALSE);

$group_id 		= JRequest::getVar('group_id', '');
$newMoreClass 	= $view.'_updates';
$newMoreClass 	= $group_id ? ' groups_'.$group_id : ' '.$newMoreClass;

$updatedSaperatorShown 	= false;
$lastMessageUpdate		= 0;
$date = new JDate();
?>

<div id="stream-content">

	<div id="flash" align="left" ></div>
	
	<div id='update' class="">
		
		<!-- Placeholder for new message arrivals -->
		<div class="morebox<?php echo $newMoreClass; ?>" id="more" style="display:none">
			<a class="more" data-group_id="<?php echo $group_id; ?>" href="#showNew" data-group_id="<?php echo $group_id; ?>">20 new items</a>
		</div>
		
		<ol id="stream" class="stream">
			
			<?php if($viewOld){ ?>
			<!-- Old/new stream data saperator -->
			<li class="older-stream-separator"><span><?php echo JText::_('COM_STREAM_LABEL_OLDER_MESSAGES');?></span></li>
			<?php } ?>
			
			<?php
			// foreach loop here
			$lastMessageViewOn	= $my->getParam('msg_last_viewon_'.$group_id);
			$updatedSaperatorShown = FALSE;
			$olderSaperatorShown = FALSE;
			$count = 0;
			foreach( $rows as $row ) {
				
				// Only in company or group view
				if( ($view == 'company' || $view =='groups') && $limitstart == 0){
					$msgDate = new JDate( $row->updated );
					// If current message uupdated date is > than last msg view on date
					// show the recently updated bar (if we haven't shown it yet)
					if( $count == 0 ){
						if(!$updatedSaperatorShown && $msgDate->toUnix() > $lastMessageViewOn ) 
						{
							echo '<li id="updated-stream-separator" class="older-stream-separator"><span>'.JText::_('COM_STREAM_LABEL_UPDATED').'</span></li>';
							$updatedSaperatorShown = true;
							$my->setParam('msg_last_viewon_'.$group_id, $msgDate->toUnix());
						}
					} else {
						if($updatedSaperatorShown && !$olderSaperatorShown && $msgDate->toUnix() <= $lastMessageViewOn){
							$olderSaperatorShown = true;
							echo '<li class="older-stream-separator"><span>'.JText::_('COM_STREAM_LABEL_OTHER_MESSAGES').'</span></li>';
						}
					}
					
					$count++;
				}
				//print_r($row); exit;
				// Get the HTML code to append 
				echo $row->getHTML();
			}
			?>
		</ol>
		
		<?php if($total == 0) { ?>
		<div class="alert-message block-message info alert-empty-stream">       
			
<p><?php echo JText::_('COM_STREAM_NO_MESSAGE'); ?></p>        
		</div>
		<?php } ?>
	</div>
</div>

<div class="pagination">
	<?php if($pagination) { echo $pagination->getPagesLinks(); } ?>
</div>
