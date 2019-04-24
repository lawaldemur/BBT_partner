<?php
$months_list = array(
	'Январь',
	'Февраль',
	'Март',
	'Апрель',
	'Май',
	'Июнь',
	'Июль',
	'Август',
	'Сентябрь',
	'Октябрь',
	'Ноябрь',
	'Декабрь'
);
$months_list2 = array(
	'января',
	'февраля',
	'марта',
	'апреля',
	'мая',
	'инюня',
	'июля',
	'августа',
	'сентября',
	'октября',
	'ноября',
	'декабря'
);

if ($_POST['format'] != 'all') {
	$where = 'WHERE `format` = \'' . $_POST['format'] . "'";
} else 
	$where = '';
if ($_POST['format'] != 'all') {
	$where2 = 'AND `format` = \'' . $_POST['format'] . "'";
} else 
	$where2 = '';

if ($_POST['table'] == '0') {
	$list = $dbc->query("SELECT date
			FROM `sold` $where GROUP BY date ORDER BY date DESC");
} elseif ($_POST['table'] == '1') {
	$list = $dbc->query("SELECT WEEK(`date`,1) AS WEEK_NUM, DATE_SUB(`date`, INTERVAL WEEKDAY(`date`) DAY) 
			AS WEEK_MON, DATE_SUB(`date`, INTERVAL (WEEKDAY(`date`)-6) DAY) AS WEEK_SUN, SUM(to_bbt)
			FROM `sold` $where GROUP BY WEEK_MON, WEEK_SUN, WEEK_NUM ORDER BY date DESC");
} elseif ($_POST['table'] == '2') {
	$list = $dbc->query("SELECT year(date), month(date), date
			FROM sold $where GROUP BY month(date) ORDER BY date DESC");
}

$array = [];
if ($list)
foreach ($list as $item)
	$array[] = $item;

// pagination
$rows = isset($_POST['rows_size']) ? intval($_POST['rows_size']) : 20;
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil(count($array) / $rows) + 1;


// set all values to items
if ($_POST['table'] == '0'):
	for ($i=0; $i < count($array); $i++) { 
		$result_b = $dbc->query("SELECT SUM(to_bbt)
		FROM sold WHERE to_partner_id > 0 AND DATE(`date`) = '{$array[$i]['date']}' $where2
		GROUP BY month(date)");
		if ($result_b && $result_b->num_rows > 0) {
			foreach ($result_b as $item3)
				$array[$i]['dogovor'] = round($item3['SUM(to_bbt)'], 2);
		} else
			$array[$i]['dogovor'] = 0;

		$result_b = $dbc->query("SELECT year(date),month(date),SUM(to_bbt)
		FROM sold WHERE to_partner_id = '0' AND DATE(`date`) = '{$array[$i]['date']}' $where2
		GROUP BY month(date)");
		if ($result_b && $result_b->num_rows > 0)
			foreach ($result_b as $item2)
				$array[$i]['bonus'] = round($item2['SUM(to_bbt)'], 2);
		else
			$array[$i]['bonus'] = 0;


		$array[$i]['total'] = round($array[$i]['dogovor'] + $array[$i]['bonus'], 2);

		$array[$i]['text_date'] = date('j', strtotime($array[$i]['date'])).' '.$months_list2[intval(date('m', strtotime($array[$i]['date']))) - 1].' '.date('Y', strtotime($array[$i]['date']));
	}
elseif($_POST['table'] == '1'):
	for ($i=0; $i < count($array); $i++) {
		$result_b = $dbc->query("SELECT SUM(to_bbt)
		FROM sold WHERE to_partner_id > 0 AND DATE(`date`) BETWEEN '{$array[$i]['WEEK_MON']}' AND '{$array[$i]['WEEK_SUN']}'
		GROUP BY month(date)");
		if ($result_b && $result_b->num_rows > 0) {
			foreach ($result_b as $item3)
				$array[$i]['dogovor'] = round($item3['SUM(to_bbt)'], 2);
		} else
			$array[$i]['dogovor'] = 0;

		$result_b = $dbc->query("SELECT year(date),month(date),SUM(to_bbt)
		FROM sold WHERE to_partner_id = '0' AND DATE(`date`) BETWEEN '{$array[$i]['WEEK_MON']}' AND '{$array[$i]['WEEK_SUN']}'
		GROUP BY month(date)");
		if ($result_b && $result_b->num_rows > 0)
			foreach ($result_b as $item2)
				$array[$i]['bonus'] = round($item2['SUM(to_bbt)'], 2);
		else
			$array[$i]['bonus'] = 0;


		$array[$i]['total'] = round($array[$i]['dogovor'] + $array[$i]['bonus'], 2);

		$array[$i]['text_date'] = date('j', strtotime($array[$i]['WEEK_MON'])).' '.$months_list2[intval(date('m', strtotime($array[$i]['WEEK_MON']))) - 1].' '.date('Y', strtotime($array[$i]['WEEK_MON'])).' &mdash; '.date('j', strtotime($array[$i]['WEEK_SUN'])).' '.$months_list2[intval(date('m', strtotime($array[$i]['WEEK_SUN']))) - 1].' '.date('Y', strtotime($array[$i]['WEEK_SUN']));
	}
elseif($_POST['table'] == '2'):
	for ($i=0; $i < count($array); $i++) {
		$year = $array[$i]['year(date)'];
		$month = $months_list[intval($array[$i]['month(date)']) - 1];

		$result_b = $dbc->query("SELECT year(date),month(date),SUM(to_bbt)
		FROM sold WHERE to_partner_id > 0 AND year(date) = $year AND month(date) = {$array[$i]['month(date)']} $where2
		GROUP BY month(date)");
		if ($result_b && $result_b->num_rows > 0) {
			foreach ($result_b as $item3)
				$array[$i]['dogovor'] = round($item3['SUM(to_bbt)'], 2);
		} else
			$array[$i]['dogovor'] = 0;

		$result_b = $dbc->query("SELECT year(date),month(date),SUM(to_bbt)
		FROM sold WHERE to_partner_id = '0' AND year(date) = $year AND month(date) = {$array[$i]['month(date)']} $where2
		GROUP BY month(date)");
		foreach ($result_b as $item2)
				$array[$i]['bonus'] = round($item2['SUM(to_bbt)'], 2);
		if ($result_b && $result_b->num_rows > 0)
			foreach ($result_b as $item2)
				$array[$i]['bonus'] = round($item2['SUM(to_bbt)'], 2);
		else
			$array[$i]['bonus'] = 0;


		$array[$i]['total'] = round($array[$i]['dogovor'] + $array[$i]['bonus'], 2);
		
		$array[$i]['text_date'] = $month.' '.$year;

		$result_b = [];
	}
endif;


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

