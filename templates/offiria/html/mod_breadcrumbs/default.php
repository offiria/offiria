<?php
/**
 * @version		$Id: default.php 20338 2011-01-18 08:44:38Z infograf768 $
 * @package		Joomla.Site
 * @subpackage	mod_breadcrumbs
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>

<ul class="breadcrumb<?php echo $moduleclass_sfx; ?>">
<?php for ($i = 0; $i < $count; $i ++) :

	// If not the last item in the breadcrumbs add the separator
	if ($i < $count -1) {
		if (!empty($list[$i]->link)) {
			echo '<li><a href="'.$list[$i]->link.'" >'.$list[$i]->name.'</a><span class="divider">/</span></li>';
		} else {
			echo '<li class="active">';
			echo $list[$i]->name;
			echo '</li>';
		}
		
	}  
	else
	{
		echo '<li>';
		echo $list[$i]->name;
		echo '</li>';
	}
endfor; ?>
</ul>
