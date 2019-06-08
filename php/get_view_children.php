<?php
if ($role == 'partner') {
	$db->set_table('users');
	$db->set_where(['id' => $user_id]);
	$code = $db->select('i')->fetch_array(MYSQLI_ASSOC)['code'];
}

$db->set_table('users');
if ($_POST['search'] == '' && $role == 'command') {
	$db->set_where(['parent' => $user_id]);
	$users = $db->select('i');
}
elseif ($_POST['search'] != '' && $role == 'command') {
	$db->set_where([
		'parent' => $user_id,
		'like' => ['name LIKE ? OR city LIKE ?', '%'.$_POST['search'].'%']
	]);
	$users = $db->select('iss');
}
elseif ($role == 'partner') {
	$db_shop->set_table('wp_users');
	$db_shop->set_where(['parent' => $code]);
	$users = $db_shop->select('s');
}


$array = array();
if ($users)
if ($role == 'command')
	foreach ($users as $user) {
		$user['summ_sold'] = 0;
		$user['summ_get'] = 0;
		$user['summ_wait'] = 0;

		$db->set_table('sold');
		$db->set_where(['to_partner_id' => $user['id'], 'date' => $period]);
		$summ_sold = $db->select('i');
		if ($summ_sold)
		foreach ($summ_sold as $summ) {
			$user['summ_sold'] += $summ['summ'];
			$user['summ_get'] += $summ['to_partner'];
		}

		$db->set_table('reports');
		$db->set_where(['from_id' => $user['id'], 'paid' => 0]);
		$summ_wait = $db->select('ii');
		if ($summ_wait)
		foreach ($summ_wait as $summ)
			$user['summ_wait'] += $summ['summ'];

		$db_shop->set_table('wp_users');
		$db_shop->set_where(['parent' => $user['code']]);
		$clients = $db_shop->select('s');
		$user['clients'] = $clients->num_rows;

		$array[] = $user;
	}
else
	foreach ($users as $client) {
		$id = $client['ID'];

		// get name
		$db_shop->set_table('wp_usermeta');
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
			if ($meta_array)
				$client['picture'] = 'http://bbt-online.ru/wp-content/uploads/' . end(explode('/', $meta_array->fetch_array(MYSQLI_ASSOC)['guid']));
		}
		if ($client['picture'] == 'http://bbt-online.ru/wp-content/uploads/')
			$client['picture'] = '/avatars/avatar.png';

		// get bought summ
		$client['bought'] = 0;

		$db->set_table('sold');
		$db->set_where(['client' => $id, 'date' => $period]);
		$bought = $db->select('i');
		if ($bought)
			foreach ($bought as $value)
				$client['bought'] += $value['summ'];

		if ($_POST['search'] != '' && stristr(strtolower($client['parent']), strtolower($_POST['search'])) === FALSE &&
		stristr(strtolower($client['first_name'].' '.$client['last_name']), strtolower($_POST['search'])) === FALSE)
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
$rows = isset($_POST['rows_size']) ? intval($_POST['rows_size']) : 20;
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil($users->num_rows / $rows) + 1;

if ($_POST['get_table'] != 'children') $page = 1;

while ($offset > count($array)) {
	$page--;
	$offset = $page * $rows - $rows;
	$limit = $page * $rows;
}
