<?php
require 'header.php';

$period = $_COOKIE['period'] ? $_COOKIE['period'] : '`date` >= CURDATE()';
$format = $_COOKIE['format'] ? $_COOKIE['format'] : 'all';
$condition = "`format` = '$format'";
$date = $period . ($format == 'all' ? '' : " AND $condition");


// данные для блока над графиком
if ($role == 'ББТ') {
	$db->set_table('sold');
	$db->set_where(['not_equal' => ['to_partner_id', 0], 'date' => $period] + ($format == 'all' ? [] : ['format' => $format]));

	$n1 = $db->select('i'.($format == 'all' ? '' : "s"), ['SUM(to_bbt)'])->fetch_array(MYSQLI_ASSOC)['SUM(to_bbt)'];

	$db->set_where(['to_partner_id' => 0, 'date' => $period] + ($format == 'all' ? [] : ['format' => $format]));
	$n2 = $db->select('i'.($format == 'all' ? '' : "s"), ['SUM(to_bbt)'])->fetch_array(MYSQLI_ASSOC)['SUM(to_bbt)'];
} else {
	$role_l = $role == 'Команда' ? 'command' : 'partner';

	$db->set_table('sold');
	$db->set_where(['to_'.$role_l.'_id' => $user_id, 'date' => $period] + ($format == 'all' ? [] : ['format' => $format]));
	$n = $db->select('i'.($format == 'all' ? '' : "s"), ["SUM(to_$role_l)"])->fetch_array(MYSQLI_ASSOC)["SUM(to_$role_l)"];
}

// таблица заработков по месяцам для ББТ
if ($role == 'ББТ') {
	if (strpos($period, 'BETWEEN') !== false) {
		$date1 = new DateTime(substr($period, 21, 10));
		$date2 = new DateTime(substr($period, 36, 10));

		$diff = $date1->diff($date2);
		$diff = intval($diff->format('%y')) * 12 + intval($diff->format('%m')) >= 2;
	}
	// отображать ли таблицу при инициализации таблицы
	$show_earn_table = $period == 'QUARTER(`date`) = QUARTER(CURDATE())' ||
						$period == 'YEAR(`date`) = YEAR(CURDATE())' ||
						(strpos($period, 'BETWEEN') !== false && $diff);

	$db->set_table('sold');
	$db->set_where($format == 'all' ? [] : ['format' => $format]);
	$months = $db->select($format == 'all' ? '' : 's', ['year(date)', 'month(date)'], ' GROUP BY month(date) ORDER BY date DESC');

	$earn = [];

	if ($months)
	foreach ($months as $item) {
		$out = [];
		// date
		$year = $item['year(date)'];
		$month = $months_list[intval($item['month(date)']) - 1];
		$out['date'] = $month.' '.$year;
		// dogovors
		$db->set_table('sold');
		$db->set_where(['not_equal' => ['to_partner_id', 0], 'year(date)' => $year, 'month(date)' => $item['month(date)']] + ($format == 'all' ? [] : ['format' => $format]));
		$n = $db->select('iss'.($format == 'all' ? '' : 's'), ['year(date)', 'month(date)', 'SUM(to_bbt)'], ' GROUP BY month(date)');

		if ($n && $n->num_rows > 0)
			$out['n1'] = $n->fetch_array(MYSQLI_ASSOC)['SUM(to_bbt)'];
		else
			$out['n1'] = 0;
		// bonuses
		$db->set_where(['to_partner_id' => 0, 'year(date)' => $year, 'month(date)' => $item['month(date)']] + ($format == 'all' ? [] : ['format' => $format]));
		$n = $db->select('iss'.($format == 'all' ? '' : 's'), ['year(date)', 'month(date)', 'SUM(to_bbt)'], ' GROUP BY month(date)');
		if ($n && $n->num_rows > 0)
			$out['n2'] = $n->fetch_array(MYSQLI_ASSOC)['SUM(to_bbt)'];
		else
			$out['n2'] = 0;
		// total
		$out['total'] = $out['n1'] + $out['n2'];

		$earn[] = $out;
	}
}

// pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$rows = isset($_COOKIE['rows']) ? intval($_COOKIE['rows']) : 20;
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil(count($earn) / $rows) + 1;