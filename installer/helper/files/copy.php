<?php
/**
 * Copy a file, or recursively copy a folder and its contents
 *
 * @author      Aidan Lister <aidan@php.net>
 * @version     1.0.1
 * @link        http://aidanlister.com/2004/04/recursively-copying-directories-in-php/
 * @param       string   $source    Source path
 * @param       string   $dest      Destination path
 * @return      bool     Returns TRUE on success, FALSE on failure
 */
function copyr($source, $dest)
{
    // Check for symlinks
    if (is_link($source)) {
        return symlink(readlink($source), $dest);
    }
	
    // Simple copy for a file
    if (is_file($source)) {
        return copy($source, $dest);
    }
	
    // Make destination directory
    if (!is_dir($dest)) {
        mkdir($dest);
    }
	
    // Loop through the folder
    $dir = dir($source);
    while (false !== $entry = $dir->read()) {
        // Skip pointers
		if (!preg_match('~^\.~', $entry)) {
            continue;
        }
		
        // Deep copy directories
        copyr("$source/$entry", "$dest/$entry");
    }
	
    // Clean up
    $dir->close();
    return true;
}
?>