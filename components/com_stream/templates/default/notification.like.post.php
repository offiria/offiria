<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
	<tr>
		<td valign="top" style="background:#f9f9f9;font-size:14px;" width="50" >
			<img src="<?php echo $postOwner->getThumbAvatarURL();?>" alt="logo" style="display:block;border:3px solid #ffffff;outline:1px solid #ccc;margin:20px 0 0 20px;" width="50" height="50" /> 
		</td>
		<td valign="top" style="background:#f9f9f9;font-size:14px;" width="10" >
			<div style="width:0;min-height:0;border-width:8px;border-style:solid;border-color:#f8fafb #dbf5f7 #f8fafb #f8fafb;margin-top:35px;"></div>
		</td>

		<td valign="middle" style="background:#f9f9f9;font-size:14px;" width="548" >		
			<div id="topic" style="background:#dbf5f7;padding:10px;color:#333333;display:block;margin:20px 20px 5px 0;">
				<p style="padding:0;margin:0;font-style:italic;font-family:georgia,serif;">&#8220; <?php echo StreamMessage::formatShortDisplay($streamMessage->get('message'), 180, $linkOption);?> &#8221;</p>			
			</div>
			<?php $date = new JDate( $streamMessage->created ); ?>
			<p style="margin:0;padding:0;font-size:13px;color:#777;"><?php echo JText::sprintf('COM_STREAM_POST_DETAIL', $postOwner->get('name'), $postOwner->get('designation'), JXDate::formatDate( $streamMessage->created, $formatDateShort, $postOwner ));?></p>
		</td>
	</tr>
	<tr>

		<td valign="top" style="background:#f9f9f9;font-size:14px;" width="50" >
			<img src="<?php echo $sender->getThumbAvatarURL();?>" alt="logo" style="display:block;border:3px solid #ffffff;outline:1px solid #ccc;margin:20px 0 0 20px;" width="50" height="50" /> 
		</td>
		<td valign="top" style="background:#f9f9f9;font-size:14px;" width="10" >
		</td>
		<td valign="middle" style="background:#f9f9f9;font-size:14px;" width="548" >
			<p style="padding:0;margin:20px 20px 0 0;font-family:Helvetica,Geneva,sans-serif;"><strong> &hearts; <?php echo JText::_('COM_STREAM_LIKE_LABEL');?></strong> <?php echo JText::_('COM_STREAM_LABEL_YOUR_WALL_POST');?> <span style="font-size:12px;color:#999;"><?php echo JText::sprintf('COM_STREAM_LIKE_AND_OTHER_MANY', ($streamMessage->countLike() - 1)); ?></span></p>
			<?php $now = new JDate(); ?>
			<p style="margin:0;padding:0;font-size:13px;color:#777;"><?php echo JText::sprintf('COM_STREAM_POST_DETAIL', $sender->get('name'), $sender->get('designation'), JXDate::formatDate( $now, $formatDateShort, $postOwner ));?></p>
		</td>
	</tr>