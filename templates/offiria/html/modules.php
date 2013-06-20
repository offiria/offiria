<?php
/**
 * @version		$Id: modules.php 21097 2011-04-07 15:38:03Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	Templates.beez_20
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * beezDivision chrome.
 *
 * @since	1.6
 */
function modChrome_default($module, &$params, &$attribs)
{

	$headerLevel = isset($attribs['headerLevel']) ? (int) $attribs['headerLevel'] : 3;
	if (!empty ($module->content)) { ?>
<div class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx')); ?>">
<?php if ($module->showtitle) { ?> <h<?php echo $headerLevel; ?>><span
	class="backh"><span class="backh2"><span class="backh3"><?php echo $module->title; ?></span></span></span></h<?php echo $headerLevel; ?>>
<?php }; ?> <?php echo $module->content; ?></div>
<?php };
}


