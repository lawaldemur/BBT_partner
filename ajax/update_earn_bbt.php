<?php
include '../db.php';
require '../php/access.php';
include 'makePeriod.php';
$per = $_POST['period'];
$_POST['period'] = makePeriod($_POST['period']);
$format = $_POST['format'];

if (!access(1, $db))
	exit('отказано в доступе');

$db->set_table('sold');
$db->set_where(['not_equal' => ['to_partner_id', 0], 'date' => $per] + ($format == 'all' ? [] : ['format' => $format]));
$n1 = $db->select('i'.($format == 'all' ? '' : 's'), ['SUM(to_bbt)']);
$n1 = intval($n1->fetch_array(MYSQLI_ASSOC)['SUM(to_bbt)']);

$db->set_where(['to_partner_id' => 0, 'date' => $per] + ($format == 'all' ? [] : ['format' => $format]));
$n2 = $db->select('i'.($format == 'all' ? '' : 's'), ['SUM(to_bbt)']);
$n2 = intval($n2->fetch_array(MYSQLI_ASSOC)['SUM(to_bbt)']);

echo $n1.'|'.$n2.'|'.($n1 + $n2).'|';

$conds = [
	[],
	['not_equal' => ['to_partner_id', 0]],
	['to_partner_id' => 0]
];

// graph info
$period = explode('\'', $_POST['period']);
$date = 'WHERE '.$date;
if ($_POST['graph'] == '0') {
	$db->set_table('sold');

	$db->set_where(['date' => $per] + ($format == 'all' ? [] : ['format' => $format]));
	$result = $db->select($format == 'all' ? '' : 's', ['date', 'COUNT(id)', 'SUM(to_bbt)'], ' GROUP BY date');

	$db->set_where($conds[1] + ['date' => $per] + ($format == 'all' ? [] : ['format' => $format]));
	$result1 = $db->select('i'.($format == 'all' ? '' : 's'), ['date', 'COUNT(id)', 'SUM(to_bbt)'], ' GROUP BY date');

	$db->set_where($conds[2] + ['date' => $per] + ($format == 'all' ? [] : ['format' => $format]));
	$result2 = $db->select('i'.($format == 'all' ? '' : 's'), ['date', 'COUNT(id)', 'SUM(to_bbt)'], ' GROUP BY date');

	$array = [];

	$start = $period[1];
	while ($start <= $period[3]) {
		$array[$start] = array(0, 0, 0);
		$start = date('Y-m-d', strtotime('+1 day', strtotime($start)));
	}

	if ($result)
	foreach ($result as $item)
		$array[$item['date']][0] = $item['SUM(to_bbt)'];

	if ($result1)
	foreach ($result1 as $item)
		$array[$item['date']][1] = $item['SUM(to_bbt)'];

	if ($result2)
	foreach ($result2 as $item)
		$array[$item['date']][2] = $item['SUM(to_bbt)'];
} elseif ($_POST['graph'] == '1') {
	$db->set_table('sold');

	$db->set_where(['date' => $per] + ($format == 'all' ? [] : ['format' => $format]));
	$result = $db->select(($format == 'all' ? '' : 's'),
		['WEEK(`date`,1) AS WEEK_NUM', 'DATE_SUB(`date`, INTERVAL WEEKDAY(`date`) DAY) AS WEEK_MON', 'SUM(to_bbt)', 'DATE_SUB(`date`, INTERVAL (WEEKDAY(`date`)-6) DAY) AS WEEK_SUN', 'COUNT(id)'],
		' GROUP BY WEEK_MON, WEEK_SUN, WEEK_NUM');

	$db->set_where($conds[1] + ['date' => $per] + ($format == 'all' ? [] : ['format' => $format]));
	$result1 = $db->select('i'.($format == 'all' ? '' : 's'),
		['WEEK(`date`,1) AS WEEK_NUM', 'DATE_SUB(`date`, INTERVAL WEEKDAY(`date`) DAY) AS WEEK_MON', 'SUM(to_bbt)', 'DATE_SUB(`date`, INTERVAL (WEEKDAY(`date`)-6) DAY) AS WEEK_SUN', 'COUNT(id)'],
		' GROUP BY WEEK_MON, WEEK_SUN, WEEK_NUM');

	$db->set_where($conds[2] + ['date' => $per] + ($format == 'all' ? [] : ['format' => $format]));
	$result2 = $db->select('i'.($format == 'all' ? '' : 's'),
		['WEEK(`date`,1) AS WEEK_NUM', 'DATE_SUB(`date`, INTERVAL WEEKDAY(`date`) DAY) AS WEEK_MON', 'SUM(to_bbt)', 'DATE_SUB(`date`, INTERVAL (WEEKDAY(`date`)-6) DAY) AS WEEK_SUN', 'COUNT(id)'],
		' GROUP BY WEEK_MON, WEEK_SUN, WEEK_NUM');

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
	$db->set_table('sold');

	$db->set_where(['date' => $per] + ($format == 'all' ? [] : ['format' => $format]));
	$result = $db->select(($format == 'all' ? '' : 's'),
		["DATE_FORMAT(DATE_SUB(date, INTERVAL 0 MONTH), '%Y-%m-01') AS FirstDayOfMonth",
		'LAST_DAY(DATE_SUB(date, INTERVAL 0 MONTH)) AS LastDayOfMonth',
		'COUNT(id)', 'SUM(to_bbt)'],
		' GROUP BY FirstDayOfMonth,LastDayOfMonth');

	$db->set_where($conds[1] + ['date' => $per] + ($format == 'all' ? [] : ['format' => $format]));
	$result1 = $db->select('i'.($format == 'all' ? '' : 's'),
		["DATE_FORMAT(DATE_SUB(date, INTERVAL 0 MONTH), '%Y-%m-01') AS FirstDayOfMonth",
		'LAST_DAY(DATE_SUB(date, INTERVAL 0 MONTH)) AS LastDayOfMonth',
		'COUNT(id)', 'SUM(to_bbt)'],
		' GROUP BY FirstDayOfMonth,LastDayOfMonth');

	$db->set_where($conds[2] + ['date' => $per] + ($format == 'all' ? [] : ['format' => $format]));
	$result2 = $db->select('i'.($format == 'all' ? '' : 's'),
		["DATE_FORMAT(DATE_SUB(date, INTERVAL 0 MONTH), '%Y-%m-01') AS FirstDayOfMonth",
		'LAST_DAY(DATE_SUB(date, INTERVAL 0 MONTH)) AS LastDayOfMonth',
		'COUNT(id)', 'SUM(to_bbt)'],
		' GROUP BY FirstDayOfMonth,LastDayOfMonth');
	$array = [];


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





