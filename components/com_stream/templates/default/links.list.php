<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

?>

<table width="100%">
	<thead>
		<tr>
			<th class="file-name"><?php echo JText::_('COM_STREAM_LABEL_LINKS');?></th>
		</tr>
	</thead>
	
	<tbody>
	<?php foreach($links as $no => $row){
		$user = JXFactory::getUser($row->user_ids);
	?>
		<tr>
			<td>
				<?php echo ($no+1).'. '.StreamMessage::format($row->link); ?>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>
<div class="pagination">
<?php echo $pagination->getPagesLinks(); ?>
</div>