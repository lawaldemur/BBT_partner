<?php
$role = $_POST['role'];
$user_id = $_POST['to'];

$array = array();
if ($role == 'ББТ') {
	$reports_array = $dbc->query("SELECT * FROM `reports` WHERE `to_id` = $user_id");

	foreach ($reports_array as $report) {
		$from = $dbc->query("SELECT * FROM `users` WHERE `id` = {$report['from_id']}");
		$from = $from->fetch_array(MYSQLI_ASSOC);

		if ($_POST['search'] != '' && stristr(mb_strtolower($from['name'], 'UTF-8'), mb_strtolower($_POST['search'], 'UTF-8')) === FALSE &&
		stristr(mb_strtolower($from['city'], 'UTF-8'), mb_strtolower($_POST['search'], 'UTF-8')) === FALSE) {
			continue;
		}

		if (isset($array[$from['id']])) {
			if ($report['paid'] == 0)
				$array[$from['id']]['count']++;
			if ($report['viewed'] == 1)
				$array[$from['id']]['view'] = 1;
		} else {
			$array[$from['id']] = array(
				'id' => $from['id'],
				'name' => $from['name'],
				'city' => $from['city'],
				'picture' => $from['picture'],
				'count' => $report['paid'] == 0 ? 1 : 0,
				'view' => $report['viewed'],
			);
		}

	}
} elseif ($role == 'Команда') {
	$reports_for_bbt = $dbc->query("SELECT * FROM `reports` WHERE `from_id` = $user_id");
	
	$reports_array = $dbc->query("SELECT * FROM `reports` WHERE `to_id` = $user_id");
	foreach ($reports_array as $report) {
		$from = $dbc->query("SELECT * FROM `users` WHERE `id` = {$report['from_id']}");
		$from = $from->fetch_array(MYSQLI_ASSOC);

		if (isset($array[$from['id']])) {
			if ($report['paid'] == 0)
				$array[$from['id']]['count']++;
		} else {
			$array[$from['id']] = array(
				'id' => $from['id'],
				'name' => $from['name'],
				'city' => $from['city'],
				'picture' => $from['picture'],
				'count' => $report['paid'] == 0 ? 1 : 0,
				'link' => '/finance_view.php?id='.$from['id'],
			);
		}

	}
} else {
	$reports_for_commands = $dbc->query("SELECT * FROM `reports` WHERE `from_id` = $user_id");
}

// ассоциативный массив в список
$array = array_values($array);

for ($i=0; $i < count($array); $i++) { 
	for ($x=$i + 1; $x < count($array); $x++) { 
		if ($_POST['sortColumnType'] == 'default')
			$bool = $array[$i][$_POST['sortColumn']] < $array[$x][$_POST['sortColumn']];
		else
			$bool = $array[$i][$_POST['sortColumn']] > $array[$x][$_POST['sortColumn']];

		if ($bool) {
			$temp = $array[$x];
			$array[$x] = $array[$i];
           	$array[$i] = $temp;
		}
	}
}

// pagination
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows_size']) ? intval($_POST['rows_size']) : 20;
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil(count($array) / $rows) + 1;

while ($offset > count($array) && $page > 1) {
	$page--;
	$offset = $page * $rows - $rows;
	$limit = $page * $rows;
}
