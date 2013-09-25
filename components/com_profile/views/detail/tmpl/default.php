<?php
/**
 * @package		Offiria
 * @subpackage	com_profile
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */

// no direct access
defined('_JEXEC') or die;
$category = new StreamCategory();
$depts = $category->getByCategory('department');
$positions = $category->getByCategory('position');

?>

<div class="profile-navbar">
	<?php echo $this->showNavBar(); ?>
</div><!--end profile-navbar-->

<form action="<?php echo JRoute::_('index.php?option=com_profile&view=edit&task=details'); ?>" method="post" class="edit">

	<?php
	$fieldSets = $this->form->getFieldsets('params');
	// collection of required fields for getting started 
	$requiredFields = array();

	foreach ($fieldSets as $name => $fieldSet) :

		if (isset($fieldSet->description) && trim($fieldSet->description)) :
			echo '<h3>'.$this->escape(JText::_($fieldSet->description)).'</h3>';
		endif;
		?>

		<fieldset>
			<ul>
				<?php foreach ($this->form->getFieldset($name) as $id => $field) : ?>
				<?php // hardcoded values as the form generated from xml and the option is from database ?>
				<?php if ($field->fieldname == 'work_department' || $field->fieldname == 'work_position'): ?>
				<?php if (!empty ($depts) && $field->fieldname == 'work_department'): ?>
				<li class="clearfix">
					<?php echo JText::_($field->label); ?>
					<select name="params[work_department]">
						<option><?php echo JText::_('COM_PROFILE_LABEL_DETAILS_UNASSIGNED'); ?></option>
						<?php foreach ($depts as $dept): ?>
						<?php $selected = ($field->value == $dept->id) ? 'selected="selected"' : ''; ?>
						<option <?php echo $selected; ?> value="<?php echo $dept->id; ?>"><?php echo $dept->category; ?></option>
						<?php endforeach; ?>
					</select>
				</li>
				<?php elseif (!empty ($positions) && $field->fieldname == 'work_position'): ?>
				<li class="clearfix">
					<?php echo JText::_($field->label); ?>
					<select name="params[work_position]">
						<option><?php echo JText::_('COM_PROFILE_LABEL_DETAILS_UNASSIGNED'); ?></option>
						<?php foreach ($positions as $pos): ?>
						<?php $selected = ($field->value == $pos->id) ? 'selected="selected"' : ''; ?>
						<option <?php echo $selected; ?> value="<?php echo $pos->id; ?>"><?php echo $pos->category; ?></option>
						<?php endforeach; ?>
					</select>
				</li>
				<?php endif; ?>
				<?php continue; ?>
				<?php endif; ?>
				<li class="clearfix">
					<?php echo JText::_($field->label); ?>
					<?php echo $field->input; ?>
				</li>
				<?php 
				  // this required fields is validated for filled the getting started task
				  if ($field->required) {
				  $requiredFields[] = $field->fieldname;
				  }
				?>
				<?php endforeach; ?>
			</ul>
		</fieldset>

	<?php endforeach; ?>
	<div class="submit">
		<input type="hidden" name="required_fields" value="<?php echo implode(',', $requiredFields); ?>" />
		<input class="btn btn-info" type="submit" value="Save" name="btnSubmit">
	</div>
</form>
<script type="text/javascript">
	$(document).ready(function() {
		$('form.edit').submit(function(e) {
			var input = $('#params_contact_secondemail'),
				contactIm = $("input[name$='[contact_im_content]']"),
				contactMobile = $("input[name$='[contact_mobilephone_no]']");

			// clear all error messages
			S.enqueueMessage();

			if(input.val().length) {
				if(!S.validate.element('email', input))
					e.preventDefault();
			}

			if(contactIm.length > 1) {
				contactIm.each(function(index) {
					// Display the error over here
					var injectErrorTo = $('#multiTexts_params_contact_im').parent().parent();

				    if($(this).val().length === 0) {
				    	S.enqueueMessage('All added fields are required', 'error', injectErrorTo);
				    	e.preventDefault();
				    	return false;
				    }
				});
			}

			if(contactMobile.length > 1) {
				contactMobile.each(function(index) {
					// Display the error over here
					var injectErrorTo = $('#multiTexts_params_contact_im').parent().parent();

				    if($(this).val().length === 0) {
				    	S.enqueueMessage('All added fields are required', 'error', injectErrorTo);
				    	e.preventDefault();
				    	return false;
				    }
				});
			}
		});
	});
</script>
