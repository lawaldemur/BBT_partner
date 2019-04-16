<?php
$array = [];
foreach ($commands_array as $command) {
	$command['summ_sold'] = 0;
	$command['summ_get'] = 0;
	$command['summ_wait'] = 0;

	$summ_sold = $dbc->query("SELECT * FROM `sold` WHERE `to_command_id` = {$command['id']} AND $period");
	if ($summ_sold)
	foreach ($summ_sold as $summ) {
		$command['summ_sold'] += $summ['summ'];
		$command['summ_get'] += $summ['to_command'];
	}

	$summ_wait = $dbc->query("SELECT * FROM `reports` WHERE `from_id` = {$command['id']} AND `paid` = 0");
	if ($summ_wait)
	foreach ($summ_wait as $summ)
		$command['summ_wait'] += $summ['sum'];

	if ($role == 'Команда') {
		if ($search != '' &&
			stripos(mb_strtolower($command['parent'], 'UTF-8'), mb_strtolower($search, 'UTF-8')) === false &&
			stripos(mb_strtolower($command['name'], 'UTF-8'), mb_strtolower($search, 'UTF-8')) === false &&
			stripos(mb_strtolower($command['city'], 'UTF-8'), mb_strtolower($search, 'UTF-8')) === false)
			continue;
	} else {
		if ($search != '' &&
			stripos(mb_strtolower($command['name'], 'UTF-8'), mb_strtolower($search, 'UTF-8')) === false &&
			stripos(mb_strtolower($command['city'], 'UTF-8'), mb_strtolower($search, 'UTF-8')) === false)
			continue;
	}

	$array[] = $command;
}


// pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$rows = isset($_COOKIE['rows']) ? intval($_COOKIE['rows']) : 20;
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil($commands_array->num_rows / $rows) + 1;

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
