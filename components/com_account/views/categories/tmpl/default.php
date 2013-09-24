<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// no direct access
defined('_JEXEC') or die;

?>
<div class="account-navbar">
	<?php 
	  echo $this->showNavBar(); 
	?>
</div><!--profile-navbar-->
	<?php
		$Category = new StreamCategory();
		$blogs = $Category->getBlogs();
		$events = $Category->getEvents();
		$groups = $Category->getGroups();
		$listings = array(
		'blog' => $blogs,
		'event' => $events,
		'group' => $groups
		);

		foreach ($listings as $type=>$lists) {
		?>
		
	<div id="category-container">
		<h3 class="section-title"><?php echo JText::_('COM_ACCOUNT_LABEL_'.strtoupper($type).'_CATEGORIES');?></h3>
		<table class="table table-bordered table-striped table-novborder">
			<tr>
				<td class="table-header"><?php echo JText::_('COM_ACCOUNT_LABEL_CATEGORY');?></td>
				<td class="table-header" colspan="2">#</td>
			</tr>
			<?php
			if (count($lists) > 0): 
			foreach ($lists as $list) {
			?>
			<tr>
				<td><?php echo $list->category;?></td>
				<td><?php echo $Category->countMessageByCategoryId($list->id, $type); ?></td>
				<td>
					<span>
						<a href="<?php echo JRoute::_('index.php?option=com_account&view=account&task=categories&action=remove&category=' . $list->id);
						?>"><?php echo JText::_('COM_ACCOUNT_LABEL_CATEGORY_REMOVE'); ?></a>
					</span>
				</td>
			</tr>
			<?php } ?>
			<?php else: ?>
			<tr>
				<td colspan="3">
					<?php echo JText::_('COM_ACCOUNT_LABEL_'.strtoupper($type).'_EMPTY_CATEGORIES'); ?>
				</td>
			</tr>
			<?php endif; ?>
			
			<tr>
				<td colspan="3">
					<form class="well form-search" method="post" action="<?php echo JRoute::_('index.php?option=com_account&view=account&task=categories'); ?>">
						<label><?php echo JText::_('COM_ACCOUNT_LABEL_ADD_NEW_CATEGORY'); ?></label>
						<input type="text" class="input-medium" id="<?php echo $type; ?>_category" name="<?php echo $type; ?>_category">
						<input type="hidden" value="<?php echo $type; ?>" name="type"/>
						<button class="btn" type="submit"><?php echo JText::_('COM_ACCOUNT_LABEL_ADD'); ?></button>
					</form>
				</td>
			</tr>
			
		</table>
	
	</div><!--end category-container-->  
	
	<?php } // end loop for listings ?>

<script type="text/javascript">
$('#category-container form').submit(function() {
	S.enqueueMessage();
});
</script>
