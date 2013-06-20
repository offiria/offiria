<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.form.formfield');

class JFormFieldMultiField extends JFormField {

	protected $type = 'MultiField';

	private $_multiFieldsId;

	public function getInput() {

		$this->_multiFieldsId = 'multiTexts_' . $this->id;
		$values = json_decode($this->value, true); // decode data from db
		$totalRows = 1;
		$html = $jsInputHtml = array();

		$html[] = '<div id="' . $this->_multiFieldsId . '" class="' . $this->type . '">';

		if ($values !== null) {
			$totalRows = count($values);
		}

		for ($i = 0; $i < $totalRows; $i++) {

			$html[] = '<p class="' . $this->type . '">';

			foreach ($this->element->children() as $element) {
				// if the javascript modify the input field by ADD/REMOVE field, it will break the iteration index.
				// skipping unexistent field as a sanity check
				//if (empty($values[$i])) continue;

				$fieldValue = StreamTemplate::escape($values[$i][(string)$element['name']]); // bind values from json decoded array
				$elementName = $this->name . "[$i][" . $element['name'] . "]"; // name for POST
				$showRemove = false; // hide remove button

				if($i > 0) {
					$showRemove = true;
				}

				if ($element['type'] == 'list') {
					// create a new select list
					$html[] = $this->_getSelectList($element, $elementName, $fieldValue);

					if($i == 0) {
						// only need one as the rest will dynamically added with js
						$jsInputHtml[] = $this->_getSelectList($element, $elementName);
					}
				} elseif ($element['type'] == 'text') {

					// create a new input field
					$html[] = $this->_getInputField($element, $elementName, $fieldValue, $showRemove);

					if($i == 0) {
						// only need one as the rest will dynamically added with js
						$jsInputHtml[] = $this->_getInputField($element, $elementName, '', true);
					}
				}
			}

			if($i == 0) {
				$html[] = '<a href="#" id="add"><span>Add</span></a>';
			}

			$html[] = '</p>';
		}

		$html[] = '</div>';

		$this->_attachScripts($jsInputHtml);

		return implode($html);
	}

	private function _getOptions($element)
	{
		// Initialize variables.
		$options = array();

		foreach ($element->children() as $option) {

			// Only add <option /> elements.
			if ($option->getName() != 'option') {
				continue;
			}

			// Create a new option object based on the <option /> element.
			$tmp = JHtml::_('select.option', (string) $option['value'], JText::alt(trim((string) $option), preg_replace('/[^a-zA-Z0-9_\-]/', '_', $element->fieldname)), 'value', 'text', ((string) $option['disabled']=='true'));

			// Set some option attributes.
			$tmp->class = (string) $option['class'];

			// Set some JavaScript option attributes.
			$tmp->onclick = (string) $option['onclick'];

			// Add the option object to the result set.
			$options[] = $tmp;
		}

		reset($options);

		return $options;
	}

	private function _getSelectList($element, $name, $value = '') {
		// Attributes are not supported for now
		$options = (array)$this->_getOptions($element);
		return JHtml::_('select.genericlist', $options, $name, '', 'value', 'text', $value, $this->id);
	}

	private function _getInputField($element, $name, $value = '', $showRemove = false) {
		$output = '<input type="' . $element['type'] . '" value="' . $value . '" id="' . $element['id'] . '" name="' . $name . '">';
		if($showRemove === true) {
			$output .= '&nbsp;<a href="#" id="remove">Remove</a>';
		}

		return $output;
	}

	private function _attachScripts($jsInputHtml) {
		$document = JFactory::getDocument();

		// TODO: this should be only called once or made dynamic
		$document->addScriptDeclaration('
			jQuery(function() {
				var multipleTextsDiv = jQuery(\'#' . $this->_multiFieldsId . '\');
				var i = jQuery(\'#' . $this->_multiFieldsId .' p\').size() + 1;

				jQuery(\'#' . $this->_multiFieldsId .' #add\').live(\'click\', function() {
					var jsInputHtml = \''. str_replace("\n",'', implode($jsInputHtml)) .'\';
		            jsInputHtml = jsInputHtml.replace(/[0]/g, i - 1);
					jQuery(\'<p class="' . $this->type . '">\' + jsInputHtml + \'</p>\').appendTo(multipleTextsDiv);
					i++;
					return false;
				});

				jQuery(\'#' . $this->_multiFieldsId .' #remove\').live(\'click\', function() {
					if( i > 2 ) {
						jQuery(this).parents(\'p\').remove();
						i--;
					}
					return false;
				});
			});
		');
	}
}
