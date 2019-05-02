<?php
if ($role == 'ББТ') {
	// get all clients
	$db_shop->set_table('wp_users');
	$db_shop->set_where([]);
	$clients_arr = $db_shop->select();
	$count = $clients_arr->num_rows;
} elseif ($role == 'Команда') {
	// get partners of command, and after get clients of each partner
	$clients_arr = array();

	$db->set_table('users');
	$db->set_where(['position' => 'partner', 'parent' => $user_id]);
	$partners_array = $db->select('si');
	foreach ($partners_array as $partner) {
		$db_shop->set_table('wp_users');
		$db_shop->set_where(['parent' => $partner['code']]);
		$clients_array = $db_shop->select('s');
		foreach ($clients_array as $client)
			$clients_arr[] = $client;
	}
	$count = count($clients_arr);
} else {
	// get clients of partner
	$db->set_table('users');
	$db->set_where(['id' => $id]);
	$code = $db->select('i')->fetch_array(MYSQLI_ASSOC)['code'];

	$db_shop->set_table('wp_users');
	$db_shop->set_where(['parent' => $code]);
	$clients_arr = $db_shop->select('s');

	$count = $clients_arr->num_rows;
}

$array = [];
foreach ($clients_arr as $client) {
	$id = $client['ID'];

	$db_shop->set_table('wp_usermeta');
	// get name
	$db_shop->set_where(['user_id' => $id, 'meta_key' => 'first_name']);
	$meta_array = $db_shop->select('is');
	if ($meta_array)
		$client['first_name'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

	// get second name
	$db_shop->set_where(['user_id' => $id, 'meta_key' => 'last_name']);
	$meta_array = $db_shop->select('is');
	if ($meta_array)
		$client['last_name'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

	$client['name'] = $client['first_name'].' '.$client['last_name'];

	// get city
	$db_shop->set_where(['user_id' => $id, 'meta_key' => 'billing_city']);
	$meta_array = $db_shop->select('is');
	if ($meta_array)
		$client['city'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

	// get picture
	$db_shop->set_where(['user_id' => $id, 'meta_key' => 'profile_pic']);
	$meta_array = $db_shop->select('is');
	if ($meta_array) {
		$client['picture'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

		$db_shop->set_table('wp_posts');
		$db_shop->set_where(['ID' => $client['picture']]);
		$meta_array = $db_shop->select('i');
		if ($meta_array && $meta_array->num_rows !== 0)
			$client['picture'] = 'http://bbt-online.ru/wp-content/uploads/' . end(explode('/', $meta_array->fetch_array(MYSQLI_ASSOC)['guid']));
	}
	if ($client['picture'] == '')
		$client['picture'] = '/avatars/avatar.png';
		

	// get parent
	$db->set_table('users');
	$db->set_where(['code' => $client['parent']]);
	$parent = $db->select('s');
	if ($parent) {
		$parent = $parent->fetch_array(MYSQLI_ASSOC)['parent'];
		$db->set_where(['id' => $parent]);
		$parent = $db->select('i');
		$client['parent'] = $parent->fetch_array(MYSQLI_ASSOC)['name'];
	}
	if ($client['parent'] == '')
		$client['parent'] = 'ББТ';

	// get clients
	$db_shop->set_table('wp_users');
	$db_shop->set_where(['code' => $client['parent']]);
	$clients = $db_shop->select('s');
	$client['clients'] = $clients->num_rows;

	// get bought summ
	$db->set_table('sold');
	$db->set_where(['client' => $id, 'date' => $period]);
	$client['bought'] = 0;
	$bought = $db->select('i');
	if ($bought)
		foreach ($bought as $value)
			$client['bought'] += $value['summ'];
	
	// get sold summ
	$client['sold'] = 0;
	if ($clients)
		foreach ($clients as $value) {
			$db->set_where(['client' => $value['ID'], 'date' => $period]);
			$bought = $db->select('i');
			if ($bought)
			foreach ($bought as $value2)
				$client['sold'] += $value2['summ'];
		}

	if ($search != '' &&
		stripos(mb_strtolower($client['parent'], 'UTF-8'), mb_strtolower($search, 'UTF-8')) === false &&
		stripos(mb_strtolower($client['first_name'].' '.$client['last_name'], 'UTF-8'), mb_strtolower($search, 'UTF-8')) === false)
		continue;

	$array[] = $client;
}

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

// pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$rows = isset($_COOKIE['rows']) ? intval($_COOKIE['rows']) : 20;
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil(count($array) / $rows) + 1;

while ($offset > count($array)) {
	$page--;
	$offset = $page * $rows - $rows;
	$limit = $page * $rows;
}
