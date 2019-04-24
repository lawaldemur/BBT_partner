<?php
require '../db.php';
require '../php/access.php';
require 'makePeriod.php';
$_POST['period'] = makePeriod($_POST['period']);

if (!access(intval($_POST['id']), $dbc))
	exit('отказано в доступе');

$id = $_POST['id'];
$date = $_POST['period'];
if ($_POST['format'] != 'all')
	$date .= ' AND `format` = \'' . $_POST['format'] . "'";

$result_on_d = $dbc->query("SELECT SUM(to_{$_POST['position']}) FROM `sold` WHERE `to_{$_POST['position']}_id` = $id AND $date");
if ($result_on_d)
foreach ($result_on_d as $money)
	echo round($money["SUM(to_{$_POST['position']})"], 2);
else
	echo 0;

echo "|";


// graph info
if ($_POST['format'] != 'all') {
	$where = 'WHERE `format` = \'' . $_POST['format'] . "' AND `to_{$_POST['position']}_id` = $id AND $date";
} else 
	$where = "WHERE `to_{$_POST['position']}_id` = $id AND $date";

$period = explode('\'', $_POST['period']);
if ($_POST['graph'] == '0') {
	$result = $dbc->query("SELECT date, COUNT(id), SUM(to_{$_POST['position']}) FROM sold $where GROUP BY date");
	$array = [];

	$start = $period[1];
	while ($start <= $period[3]) {
		$array[$start] = array(0);
		$start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
	}

	foreach ($result as $item)
		$array[$item['date']][0] = $item["SUM(to_{$_POST['position']})"];
} elseif ($_POST['graph'] == '1') {
	$result = $dbc->query("SELECT WEEK(`date`,1) AS WEEK_NUM, DATE_SUB(`date`, INTERVAL WEEKDAY(`date`) DAY) 
		AS WEEK_MON, DATE_SUB(`date`, INTERVAL (WEEKDAY(`date`)-6) DAY) AS WEEK_SUN, COUNT(id), SUM(to_{$_POST['position']}) 
		FROM `sold` $where GROUP BY WEEK_MON, WEEK_SUN, WEEK_NUM");
	$array = [];

	$endDate = strtotime($period[3]);
	for($i = strtotime('Monday', strtotime($period[1])); $i <= $endDate; $i = strtotime('+1 week', $i))
		$array[date('Y-m-d', $i)] = array(0);

	foreach ($result as $item)
		$array[$item['WEEK_MON']][0] = $item["SUM(to_{$_POST['position']})"];
} elseif ($_POST['graph'] == '2') {
	$result = $dbc->query("SELECT DATE_FORMAT(DATE_SUB(date, INTERVAL 0 MONTH), '%Y-%m-01') 
		AS FirstDayOfMonth, 
		LAST_DAY(DATE_SUB(date, INTERVAL 0 MONTH)) 
		AS LastDayOfMonth, COUNT(id), SUM(to_{$_POST['position']})
		FROM sold $where GROUP BY FirstDayOfMonth,LastDayOfMonth");
	$array = [];

	$endDate = strtotime($period[3]);
	for($i = strtotime(date('Y-m-01', strtotime($period[1]))); $i <= $endDate; $i = strtotime('+1 month', $i)) {
		$array[date('Y-m-d', $i)] = array(0);
	}

	foreach ($result as $item)
		$array[$item['FirstDayOfMonth']][0] = $item["SUM(to_{$_POST['position']})"];
}

ksort($array);
$list = [];
foreach ($array as $key => $value) {
	$list[] = array('date' => $key,
					'value' => $value[0]);
}

echo json_encode($list);
