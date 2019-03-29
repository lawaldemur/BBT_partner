<?php

if (strpos($_POST['period'], 'BETWEEN') !== false) {
	$date2 = new DateTime(explode('\'', $_POST['period'])[1]);
	$date1 = new DateTime(explode('\'', $_POST['period'])[3]);

	$diff = $date1->diff($date2);
	$diff = intval($diff->format('%y')) * 12 + intval($diff->format('%m')) >= 2;
}
echo $_POST['period'] == 'QUARTER(`date`) = QUARTER(CURDATE())' || $_POST['period'] == 'YEAR(`date`) = YEAR(CURDATE())' ||
		(strpos($_POST['period'], 'BETWEEN') !== false && $diff);
