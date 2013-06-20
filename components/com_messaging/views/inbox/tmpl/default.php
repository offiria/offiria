<?php
defined('_JEXEC') or die;
?>

<script src="<?php echo JURI::root(); ?>components/com_messaging/assets/scripts.js" type="text/javascript"></script>

<div class="tabbable" style="display: none;">
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab1" data-toggle="tab">Received</a></li>
		<li><a href="#tab2" data-toggle="tab">Sent</a></li>
	</ul>
</div>

<div class="blue-button compose-direct-message" style="display: block;">
	<a class="btn btn-info" href="#" id="direct-message-compose"><i class="icon-plus icon-white"></i><?php echo JText::_('COM_COMMUNITY_INBOX_COMPOSE'); ?></a>
</div>

<?php
  $my = JXFactory::getUser();
  if(! $my->getParam(ALERT_PRIVATE_MESSAGING)) :
?>
<div class="alert alert-success" data-alert_id="<?php echo ALERT_PRIVATE_MESSAGING; ?>" >
	<a data-dismiss="alert" class="close">×</a>
	<?php echo JText::_('COM_STREAM_HELPER_DIRECT'); ?>
</div>
<?php endif; ?>
<div id="direct-message" style="display: <?php echo (!is_null($this->toUser)) ? 'block' : 'none'; ?>;">
	<form id="messaging-write-form">
		<div class="modal-body" style="padding: 0; overflow-y: visible;">
			<div id="stream-post">
				<div class="stream-post-message">
					<div id="stream-post-update" class="stream-post-message-share tab-content" style="display: block;">
						<div class="stream-post-details" style="margin: 0 0 10px 0;">
							<ol class="recipient-list">
								<?php if(!is_null($this->toUser)): ?>
								<li style=""><span title=""><?php echo $this->toUser->name; ?></span><a class="close small">×</a><input type="hidden" value="<?php echo $this->toUser->id; ?>" name="members[]"></li>
								<?php endif; ?>
								<input placeholder="<?php echo JText::_('COM_COMMUNITY_INBOX_ADD_RECIPIENT'); ?>..."
									   style="float:left; height: 28px; width: 150px; padding: 0; " type="text"
									   autocomplete="off" class="recipient-input recipient-typeahead" name="recipient"/>
							</ol>
							<div class="clear"></div>
						</div>
						<div class="stream-post-details" style="margin: 0 0 10px 0;">
							<input placeholder="<?php echo JText::_('COM_COMMUNITY_COMPOSE_SUBJECT'); ?>..."
								   style="float:left; height: 28px; width: 98%; padding: 0; " type="text"
								   autocomplete="off" name="subject"/>
							<div class="clear"></div>
						</div>
						<textarea placeholder="<?php echo JText::_('COM_COMMUNITY_COMPOSE_MESSAGE'); ?>..." cols="63"
								  style="resize: vertical; padding: 0; height: 74px; overflow: hidden; margin-left: 5px;"
								  class="stream-post" name="body" id="message-box">
						</textarea>
						<ul id="pm-attachment-list" class="pm-attachment"></ul>
					</div>
					
					<div class="clear"></div>
					
					<div class="stream-post-message-tabs">
						<div id="pm-file-uploader" class="pull-left">
							<noscript>
								<p>Please enable JavaScript to use file uploader.</p>
								<!-- or put a simple form for upload here -->
							</noscript>
						</div>
						<div class="pull-right">
							<input type="submit" value="<?php echo JText::_('COM_COMMUNITY_COMPOSE_SEND'); ?>" class="btn btn-primary">
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>

<div class="clear"></div>

<div id="inbox-top-action">
	<div id="inbox-filter" class="pull-left">
		<span><?php echo JText::_('COM_COMMUNITY_INBOX_FILTER_SELECT'); ?>:</span>
		<a id="check-all"><?php echo JText::_('COM_COMMUNITY_INBOX_FILTER_ALL'); ?></a>, 
		<a id="check-read"><?php echo JText::_('COM_COMMUNITY_INBOX_FILTER_READ'); ?></a>, 
		<a id="check-unread"><?php echo JText::_('COM_COMMUNITY_INBOX_FILTER_UNREAD'); ?></a>, 
		<a id="check-none"><?php echo JText::_('COM_COMMUNITY_INBOX_FILTER_NONE'); ?></a>
	</div> 
	<div class="btn-group pull-right">
		<button class="btn dropdown-toggle" data-toggle="dropdown"><span class="action"><span class="caret"></span></span></button>
		<ul id="inbox-action" class="dropdown-menu">
			<li><a id="action-read"><?php echo JText::_('COM_COMMUNITY_INBOX_DROPDOWN_MARKREAD'); ?></a></li>
			<li><a id="action-unread"><?php echo JText::_('COM_COMMUNITY_INBOX_DROPDOWN_MARKUNREAD'); ?></a></li>
			<li><a id="action-delete"><?php echo JText::_('COM_COMMUNITY_INBOX_DROPDOWN_DELETE'); ?></a></li>
		</ul>
	</div>
</div>

<div class="clear"></div>

<!-- LIST OF MESSAGES -->
<div id="inbox-message">
	<ul class="nav nav-pills filter" style="display: none;">
		<li><input id="checkall" type="checkbox" style="margin:8px 10px;" /></li>
		<li><a href="">mark as read</a></li>
		<li><a href="">mark as unread</a></li>
		<li><a href="">delete message</a></li>
	</ul>
<?php if(count($this->messages)): ?>
	<div class="inbox-table">
		<ul class="nav" id="inbox-listing">
			<?php 
			foreach ($this->messages as $message) : 
				$link = JRoute::_('index.php?option=com_messaging&view=inbox&task=read&msgid=' . $message->parent . '#message_' . $message->id);
			?>
			<li class="inbox-list <?php echo ($message->isUnread) ? 'inbox-list-unread' : 'inbox-list-read';  ?>" id="message_<?php echo $message->parent; ?>">
				<ul class="nav">
					<?php
						$class='';
						if($message->isUnread) {
							$class='unread';
						} else if($message->from == $this->my->id && ($message->reply_count > 1)) {
							$class='replied';
						}
					?>
					<li class="inbox-status <?php echo $class; ?>">
						<div></div>
					</li>
					<li class="inbox-checkbox">
						<input type="checkbox" class="checkbox" value="<?php echo $message->id; ?>"/>
					</li>
					<li class="inbox-details">
						<div class="user-avatar">
							<img width="48" src="<?php echo $message->avatar; ?>"
								 alt="<?php echo $this->escape(JString::ucfirst($message->from_name)); ?>" class="cAvatar"/>
						</div>
						<div class="inbox-latest">
							<?php if(!empty($message->subject)): ?>
							<a class="inbox-message-subject"
							   href="<?php echo $link; ?>"><strong><?php echo $message->subject; ?></strong></a>
							<?php endif; ?>
							<a href="<?php echo $link; ?>" class="inbox-message-body">
								<?php echo JXString::truncateWords($message->body, 20); ?>
							</a>
						</div>
						<div class="inbox-info">
							<span>
								<?php
								if ((JRequest::getVar('task') == 'sent') && (!empty($message->smallAvatar[0]))) {
									echo $message->to_name[0];
								} else {
									$recipientsName = array();
									foreach($message->recipients as $recipient){
										$user = JXFactory::getUser($recipient);
										$recipientsName[] = $user->name;
									}

									echo implode(', ', $recipientsName);
								}
								?>
							</span>
							<span class="small">
								<?php
								$postdate = new JDate($message->posted_on);
								echo JXDate::formatLapse($postdate);
								?>
							</span>
						</div>
					</li>
					<li class="inbox-delete">
						<a href="" class="close">&times;</a>
					</li>
				</ul>
				<div class="clear"></div>
			</li>
			<?php endforeach; ?>
		</ul>
		<div class="clear"></div>
	</div>
<?php else: ?>
	<div class="alert-message block-message info">
		<!--
		// comment out since non-closable
		<a data-dismiss="alert" class="close">×</a>
		-->
		<?php echo JText::_('COM_MESSAGING_DEFAULT_MESSAGE_PLACEHOLDER'); ?></div>
<?php endif; ?>
</div>

<script type="text/javascript">
$(function() {
	Messaging.inbox.init();
});
</script>