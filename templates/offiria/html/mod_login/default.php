<?php
/**
 * @version		$Id: default.php 21322 2011-05-11 01:10:29Z dextercowley $
 * @package		Joomla.Site
 * @subpackage	mod_login
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');

$return = base64_encode('index.php?option=com_stream&view=company');
?>
<?php if ($type == 'logout') : ?>
<form action="<?php echo JURI::root(); ?>" method="post" id="login-form">
<?php if ($params->get('greeting')) : ?>
	<div class="login-greeting">
	<?php if($params->get('name') == 0) : {
		echo JText::sprintf('MOD_LOGIN_HINAME', $user->get('name'));
	} else : {
		echo JText::sprintf('MOD_LOGIN_HINAME', $user->get('username'));
	} endif; ?>
	</div>
<?php endif; ?>
	<div class="logout-button">
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_('JLOGOUT'); ?>" />
		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.logout" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>

<?php else : ?>

<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" id="login-form" >
	<div class="login-top">	
		<label for="modlgn-username" title="Username"><?php echo JText::_('MOD_LOGIN_VALUE_USERNAME') ?></label>
		<input id="modlgn-username" type="text" name="username" class="offiria_inputbox" title="Username" />
		
		<label for="modlgn-password" title="Password"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
		<input id="modlgn-passwd" type="password" name="password" class="offiria_inputbox" title="Password" />

		<?php if (JPluginHelper::isEnabled('system', 'remember')) : ?>
			<label for="modlgn-remember" title="Remember Me"><input id="modlgn-remember" type="checkbox" name="remember" class="inputbox" value="yes"/><?php echo JText::_('MOD_LOGIN_REMEMBER_ME') ?></label>
		<?php endif; ?>

		<input type="submit" name="Submit" class="offiria_inputsubmit" value="<?php echo JText::_('JLOGIN') ?>" />

		<input type="hidden" name="option" value="com_users" />

		<input type="hidden" name="task" value="user.login" />

		<input type="hidden" name="return" value="<?php echo $return; ?>" />

		<?php echo JHtml::_('form.token'); ?>
	</div>

	<div class="login">
		<div>
			<a class="username" href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
				<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_USERNAME'); ?>
			</a>

			<a class="password" href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
				<?php echo JText::_('MOD_LOGIN_FORGOT_YOUR_PASSWORD'); ?>
			</a>
		</div>
		<?php
		jimport('joomla.utitlies.xconfig');
		$jxConfig		= new JXConfig();
		$usersConfig	= JComponentHelper::getParams('com_users');
		if ($jxConfig->allowUsersRegister() && $usersConfig->get('allowUserRegistration')) : ?>
			<div class="new-account">
				<a href="<?php echo JRoute::_('index.php?option=com_register&view=signup'); ?>">
					<?php echo JText::_('MOD_LOGIN_REGISTER'); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>

	<?php
	jimport('joomla.utilities.integration.activedirectory');
	
	$activeDirectory = new JActiveDirectory();
	if ($activeDirectory->isEnabled()):
	?>
	<div>
		<input type="button" id="active_directory_connect" onclick="window.open('<?php echo JRoute::_('index.php?option=com_register&view=connect&type=ad');?>', 'Active Directory Connect', 'width=500,height=500,toolbar=0,menubar=0,resizable=0');" value="AD Connect"/>
	</div>
	<?php endif; ?>
	
	<?php if ($params->get('posttext')): ?>
		<div class="posttext">
		<p><?php echo $params->get('posttext'); ?></p>
		</div>
	<?php endif; ?>
	
</form>
<?php endif; ?>
