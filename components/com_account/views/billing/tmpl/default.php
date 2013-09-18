<?php
/**
 * @version     1.0.0
 * @package     com_account
 * @copyright   Copyright (C) 2011 - 2013 Slashes & Dots Sdn Bhd. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 * @author      Offiria Team
 */

// no direct access
defined('_JEXEC') or die;

?>
<div class="account-navbar">
<?php 
echo $this->showNavBar(); 
?>
</div>
<h3 class="section-title"><?php echo JText::_('COM_ACCOUNT_LABEL_PACKAGE');?></h3>
<table class="table table-bordered table-striped table-condensed table-novborder">
	<thead>
		<tr>
			<th><?php echo JText::_('COM_ACCOUNT_LABEL_PACKAGE');?></th>
			<th><?php echo JText::_('COM_ACCOUNT_LABEL_USER_LIMIT');?></th>
			<th><?php echo JText::_('COM_ACCOUNT_LABEL_STORAGE_LIMIT');?> (Gb)</th>
			<th><?php echo JText::_('COM_ACCOUNT_LABEL_PRICING');?> (USD)</th>
			<th><?php echo JText::_('COM_ACCOUNT_LABEL_SELECT');?></th>
		</tr>
	</thead>
	<tbody>
		<?php 
		$showUpgrade = true;
		if ($this->availablePlans) 
		{
			for ($i = 0; $i < count($this->availablePlans); $i++)
			{
				if (($this->plan == $this->availablePlans[$i][0] && $showUpgrade))
				{
					$showUpgrade = false;
				}
				else
				{
					$showUpgrade = true;
				}
		?>
		<tr>
			<td><?php echo end($this->availablePlans[$i]);?></td>
			<td><?php echo $this->availablePlans[$i][1];?></td>
			<td><?php echo $this->availablePlans[$i][2];?></td>
			<td><?php echo $this->availablePlans[$i][3];?></td>
			<td><?php if ($showUpgrade) : ?><input type="button" class="btn" value="<?php echo JText::_('COM_ACCOUNT_LABEL_UPGRADE');?>" /><?php endif;?></td>
		</tr>
		<?php
			} // end for loop
		} // end if
		?>
	</tbody>
</table>

<ul>
	<li>Current Package</li>
	<li>Package: <?php echo $this->jxConfig->get('plan');?></li>
	<li>Activation Date: <?php echo $this->jxConfig->getActivationDate();?></li>
	<li>Expiry Date: <?php echo $this->jxConfig->getExpiryDate();?></li>
</ul>


<div class="pagination">
<?php echo $this->pagination->getPagesLinks(); ?>
</div>