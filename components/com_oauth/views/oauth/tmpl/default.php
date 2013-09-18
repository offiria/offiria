<?php
/**
 * @version     1.0.0
 * @package     com_oauth
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// no direct access
defined('_JEXEC') or die;
?>
<?php if (JRequest::getVar('appId')): ?>
<?php if (!JRequest::getVar('approved')): ?>
<h1><?php echo JText::_('COM_OAUTH_PROMPT_APPROVAL'); ?></h1>	
<p>
<?php echo JText::_('COM_OAUTH_PROMPT_REASON'); ?>
</p>
<form method="post" action="<?php JRoute::_('?option=com_oauth&view=oauth&task=authenticate')?>">
<input type="submit" name="app_true" value="<?php echo JText::_('COM_OAUTH_FORM_APPROVED'); ?>" />
<input type="submit" name="app_false" value="<?php echo JText::_('COM_OAUTH_FORM_NOT_APPROVED'); ?>" />
</form>
<?php else: ?>
<h1><?php echo JText::_('COM_OAUTH_APPROVAL_COMPLETE'); ?></h1>	
<p>
<?php echo JText::_('COM_OAUTH_TOKEN_INSTRUCTION'); ?>
<h2><?php echo JRequest::getVar('token'); ?></h2>
<?php echo JText::_('COM_OAUTH_APPROVAL_NOTIFICATION'); ?>
</p>
</form>
<?php endif; // end view where device need approval or approved ?>
<?php endif; // end view where appId parameter is provided ?>
