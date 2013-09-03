<?php
defined('_JEXEC') or die();

if(!empty($messages))
{
?>

<script src="<?php echo JURI::root(); ?>/components/com_messaging/assets/scripts.js" type="text/javascript"></script>

<div class="inbox-message-heading">
	<?php if(JRequest::getVar('dev') == '1') : ?>
	<div style="margin-bottom: 20px;">
		<form id="messaging-add-participant" action="<?php echo JRoute::_('index.php?option=com_messaging&task=addParticipant&msgid=' . $msgid); ?>" method="post" class="form-validate">
			<div>
				<label for="participant-id" title="Participant ID"><?php echo JText::_('ADD PARTICIPANT ID') ?></label>
				<input id="participant-id" type="text" name="participantId" class="" title="Username" />
				<input type="submit" class="btn btn-info submit" value="<?php echo JText::_('JSUBMIT'); ?>">
			</div>
		</form>
	</div>
	<?php endif; ?>

	<div id="addGroupMemberField">
		<form action="" class="well form-search" _lpchecked="1" style="padding: 5px">
			<div class="stream-post-details" style="margin: 0; padding: 0; background-color: #FFFFFF;">
				<ol class="recipient-list" style="padding: 0; margin: 0;">
					<?php
					foreach ($recipient as $row) {
						$user = JXFactory::getUser($row->to);

						echo '<li>';
						echo '<img class="attendee-avatar" src="' . $user->getThumbAvatarURL() . '" />';
						echo '<span>' . $user->name . '</span>';
						if($parentData->from == $my->id && count($recipient) > 1) {
							// Disabled until we figure out the proper way to remove participants
							//echo '<a class="close small" data-userid="' . $user->id . '">×</a><input type="hidden" value="61" name="members[]">';
						}
						echo '</li>';
					}
					?>
					<?php if($parentData->from == $my->id): ?>
					<input placeholder="<?php echo JText::_('COM_COMMUNITY_INBOX_ADD_RECIPIENT'); ?>..."
						   style="float:left; height: 33px; width: 150px; padding: 0;" type="text"
						   autocomplete="off" class="recipient-input recipient-typeahead" name="recipient"/>
				   <?php endif; ?>
				</ol>
				<div class="clear"></div>
			</div>
		</form>
		<div class="clear"></div>
	</div>

	<?php echo $messageHeading;?>
</div>

<div id="inbox-messages">
	<div id="inbox-message">
		<div class="inbox-table thread">
			<ul class="nav">
				<?php echo $htmlContent; ?>
			</ul>
		</div>
	</div>
	<div class="clr"></div>
</div>

<a name="latest"></a>

<div class="clr"></div>

<div class="inbox-reply">
	<div class="user-avatar">
		<?php
		$user = JXFactory::getUser();
		?>
		<img class="cAvatar"border="0" author="85" alt="" src="<?php echo $user->getThumbAvatarURL(); ?>" />
	</div>
	<form id="inbox-reply-form" action="" method="post">
		<div class="reply-box">
			<textarea id="replybox" placeholder="<?php echo JText::_('COM_COMMUNITY_COMPOSE_MESSAGE'); ?>..."></textarea>
		</div>
		<div class="inbox-reply-button">
			<input type="hidden" name="action" value="doSubmit" />
			<ul id="pm-attachment-list" class="pm-attachment"></ul>
			<hr style="margin: 8px 0 5px;" />
			
			<div id="pm-file-uploader" class="pull-left">
				<noscript>
					<p>Please enable JavaScript to use file uploader.</p>
					<!-- or put a simple form for upload here -->
				</noscript>
			</div>
			<div style="float: left; margin-top: 4px; display: none;">
				<input type="dropbox-chooser" name="selected-file" id="db-chooser" />
			</div>
			<script type="text/javascript">
			    document.getElementById("db-chooser").addEventListener("DbxChooserSuccess",
			        function(e) {
			            $('ul#pm-attachment-list').append('<li style="list-style: none outside none;margin-left:0px;">' +
							'<div class="message-content-file"><span class="qq-upload-file"><a href="' + e.files[0].link + '" target="_blank">' + e.files[0].name + '</a></span>' +
							//'<span class="qq-upload-spinner"></span>' +
							//'<span class="qq-upload-size"></span>' +
							'<a class="qq-upload-cancel" href="#">Cancel</a>' +
							'<span class="qq-upload-failed-text">Failed</span>' +
							'<input type="hidden" name="attachment[]" value="" />' +
							'</div>' +
							'</li>');
			        }, false);
			</script>

			<button id="replybutton" class="btn btn-primary ajax-wait submit pull-right"><?php echo JText::_('COM_COMMUNITY_ADD_REPLY_BUTTON'); ?></button>
			<div class="clear"></div>
		</div>
	</form>
</div>

<script type="text/javascript">
$(function() {
	Messaging.reply.init(<?php echo $msgid; ?>);
});
</script>

<?php } else { ?>

<?php echo $htmlContent; ?>

<?php } ?>