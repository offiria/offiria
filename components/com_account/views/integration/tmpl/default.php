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
<?php echo $this->showNavBar(); ?>
</div>


<?php 
	for ($x = 0; $x < count($this->row); $x++)
	{
		echo $this->row[$x]->getHTML();
	}
?>