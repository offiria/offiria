<?php
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class MessagingTemplate
{
	var $vars; // Holds all the template variables

	/**
	 * Constructor
	 *
	 * @param $file string the file name you want to load
	 */
	public function __construct($file = null)
	{
		// $file can also be a view object. If it is an object, assign to internal object
		$this->file = $file;
	}

	/**
	 * Set a template variable.
	 */
	public function set($name, $value)
	{
		$this->vars[$name] = $value;

		// Return this object
		return $this;
	}

	/**
	 * Set a template variable by reference
	 */
	public function setRef($name, &$value)
	{
		$this->vars[$name] =& $value;

		// Return this object
		return $this;
	}

	public function load($file)
	{
		$template = new MessagingTemplate();

		if ($this->vars) {
			extract($this->vars, EXTR_REFS);
		}

		$file = JPATH_ROOT . DS . 'components' . DS . 'com_messaging' . DS . 'templates' . DS . 'default' . DS . $file . '.php';

		include($file);

		return $this;
	}

	/**
	 * Open, parse, and return the template file.
	 *
	 * @param $file string the template file name
	 */
	public function fetch($file = null)
	{
		$tmpFile = $file;

		if (empty($file)) {
			$file = $this->file;
		}

		$file = JPATH_ROOT . DS . 'components' . DS . 'com_messaging' . DS . 'templates' . DS . 'default' . DS . $file . '.php';

		// Template variable: $my;
		$my = JXFactory::getUser();
		$this->setRef('my', $my);

		// Template variable: the rest.
		if ($this->vars) {
			extract($this->vars, EXTR_REFS);
		}

		if (!JFile::exists($file)) {
			$app = JFactory::getApplication();
			$app->enqueueMessage(JText::sprintf('COM_STREAM_TEMPLATE_FILE_NOT_FOUND', $tmpFile . '.php'), 'error');
			return;
		}

		ob_start(); // Start output buffering
		require($file); // Include the file
		$contents = ob_get_contents(); // Get the contents of the buffer
		ob_end_clean(); // End buffering and discard

		// Replace all _QQQ_ to "
		// Language file now uses new _QQQ_ to maintain Joomla 1.6 compatibility
		$contents = MessagingTemplate::quote($contents);

		return $contents; // Return the contents
	}

	/**
	 * Alias to $document->countModules function
	 */
	public function countModules($condition)
	{
		$document = JFactory::getDocument();
		return $document->countModules($condition);
	}

	/**
	 * Render external module position
	 */
	public function renderModules($position, $attribs = array())
	{
		jimport('joomla.application.module.helper');

		$modules = JModuleHelper::getModules($position);
		$modulehtml = '';

		foreach ($modules as $module) {
			// If style attributes are not given or set, we enforce it to use the xhtml style
			// so the title will display correctly.
			if (!isset($attribs['style']))
				$attribs['style'] = 'xhtml';

			$modulehtml .= JModuleHelper::renderModule($module, $attribs);
		}

		// Add placholder code for onModuleRender search/replace
		$modulehtml .= '<!-- ' . $position . ' -->';
		echo $modulehtml;
	}

	/**
	 *   Escape output string
	 */
	public function escape($var, $function = 'htmlspecialchars')
	{
		if (in_array($function, array('htmlspecialchars', 'htmlentities'))) {
			return call_user_func($function, $var, ENT_COMPAT, 'UTF-8');
		}
		return call_user_func($function, $var);
	}

	/**
	 * Replace all _QQQ_ to "
	 * Language file now uses new _QQQ_ to maintain Joomla 1.6 compatibility
	 */
	public function quote($str)
	{
		$str = str_replace('_QQQ_', '"', $str);
		return $str;
	}

	public function object_to_array($obj)
	{
		$_arr = is_object($obj) ? get_object_vars($obj) : $obj;
		$arr = array();
		foreach ($_arr as $key => $val) {
			$val = (is_array($val) || is_object($val)) ? $this->object_to_array($val) : $val;
			$arr[$key] = $val;
		}
		return $arr;
	}
}