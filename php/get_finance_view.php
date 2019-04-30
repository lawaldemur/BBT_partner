<?php
$db->set_table('reports');
$db->set_update(['viewed' => 0]);
$db->set_where(['from_id' => $_POST['id']]);
$db->update('ii');

$db->set_table('users');
$db->set_where(['id' => $_POST['id']]);
$view = $db->select('i')->fetch_array(MYSQLI_ASSOC);
if ($view['position'] == 'command') {
	$db->set_where(['parent' => $_POST['id']]);
	$children = $db->select('i');
	$count = $children->num_rows;
}

$address = json_decode($view['data'])->general_address;
$address = $address == '' ? $view['city'] : $address;

echo $view['picture'].'|0|'.$view['name'].'|0|'.$count.'|0|'.$address;

$db->set_table('reports');
$db->set_where(['from_id' => $_POST['id']]);
$reports = $db->select('i');
$array = [];
foreach ($reports as $report)
	$array[] = $report;

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$rows = isset($_POST['rows_size']) ? intval($_POST['rows_size']) : 20;
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil(count($array) / $rows) + 1;

// sort array
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
