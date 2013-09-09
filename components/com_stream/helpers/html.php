<?php

class StreamHtmlHelper { 
	static function getTimeOptions($date = '0000-00-00 00:00:00'){		
		// Get time (not including the final secons)
		$time = substr($date, 11, 5);
		$html = '';

		$config = new JXConfig();
		$tz = ($config->getTimezone() != '') ? $config->getTimezone() : JText::_('JXLIB_DEFAULT_TIMEZONE');
		$tz = new DateTimeZone($tz);

		/* create a date for every hour */
        for($i = 0; $i < 24; $i++){
          	$newDate = new JDate( mktime($i, 0, 0, 7, 1, 2000), $tz);
			$newDate->format(JText::_('H:i'), false);

			/* if the selected is in hours */
			$selected = ($time == $newDate->format(JText::_('H:i'), true)) ? 'selected="selected"' : '';
			/* add the options */
			$html .= '<option '. $selected .'value="'.$newDate->format(JText::_('H:i'), true).'" >'.$newDate->format(JText::_('JXLIB_TIME_SHORT_FORMAT'), true).'</option>';
			/* and modify for 30 mins between these hours */
			$newDate->modify('+30 minute');
			/* check again to see if its in 30 mins interval */
			$selected = ($time == $newDate->format(JText::_('H:i'), true)) ? 'selected="selected"' : '';

			/* add the options */
			$html .= '<option '. $selected .'value="'.$newDate->format(JText::_('H:i'), true).'" >'.$newDate->format(JText::_('JXLIB_TIME_SHORT_FORMAT'), true).'</option>';
		}
		return $html;
	}
	
	static function dropDown($options, $name, $value)
	{
		?>
		<ul class="nav nav-pills" style="margin-bottom: 0px;">
			<li class="dropdown">
				<a class="dropdown-toggle" data-toggle="dropdown" href="#<?php echo $name; ?>">     Milestone: none   <b class="caret"></b>
				</a>
				
				<ul class="dropdown-menu">
					<li><a href="#selectOption" data-target-input="<?php echo $name; ?>" value="0">Milestone: none</a></li>
					<?php
					foreach( $options as $option ){ ?> 
					<li><a href="#selectOption" data-target-input="<?php echo $name; ?>" value="<?php echo $option->key; ?>"><?php echo $option->label; ?></a></li>
					<?php } ?>
				</ul>
			</li>
		</ul>
		<?php
	}
}