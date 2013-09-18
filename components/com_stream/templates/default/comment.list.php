<?php

/**
 * @package		Offiria
 * @subpackage	Core 
 * @copyright (C) 2011 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license		GNU/GPL, see LICENSE.php
 */
defined('_JEXEC') or die('Restricted access');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>


<?php foreach($comments as $comment ) { 	
	$tmpl = new StreamTemplate();
	echo $tmpl->set('comment', $comment)->fetch('comment.item');
?>

<?php } ?>