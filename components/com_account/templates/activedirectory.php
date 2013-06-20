<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<h3 class="section-title"><?php echo JText::_('COM_ACCOUNT_AD_LABEL_ACTIVE_DIRECTORY');?></h3>
<div id="account-integration">
	<form class="edit" action="<?php echo JRoute::_('index.php?option=com_account&view=integration');?>" method="post">
		<ul class="account-form">			
			<li>
				<label for="ad_integration" class="" id="ad_integration-lbl"><?php echo JText::_('COM_ACCOUNT_AD_LABEL_INTEGRATION');?></label>
				<select id="ad_integration" name="ad_integration" class="vertical">
					<option value="activedirectory" selected><?php echo JText::_('COM_ACCOUNT_AD_LABEL_ACTIVE_DIRECTORY');?></option>
					<!--option value="facebookconnect">Facebook Connect</option>
					<option value="twitter">Twitter</option-->
				</select>
			</li>					
			<li>
				<label for="ad_hi" class="" id="ad_hi-lbl"><?php echo JText::_('COM_ACCOUNT_AD_LABEL_HOST_IP_NAME');?></label>
				<input type="text" id="ad_hi" name="ad_hi" class="vertical" value="<?php echo $this->getParam('hi');?>" />
			</li>					
			<li>
				<label for="ad_dm" class="" id="ad_dm-lbl"><?php echo JText::_('COM_ACCOUNT_AD_LABEL_DOMAIN');?></label>
				<input type="text" id="ad_dm" name="ad_dm" class="vertical" value="<?php echo $this->getParam('dm');?>" />
			</li>			
			<li>
				<label for="ad_dc" class="" id="ad_dc-lbl"><?php echo JText::_('COM_ACCOUNT_AD_LABEL_DIRECTORY_CONTROL');?></label>
				<input type="text" id="ad_dc" name="ad_dc" class="vertical" value="<?php echo $this->getParam('dc');?>" />
			</li>				
			<li>
				<label for="ad_un" class="" id="ad_un-lbl"><?php echo JText::_('COM_ACCOUNT_AD_LABEL_USERNAME');?></label>
				<input type="text" id="ad_un" name="ad_un" class="vertical" value="<?php echo $this->getParam('un');?>" />
			</li>				
			<li>
				<label for="ad_pw" class="" id="ad_pw-lbl"><?php echo JText::_('COM_ACCOUNT_AD_LABEL_PASSWORD');?></label>
				<input type="password" id="ad_pw" name="ad_pw" class="vertical" value="" />
			</li>	
		</ul>		
		<div class="submit">
			<input type="hidden" value="integration" name="view"/>
			<input class="btn btn-info" type="submit" value="<?php echo JText::_('COM_STREAM_LABEL_SAVE');?>" name="submit"/>
		</div>		
	</form>
</div>