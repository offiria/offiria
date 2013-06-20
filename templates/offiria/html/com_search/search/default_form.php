<?php
/**
 * @version		$Id: default_form.php 21504 2011-06-10 06:21:35Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	com_search
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
$lang = JFactory::getLanguage();
$upper_limit = $lang->getUpperLimitSearchWord();
$areas = JRequest::getVar('areas', array(''));
if (preg_match('/_\d\w$/', $areas[0]))
{
	$splitVal	= explode('_', $areas[0]);
	$groupId	= $splitVal[0];
	$filterRange= $splitVal[1]; 
}
else
{
	if (is_null($areas) || empty($areas))
	{
		$groupId = JRequest::getInt('group_id', 0);
	}
	else
	{
		$groupId = is_array($areas) ? $areas[0] : $areas;
	}
	$filterRange= '0a';
}
$searchPhrase = array('all' => JText::_('PLG_SEARCH_FILTER_ALL'), 
						'people' => JText::_('PLG_SEARCH_FILTER_PEOPLE'), 
						'streams' => JText::_('PLG_SEARCH_FILTER_STREAMS'), 
						//'comments' => JText::_('PLG_SEARCH_FILTER_COMMENTS'), 
						'files' => JText::_('PLG_SEARCH_FILTER_FILES'));
$rangeArray = array('2w' => JText::_('PLG_SEARCH_FILTER_2_WEEKS_AGO'), 
					'1m' => JText::_('PLG_SEARCH_FILTER_1_MONTH_AGO'), 
					'3m' => JText::_('PLG_SEARCH_FILTER_3_MONTHS_AGO'), 
					'6m' => JText::_('PLG_SEARCH_FILTER_6_MONTHS_AGO'));

?>

<form id="searchForm" action="<?php echo JRoute::_('index.php?option=com_search');?>" method="post">

	<div class="form-search">	
		<input type="text" name="searchword" id="search-searchword" size="30" maxlength="20" value="<?php echo $this->escape($this->origkeyword); ?>" class="inputbox" placeholder="Search keyword">
		<button name="Search" onclick="this.form.submit()" class="btn"><?php echo JText::_('PLG_SEARCH_LABEL_SEARCH');?></button>
		<input type="hidden" name="task" value="search">
		<input type="hidden" id="areas" name="areas" value="<?php echo $groupId.'_'.$filterRange;?>" />
		<input type="hidden" id="searchphrase" name="searchphrase" value="<?php echo $this->searchphrase;?>" />
		<input type="hidden" id="limit" name="limit" value="20" />
	</div>
	
	<p><?php echo JText::_('PLG_SEARCH_LABEL_RESULTS');?>: <?php echo JText::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', $this->total);?></p>
	<ul class="nav nav-pills filter">
		<?php foreach ($searchPhrase as $index => $label) { ?>
		<li <?php echo ($this->searchphrase == $index) ? 'class="active"' : '';?>><a href="javascript:void(0);" onclick="$('#searchphrase').val('<?php echo $index;?>'); $('#searchForm').submit();"><?php echo $label;?></a></li>
		<?php } ?>
		
		<li style="float:right" class="dropdown">
			<a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo ($filterRange == '' || $filterRange == '0a') ? 'All' : $rangeArray[$filterRange];?><b class="caret"></b></a>
			<ul class="dropdown-menu">
				<li><a href="javascript:void(0);" onclick="appendSearchRange('0a'); $('#searchForm').submit();"><?php echo JText::_('PLG_SEARCH_FILTER_ALL');?></a></li>
				<li class="divider"></li>				
				<?php foreach ($rangeArray as $index => $label) { ?>
				<li><a href="javascript:void(0);" onclick="appendSearchRange('<?php echo $index;?>'); $('#searchForm').submit();"><?php echo $label;?></a></li>
				<?php } ?>
			</ul>
		</li>
	</ul>

</form>
<?php /*
<div class="searchResult">

	<div class="searchPeople">
		<!-- PEOPLE LISTING -->
		<p class="older-stream-separator"><span>People</span></p>
		<ul class="nav">
			<li class="message-item">
				<div class="message-avatar">
					<a href="">
						<img border="0" class="cAvatar" author="85" alt="" src="<?php echo JURI::root(); ?>images/avatar/user-thumb.png">
					</a>
				</div>
				<div class="message-content">
		
					<div class="message-meta-top">
						<div class="message-content-actor">
							<a href="" class="actor-link"><strong><span class="alert-yellow">Lion</span>el Messi</strong></a> 
							<span style="color:#999;">@messi</span>
							<span style="float:right;" class="btn-group">
								<button class="btn dropdown-toggle" data-toggle="dropdown">
									<span class="caret"></span>
								</button>
								<ul class="dropdown-menu">
									<li><a href="#">View profile</a></li>
									<li><a href="#">Send message</a></li>
								</ul>
							</span>
						</div>
						<div class="message-content-text">
							A full-time Front-end Ninja and a part-time Foodist.
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</li>
		</ul>
	</div>

	<!-- STREAM LISTING -->
	<div class="searchStream"> 
		<p class="older-stream-separator"><span>Stream</span></p>
		<ul class="nav">
			
			<!-- COPIED FROM THE STREAM. JUST ADD <span class="alert-yellow"></span> TO HIGHLIGHT THE SEARCH KEYWORDS -->

			<li class="message-item">
				<!-- STATUS UPDATE AND COMMENT -->
				<div class="message-avatar">
					<a href="">
						<img border="0" class="cAvatar" author="85" alt="" src="<?php echo JURI::root(); ?>images/avatar/user-thumb.png">
					</a>
				</div>
			 	<div class="message-content">
					<div class="message-content-top">	
						<div class="message-meta-top">
							<div class="message-content-actor">
								<strong>
									<a href="" class="actor-link">John Mayer</a>
								</strong>
							</div>
							<div class="message-content-text">Is there blabla bla <span class="alert-yellow">Lion</span> King 3D? Like what whaaaaaaatt?</div>
							<div class="message-content-tag-error"></div>
							<div class="message-content-tag-parent">
								<div class="message-content-tag small" style="margin-right: 4px; float: left;">
									<i style="opacity: 0.5; margin-right: 4px; float: left;" class="icon-tag"></i>
									<span style="font-style:italic;"></span>
									<span><a href="#editTags">Add Tags</a></span>	</div>
									<div class="message-content-tag-edit tag-container-parent" style="display: none;">
										<i style="opacity: 0.5; float: left;" class="icon-tag"></i>
										<div style="float: left; width: 90%;">
											<div>
												<ol class="tag-container"></ol>
											</div>
											<div class="clear"></div>
											<div style="margin: 2px 0 0 4px;">
												<form>
													<input type="text" class="tag-input tag-typeahead" style="margin-bottom: 3px; width: 100px;">
													<div class="btn btn-primary tag-add">Add Tag</div>
													<div class="btn tag-cancel">Done</div>
												</form>
											</div>
										</div>
									</div>
								</div>
								<div class="clear"></div>
								<div class="message-content-topic topic-container-parent" style="display: none;">
									<ul class="topic-container"></ul>
									<div>
										<input type="text" class="topic-input">
										<input type="button" class="topic-add stream-form-topic-add" name="" value="Add Topic">
									</div>
									<a href="javascript:void(0)" class="message-topic-edit topic-edit-change">Edit</a>
								</div>
							</div>
							<div class="clear"></div>
										
							<div class="message-meta small">
								<a class="meta-date" href="<?php echo JURI::root(); ?>index.php/component/stream/message?task=show&amp;message_id=85">A moment ago</a>
								• <a class="meta-like" href="#like">Like</a>
								• <a class="meta-comment" href="#comment">Comment</a>
								• <a class="meta-edit" href="#edit">Edit</a>
								<div class="clear"></div>
							</div>
							<div class="clear"></div>
						</div>

									<div class="message-content-bottom">
										<div class="stream-comment" id="stream-cmt-85">
								
									<div style="display:none;" class="stream-like"></div>
									
								
								
							<div class="comment-item" id="comment-20">
								<a href="<?php echo JURI::root(); ?>index.php/component/profile/?view=display&amp;user=john_m">
									<span class="comment-avatar-container"><img src="<?php echo JURI::root(); ?>images/avatar/thumb_d989297d1b46177b6b06bd2e.jpg" alt="" class="comment-avatar"></span>
								</a>
										<a href="<?php echo JURI::root(); ?>index.php/component/profile/?view=display&amp;user=john_m">Huge Jekmen</a> what kind of <span style="background:yellow">lion</span> is this?!	<div class="comment-meta">
									<span>A moment ago </span>
									<span>• <a class="comment-like" href="#">0</a></span>
									<span style="display: none;" class="comment-option">	
										<span>• <a href="#commentlike">Like</a> </span>
										<span style="">
											• <a href="#delete">Remove</a>
										</span>
									</span>
								</div>
							</div><div class="comment-form">
									<!-- post new comment form -->
									<form style="display: none;" action="" class="">
										<textarea tabindex="85284" style="resize:vertical;" cols="" rows="" name="comment"></textarea>
										<div class="comment-action clearfix">
											<ul>
																				
												<li class="right">
													<a class="cancel" href="#cancelPostinComment">Cancel</a>
													<button tabindex="85285" class="submit" type="submit">Post Comment</button>
													<div class="clear"></div>
												</li>
											</ul>
										</div>
										<div class="clear"></div>
										<input type="hidden" value="85" name="stream_id">
									</form>
											<span style="" class="comment-reply">
										<a href="#reply">Reply</a>
									</span>

								</div>
								
								<div class="clear"></div>
							</div>		</div>
									
				</div>
					
				<div class="message-remove" style="display: none;">
					<a original-title="Delete" class="remove" href="javascript:void(0);">Delete</a>
				</div>
				<div class="clear"></div>
			</li>

			<li class="message-item type_event">
				<!-- EVENT -->
				<div class="message-avatar">
					<a href="<?php echo JURI::root(); ?>index.php/component/profile/?view=display&amp;user=admin">
						<img border="0" class="cAvatar" author="85" alt="" src="<?php echo JURI::root(); ?>images/avatar/user-thumb.png">
					</a>
				</div>

				<div class="message-content">
					
					<div class="message-content-top">
						
						<div class="message-meta-top">
							<div class="message-content-actor">
								<strong><a href="<?php echo JURI::root(); ?>index.php/component/profile/?view=display&amp;user=admin" class="actor-link">Super User</a></strong>
												</div>
							<div class="message-content-text">
						
								<div class="message-event-item vanity-list events-ongoing">
									
									<div class="vanity-col1">
										<div class="cal-box">
											<span class="cal-boxTop"></span>
											<span class="cal-date"><h2>30</h2></span>
											<span class="cal-month">Jan</span>
											<span class="cal-boxBottom"></span>
										</div>
									</div>

									<div class="vanity-col2">
										<div class="cal-info">
											<div class="vanity-title"> 									huahuahua <span class="alert-yellow">lion</span>el								</div>
											
											<div class="small">
																				</div>
											
											<div class="small">
												<span class="start-time">
													Start: Mon, 30 Jan 2012 <!-- Only show the date if the event held in >= 2 days -->
													@&nbsp;12:00 am									</span>
												
																						<div class="clear"></div>
													<span class="end-time">
														End: Mon, 30 Jan 2012  <!-- Only show the date if the event held in >= 2 days -->
														@&nbsp;12:00 am										</span>
												
																					
											</div>
										</div>
										
																</div>
									
									<div class="vanity-col3">
																	<div class="btn"> 								Attending							</div>
																</div>
									
									<div class="clear"></div>
								</div>
							</div>

							<div class="message-content-topic topic-container-parent" style="display:none">
								<ul class="topic-container">
														</ul>
					
											
								<div>
									<input type="text" class="topic-input">
									<input type="button" class="topic-add stream-form-topic-add" name="" value="Add Topic">
								</div>
					
								<a href="javascript:void(0)" class="message-topic-edit topic-edit-change">Edit</a>
					
												</div>

				<div class="message-content-tag-error"></div>
				<div class="message-content-tag-parent">
				<div class="message-content-tag small" style="margin-right: 4px; float: left;">
					<i style="opacity: 0.5; margin-right: 4px; float: left;" class="icon-tag"></i>
					<span style="font-style:italic;">
							</span>

					<span><a href="#editTags">Add Tags</a></span>	</div>
				<div class="message-content-tag-edit tag-container-parent" style="display: none;">
					<i style="opacity: 0.5; float: left;" class="icon-tag"></i>
					<div style="float: left; width: 90%;">
						<div>
							<ol class="tag-container">
											</ol>
						</div>
						<div class="clear"></div>
						<div style="margin: 2px 0 0 4px;">
							<form>
								<input type="text" class="tag-input tag-typeahead" style="margin-bottom: 3px; width: 100px;">
								<div class="btn btn-primary tag-add">Add Tag</div>
								<div class="btn tag-cancel">Done</div>
							</form>
						</div>
					</div>
				</div>
				</div>
				<div class="clear"></div>
						</div>

						<div class="clear"></div>

						<!-- NEWS FEED DATE, ICON & ACTIONS -->
						<div class="message-meta small">
											<a class="meta-date" href="<?php echo JURI::root(); ?>index.php/component/stream/message?task=show&amp;message_id=42">Thu, 26 Jan 2012</a>
							• <a class="meta-like" href="#like">Like</a>
							• <a class="meta-comment" href="#comment">Comment</a>
							
							<div class="clear"></div>
						</div>
						<!-- /NEWS FEED DATE, ICON & ACTIONS -->
						
						<div class="clear"></div>
					</div>

					<div class="message-content-bottom">
						<div id="stream-cmt-42" class="stream-comment">

					<div class="stream-like" style="display:none;"></div>
					

				<div class="comment-form">
					<!-- post new comment form -->
					<form action="" style="display: none;">
						<textarea name="comment" rows="" cols="" style="resize: vertical; padding-top: 0px; padding-bottom: 0px;" tabindex="70367"></textarea>
						<div class="comment-action clearfix">
							<ul>
																
								<li class="right">
									<a href="#cancelPostinComment" class="cancel">Cancel</a>
									<button type="submit" class="submit" tabindex="70368">Post Comment</button>
									<div class="clear"></div>
								</li>
							</ul>
						</div>
						<div class="clear"></div>
						<input type="hidden" name="stream_id" value="42">
					</form>
							<span class="comment-reply" style="display: none">
						<a href="#reply">Reply</a>
					</span>

				</div>

				<div class="clear"></div>
				</div>			
					</div>
				</div>
					
						
				<div class="clear"></div>
			</li>
		</ul>
	</div>

	<!-- FILE LISTING -->
	<div class="searchFile">
		<p class="older-stream-separator"><span>File</span></p>
		<ul class="nav">
			<li class="message-item">
				<div class="message-avatar">
					<a href="">
						<img border="0" class="cAvatar" author="85" alt="" src="<?php echo JURI::root(); ?>images/avatar/user-thumb.png">
					</a>
				</div>
				<div class="message-content">
		
					<div class="message-meta-top">
						<div class="message-content-actor">
							<a href="" class="actor-link"><strong>Zooey Deschanel</strong></a> 
							<span style="color:#999;">@zooey</span>
							<span style="float:right;" class="btn-group">
								<button class="btn" data-toggle="dropdown">
									Download
								</button>
							</span>
						</div>
						<div class="message-content-text">
							<a href="">the-<span class="alert-yellow">lion</span>e-krueger.pdf</a>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</li>
			<li class="message-item">
				<div class="message-avatar">
					<a href="">
						<img border="0" class="cAvatar" author="85" alt="" src="<?php echo JURI::root(); ?>images/avatar/user-thumb.png">
					</a>
				</div>
				<div class="message-content">
		
					<div class="message-meta-top">
						<div class="message-content-actor">
							<a href="" class="actor-link"><strong>Cece Meyers</strong></a> 
							<span style="color:#999;">@cece</span>
							<span style="float:right;" class="btn-group">
								<button class="btn" data-toggle="dropdown">
									Download
								</button>
							</span>
						</div>
						<div class="message-content-text">
							<a href="">osx-<span class="alert-yellow">lion</span>-mockup.jpg</a>
						</div>
					</div>
				</div>
				<div class="clear"></div>
			</li>
		</ul>
	</div>
</div>
*/?>




<!-- ORIGINAL CODES -->

<?php /*
<form id="searchForm" action="<?php echo JRoute::_('index.php?option=com_search');?>" method="post">
	<input type="hidden" id="areas" name="areas" value="<?php echo $groupId;?>" />

	<fieldset class="word">
		<label for="search-searchword">
			<?php echo JText::_('COM_SEARCH_SEARCH_KEYWORD'); ?>
		</label>
		<input type="text" name="searchword" id="search-searchword" size="30" maxlength="<?php echo $upper_limit; ?>" value="<?php echo $this->escape($this->origkeyword); ?>" class="inputbox" />
		<button name="Search" onclick="this.form.submit()" class="button"><?php echo JText::_('COM_SEARCH_SEARCH');?></button>
		<input type="hidden" name="task" value="search" />
	</fieldset>

	<div class="searchintro<?php echo $this->params->get('pageclass_sfx'); ?>">
		<?php if (!empty($this->searchword)):?>
		<p><?php echo JText::plural('COM_SEARCH_SEARCH_KEYWORD_N_RESULTS', $this->total);?></p>
		<?php endif;?>
	</div>
	
	<div>		
		<label for="filter_range">Search records in </label>
		<select id="filter_range" name="filter_range" class="inputbox" size="1" onchange="appendSearchRange();">
			<option value="2w" <?php echo ($filterRange == '2w') ? 'selected' : '';?>>past 2 weeks</option>
			<option value="1m" <?php echo ($filterRange == '1m') ? 'selected' : '';?>>past 1 month</option>
			<option value="3m" <?php echo ($filterRange == '3m') ? 'selected' : '';?>>past 3 months</option>
			<option value="0a" <?php echo ($filterRange == '0a') ? 'selected' : '';?>>All</option>
		</select>
	</div>


	<fieldset class="phrases">
		<legend><?php echo JText::_('COM_SEARCH_FOR');?>
		</legend>
			<div class="phrases-box" style="display:block;">
			<?php //echo $this->lists['searchphrase']; ?>
				<input name="searchphrase" id="searchphraseall" value="all" <?php echo ($this->searchphrase == 'all') ? 'checked="checked"' : '';?> type="radio">
				<label for="searchphraseall" id="searchphraseall-lbl" class="radiobtn">All</label>
			</div>
			<div class="clear"></div>
			<div class="phrases-box" style="display:block;">
				<input name="searchphrase" id="searchphrasestream" value="streams" <?php echo ($this->searchphrase == 'streams') ? 'checked="checked"' : '';?> type="radio">
				<label for="searchphrasestream" id="searchphrasestream-lbl" class="radiobtn">Updates/Event/Todo/Milestone/Blog</label>
			</div><div class="clear"></div>
			<div class="phrases-box" style="display:block;">
				<input name="searchphrase" id="searchphrasecomment" value="comments" <?php echo ($this->searchphrase == 'comments') ? 'checked="checked"' : '';?> type="radio">
				<label for="searchphrasecomment" id="searchphrasecomment-lbl" class="radiobtn">Comments</label>
			</div><div class="clear"></div>
			<div class="phrases-box" style="display:block;">
				<input name="searchphrase" id="searchphrasefile" value="files" <?php echo ($this->searchphrase == 'files') ? 'checked="checked"' : '';?> type="radio">
				<label for="searchphrasefile" id="searchphrasefile-lbl" class="radiobtn">Files</label>
			</div><div class="clear"></div>
			<div class="phrases-box" style="display:block;">
				<input name="searchphrase" id="searchphrasepeople" value="people" <?php echo ($this->searchphrase == 'people') ? 'checked="checked"' : '';?> type="radio">
				<label for="searchphrasepeople" id="searchphrasepeople-lbl" class="radiobtn">People</label>
			</div><div class="clear"></div>
			<div class="ordering-box">
			<label for="ordering" class="ordering">
				<?php //echo JText::_('COM_SEARCH_ORDERING');?>
			</label>
			<?php //echo $this->lists['ordering'];?>
			</div>
	</fieldset>
<?php /*	
	<?php if (false): //($this->params->get('search_areas', 1)) : ?>
		<fieldset class="only">
		<legend><?php echo JText::_('COM_SEARCH_SEARCH_ONLY');?></legend>
		<?php foreach ($this->searchareas['search'] as $val => $txt) :
			$checked = is_array($this->searchareas['active']) && in_array($val, $this->searchareas['active']) ? 'checked="checked"' : '';
		?>
		<input type="checkbox" name="areas[]" value="<?php echo $val;?>" id="area-<?php echo $val;?>" <?php echo $checked;?> />
			<label for="area-<?php echo $val;?>">
				<?php echo JText::_($txt); ?>
			</label>
		<?php endforeach; ?>
		</fieldset>
	<?php endif; ?>
*/?>
<?php /*
<?php if ($this->total > 0) : ?>
	<div class="form-limit">
		<label for="limit">
			<?php echo JText::_('JGLOBAL_DISPLAY_NUM'); ?>
		</label>
		<?php echo $this->pagination->getLimitBox(); ?>
	</div>
	<p class="counter">
		<?php echo $this->pagination->getPagesCounter(); ?>
	</p>
<?php endif; ?>

</form>
*/?>
<script type="text/javascript">
	function appendSearchRange(text)
	{
		var area = $('#areas').val();
		if (area.match(/_\d\w$/))
		{
			var splitArea = area.split(/_/);
			var areaReal = splitArea[0];
		}
		else
		{
			var areaReal = area;
		}
		
		$('#areas').val(areaReal+'_'+text);
	}
</script>