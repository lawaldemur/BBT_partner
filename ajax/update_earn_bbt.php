<?php
include '../db.php';
include 'makePeriod.php';
$_POST['period'] = makePeriod($_POST['period']);

if(isset($_POST['period'])){
	$date = $_POST['period'];
	if (isset($_POST['format']) && $_POST['format'] != 'all'){
		//echo $_POST['format'];
		$date .= ' AND `format` = \'' . $_POST['format'] . "'";
	}
}
else
	$date = "YEAR(`date`) = YEAR(CURDATE())";
if ($_POST['format'] != 'all') {
	$where = 'WHERE `format` = \'' . $_POST['format'] . "'";
} else 
	$where = '';


$result_on_d = $dbc->query("SELECT SUM(to_bbt) FROM sold WHERE to_partner_id <> 0 AND $date");
foreach ($result_on_d as $money)
	echo round($money['SUM(to_bbt)'], 2).'|';

$result_on_d = $dbc->query("SELECT SUM(to_bbt) FROM sold WHERE to_partner_id = 0 AND $date");
foreach ($result_on_d as $money2)
	echo round($money2['SUM(to_bbt)'], 2).'|';

echo round($money['SUM(to_bbt)'] + $money2['SUM(to_bbt)'], 2).'|';


// graph info
$period = explode('\'', $_POST['period']);
$date = 'WHERE '.$date;
if ($_POST['graph'] == '0') {
	$result = $dbc->query("SELECT date, COUNT(id), SUM(to_bbt) FROM sold $date GROUP BY date");
	$result1 = $dbc->query("SELECT date, COUNT(id), SUM(to_bbt) FROM sold $date AND `to_partner_id` <> 0 GROUP BY date");
	$result2 = $dbc->query("SELECT date, COUNT(id), SUM(to_bbt) FROM sold $date AND `to_partner_id` = 0 GROUP BY date");
	$array = [];

	$start = $period[1];
	while ($start <= $period[3]) {
		$array[$start] = array(0, 0, 0);
		$start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
	}

	foreach ($result as $item)
		$array[$item['date']][0] = $item['SUM(to_bbt)'];
	foreach ($result1 as $item)
		$array[$item['date']][1] = $item['SUM(to_bbt)'];
	foreach ($result2 as $item)
		$array[$item['date']][2] = $item['SUM(to_bbt)'];
} elseif ($_POST['graph'] == '1') {
	$result = $dbc->query("SELECT WEEK(`date`,1) AS WEEK_NUM, DATE_SUB(`date`, INTERVAL WEEKDAY(`date`) DAY) 
		AS WEEK_MON, DATE_SUB(`date`, INTERVAL (WEEKDAY(`date`)-6) DAY) AS WEEK_SUN, COUNT(id), SUM(to_bbt) 
		FROM `sold` $date GROUP BY WEEK_MON, WEEK_SUN, WEEK_NUM");
	$result1 = $dbc->query("SELECT WEEK(`date`,1) AS WEEK_NUM, DATE_SUB(`date`, INTERVAL WEEKDAY(`date`) DAY) 
		AS WEEK_MON, DATE_SUB(`date`, INTERVAL (WEEKDAY(`date`)-6) DAY) AS WEEK_SUN, COUNT(id), SUM(to_bbt) 
		FROM `sold` $date AND `to_partner_id` <> 0 GROUP BY WEEK_MON, WEEK_SUN, WEEK_NUM");
	$result2 = $dbc->query("SELECT WEEK(`date`,1) AS WEEK_NUM, DATE_SUB(`date`, INTERVAL WEEKDAY(`date`) DAY) 
		AS WEEK_MON, DATE_SUB(`date`, INTERVAL (WEEKDAY(`date`)-6) DAY) AS WEEK_SUN, COUNT(id), SUM(to_bbt) 
		FROM `sold` $date AND `to_partner_id` = 0 GROUP BY WEEK_MON, WEEK_SUN, WEEK_NUM");
	$array = [];

	$endDate = strtotime($period[3]);
	for($i = strtotime('Monday', strtotime($period[1])); $i <= $endDate; $i = strtotime('+1 week', $i))
		$array[date('Y-m-d', $i)] = array(0, 0, 0);

	foreach ($result as $item)
		$array[$item['WEEK_MON']][0] = $item['SUM(to_bbt)'];
	foreach ($result1 as $item)
		$array[$item['WEEK_MON']][1] = $item['SUM(to_bbt)'];
	foreach ($result2 as $item)
		$array[$item['WEEK_MON']][2] = $item['SUM(to_bbt)'];
} elseif ($_POST['graph'] == '2') {
	$result = $dbc->query("SELECT DATE_FORMAT(DATE_SUB(date, INTERVAL 0 MONTH), '%Y-%m-01') 
		AS FirstDayOfMonth, 
		LAST_DAY(DATE_SUB(date, INTERVAL 0 MONTH)) 
		AS LastDayOfMonth, COUNT(id), SUM(to_bbt)
		FROM sold $date GROUP BY FirstDayOfMonth,LastDayOfMonth");
	$result1 = $dbc->query("SELECT DATE_FORMAT(DATE_SUB(date, INTERVAL 0 MONTH), '%Y-%m-01') 
		AS FirstDayOfMonth, 
		LAST_DAY(DATE_SUB(date, INTERVAL 0 MONTH)) 
		AS LastDayOfMonth, COUNT(id), SUM(to_bbt)
		FROM sold $date AND `to_partner_id` <> 0 GROUP BY FirstDayOfMonth,LastDayOfMonth");
	$result2 = $dbc->query("SELECT DATE_FORMAT(DATE_SUB(date, INTERVAL 0 MONTH), '%Y-%m-01') 
		AS FirstDayOfMonth, 
		LAST_DAY(DATE_SUB(date, INTERVAL 0 MONTH)) 
		AS LastDayOfMonth, COUNT(id), SUM(to_bbt)
		FROM sold $date AND `to_partner_id` = 0 GROUP BY FirstDayOfMonth,LastDayOfMonth");
	$array = [];

	// $start = $period[1];
	// while ($start <= $period[3]) {
	// 	$array[$start] = array(0, 0, 0);
	// 	$start = date('Y-m-d', strtotime('+1 month', strtotime($start)));
	// }
	$endDate = strtotime($period[3]);
	for($i = strtotime(date('Y-m-01', strtotime($period[1]))); $i <= $endDate; $i = strtotime('+1 month', $i)) {
		$array[date('Y-m-d', $i)] = array(0, 0, 0);
	}

	foreach ($result as $item)
		$array[$item['FirstDayOfMonth']][0] = $item['SUM(to_bbt)'];
	foreach ($result1 as $item)
		$array[$item['FirstDayOfMonth']][1] = $item['SUM(to_bbt)'];
	foreach ($result2 as $item)
		$array[$item['FirstDayOfMonth']][2] = $item['SUM(to_bbt)'];
}

ksort($array);
$list = [];
foreach ($array as $key => $value) {
	$list[] = array('date' => $key,
					'value' => $value[0],
					'value2' => $value[1],
					'value3' => $value[2]);
}

echo json_encode($list);



