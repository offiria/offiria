<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
	<tr>
		<td valign="top" style="background:#f9f9f9;font-size:14px;" width="50" >
			<img src="<?php echo $actor->getThumbAvatarURL();?>" alt="logo" style="display:block;border:3px solid #ffffff;outline:1px solid #ccc;margin:20px 0 0 20px;" width="50" height="50" /> 
		</td>
		<td valign="top" style="background:#f9f9f9;font-size:14px;" width="20" >

		</td>
		<td valign="middle" style="background:#f9f9f9;font-size:14px;" width="538" >		
			<p style="padding:0;margin:20px 20px 0 0;font-family:Helvetica,Geneva,sans-serif;"><strong><?php echo StreamMessage::formatShortDisplay($contentName, $linkOption);?></strong></p>
			<?php $now = new JDate(); ?>
			<p style="margin:0;padding:0;font-size:13px;color:#777;"><?php echo JText::sprintf('COM_STREAM_POST_DETAIL', $actor->get('name'), $actor->get('designation'), JXDate::formatDate($now, $formatDateShort));?></p>
		</td>
	</tr>