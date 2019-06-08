<?php
require '../db.php';
require '../php/access.php';
require 'makePeriod.php';

$id = $_POST['id'];
$per = $_POST['period'];
$_POST['period'] = makePeriod($_POST['period']);
$format = $_POST['format'];
if ($_POST['position'] != 'partner')
	$_POST['position'] = 'command';


if (!access(intval($_POST['id']), $db))
	exit('отказано в доступе');

$db->set_table('sold');
$db->set_where(['to_'.$_POST['position'].'_id' => $id, 'date' => $per] + ($format == 'all' ? [] : ['format' => $format]));
$result_on_d = $db->select('i'.($format == 'all' ? '' : 's'), ['SUM(to_'.$_POST['position'].')']);
$result_on_d = floatval($result_on_d->fetch_array(MYSQLI_ASSOC)['SUM(to_'.$_POST['position'].')']);

echo $result_on_d."|";
// graph info
$period = explode('\'', $_POST['period']);
if ($_POST['graph'] == '0') {
	$db->set_table('sold');
	$db->set_where(['to_'.$_POST['position'].'_id' => $id, 'date' => $per] + ($format == 'all' ? [] : ['format' => $format]));
	$result = $db->select('i'.($format == 'all' ? '' : 's'), ['date', 'SUM(to_'.$_POST['position'].')'], ' GROUP BY date');
	$array = [];

	$start = $period[1];
	while ($start <= $period[3]) {
		$array[$start] = array(0);
		$start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
	}

	foreach ($result as $item)
		$array[$item['date']][0] = floatval($item['SUM(to_'.$_POST['position'].')']);
} elseif ($_POST['graph'] == '1') {
	$db->set_table('sold');
	$db->set_where(['to_'.$_POST['position'].'_id' => $id, 'date' => $per] + ($format == 'all' ? [] : ['format' => $format]));
	$result = $db->select('i'.($format == 'all' ? '' : 's'),
		['WEEK(`date`,1) AS WEEK_NUM', 'DATE_SUB(`date`, INTERVAL WEEKDAY(`date`) DAY) AS WEEK_MON', 'SUM(to_'.$_POST['position'].')', 'DATE_SUB(`date`, INTERVAL (WEEKDAY(`date`)-6) DAY) AS WEEK_SUN', 'COUNT(id)'],
		' GROUP BY WEEK_MON, WEEK_SUN, WEEK_NUM');

	$array = [];

	$endDate = strtotime($period[3]);
	for($i = strtotime('Monday', strtotime($period[1])); $i <= $endDate; $i = strtotime('+1 week', $i))
		$array[date('Y-m-d', $i)] = array(0);

	foreach ($result as $item)
		$array[$item['WEEK_MON']][0] = floatval($item['SUM(to_'.$_POST['position'].')']);
} elseif ($_POST['graph'] == '2') {
	$db->set_table('sold');
	$db->set_where(['to_'.$_POST['position'].'_id' => $id, 'date' => $per] + ($format == 'all' ? [] : ['format' => $format]));

	$result = $db->select('i'.($format == 'all' ? '' : 's'),
		["DATE_FORMAT(DATE_SUB(date, INTERVAL 0 MONTH), '%Y-%m-01') AS FirstDayOfMonth",
		'LAST_DAY(DATE_SUB(date, INTERVAL 0 MONTH)) AS LastDayOfMonth',
		'COUNT(id)', 'SUM(to_'.$_POST['position'].')'],
		' GROUP BY FirstDayOfMonth,LastDayOfMonth');
	$array = [];

	$endDate = strtotime($period[3]);
	for($i = strtotime(date('Y-m-01', strtotime($period[1]))); $i <= $endDate; $i = strtotime('+1 month', $i)) {
		$array[date('Y-m-d', $i)] = array(0);
	}

	foreach ($result as $item)
		$array[$item['FirstDayOfMonth']][0] = floatval($item['SUM(to_'.$_POST['position'].')']);
}

ksort($array);
$list = [];
foreach ($array as $key => $value) {
	$list[] = array('date' => $key,
					'value' => $value[0]);
}

echo json_encode($list);

