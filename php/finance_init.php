<?php
require 'header.php';

$period = $_COOKIE['period'] ? $_COOKIE['period'] : '`date` >= CURDATE()';
$format = $_COOKIE['format'] ? $_COOKIE['format'] : 'all';
$condition = "`format` = '$format'";
$date = $period . ($format == 'all' ? '' : " AND $condition");


// данные для блока над графиком
if ($role == 'ББТ') {
	$n1 = $dbc->query("SELECT SUM(to_bbt) FROM sold WHERE to_partner_id <> 0 AND $date");
	$n1 = intval($n1->fetch_array(MYSQLI_ASSOC)['SUM(to_bbt)']);

	$n2 = $dbc->query("SELECT SUM(to_bbt) FROM sold WHERE to_partner_id = 0 AND $date");
	$n2 = intval($n2->fetch_array(MYSQLI_ASSOC)['SUM(to_bbt)']);
} else {
	$role_l = $role == 'Команда' ? 'command' : 'partner';

	$n = $dbc->query("SELECT SUM(to_$role_l) FROM sold WHERE to_{$role_l}_id = $user_id AND $date");
	$n = intval($n->fetch_array(MYSQLI_ASSOC)["SUM(to_$role_l)"]);
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

	$where = $format == 'all' ? '' : "WHERE $condition";
	$wher2 = $format == 'all' ? '' : "AND $condition";

	$months = $dbc->query("SELECT year(date), month(date)
			FROM sold $where GROUP BY month(date) ORDER BY date DESC");
	$earn = [];

	if ($months)
	foreach ($months as $item) {
		$out = [];
		// date
		$year = $item['year(date)'];
		$month = $months_list[intval($item['month(date)']) - 1];
		$out['date'] = $month.' '.$year;
		// dogovors
		$n = $dbc->query("SELECT year(date), month(date), SUM(to_bbt) FROM sold
			WHERE to_partner_id > 0 AND year(date) = $year AND month(date) = {$item['month(date)']} $where2 GROUP BY month(date)");
		if ($n && $n->num_rows > 0)
			$out['n1'] = $n->fetch_array(MYSQLI_ASSOC)['SUM(to_bbt)'];
		else
			$out['n1'] = 0;
		// bonuses
		$n = $dbc->query("SELECT year(date),month(date),SUM(to_bbt) FROM sold
			WHERE to_partner_id = '0' AND year(date) = $year AND month(date) = {$item['month(date)']} $where2 GROUP BY month(date)");
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