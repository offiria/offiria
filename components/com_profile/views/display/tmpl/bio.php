<?php
$my = JXFactory::getUser();
$category = new StreamCategory();
$depts = $category->getByCategory('department');
$positions = $category->getByCategory('position');

if ($this->user->id == $my->id) {
	echo '<div class="alert alert-info">' . JText::_('COM_PROFILE_DESCRIPTION_YOU_SHARE') . '</div>';
} else {
	if ($my->getGettingStartedCompletion('COMPLETE_PROFILE') < 100) {
		echo '<div class="alert alert-info">' . $this->user->name . ' ' . JText::_('COM_PROFILE_DESCRIPTION_OTHER_SHARE') . '</div>';
	}
} 
?>

<table border="0" cellpadding="0" class="table">
	<col width="30%"/>
	<col width="70%"/>
	<?php
	$fieldSets = $this->form->getFieldsets('params');
	foreach ($fieldSets as $name => $fieldSet) {
		if (isset($fieldSet->description) && trim($fieldSet->description)) {
			?>
			<tr>
				<td colspan="2" class="table-title"><label><?php echo $this->escape(JText::_($fieldSet->description));?></label></td>
			</tr>	
			<?
		}
		
		
		foreach ($this->form->getFieldset($name) as $id => $field) {
			$values = json_decode($field->value, true);
			if (($field->fieldname == 'personal_birthday' && $values[0]['personal_birthday_age_public'] == 'Public') || ($field->fieldname != 'personal_birthday')) {
				?>
				<tr>
					<td><?php echo $field->label;?></td>
					<td class="data-grid">
				<?php
				if(!is_array($values) || is_null($values)){
					if ($field->fieldname == 'work_department' && !empty ($depts)) {
					   	// if it's department, lets fetch the value
						foreach ($depts as $dept) { if ($field->value == $dept->id) echo $dept->category; }
					} elseif ($field->fieldname == 'work_position' && !empty ($positions)) {
						// if it's position, lets fetch the value
						foreach ($positions as $position) { if ($field->value == $position->id) echo $position->category; }
					} else {
						// If it's not decoded it means it's not a multiple value field/array
						echo StreamTemplate::escape($field->value);
					}
				} else {
					echo '<ul class="profile-data">';
					foreach($values as $value){
						echo '<li><div class="profile-arrow">&#8250;</div>';
						foreach($value as $elementKey=>$elementValue){
							echo '<div class="' . $elementKey . ' details-' . strtolower(str_replace(' ', '-', $elementValue)) . '">';
							echo StreamTemplate::escape($elementValue);
							echo '</div>';
						}
						echo '<div class="clear"></div></li>';
					}
					echo '</ul>';
				}
				?>
				</td>
			</tr>				
			<?php
			}
		}
	} ?>
</table>