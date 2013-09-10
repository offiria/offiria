<?php
/**
 * @package	JomSocial
 * @subpackage 	Template 
 * @copyright	(C) 2008 by Slashes & Dots Sdn Bhd - All rights reserved!
 * @license	GNU/GPL, see LICENSE.php
 * 
 */
defined('_JEXEC') or die();
$mobile = StreamMobile::isMobile();
?>

<?php if (!$mobile): ?>
<div class="map-container">
	<div class="map" style="display: block; overflow: hidden;">
		<div class="map-fade" style="width:<?php echo $width; ?>px;height:<?php echo $height;?>px">
			<div class="map-heatzone" style="top: <?php echo (($height - 40)/2)-20 ?>px; left: <?php echo (($width - 30)/2) ?>px;">&nbsp;</div>
			<div class="map-filler"></div>
			<img src="https://maps.google.com/maps/api/staticmap?center=<?php echo urlencode($address); ?>&amp;zoom=14&amp;size=<?php echo $width; ?>x<?php echo $height; ?>&amp;sensor=false&amp;markers=color:red|<?php echo urlencode($address); ?>">
			<img src="https://maps.google.com/maps/api/staticmap?center=<?php echo urlencode($address); ?>&amp;zoom=8&amp;size=<?php echo $width; ?>x<?php echo $height; ?>&amp;sensor=false&amp;markers=color:red|<?php echo urlencode($address); ?>" style="display: block;">
		</div>
	</div>
</div>
<?php else: ?>
<div class="map-container">
	<a href="https://maps.google.com/maps?f=q&amp;source=embed&amp;hl=en&amp;geocode=amp;q=<?php echo $address; ?>" target="_blank"><img src="https://maps.google.com/maps/api/staticmap?center=<?php echo urlencode($address); ?>&amp;zoom=14&amp;size=480x200&amp;sensor=false&amp;markers=color:red|<?php echo urlencode($address); ?>"></a>
</div>
<?php endif; ?>

<div class="map-details">
	<small class="map-loc"><span><?php echo $address; ?></span></small>
	<small class="map-bigger"><a href="https://www.google.com/maps/preview#!q=<?php echo $address; ?>" target="_blank"><?php echo JText::_('View larger map');?></a></small>
	<div class="clear"></div>
</div>
