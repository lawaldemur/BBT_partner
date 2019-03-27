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

if ($_POST['format'] != 'all')
	$where = 'WHERE `format` = \''.$_POST['format']."' AND to_command_id = {$_POST['id']} AND ".$_POST['period'];
else
	$where = "WHERE to_command_id = {$_POST['id']} AND ".$_POST['period'];

$result_on_d = $dbc->query("SELECT SUM(to_command) FROM sold WHERE $date");
foreach ($result_on_d as $money)
	echo round($money['SUM(to_command)'], 2);
echo "|";

// graph info
$period = explode('\'', $_POST['period']);
if ($_POST['graph'] == '0') {
	$result = $dbc->query("SELECT date, COUNT(id), SUM(to_command) FROM sold $where GROUP BY date");
	$array = [];

	$start = $period[1];
	while ($start <= $period[3]) {
		$array[$start] = array(0);
		$start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
	}

	foreach ($result as $item)
		$array[$item['date']][0] = $item['SUM(to_command)'];
} elseif ($_POST['graph'] == '1') {
	$result = $dbc->query("SELECT WEEK(`date`,1) AS WEEK_NUM, DATE_SUB(`date`, INTERVAL WEEKDAY(`date`) DAY) 
		AS WEEK_MON, DATE_SUB(`date`, INTERVAL (WEEKDAY(`date`)-6) DAY) AS WEEK_SUN, COUNT(id), SUM(to_command) 
		FROM `sold` $where GROUP BY WEEK_MON, WEEK_SUN, WEEK_NUM");
	$array = [];

	$endDate = strtotime($period[3]);
	for($i = strtotime('Monday', strtotime($period[1])); $i <= $endDate; $i = strtotime('+1 week', $i))
		$array[date('Y-m-d', $i)] = array(0);

	foreach ($result as $item)
		$array[$item['WEEK_MON']][0] = $item['SUM(to_command)'];
} elseif ($_POST['graph'] == '2') {
	$result = $dbc->query("SELECT DATE_FORMAT(DATE_SUB(date, INTERVAL 0 MONTH), '%Y-%m-01') 
		AS FirstDayOfMonth, 
		LAST_DAY(DATE_SUB(date, INTERVAL 0 MONTH)) 
		AS LastDayOfMonth, COUNT(id), SUM(to_command)
		FROM sold $where GROUP BY FirstDayOfMonth,LastDayOfMonth");
	$array = [];

	$endDate = strtotime($period[3]);
	for($i = strtotime(date('Y-m-01', strtotime($period[1]))); $i <= $endDate; $i = strtotime('+1 month', $i)) {
		$array[date('Y-m-d', $i)] = array(0);
	}

	foreach ($result as $item)
		$array[$item['FirstDayOfMonth']][0] = $item['SUM(to_command)'];
}

ksort($array);
$list = [];
foreach ($array as $key => $value) {
	$list[] = array('date' => $key,
					'value' => $value[0]);
}

echo json_encode($list);



