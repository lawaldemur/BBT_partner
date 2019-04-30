<?php
include 'months_names.php';

$db->set_table('sold');
$db->set_where($_POST['format'] == 'all' ? [] : ['format' => $format]);
$list = $db->select($_POST['format'] == 'all' ? '' : 's', ['year(date)', 'month(date)', 'date'], ' GROUP BY month(date) ORDER BY date DESC');

// pagination
$rows = isset($_POST['rows_size']) ? intval($_POST['rows_size']) : 20;
$page = isset($_POST['page']) && $_POST['page'] > 0 ? intval($_POST['page']) : 1;
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil($list->num_rows / $rows) + 1;

$array = [];
if ($list)
foreach ($list as $item) {
	$year = $item['year(date)'];
	$month = $months_list[intval($item['month(date)']) - 1];

	$db->set_table('sold');
	$db->set_where(['not_equal' => ['to_partner_id', 0], 'year(date)' => $year, 'month(date)' => $item['month(date)']] + ($_POST['format'] == 'all' ? [] : ['format' => $format]));

	$result_b = $db->select('iss'.($_POST['format'] == 'all' ? '' : 's'), ['year(date)', 'month(date)', 'SUM(to_bbt)'], ' GROUP BY month(date)');

	if ($result_b && $result_b->num_rows > 0)
		$item['dogovor'] = $result_b->fetch_array(MYSQLI_ASSOC)['SUM(to_bbt)'];
	else
		$item['dogovor'] = 0;

	$db->set_table('sold');
	$db->set_where(['to_partner_id' => 0, 'year(date)' => $year, 'month(date)' => $item['month(date)']] + ($_POST['format'] == 'all' ? [] : ['format' => $format]));

	$result_b = $db->select('iss'.($_POST['format'] == 'all' ? '' : 's'), ['year(date)', 'month(date)', 'SUM(to_bbt)'], ' GROUP BY month(date)');

	if ($result_b && $result_b->num_rows > 0)
		$item['bonus'] = $result_b->fetch_array(MYSQLI_ASSOC)['SUM(to_bbt)'];
	else
		$item['bonus'] = 0;


	$item['total'] = $item['dogovor'] + $item['bonus'];
	$item['text_date'] = $month.' '.$year;

	$array[] = $item;
	$result_b = [];
}

// sort array by column
for ($i=0; $i < count($array); $i++) { 
	for ($x=$i + 1; $x < count($array); $x++) { 
		if ($_POST['sortColumnType'] == 'default')
			$bool = $array[$i][$_POST['sortColumn']] < $array[$x][$_POST['sortColumn']];
		else
			$bool = $array[$i][$_POST['sortColumn']] > $array[$x][$_POST['sortColumn']];
		if ($_POST['sortColumn'] == 'name')
			$bool = !$bool;

		if ($bool) {
			$temp = $array[$x];
			$array[$x] = $array[$i];
           	$array[$i] = $temp;
		}
	}
}
