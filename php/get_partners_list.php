<?php
$array = [];
foreach ($partners_array as $partner) {
	$partner['summ_sold'] = 0;
	$partner['summ_get'] = 0;
	$partner['summ_wait'] = 0;

	$db->set_table('sold');
	$db->set_where(['to_partner_id' => $partner['id'], 'date' => $period]);
	$summ_sold = $db->select('i');
	if ($summ_sold)
	foreach ($summ_sold as $summ) {
		$partner['summ_sold'] += $summ['summ'];
		$partner['summ_get'] += $summ['to_partner'];
	}

	$db->set_table('reports');
	$db->set_where(['from_id' => $partner['id'], 'paid' => 0]);
	$summ_wait = $db->select('ii');
	if ($summ_wait)
	foreach ($summ_wait as $summ)
		$partner['summ_wait'] += $summ['sum'];

	$db_shop->set_table('wp_users');
	$db_shop->set_where(['parent' => $partner['code']]);
	$clients = $db_shop->select('s');

	$partner['clients'] = $clients->num_rows;

	$db->set_table('users');
	$db->set_where(['id' => $partner['parent']]);
	$partner['parent'] = $db->select('i')->fetch_array(MYSQLI_ASSOC)['name'];

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


while ($offset > count($array)) {
	$page--;
	$offset = $page * $rows - $rows;
	$limit = $page * $rows;
}
