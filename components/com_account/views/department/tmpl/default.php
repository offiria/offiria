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
$Category = new StreamCategory();
$departments = $Category->getByCategory('department');
$positions = $Category->getByCategory('position');

?>
</div>
	<div id="department-container">
		<h3 class="section-title"><?php echo JText::_('COM_ACCOUNT_LABEL_DEPARTMENT');?></h3>
		<table class="table table-bordered table-striped table-novborder">
			<tr>
				<td class="table-header"><?php echo JText::_('COM_ACCOUNT_LABEL_DEPARTMENT_NAME'); ?></td>
				<td class="table-header" colspan="2"></td>
			</tr>
			<?php if (!empty($departments)): ?>
			<?php foreach ($departments as $dept): ?>
			<tr>
				<td><?php echo $dept->category; ?></td>
				<td>
					<span>
						<a href="<?php echo JRoute::_('index.php?option=com_account&view=account&task=manageDepartment&action=remove&category_id=' . $dept->id);
						?>"><?php echo JText::_('COM_ACCOUNT_LABEL_CATEGORY_REMOVE'); ?></a>
					</span>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php endif; ?>
			<tr>
				<td colspan="2">
					<form class="well form-search" method="post" action="<?php echo JRoute::_('index.php?option=com_account&view=account&task=manageDepartment&type=department'); ?>">
					<label><?php echo JText::_('COM_ACCOUNT_LABEL_ADD_NEW_DEPARTMENT'); ?></label>
					<input type="text" class="input-medium" id="department_category" name="department">
						<button class="btn" type="submit"><?php echo JText::_('COM_ACCOUNT_LABEL_ADD'); ?></button>
					</form>
				</td>
			</tr>
		</table>

		<h3 class="section-title"><?php echo JText::_('COM_ACCOUNT_LABEL_POSITION');?></h3>
		<table class="table table-bordered table-striped table-novborder">
			<tr>
				<td class="table-header"><?php echo JText::_('COM_ACCOUNT_LABEL_POSITION_NAME'); ?></td>
				<td class="table-header" colspan="2"></td>
			</tr>
			<?php if (!empty($positions)): ?>
			<?php foreach ($positions as $pos): ?>
			<tr>
				<td><?php echo $pos->category; ?></td>
				<td>
					<span>
						<a href="<?php echo JRoute::_('index.php?option=com_account&view=account&task=manageDepartment&action=remove&category_id=' . $pos->id);
						?>"><?php echo JText::_('COM_ACCOUNT_LABEL_CATEGORY_REMOVE'); ?></a>
					</span>
				</td>
			</tr>
			<?php endforeach; ?>
			<?php endif; ?>
			<tr>
				<td colspan="2">
					<form class="well form-search" method="post" action="<?php echo JRoute::_('index.php?option=com_account&view=account&task=manageDepartment&type=position'); ?>">
					<label><?php echo JText::_('COM_ACCOUNT_LABEL_ADD_NEW_POSITION'); ?></label>
					<input type="text" class="input-medium" id="position_category" name="position">
						<button class="btn" type="submit"><?php echo JText::_('COM_ACCOUNT_LABEL_ADD'); ?></button>
					</form>
				</td>
			</tr>
		</table>
	</div><!--end department-container-->  
<script type="text/javascript">
	$('input[id$=category]').focus(function() {
	var currentName = $(this).attr('name');
	// remove value on another field that is not the current one
	$('input[id$=category][name!='+currentName+']').val('');
	});
</script>