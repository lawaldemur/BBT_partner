<?php
$array = [];
foreach ($partners_array as $partner) {
	$partner['summ_sold'] = 0;
	$partner['summ_get'] = 0;
	$partner['summ_wait'] = 0;

	$summ_sold = $dbc->query("SELECT * FROM `sold` WHERE `to_partner_id` = {$partner['id']} AND $period");
	if ($summ_sold)
	foreach ($summ_sold as $summ) {
		$partner['summ_sold'] += $summ['summ'];
		$partner['summ_get'] += $summ['to_partner'];
	}

	$summ_wait = $dbc->query("SELECT * FROM `reports` WHERE `from_id` = {$partner['id']} AND `paid` = 0");
	if ($summ_wait)
	foreach ($summ_wait as $summ)
		$partner['summ_wait'] += $summ['sum'];

	$clients = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '{$partner['code']}'");
	$partner['clients'] = $clients->num_rows;

	$partner['parent'] = $dbc->query("SELECT * FROM `users` WHERE `id` = {$partner['parent']}");
	$partner['parent'] = $partner['parent']->fetch_array(MYSQLI_ASSOC)['name'];

	if ($role == 'Команда') {
		if ($search != '' &&
			stripos(mb_strtolower($partner['parent'], 'UTF-8'), mb_strtolower($search, 'UTF-8')) === false &&
			stripos(mb_strtolower($partner['name'], 'UTF-8'), mb_strtolower($search, 'UTF-8')) === false &&
			stripos(mb_strtolower($partner['city'], 'UTF-8'), mb_strtolower($search, 'UTF-8')) === false)
			continue;
	} else {
		if ($search != '' &&
			stripos(mb_strtolower($partner['name'], 'UTF-8'), mb_strtolower($search, 'UTF-8')) === false &&
			stripos(mb_strtolower($partner['city'], 'UTF-8'), mb_strtolower($search, 'UTF-8')) === false)
			continue;
	}

	$array[] = $partner;
}

// pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$rows = isset($_COOKIE['rows']) ? intval($_COOKIE['rows']) : 20;
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil($partners_array->num_rows / $rows) + 1;

for ($i=0; $i < count($array); $i++) { 
	for ($x=$i + 1; $x < count($array); $x++) { 
		if ($array[$i]['name'] > $array[$x]['name']) {
			$temp = $array[$x];
			$array[$x] = $array[$i];
           	$array[$i] = $temp;
		}
	}
}


while ($offset > count($array)) {
	$page--;
	$offset = $page * $rows - $rows;
	$limit = $page * $rows;
}
