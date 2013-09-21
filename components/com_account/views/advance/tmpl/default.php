<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Created by com_combuilder - http://www.notwebdesign.com
 */

// no direct access
defined('_JEXEC') or die;
$jxConfig = new JXConfig();

?>
<div class="account-navbar">
<?php echo $this->showNavBar(); ?>
</div>
<style>
/**
 * Start by hiding the checkboxes
 */
input[type=checkbox] {
	visibility: hidden;
}
</style>

<form class="edit" action="<?php echo JRoute::_('index.php?option=com_account&view=account');?>" method="post">
<table border="0" cellpadding="0" class="table">
	<col width="30%"/>
	<col width="20%"/>
	<col width="40%"/>
	<col width="10%"/>
	<!-- CROCODOCS SETTINGS -->
	<tr>
		<td class="table-title" colspan="3"><label for="jform_crocodocsenable"><?php echo JText::_('COM_ACCOUNT_LABEL_CROCODOCS');?></label></td>
		<td class="table-title"><label style="float: right !important;">
			<div class="checkboxThree" >
				<input type="checkbox" id="jform_crocodocsenable" name="jform[crocodocsenable]" value="1" <?php echo ($this->crocodocsenable == '1') ? 'checked="checked"' : '';?> onchange="javascript:enableFields();"/>
				<label for="jform_crocodocsenable"></label>
			</div></label>
		</td>
	</tr>
	<tr>
		<td><label id="jform_crocodocs-lbl" for="jform_crocodocs" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_TOKEN');?></label></td>
		<td colspan="3"><input type="text" name="jform[crocodocs]" id="jform_crocodocs" value="<?php echo $this->crocodocs;?>" size="30"></td>
	</tr>
	<tr>
		<td colspan="4"><span class="api-note"><?php echo JText::_('COM_ACCOUNT_LABEL_HELPER_CROCODOCS'); ?></span></td>
	</tr>
	<!-- SCRIBD SETTINGS -->
	<tr>
		<td class="table-title" colspan="3"><label for="jform_scribdenable"><?php echo JText::_('COM_ACCOUNT_LABEL_SCRIBD');?></label></td>
		<td class="table-title"><label style="float: right !important;">
		  	<div class="checkboxThree">
				<input type="checkbox" id="jform_scribdenable" name="jform[scribdenable]" value="1" <?php echo ($this->scribdenable == '1') ? 'checked="checked"' : '';?> onchange="javascript:enableFields();"/>
				<label for="jform_scribdenable"></label>
			</div></label>
		</td>
	</tr>
	<tr>
		<td><label id="jform_scribd_api-lbl" for="jform_scribd_api" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_API_KEY');?></label></td>
		<td colspan="3"><input type="text" name="jform[scribd_api]" id="jform_scribd_api" value="<?php echo $this->scribd_api;?>" size="30"></td>
	</tr>
	<tr>
		<td><label id="jform_scribd_secret-lbl" for="jform_scribd_secret" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_API_SECRET');?></label></td>
		<td colspan="3"><input type="text" name="jform[scribd_secret]" id="jform_scribd_secret" value="<?php echo $this->scribd_secret;?>" size="30"></td>
	</tr>
	<tr>
		<td colspan="4"><span class="api-note"><?php echo JText::_('COM_ACCOUNT_LABEL_HELPER_SCRIBD'); ?></span></td>
	</tr>
	<!-- DIFFBOT SETTINGS -->
	<tr>
		<td class="table-title" colspan="3"><label for="jform_diffbotenable"><?php echo JText::_('COM_ACCOUNT_LABEL_DIFFBOT');?></label></td>
		<td class="table-title"><label style="float: right !important;">
		  	<div class="checkboxThree">
				<input type="checkbox" id="jform_diffbotenable" name="jform[diffbotenable]" value="1" <?php echo ($this->diffbotenable == '1') ? 'checked="checked"' : '';?> onchange="javascript:enableFields();"/>
				<label for="jform_diffbotenable"></label>
			</div></label>
		</td>
	</tr>
	<tr>
		<td><label id="jform_diffbot-lbl" for="jform_diffbot" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_TOKEN');?></label></td>
		<td colspan="3"><input type="text" name="jform[diffbot]" id="jform_diffbot" value="<?php echo $this->diffbot;?>" size="30"></td>
	</tr>
	<tr>
		<td colspan="4"><span class="api-note"><?php echo JText::_('COM_ACCOUNT_LABEL_HELPER_DIFFBOT'); ?></span></td>
	</tr>
	<!-- MAIL SETTINGS -->
	<tr>
		<td colspan="4" class="table-title"><label><?php echo JText::_('COM_ACCOUNT_LABEL_MAIL_SETTING');?></label></td>
	</tr>
	<tr>
		<td><label id="jform_mailer-lbl" for="jform_mailer" class="hasTip required" title="" aria-invalid="false"><?php echo JText::_('COM_ACCOUNT_LABEL_MAILER');?><span class="star">&nbsp;*</span></label></td>
		<td colspan="3">
			<select id="jform_mailer" name="jform[mailer]" class="required" aria-required="true" required="required" aria-invalid="false" onchange="javascript:enableFields();">
				<option value="mail" <?php echo ($this->mailer == 'mailer') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_PHP_MAIL');?></option>
				<option value="sendmail" <?php echo ($this->mailer == 'sendmail') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_SENDMAIL');?></option>
				<option value="smtp" <?php echo ($this->mailer == 'smtp') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP');?></option>
			</select>		
		</td>
	</tr>
	<tr>
		<td><label id="jform_mailfrom-lbl" for="jform_mailfrom" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_FROM_EMAIL');?></label></td>
		<td colspan="3"><input type="email" name="jform[mailfrom]" class="validate-email" id="jform_mailfrom" value="<?php echo $this->mailfrom;?>" size="30"></td>
	</tr>	
	<tr>
		<td><label id="jform_fromname-lbl" for="jform_fromname" class="hasTip" title="" aria-invalid="false"><?php echo JText::_('COM_ACCOUNT_LABEL_FROM_NAME');?></label></td>
		<td colspan="3"><input type="text" name="jform[fromname]" id="jform_fromname" value="<?php echo $this->fromname;?>" size="30" class="" aria-invalid="false"></td>
	</tr>
	<tr>
		<td><label id="jform_sendmail-lbl" for="jform_sendmail" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SENDMAIL_PATH');?></label></td>
		<td colspan="3"><input type="text" name="jform[sendmail]" id="jform_sendmail" value="<?php echo $this->sendmail;?>" size="30"></td>
	</tr>
	<tr>
		<td><label id="jform_smtpauth-lbl" for="jform_smtpauth" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_AUTHENTICATION');?></label></td>
		<td colspan="3">
			<label>
		  	<div class="checkboxThree">
				<input type="checkbox" id="jform_smtpauth" name="jform[smtpauth]" value="1" <?php echo ($this->smtpauth == '1') ? 'checked="checked"' : '';?>/>
				<label for="jform_smtpauth"></label>
			</div></label>		
		</td>
	</tr>
	<tr>
		<td><label id="jform_smtpsecure-lbl" for="jform_smtpsecure" class="hasTip" title="" aria-invalid="false"><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_SECURITY');?></label></td>
		<td colspan="3">
			<select id="jform_smtpsecure" name="jform[smtpsecure]" class="" aria-invalid="false">
				<option value="none" <?php echo ($this->smtpsecure == 'none') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_NONE');?></option>
				<option value="ssl" <?php echo ($this->smtpsecure == 'ssl') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_SSL');?></option>
				<option value="tls" <?php echo ($this->smtpsecure == 'tls') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_LABEL_TLS');?></option>
			</select>		
		</td>
	</tr>
	<tr>
		<td><label id="jform_smtpport-lbl" for="jform_smtpport" class="hasTip required" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_PORT');?><span class="star">&nbsp;*</span></label></td>
		<td colspan="3"><input type="text" name="jform[smtpport]" id="jform_smtpport" value="<?php echo $this->smtpport;?>" class="required" size="6" aria-required="true" required="required"></td>
	</tr>
	<tr>
		<td><label id="jform_smtpuser-lbl" for="jform_smtpuser" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_USERNAME');?></label></td>
		<td colspan="3"><input type="text" name="jform[smtpuser]" id="jform_smtpuser" value="<?php echo $this->smtpuser;?>" size="30"></td>
	</tr>
	<tr>
		<td><label id="jform_smtppass-lbl" for="jform_smtppass" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_PASSWORD');?></label></td>
		<td colspan="3"><input type="password" name="jform[smtppass]" id="jform_smtppass" value="<?php echo $this->smtppass;?>" size="30"></td>
	</tr>
	<tr>
		<td><label id="jform_smtphost-lbl" for="jform_smtphost" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_SMTP_HOST');?></label></td>
		<td colspan="3"><input type="text" name="jform[smtphost]" id="jform_smtphost" value="<?php echo $this->smtphost;?>" size="30"></td>
	</tr>
	<!-- WEATHER MODULE SETTINGS -->
	<tr>
		<td class="table-title" colspan="4"><label for="jform_module_weatherenable"><?php echo JText::_('COM_ACCOUNT_WEATHER_TITLE');?></label>
		<label style="float: right !important;">
			<div class="checkboxThree" >
				<input type="checkbox" id="jform_module_weatherenable" name="jform[module_weatherenable]" value="1" <?php echo ($this->module_weatherenable == '1') ? 'checked="checked"' : '';?> onchange="javascript:enableFields();"/>
				<label for="jform_module_weatherenable"></label>
			</div></label>
		</td>
	</tr>
	<tr>
		<td><label id="jform_weather_showcity-lbl" for="jform_weather_showcity" class="tips" title="<?php echo JText::_('COM_ACCOUNT_WEATHER_CITY_DESC');?>"><?php echo JText::_('COM_ACCOUNT_WEATHER_CITY');?></label></td>
		<td>
			<div class="checkboxOne" >
				<input type="checkbox" id="jform_weather_showcity" name="jform[weather_showcity]" value="1" <?php echo ($this->weather_showcity == '1') ? 'checked="checked"' : '';?>/>
				<label for="jform_weather_showcity"></label>
			</div>
		</td>
		<td><label id="jform_weather_condition-lbl" for="jform_weather_condition" class="tips" title="<?php echo JText::_('COM_ACCOUNT_WEATHER_CONDITION_DESC');?>"><?php echo JText::_('COM_ACCOUNT_WEATHER_CONDITION');?></label></td>
		<td>
			<div class="checkboxOne" >
				<input type="checkbox" id="jform_weather_condition" name="jform[weather_condition]" value="1" <?php echo ($this->weather_condition == '1') ? 'checked="checked"' : '';?>/>
				<label for="jform_weather_condition"></label>
			</div>
		</td>
	</tr>
	<tr>
		<td><label id="jform_weather_humidity-lbl" for="jform_weather_humidity" class="tips" title="<?php echo JText::_('COM_ACCOUNT_WEATHER_HUMIDITY_DESC');?>"><?php echo JText::_('COM_ACCOUNT_WEATHER_HUMIDITY');?></label></td>
		<td>
			<div class="checkboxOne" >
				<input type="checkbox" id="jform_weather_humidity" name="jform[weather_humidity]" value="1" <?php echo ($this->weather_humidity == '1') ? 'checked="checked"' : '';?>/>
				<label for="jform_weather_humidity"></label>
			</div>
		</td>
		<td><label id="jform_weather_wind-lbl" for="jform_weather_wind" class="tips" title="<?php echo JText::_('COM_ACCOUNT_WEATHER_WIND_DESC');?>"><?php echo JText::_('COM_ACCOUNT_WEATHER_WIND');?></label></td>
		<td>
			<div class="checkboxOne" >
				<input type="checkbox" id="jform_weather_wind" name="jform[weather_wind]" value="1" <?php echo ($this->weather_wind == '1') ? 'checked="checked"' : '';?>/>
				<label for="jform_weather_wind"></label>
			</div>
		</td>
	</tr>
	<tr>
		<td><label id="jform_weather_forecast-lbl" for="jform_weather_forecast" class="tips" title="<?php echo JText::_('COM_ACCOUNT_WEATHER_FORECAST_DESC');?>" aria-invalid="false"><?php echo JText::_('COM_ACCOUNT_WEATHER_FORECAST');?></label></td>
		<td colspan="3">
			<select id="jform_weather_forecast" name="jform[weather_forecast]" class="" aria-invalid="false">
				<option value="2" <?php echo ($this->weather_forecast == '2') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_WEATHER_1DAY');?></option>
				<option value="3" <?php echo ($this->weather_forecast == '3') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_WEATHER_2DAYS');?></option>
				<option value="4" <?php echo ($this->weather_forecast == '4') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_WEATHER_3DAYS');?></option>
				<option value="5" <?php echo ($this->weather_forecast == '5') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_WEATHER_4DAYS');?></option>
				<option value="6" <?php echo ($this->weather_forecast == '6') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_WEATHER_5DAYS');?></option>
				<option value="7" <?php echo ($this->weather_forecast == '7') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_WEATHER_6DAYS');?></option>
				<option value="8" <?php echo ($this->weather_forecast == '8') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_WEATHER_1WEEK');?></option>
				<option value="disabled" <?php echo ($this->weather_forecast == 'disabled') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_WEATHER_DISABLED');?></option>
			</select>		
		</td>
	</tr>
	<tr>
		<td><label id="jform_weather_layout-lbl" for="jform_weather_layout" class="tips" title="<?php echo JText::_('COM_ACCOUNT_WEATHER_LAYOUT_DESC');?>" aria-invalid="false"><?php echo JText::_('COM_ACCOUNT_WEATHER_LAYOUT');?></label></td>
		<td colspan="3">
			<select id="jform_weather_layout" name="jform[weather_layout]" class="" aria-invalid="false">
				<option value="block" <?php echo ($this->weather_layout == 'block') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_WEATHER_BlOCK');?></option>
				<option value="list" <?php echo ($this->weather_layout == 'list') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_WEATHER_LIST');?></option>
			</select>		
		</td>
	</tr>
	<tr>
		<td><label id="jform_weather_separator-lbl" for="jform_weather_separator" class="tips" title="<?php echo JText::_('COM_ACCOUNT_WEATHER_SEPARATOR_DESC');?>"><?php echo JText::_('COM_ACCOUNT_WEATHER_SEPARATOR');?></label></td>
		<td colspan="3"><input type="text" name="jform[weather_separator]" id="jform_weather_separator" value="<?php echo $this->weather_separator;?>" size="1"></td>
	</tr>
	<tr>
		<td><label id="jform_weather_tempUnit-lbl" for="jform_weather_tempUnit" class="tips" title="<?php echo JText::_('COM_ACCOUNT_WEATHER_UNIT_DESC');?>" aria-invalid="false"><?php echo JText::_('COM_ACCOUNT_WEATHER_UNIT');?></label></td>
		<td colspan="3">
			<select id="jform_weather_tempUnit" name="jform[weather_tempUnit]" class="" aria-invalid="false">
				<option value="c" <?php echo ($this->weather_tempUnit == 'c') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_WEATHER_CELSIUS');?></option>
				<option value="f" <?php echo ($this->weather_tempUnit == 'f') ? 'selected="selected"' : '';?>><?php echo JText::_('COM_ACCOUNT_WEATHER_FAHRENHEIT');?></option>
			</select>		
		</td>
	</tr>	
	<tr>
		<td><label id="jform_weather_useCache-lbl" for="jform_weather_useCache" class="tips" title="<?php echo JText::_('COM_ACCOUNT_WEATHER_USECACHE_DESC');?>"><?php echo JText::_('COM_ACCOUNT_WEATHER_USECACHE');?></label></td>
		<td colspan="3">
			<div class="checkboxOne" >
				<input type="checkbox" id="jform_weather_useCache" name="jform[weather_useCache]" value="1" <?php echo ($this->weather_useCache == '1') ? 'checked="checked"' : '';?> onchange="javascript:enableFields();"//>
				<label for="jform_weather_useCache"></label>
			</div>
		</td>
	</tr>
	<tr>
		<td><label id="jform_weather_cacheTime-lbl" for="jform_weather_cacheTime" class="tips" title="<?php echo JText::_('COM_ACCOUNT_WEATHER_CACHETIME_DESC');?>"><?php echo JText::_('COM_ACCOUNT_WEATHER_CACHETIME');?></label></td>
		<td colspan="3"><input type="text" name="jform[weather_cacheTime]" id="jform_weather_cacheTime" value="<?php echo $this->weather_cacheTime;?>" size="1"></td>
	</tr>
	
	<!-- MODULE SETTINGS -->
	<tr>
		<td colspan="4" class="table-title"><label><?php echo JText::_('COM_ACCOUNT_LABEL_MODULES');?></label></td>
	</tr>
	<tr>
		<td><label id="jform_mailer-lbl" class="hasTip" title=""><?php echo JText::_('COM_ACCOUNT_LABEL_MODULES_TITLE');?></label></td>
		<td colspan="3">
			<table>
				<?php 			
				foreach ($this->Modules as $key => $value) {
					$checked = (($this->{'module_' . $key}) ? 'checked="yes"' : ''); ?>
					<tr>
						<td><?php echo $value; ?></td>
						<td>
							<label style="float: right !important;">
							<div class="checkboxOne" >
								<input type="checkbox" id="jform[module_<?php echo $key; ?>]" name="jform[module_<?php echo $key; ?>]" value="1" <?php echo $checked;?>/>
								<label for="jform[module_<?php echo $key; ?>]"></label>
							</div></label>
						</td>
					</tr>
				<?php } ?>				
			</table>
		</td>
	</tr>
</table>
<div class="submit">
	<input type="hidden" value="advance" name="task">
	<input class="btn btn-info" type="submit" value="<?php echo JText::_('COM_STREAM_LABEL_SAVE');?>" name="submit" style="float: right !important;">
</div>
</form>
<script type="text/javascript">
	function enableFields() {
		var d = document;
		if (d.getElementById('jform_crocodocsenable').checked == true) {
			d.getElementById("jform_crocodocs").readOnly=false;
		} else {
			d.getElementById("jform_crocodocs").readOnly=true;
		}

		if (d.getElementById('jform_scribdenable').checked == true) {
			d.getElementById("jform_scribd_api").readOnly=false;
			d.getElementById("jform_scribd_secret").readOnly=false;
		} else {
			d.getElementById("jform_scribd_api").readOnly=true;
			d.getElementById("jform_scribd_secret").readOnly=true;
		}

		if (d.getElementById('jform_diffbotenable').checked == true) {
			d.getElementById("jform_diffbot").readOnly=false;
		} else {
			d.getElementById("jform_diffbot").readOnly=true;
		}
		
		var e = d.getElementById("jform_mailer");
		var strMailer = e.options[e.selectedIndex].value;
		switch(strMailer) {
			default:
			case 'mailer':
				d.getElementById("jform_sendmail").readOnly=true;
				d.getElementById("jform_smtpauth").disabled=true;
				d.getElementById("jform_smtpsecure").disabled=true;
				d.getElementById("jform_smtpport").readOnly=true;
				d.getElementById("jform_smtpuser").readOnly=true;
				d.getElementById("jform_smtppass").readOnly=true;
				d.getElementById("jform_smtphost").readOnly=true;
				break;
			case 'sendmail':
				d.getElementById("jform_sendmail").readOnly=false;
				d.getElementById("jform_smtpauth").disabled=true;
				d.getElementById("jform_smtpsecure").disabled=true;
				d.getElementById("jform_smtpport").readOnly=true;
				d.getElementById("jform_smtpuser").readOnly=true;
				d.getElementById("jform_smtppass").readOnly=true;
				d.getElementById("jform_smtphost").readOnly=true;				
				break;
			case 'smtp':
				d.getElementById("jform_sendmail").readOnly=true;
				d.getElementById("jform_smtpauth").disabled=false;
				d.getElementById("jform_smtpsecure").disabled=false;
				d.getElementById("jform_smtpport").readOnly=false;
				d.getElementById("jform_smtpuser").readOnly=false;
				d.getElementById("jform_smtppass").readOnly=false;
				d.getElementById("jform_smtphost").readOnly=false;			
				break;
		}
		
		if (d.getElementById('jform_module_weatherenable').checked == true) {
			d.getElementById("jform_weather_showcity").disabled=false;
			d.getElementById("jform_weather_condition").disabled=false;
			d.getElementById("jform_weather_humidity").disabled=false;
			d.getElementById("jform_weather_wind").disabled=false;
			d.getElementById("jform_weather_forecast").disabled=false;
			d.getElementById("jform_weather_layout").disabled=false;
			d.getElementById("jform_weather_separator").readOnly=false;
			d.getElementById("jform_weather_tempUnit").disabled=false;
			d.getElementById("jform_weather_useCache").disabled=false;
			if (d.getElementById('jform_weather_useCache').checked == true) {
				d.getElementById("jform_weather_cacheTime").readOnly=false;
			} else {
				d.getElementById("jform_weather_cacheTime").readOnly=true;
			}
		} else {
			d.getElementById("jform_weather_showcity").disabled=true;
			d.getElementById("jform_weather_condition").disabled=true;
			d.getElementById("jform_weather_humidity").disabled=true;
			d.getElementById("jform_weather_wind").disabled=true;
			d.getElementById("jform_weather_forecast").disabled=true;
			d.getElementById("jform_weather_layout").disabled=true;
			d.getElementById("jform_weather_separator").readOnly=true;
			d.getElementById("jform_weather_tempUnit").disabled=true;
			d.getElementById("jform_weather_useCache").disabled=true;
			d.getElementById("jform_weather_cacheTime").readOnly=true;
		}
		return false;
	}
	enableFields();
</script>