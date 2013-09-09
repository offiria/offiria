<?php
$message_id = JRequest::getVar('message_id', 0);
?>

<style type="text/css">
	.message-content {
		margin-left: 0;
	}
	.stream-comment {
		padding-left: 52px;
	}
</style>

<div class="alert alert-success">
	<a data-dismiss="alert" class="close">×</a>
	<?php echo JText::_('COM_STREAM_HELPER_DIRECT'); ?>
</div>

<div class="blue-button compose-direct-message">
	<a class="btn btn-info" href="#"><i class="icon-plus icon-white"></i>New Message</a>
</div>

<div id="direct">
	<ul class="direct-list">
		<?php
		// TODO: use stream.item.direct template instead
		/*$count = 0;
		foreach( $rows as $row ) {
			echo $row->getHTML();
		}*/
		?>

		<?php
		foreach ($rows as $stream) :
			$my = JXFactory::getUser();
			$user = JXFactory::getUser($stream->user_id);
			$data = json_decode($stream->raw);
			$date = new JDate( $stream->created );

			$members = array();
			if (!empty($stream->group_id)) {
				$group = $stream->getGroup();

				// Message sent to
				$memberIds = $group->members;
				$memberIds = explode(',', trim($memberIds, ','));
				$memberCount = JXUtility::csvCount($group->members);

				if($memberCount > 1) {
					foreach ($memberIds as $id) {
						if (intval($id) > 0 && $id != $group->creator) {
							$member = JXFactory::getUser($id);
							$members[] = '<a href="' . $member->getURL() . '">' . $this->escape($member->name) . '</a>';
						}
					}
				} else {
					$members[] = 'Yourself';
				}
			}
		?>
		<li class="direct-list-item">
			<div class="direct-avatar">
				<a href="<?php echo $user->getURL();?>">
					<img class="cAvatar"border="0" author="85" alt="" src="<?php echo $user->getThumbAvatarURL(); ?>">
				</a>
			</div>
			<div class="direct-content">
				<div class="direct-content-to">
					<a href="<?php echo $user->getURL();?>" class="actor-link"><?php echo $this->escape($user->name); ?></a> <span style="font-size: 16px;">&nbsp;&#8250;&nbsp;</span>
					<?php echo implode($members, ', '); ?>
				</div>
				<div class="direct-content-message">
					<div class="autoShorten">
						<?php echo StreamMessage::format($stream->message); ?>
					</div>
					<div class="clear"></div>
					<div class="message-content">
					<?php echo StreamMessage::getAttachmentHTML($stream); ?>
					</div>
				</div>
			</div>
			<div class="direct-date">
				<?php echo JXDate::formatLapse($date); ?>
			</div>
			<div class="clear"></div>
			<div>
				<ul class="replies">
					<li id="message_<?php echo $stream->id; ?>" class="message-item type_<?php echo $stream->type; ?>">
						<div class="message-meta small" style="display: block;">
							<!--<a class="meta-like" href="#<?php echo (!$stream->isLike($my->id))? '' : 'un'; ?>like"><?php echo (!$stream->isLike($my->id)) ? JText::_('COM_STREAM_LIKE_LABEL') : JText::_('COM_STREAM_UNLIKE_LABEL'); ?></a>
							-->
							<a class="meta-comment" href="#comment">Reply</a>
							<?php
							// TODO: disabled until edit callback output fixed
							if( $my->authorise('stream.message.edit', $stream) && false){ ?>
								• <a class="meta-edit" href="#edit">Edit</a>
								• <a class="meta-edit" href="#remove">Delete</a>
								<?php
							}
							?>
							<div class="clear"></div>
						</div>

						<div class="message-content-bottom">
							<?php echo StreamComment::getCommentSummaryHTML($stream); ?>
						</div>
					</li>
				</ul>
			</div>
		</li>
		<?php endforeach; ?>
	</ul>
</div>

<div class="pagination" style="display: none;">
	<?php if($pagination) { echo $pagination->getPagesLinks(); } ?>
</div>

<!--TODO: TEMPORARY START-->
<script type="text/javascript">
	$(function () {
		S.direct.init();

		$.fn.scrollView = function () {
			return this.each(function () {
				$('html, body').animate({
					scrollTop:$(this).offset().top
				}, 1000);
			});
		};

		$.fn.autoShorten = function () {
			return this.each(function () {
				var self = $(this);
				if (self.text().length > 100) {
					var words = self.text().substring(0, 100).split(" "),
						shortText = words.slice(0, words.length - 1).join(" ") + "...";

					self.data('replacementText', self.text())
						.text(shortText)
						.css({ cursor:'pointer' })
						.on('expandText', function () {
							var tempText = self.text();
							self.text(self.data('replacementText'));
							self.css({ cursor:'auto' });
						})
						.on('click', function () {
							// clicking on the excerpt will expand the text and show all the replies - vice versa
							var stream = self.closest('.direct-list-item');
							stream.find('a[href="#showallcomments"]').trigger('click');
							self.trigger('expandText');
						});
				}
			});
		};

		$('.comment-more a[href="#showallcomments"]').live('click', function () {
			var stream = $(this).closest('.direct-list-item');

			stream.find('.direct-content-message').trigger('expandText');
			stream.find('.comment-reply').show();
			stream.find('.comment-more').show();
		});

		$('ul.replies li a[href="#comment"]').live('click', function () {
			var stream = $(this).closest('.direct-list-item'),
				showAllReplies = stream.find('.comment-more');

			stream.find('a[href="#showallcomments"]').trigger('click');
			stream.find('.direct-content-message').trigger('expandText');
			stream.find('.comment-reply').hide();

			if (showAllReplies.length != 0) {
				showAllReplies.hide();
				stream.find('textarea[name="comment"]').focus().scrollView();
			}
		});

		$('.autoShorten').autoShorten();

		if (document.location.hash) {
			var message_id = <?php echo $message_id; ?>;

			var stream = $('#message_' + message_id);
			stream.find('a[href="#showallcomments"]').trigger('click');
		}
	});
</script>
<!--TODO: TEMPORARY END-->
