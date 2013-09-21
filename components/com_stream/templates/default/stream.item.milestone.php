<?php

/**
 * @package		JomSocial
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

$my 	= JXFactory::getUser();
$user 	= JXFactory::getUser($stream->user_id);

$data = $stream->getData();
$todoIds = $stream->getParam('todo');
$now = new JDate();
$startDate = new JDate($stream->start_date);
$dateDiff = JXDate::timeDifference($startDate->toUnix(), $now->toUnix());

$milestoneTaskCompletedCount = $stream->getTaskCompletedCount();
$milestoneTaskCount = $stream->getTaskCount();

$milestoneStatus = (!empty($dateDiff['days']) && ($dateDiff['days'] > 0) ) ? OVERDUE : INCOMPLETE;
$milestoneStatus = ($stream->status == StreamMilestoneType::COMPLETED) ? COMPLETED : $milestoneStatus;
$milestoneDays = abs($dateDiff['days']);

$milestoneClass = '';
$milestoneDaysText = '';
$milestoneDaysClass = '';
$milestoneLabel = '';

switch($milestoneStatus) {
	case OVERDUE:
		$milestoneClass = 'overdue';
		$milestoneDaysText = $milestoneDays . ' ' . JText::_('COM_STREAM_LABEL_DAYS_LATE');
		$milestoneDaysClass = 'day-overdue';
		$milestoneLabel = 'label-important';
		break;
	case INCOMPLETE:
		$milestoneClass = 'incomplete';
		$milestoneDaysText = $milestoneDays . ' ' . JText::_('COM_STREAM_LABEL_DAYS_LEFT');
		$milestoneDaysClass = 'day-left';
		$milestoneLabel = 'label-warning';
		break;
	case COMPLETED:
		$milestoneClass = 'completed';
		break;
}

?>
<li id="message_<?php echo $stream->id; ?>" class="message-item type_<?php echo $stream->type; ?>">

	<div class="message-avatar">
		<a href="<?php echo $user->getURL();?>">
			<img class="cAvatar" border="0" author="85" alt="" src="<?php echo $user->getThumbAvatarURL(); ?>">
		</a>
	</div>

	<div class="message-content">
		
		<div class="message-content-top">

			<div class="message-meta-top">
				
				<div class="message-content-actor">
					<strong><a href="<?php echo $user->getURL();?>" class="actor-link"><?php echo $this->escape($user->name); ?></a></strong>
					<?php if(!empty( $stream->group_id)){
					$group	= $stream->getGroup();
					$group->load($stream->group_id);
					echo ' <span class="message-in-groups"><a href="'.  StreamMessage::getGroupUri( $group->id ).'">'. $this->escape($group->name). '</a></span>'; 
					}?>
				</div>
				
				<div class="message-content-text">
					<div class="message-milestone-item">
						
						<div class="vanity-col1">
							<div class="vanity-title">
								<?php echo StreamMessage::format($stream->message); ?>
							</div>
							<div class="progress progress-info progress-striped tips" milestone="<?php echo $stream->id; ?>" title="<?php echo $stream->getParam('progress', 0);?>%">
								<div class="bar" style="width: <?php echo $stream->getParam('progress'); ?>%;"></div>
							</div>
							<div class="small">
								<?php
								echo JText::_('COM_STREAM_LABEL_DUE_DATE') . ': ' . $startDate->format( JText::_('JXLIB_DATE_SHORT_FORMAT')) . '&nbsp;&#8226;&nbsp;';

								if($milestoneTaskCount > 0) {
									//echo $milestoneTaskCompletedCount . '/' . $milestoneTaskCount . ' task completed';								
									echo sprintf(JText::_('COM_STREAM_NO_TASK_COMPLETED'), $milestoneTaskCompletedCount, $milestoneTaskCount);
								} else {
									echo JText::_('COM_STREAM_NO_TASK_IN_MILESTONE');
								}
								?>
							</div>
						</div>
						
						<div class="vanity-col2">
							<div class="label <?php echo $milestoneLabel; ?>"><?php echo strtoupper($milestoneClass); ?></div>
							<?php if($milestoneStatus !== COMPLETED) : ?>
							<div class="small <?php echo $milestoneDaysClass; ?>"><?php echo $milestoneDaysText; ?></div>
							<?php endif; ?>
						</div>
						
						<div class="clear"></div>
						
						<div class="">
							<!-- COMMENTS / LIKES -->
							<ul id="stream" class="stream noBorder">
								<?php if($todoIds): ?>

									<?php
									$tasks = StreamFactory::getModel('stream')->getStream(array('id' => $todoIds));
									foreach($tasks as $task):
									?>
										<li>
											<div class="milestone-task">

												<div class="milestone-task-title"><a href="<?php echo $task->getUri(); ?>"><?php echo StreamTemplate::escape($task->message); ?></a></div>
												<ul class="todolist noBorder">
													<?php
													$todoData = json_decode($task->raw);
													$todoIndex = 0;
													$numTodo = count($todoData->todo);
													$doneTodo = 0;
													foreach( $todoData->todo as $todo )
													{
														// @todo: move checking to input filtering
														$isDone = $task->getState($todoIndex);
														$doneBy = JXFactory::getUser( ( intval($task->getDoneBy($todoIndex))));
														$doneOn = $task->getDoneOn($todoIndex);
														$doneOn = empty($doneOn) ? '': ' - '.JXDate::formatDate($doneOn);
														if(!empty($todo)) {?>
															<li class="clearfix todo-item<?php if($isDone){ echo ' todo-done'; $doneTodo++; } ?>">
																<a href="#done" data-message_id="<?php echo $task->id; ?>" data-todo_index="<?php echo $todoIndex; ?>" class="<?php echo ($my->authorise('stream.todo.done',  $task)) ? '' :'readonly'; ?>"></a>
																<span><?php echo StreamMessage::format($todo); ?>
																	<?php if($isDone){ ?>&nbsp;<span class="small hint"><?php echo $this->escape($doneBy->name); ?><?php echo $doneOn; ?></span><?php }?>
																</span>
															</li>
															<?php
															$todoIndex++;
														}
													}
													?>
												</ul>
											</div>
										</li>
									<?php endforeach; ?>

								<?php endif; ?>
							</ul>
						</div>
						<div class="clear"></div>
					</div>
					
				</div>
				
				<?php echo StreamMessage::getAttachmentHTML($stream); ?>

				<?php echo StreamTag::getTagsHTML($stream); ?>

				<div class="message-content-topic topic-container-parent" style="display:none">
					<ul class="topic-container">
						<?php 
						$topics = array();
						if (strlen($stream->topics) > 0) {
						$topics = explode(',', $stream->topics);
						}

						foreach ($topics as $topic) {
						?>
						<li class="stream-topics-element">
							<a href="#"><?php echo $topic; ?></a>
							<span class="stream-topics-close">x</span>
						</li>
						<?php } ?>
					</ul>
		
					<?php if (isset($topics)): ?>
			
					<div>
						<input type="text" class="topic-input" />
						<input type="button" class="topic-add stream-form-topic-add" name="" value="Add Topic" />
					</div>
		
					<a href="javascript:void(0)" class="message-topic-edit topic-edit-change"><?php echo JText::_('COM_STREAM_LABEL_EDIT');?></a>
		
					<?php endif; ?>
				</div>
				
			</div>
			
			<div class="clear"></div>
			
			<!-- NEWS FEED DATE, ICON & ACTIONS -->
			<div class="message-meta small">
				<?php 
				$date = new JDate( $stream->created );	?>
				<a class="meta-date" href="<?php echo $stream->getUri();;?>"><?php echo JXDate::formatLapse( $date ); ?></a>
				• <a class="meta-like" href="#<?php echo (!$stream->isLike($my->id))? '' : 'un'; ?>like"><?php echo (!$stream->isLike($my->id)) ? JText::_('COM_STREAM_LIKE_LABEL') : JText::_('COM_STREAM_UNLIKE_LABEL'); ?></a>
				• <a class="meta-comment" href="#comment"><?php echo JText::_('COM_STREAM_LABEL_COMMENT');?></a>
				<?php
				if( $my->authorise('stream.message.edit', $stream) ){ ?>
				• <a class="meta-edit" href="#edit"><?php echo JText::_('COM_STREAM_LABEL_EDIT');?></a>
				<?php
				}
				?>

				<div class="clear"></div>
			</div>
			<!-- /NEWS FEED DATE, ICON & ACTIONS -->
			
			<div class="clear"></div>
		</div>

		<div class="message-content-bottom">
			<?php echo $comment; ?>
		</div>
	</div>
	
	<?
	if(!empty($data->pinned)) :
		$tmpDate = date_parse($stream->created);
		$start = strtotime("now");
		if ($data->pinned == "1 month") {
			$end = strtotime("+1 month", mktime($tmpDate["hour"],$tmpDate["minute"],$tmpDate["second"],$tmpDate["month"],$tmpDate["day"], $tmpDate["year"]));	
		} elseif ($data->pinned == "1 week") {
			$end = strtotime("+1 week", mktime($tmpDate["hour"],$tmpDate["minute"],$tmpDate["second"],$tmpDate["month"],$tmpDate["day"], $tmpDate["year"]));	
		} else {
			$end = strtotime("+1 day", mktime($tmpDate["hour"],$tmpDate["minute"],$tmpDate["second"],$tmpDate["month"],$tmpDate["day"], $tmpDate["year"]));	
		}
		$seconds_diff = $end - $start;
		
		if (floor($seconds_diff/3600/24) >= 0) :
		?>
			<div class="pinned-message tips" title="<?php echo sprintf(JText::_('COM_STREAM_LABEL_PINNED_TIP'),floor($seconds_diff/3600/24)); ?>"></div>
	<?php endif;
	endif; ?>	
	
	<?php
	// You can only delete your own message
	if( $my->authorise('stream.message.edit', $stream) ) {
	?>
	<div class="message-remove">
		<a href="javascript:void(0);" class="remove" original-title="Delete"><?php echo JText::_('COM_STREAM_LABEL_DELETE');?></a>
	</div>
	<?php } ?>
		
	<div class="clear"></div>
</li>