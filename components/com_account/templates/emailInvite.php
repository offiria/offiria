<?php /* 
<div>Hi there,</div>
<div><?php echo $sender->get('name');?> is inviting you to join his site at <?php echo JURI::base();?></div>
<div>Please click on the link below to get to the sign up page and join <?php echo $sender->get('name');?> in his updates!</div>
<div><a href="<?php echo $invitationUrl;?>"><?php echo $invitationUrl;?></a></div>
*/?>

<html>
<head>
	<title>Invitation to <?php echo JText::_('CUSTOM_SITE_NAME');?></title>
</head>
<body style="background-color:#F6F6F6;font-family:'Helvetica Neue', helvetica, arial, sans-serif; font-size:16px; color:#333;" >

<center>
<table width="608" cellpadding="0" cellspacing="0" border="0" align="center" style="font-family:arial, sans-serif; font-size:16px; color:#353535;margin-bottom:45px; margin-top: 30px;line-height:21px;">
	
	<tr class="header" height="90px">
		<td>
			<div class="title"><img src="<?php echo JURI::base(); ?>/templates/<?php echo $currentTemplate;?>/images/email/logo-email.png" /></div>
		</td>
		
		<td style="text-align: right;">
			<div class="slogan" style="margin-top: 3px; margin-right: 3px; color:#467089;"><?php echo JText::_('COM_ACCOUNT_LABEL_SLOGAN');?></div>
		</td>
	</tr>
	
	<tr class="content">
		<td colspan="2" style="border:1px solid #e2e2e2; background: #fff;">
			<table cellpadding="0" cellspacing="0" style="width: 590px; margin: 8px auto;">
				<tr>
					<td style="background: #1A304D; padding: 21px 20px 19px; color: #fff; font-size:20px; font-weight: bold;">Invitation to <?php echo JText::_('CUSTOM_SITE_NAME');?> <span style="display: block; font-size: 15px; text-transform: normal; margin-top: 2px; font-weight: normal;"><?php echo $sender->get('name');?> is inviting you to join his site at <strong><?php echo JURI::base(); ?></strong> </span></td>
					<td style="text-align: center; background: #264260; width: 80px; height: 80px; vertical-align: middle;"><img src="<?php echo JURI::base(); ?>/templates/<?php echo $currentTemplate;?>/images/email/icon-info.png" /></td>
				</tr>
				
				<tr>
					<td colspan="2">
						<table cellpadding="0" cellspacing="0" style="padding: 0 20px 12px; margin: 10px 0 10px; width: 100%;">
							<tr height="50">
								<td style="font-size: 15px; color: #888; border-bottom: 1px solid #ddd;">Site :</td>
								<td style="border-bottom: 1px solid #ddd; font-weight: bold;"><?php echo JURI::base();?></td>
							</tr>
							
							<tr>
								<td colspan="2">
									<div style="margin-top: 20px; font-size: 14px; line-height: 18px;">
										Please click on the link below to get to the sign up page and join <strong><?php echo $sender->get('name');?></strong> in <?php echo JText::_('CUSTOM_SITE_NAME');?>!
									</div>
								</td>
							</tr>

							<tr>
								<td colspan="2">
									<div style="width: 528px; padding: 10px;margin-top: 10px; margin-bottom: 25px; font-size: 14px; line-height: 18px; color: #3A87AD; background-color: #D9EDF7; border: 1px solid #BCE8F1; word-wrap: break-word;">
										<a href="<?php echo $invitationUrl;?>"><?php echo $invitationUrl;?></a>
									</div>
								</td>
							</tr>
							
							<tr>
								<td colspan="2" style="border-top: 1px solid #ddd;">
									<div style="font-size: 20px; text-align: center; margin-top: 22px;">Here are the <strong>Top 3 Reasons</strong> why should accept </br><strong><?php echo $sender->get('name');?></strong>'s invitation.</div>								
								</td>
							</tr>
						</table>
						
						<table cellpadding="0" cellspacing="0" style="padding: 0 20px 12px; margin: 8px 0 10px; width: 100%;">
							<tr>

								<td style="text-align:center; width: 183px;">
									<img src="<?php echo JURI::base(); ?>/templates/<?php echo $currentTemplate;?>/images/email/mini-one.png" style="padding: 0 3px; border: 1px solid #ddd; margin-bottom: 12px;" />
									<div style="font-weight: bold;">Enhance Collaboration</div>
									<p style="line-height: 17px; margin-left: 6px; margin-right: 6px; text-align: left; font-size: 13px;">With familiar social network concept, it makes internal collaboration and knowledge sharing fun and effortless.</p>
								</td>
								<td style="text-align:center; width: 183px;">
									<img src="<?php echo JURI::base(); ?>/templates/<?php echo $currentTemplate;?>/images/email/mini-two.png" style="padding: 0 3px; border: 1px solid #ddd; margin-bottom: 12px;" />
									<div style="font-weight: bold;">Share Everything</div>
									<p style="line-height: 17px; margin-left: 6px; margin-right: 6px; text-align: left; font-size: 13px;">We believe in the power of sharing. Almost everything else bring more benefit when we share it with our team.</p>
								</td>
								<td style="text-align: center; width: 183px;">
									<img src="<?php echo JURI::base(); ?>/templates/<?php echo $currentTemplate;?>/images/email/mini-three.png" style="padding: 0 3px; border: 1px solid #ddd; margin-bottom: 12px;" />
									<div style="font-weight: bold;">Completely Mobile</div>
									<p style="line-height: 17px; margin-left: 6px; margin-right: 6px; text-align: left; font-size: 13px;">We design <?php echo JText::_('CUSTOM_SITE_NAME');?> to be mobile friendly from day one. So you can share everything while on-the-go.</p>
								</td>
							</tr>
							
							<tr>
								<td colspan="3">
									<div style="margin-top: 20px; font-size: 14px; line-height: 18px; border-top: 1px solid #ddd; padding-top: 20px;">
										So, what are you waiting for? <strong>Sign Up and Join <i><?php echo $sender->get('name');?></i> in <?php echo JText::_('CUSTOM_SITE_NAME');?> now!</strong>
									</div>
								</td>
							</tr>
							
							<tr>
								<td colspan="3">
									<div style="width: 528px; padding: 10px;margin-top: 10px; margin-bottom: 0px; font-size: 14px; line-height: 18px; color: #3A87AD; background-color: #D9EDF7; border: 1px solid #BCE8F1; word-wrap: break-word;">
										<a href="<?php echo $invitationUrl;?>"><?php echo $invitationUrl;?></a>
									</div>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				
			</table>
		</td>
	</tr>
	<tr height="30"><td>&nbsp;</td><tr>
	<tr class="footer">
		<td style="border-bottom: 1px solid #ddd; border-top: 1px solid #ddd; padding: 20px 0; font-size: 12px;">
			<div>&copy; <?php echo JText::_('CUSTOM_COMPANY_NAME');?></div>
		</td>
		<td style="border-top: 1px solid #ddd; border-bottom: 1px solid #ddd; text-align: right;">
			<span><a href="http://www.twitter.com/<?php echo strtolower(JText::_('CUSTOM_TWITTER_PAGE'));?>" target="_blank"><img src="<?php echo JURI::base(); ?>/templates/<?php echo $currentTemplate;?>/images/email/twitter.png" /></a></span>
			<span><a href="http://www.facebook.com/<?php echo strtolower(JText::_('CUSTOM_FACEBOOK_PAGE'));?>" target="_blank"><img src="<?php echo JURI::base(); ?>/templates/<?php echo $currentTemplate;?>/images/email/fb.png" /></a></span>
		</td>
	</tr>

</table>

</center>
</body>
</html>
