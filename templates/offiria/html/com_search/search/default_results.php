<?php
/**
 * @version		$Id: default_results.php 21321 2011-05-11 01:05:59Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<style type="text/css">
.found-words {
	background: #DDDDDD;
	color:#333333;
}
</style>
<dl class="search-results<?php echo $this->pageclass_sfx; ?>">
<?php 
	foreach($this->results as $count => $result) : 
		// decide if need to show header line
		$showHeader = ($count == 0 || ($count > 0 && ($result->section != $this->results[$count-1]->section)));
		switch (strtolower($result->section)) {
			case "uncategorised":
				// @todo: return result for normal article content
				break;
			case 'profiles':
?>
	
	<div class="searchPeople">
		<!-- PEOPLE LISTING -->
		<?php if ($showHeader) { ?>
		<p class="older-stream-separator"><span><?php echo JText::_('NAVIGATOR_LABEL_PEOPLE'); ?></span></p>
		<?php } ?>
		<ul class="nav">
			<li class="message-item">
				<div class="message-avatar">
					<a href="">
						<img border="0" class="cAvatar" author="85" alt="" src="<?php echo $result->objectInfo->getThumbAvatarURL(); ?>">
					</a>
				</div>
				<div class="message-content">
		
					<div class="message-meta-top">
						<div class="message-content-actor">
							<a href="<?php echo $result->href;?>" target="_blank" class="actor-link"><strong><?php echo $result->objectInfo->name;?></strong></a> 
							<span style="color:#999;">@<?php echo $result->objectInfo->username;?></span>
							<span style="float:right;" class="btn-group">
								<button class="btn" data-toggle="dropdown" onclick="document.location = '<?php echo $result->href;?>';">
									<?php echo JText::_('NAVIGATOR_LABEL_PROFILE'); ?>
								</button>
							</span>
						</div>
						<div class="message-content-text">							
							<?php echo $result->title;?>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</li>
		</ul>
	</div>
<?php		
				break; // profile case
			case 'files':
			default:
				$user = JXFactory::getUser($result->objectInfo->user_id);
?>
	
	<!-- Stream LISTING -->
	<div class="searchStream">
		<?php if ($showHeader) { ?>
		<p class="older-stream-separator"><span>
			<?php
				switch ($result->objectInfo->type) {
					case 'update':
						echo JText::_('COM_STREAM_LABEL_STATUS_UPDATE'); break;
					case 'page':
						echo JText::_('COM_STREAM_LABEL_PAGE');  break;
					case 'milestone':
						echo JText::_('COM_STREAM_LABEL_MILESTONE');  break;
					case 'todo':
						echo JText::_('COM_STREAM_LABEL_TODO');  break;
					case 'files':
						echo JText::_('COM_STREAM_LABEL_FILES'); break;
					default:
					case 'event':
						echo JText::_('COM_STREAM_LABEL_EVENT');  break;
						break;
				}
			?>
		</span></p>
		<?php } ?>
		<ul class="nav">
			<li class="message-item">
				<div class="message-avatar">
					<a href="">
						<img border="0" class="cAvatar" author="85" alt="" src="<?php echo $user->getThumbAvatarURL(); ?>">
					</a>
				</div>
				<div class="message-content">		
					<div class="message-meta-top">
						<div class="message-content-actor">
							<a href="<?php echo $user->getURL();?>" target="_blank" class="actor-link"><strong><?php echo $result->text;?></strong></a> 
							<span style="color:#999;">@<?php echo $user->get('username');?></span>
							<span style="float:right;" class="btn-group">
								<button class="btn" data-toggle="dropdown" onclick="document.location = '<?php echo $result->href;?>';">
									<?php echo $result->section;?>
								</button>
							</span>
						</div>
						<div class="message-content-text">
							<a href="<?php echo $result->addhref;?>" target="_blank"><?php echo $result->title;?></a>
							<br/>
							<div class="message-meta small">
								<a class="meta-date" href="<?php echo $result->href;?>" target="_blank">
							<?php 							
								$date = new JDate( $result->created );
								echo JXDate::formatLapse( $date ); 
							?>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</li>
		</ul>
	</div>
<?php
				break; // case default
	} // end if $result->section
	endforeach; 
?>
</dl>

<div class="pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
