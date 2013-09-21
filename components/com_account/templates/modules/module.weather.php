<div class="moduletable" id="module_weather_container">
	<h3><?php echo JText::_('COM_ACCOUNT_WEATHER_TITLE');?></h3>
	<div id="weather_sp1_id" class="weather_sp1">
		<div class="weather_sp1_c">
			<div class="weather_sp1_cleft">
				<img class="spw_icon_big" src="<?php echo  $helper->icon( $weather_data['item']['condition']['code'] ) ?>" title="<?php echo $helper->txt2lng($weather_data['item']['condition']['text']); ?>" alt="<?php echo $helper->txt2lng($weather_data['item']['condition']['text']); ?>" />
				<br style="clear" />
				<p class="spw_current_temp">
				<?php echo $helper->convertUnit( $weather_data['item']['condition']['temp'], $params->get('weather_tempUnit')); ?>
				</p>
			</div>

			<div class="weather_sp1_cright">
				<?php if($params->get('weather_showcity')==1) { ?><p class="weather_sp1_city"><?php echo $location ?></p> <?php } ?>
				<?php if($params->get('weather_condition')==1) { ?>
					<div class="spw_row"><?php echo $helper->txt2lng($weather_data['item']['condition']['text']); ?></div>
				<?php } ?>
				<?php if($params->get('weather_humidity')==1) { ?>
					<div class="spw_row"><?php echo JText::_('SP_WEATHER_HUMIDITY');  ?>: <?php echo $helper->Numeric2Lang($weather_data['atmosphere']['humidity']); ?>%</div>
				<?php } ?>

				<?php if($params->get('weather_wind')==1) { ?>
					<div class="spw_row"><?php echo JText::_('SP_WEATHER_WIND');  ?>: <?php 

					$compass = array('N', 'NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW', 'N');

					$weather_data['wind']['direction'] = $compass[round($weather_data['wind']['direction'] / 22.5)];

					echo JText::_('SP_WEATHER_' . $weather_data['wind']['direction']) . JText::_('SP_WEATHER_AT') . $helper->Numeric2Lang($weather_data['wind']['speed']) . ' ' . JText::_('SP_WEATHER_' . strtoupper($weather_data['units']['speed'])); ?></div>
				<?php } ?>
			</div>
			<div class="clear"></div>		
		</div>

		<div class="clear"></div>
		<?php if ($params->get('weather_forecast')!='disabled') { ?>
			<div class="weather_sp1_forecasts">
			<?php

			$fcast = (int) $params->get('weather_forecast');
			$j = 1;
			unset($forecast[0]);

			foreach($forecast as $i=>$value ) { 
				if($fcast<$j) break;

				if ($params->get('weather_layout')=='list') { ?>
					<div class="list_<?php echo ($i%2 ? 'even' : 'odd') ?>">
						<span class="weather_sp1_list_day"><?php echo $helper->txt2lng($value['day']); ?></span>
						<span class="weather_sp1_list_temp"><?php echo $helper->convertUnit( $value['low'], $weather_data['units']['temperature']) . '&nbsp;' . $params->get('weather_separator') . '&nbsp;' . $helper->convertUnit( $value['high'], $weather_data['units']['temperature']); ?></span>
						<span class="weather_sp1_list_icon"><img class="spw_icon" src="<?php echo $helper->icon( $value['code'] ); ?>" align="right" title="<?php echo $helper->txt2lng( $value['text'] ); ?>" alt="<?php echo $helper->txt2lng($value['text']); ?>" /></span>
						<div class="clear"></div>
					</div>				
				<?php } else { ?> 
					<div class="block_<?php echo ($i%2 ? 'even' : 'odd') ?>" style="float:left;width:<?php echo round(100/$fcast) ?>%">
						<span class="weather_sp1_day"><?php echo $helper->txt2lng($value['day']); ?></span>
						<br style="clear:both" />
						<span class="weather_sp1_icon"><img  class="spw_icon" src="<?php echo $helper->icon( $value['code'] ); ?>" title="<?php echo $helper->txt2lng($value['text']); ?>" alt="<?php echo $helper->txt2lng($value['text']); ?>" />
						</span><br style="clear:both" />
						<span class="weather_sp1_temp"><?php echo $helper->convertUnit( $value['low'], $weather_data['units']['temperature']) . '&nbsp;' . $params->get('weather_separator') . '&nbsp;' . $helper->convertUnit( $value['high'], $weather_data['units']['temperature']); ?></span>
						<div class="clear"></div>
					</div>
				<?php }
				$j++;
			} ?>
			</div>
		<?php } ?>
	<div class="clear"></div>
	</div>
</div>