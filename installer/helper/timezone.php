<?php
function getTimezoneOption() {
	$list = DateTimeZone::listAbbreviations();
    $idents = DateTimeZone::listIdentifiers();

    $data = $offset = $added = array();
    foreach ($list as $abbr => $info) {
        foreach ($info as $zone) {
            if ( ! empty($zone['timezone_id'])
				 AND
				 ! in_array($zone['timezone_id'], $added)
				 AND 
				 in_array($zone['timezone_id'], $idents)) {
                $z = new DateTimeZone($zone['timezone_id']);
                $c = new DateTime(null, $z);
                $zone['time'] = $c->format('H:i');
                $data[] = $zone;
                $offset[] = $z->getOffset($c);
                $added[] = $zone['timezone_id'];
            }
        }
    }

	array_multisort($offset, SORT_ASC, $data);
	$options = array();
	foreach ($data as $key => $row) {
		$options[$row['timezone_id']] = formatOffset($row['offset']) 
			. ' ' . $row['timezone_id'];
	}
	return $options;
}

// now you can use $options;
function formatOffset($offset) {
	$hours = $offset / 3600;
	$remainder = $offset % 3600;
	$sign = $hours > 0 ? '+' : '-';
	$hour = (int) abs($hours);
	$minutes = (int) abs($remainder / 60);

	if ($hour == 0 AND $minutes == 0) {
		$sign = ' ';
	}
	return 'GMT' . $sign . str_pad($hour, 2, '0', STR_PAD_LEFT) 
		.':'. str_pad($minutes, 2, '0');
}
?>
