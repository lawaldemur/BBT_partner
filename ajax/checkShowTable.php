<?php
$period = $_POST['period'];

if (strpos($period, 'BETWEEN') !== false) {
	$date1 = new DateTime(substr($period, 21, 10));
	$date2 = new DateTime(substr($period, 36, 10));

	$diff = $date1->diff($date2);
	$diff = intval($diff->format('%y')) * 12 + intval($diff->format('%m')) >= 2;
}
echo $period == 'QUARTER(`date`) = QUARTER(CURDATE())' || $period == 'YEAR(`date`) = YEAR(CURDATE())' ||
		(strpos($period, 'BETWEEN') !== false && $diff);
