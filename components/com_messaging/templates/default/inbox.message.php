<?php
defined('_JEXEC') or die();
?>

<li class="inbox-list" id="message_<?php echo $msg->id; ?>">
	<ul class="nav">
		<li class="inbox-details">
			<div class="user-avatar">
				<a href="<?php echo $authorLink;?>" title="<?php echo $user->name; ?>">
					<img src="<?php echo $user->getThumbAvatarURL(); ?>" alt="<?php echo $user->name; ?>"/>
				</a>
			</div>
			<div class="inbox-message-body">
				<span>
					<a href="<?php echo $authorLink;?>"><?php echo $user->name; ?></a>
				</span>
				<span>
					<?php echo $msg->body; ?>
				</span>
				<span class="small">
					<?php
					$postdate = new JDate($msg->posted_on);
					echo JXDate::formatLapse($postdate);
					?>
					<?php if(!empty($readBy)): ?>
					&bull;&nbsp;Read by:
					<?php
						$readByUsers = array();
						foreach($readBy as $recipientId) {
							$readByUsers[] = JXFactory::getUser($recipientId)->name;
						}

						echo implode(', ', $readByUsers);
					?>
					<?php endif; ?>
				</span>
			</div>
		</li>
		<li class="inbox-delete">
			<a href="" class="close">&times;</a>
		</li>
		
		<?php
			if(!empty($attachments)) {
					echo '<li style="border-top: 1px solid #EFEFEF; width: 540px; margin-left: 40px;">';
					foreach($attachments as $attachment) {
					$dlLink = JRoute::_('index.php?option=com_messaging&task=download&file_id='.$attachment->id);

					$html = '<div class="message-content-file ">';
					$html .= '<a  title="Click to download" href="'.$dlLink.'">'.StreamTemplate::escape( JHtmlString::abridge($attachment->filename, 16)).'</a>';
					$html .= ' <span class="small hint">('.StreamMessage::formatBytes($attachment->filesize, 1). ')</span>';
					$html .= '</div>';

					echo $html;
				}
				echo '</li>';
			}
		?>
	</ul>
</li>