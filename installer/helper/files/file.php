<?php
class FileHelper {
	public static function filterStuckFiles($files) {
		foreach ($files as $idx=>$file) {
			if (is_writable($file)) {
				unset($files[$idx]);
			}
		}
		return $files;
	}
}
?>