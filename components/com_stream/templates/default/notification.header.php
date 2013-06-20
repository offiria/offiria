<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0">
	
<center style="background-color:#f2f2f2;font-family:'Helvetica Neue', helvetica, arial, sans-serif; font-size:16px; color:#353535;">

<table width="608" cellpadding="0" cellspacing="0" border="0" align="center" style="font-family:'Helvetica'' Neue', helvetica, arial, sans-serif; font-size:16px; color:#353535;">
	<tr>
	<td height="60" valign="middle" style="background:#f2f2f2;">

	<a href="<?php echo JURI::base();?>"><img src="<?php echo JURI::base();?>components/com_stream/assets/images/logoblack.png" alt="logo" style="display:block;border:none;margin-left:10px;" width="154" height="50" /></a>
	</td>
	</tr>
</table> 
<table width="608" cellpadding="0" cellspacing="0" border="0" align="center" style="font-family:'Helvetica'' Neue', helvetica, arial, sans-serif; font-size:16px; color:#353535;margin-bottom:50px;border:1px solid #e2e2e2;line-height:21px;">
	<tr>
		<td valign="middle" style="background:#f9f9f9;font-size:14px;" colspan="3" >
		<div id="notification" style="line-height:30px;font-size:15px;font-weight:bold;padding-bottom:5px;border-bottom:1px solid #e2e2e2;margin:20px 20px 0;padding-bottom:0px;"><?php echo $notificationTitle;?></div>
		<div id="reply" style="float:right;padding:5px 10px;background:#333333;margin-right:20px;margin-top:-52px;"><a href="<?php echo $messageUrl;?>" style="text-decoration:none;color:#fefefe;"><?php echo JText::_('COM_STREAM_NOTIFICATION_LABEL_CHECK_NOW');?></a></div>

		</td>
	</tr>