<?php
class ConfigManager 
{
	private $filepath;
	private $buffer;
	public function __construct($filepath) {
		$this->filepath = $filepath;
		if (!$this->buffer) {
			$this->buffer = file_get_contents($this->filepath);
		}
		return $this;
	}

	/**
	 * Standard search for config lookup
	 * @return Array preg_match result of find
	 */
	public function find($key) {
		$buffer = $this->buffer;
		$pattern = "~.*\\$".$key."\b\s?=\s?'(.*)';~";
		preg_match($pattern, $buffer, $matches);
		return $matches;
	}

	public function add($key, $value) {}

	public function modify($key, $new) {
		$finder = $this->find($key);
		if (!isset($finder[1])) return false;

		$originalFind = $finder[1];
		$originalLine = $finder[0];
		// replace the entire line with the new value and make sure its wrapped with quote, otherwise it will break the replace for empty values
		$newLine = str_replace("'".$originalFind."'", "'".$new."'", $originalLine);
		$this->buffer = str_replace($originalLine, $newLine, $this->buffer);
		return $this;
	}

	public function getValue($key) {
		$result = $this->find($key);
		if (empty($result[1])) return false;
		else return $result[1];
	}

	public function delete($value) {}

	public function save() {
		return file_put_contents($this->filepath, $this->buffer);
	}

	public function copy($destination) {
		return copy($this->filepath, $destination);
	}
}
?>