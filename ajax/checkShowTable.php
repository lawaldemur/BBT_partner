<?php
if (strpos($_POST['period'], 'BETWEEN') !== false) {
	$date1 = new DateTime(substr($period, 21, 10));
	$date2 = new DateTime(substr($period, 36, 10));

	$diff = $date1->diff($date2);
	$diff = intval($diff->format('%y')) * 12 + intval($diff->format('%m')) >= 2;
}
echo $_POST['period'] == 'QUARTER(`date`) = QUARTER(CURDATE())' || $_POST['period'] == 'YEAR(`date`) = YEAR(CURDATE())' ||
		(strpos($_POST['period'], 'BETWEEN') !== false && $diff);
