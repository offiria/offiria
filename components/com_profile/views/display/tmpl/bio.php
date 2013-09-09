<?php
$my = JXFactory::getUser();
$category = new StreamCategory();
$depts = $category->getByCategory('department');
$positions = $category->getByCategory('position');
?>
<div class="alert alert-info">
	<?php if($this->user->id == $my->id){ ?>
	<?php echo JText::_('COM_PROFILE_DESCRIPTION_YOU_SHARE'); ?>
	<?php } else { ?>
	<?php echo $this->user->name; ?> <?php echo JText::_('COM_PROFILE_DESCRIPTION_OTHER_SHARE'); ?>
	<?php } ?>
  </div>
<?php
$fieldSets = $this->form->getFieldsets('params');
foreach ($fieldSets as $name => $fieldSet) :
	?>
	<div class="data-grid">
	<?php
	if (isset($fieldSet->description) && trim($fieldSet->description)) :
		echo '<h3>'.$this->escape(JText::_($fieldSet->description)).'</h3>';
	endif;
	?>
	
		<ul>
			<?php foreach ($this->form->getFieldset($name) as $id => $field) : ?>
			<li>
				<?php echo '<div class="profile-details-box">'; ?>

				<?php echo $field->label; ?>
				
				<?php echo '<div class="profile-data">'; ?>
					
				<?php
				$values = json_decode($field->value, true);

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

					echo '<ul>';

					foreach($values as $value){

						echo '<li><div class="profile-arrow">&#8250;</div>';

						// Get the last item so we can wrap it with rounded brackets
						$last_item = end($value);

						foreach($value as $elementKey=>$elementValue){
							echo '<div class="' . $elementKey . ' details-' . strtolower(str_replace(' ', '-', $elementValue)) . '">';
							echo StreamTemplate::escape($elementValue);
							echo '</div>';
						}

						echo '<div class="clear"></div></li>';
					}

					echo '</ul>';
				}
				
				echo '</div>';
				
				echo '</div>';
				
				echo '<div class="clear"></div>';
				?>

			</li>
			<?php endforeach; ?>
		</ul>
	</div>
	
<?php endforeach; ?>
