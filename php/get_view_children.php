<?php
if ($role == 'partner') {
	$code = $dbc->query("SELECT * FROM `users` WHERE `id` = $user_id");
	$code = $code->fetch_array(MYSQLI_ASSOC)['code'];
}

if ($_POST['search'] == '' && $role == 'command')
	$users = $dbc->query("SELECT * FROM `users` WHERE `parent` = $user_id");
elseif ($_POST['search'] != '' && $role == 'command')
	$users = $dbc->query("SELECT * FROM `users` WHERE `parent` = $user_id AND `name` LIKE '%{$_POST['search']}%' OR `city` LIKE '%{$_POST['search']}%'");
elseif ($role == 'partner')
	$users = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '$code'");


$array = array();
if ($users)
if ($role == 'command')
	foreach ($users as $user) {
		$user['summ_sold'] = 0;
		$user['summ_get'] = 0;
		$user['summ_wait'] = 0;

		$summ_sold = $dbc->query("SELECT * FROM `sold` WHERE `to_partner_id` = {$user['id']} AND $period");
		if ($summ_sold)
		foreach ($summ_sold as $summ) {
			$user['summ_sold'] += $summ['summ'];
			$user['summ_get'] += $summ['to_partner'];
		}

		$summ_wait = $dbc->query("SELECT * FROM `reports` WHERE `from_id` = {$user['id']} AND `paid` = 0");
		if ($summ_wait)
		foreach ($summ_wait as $summ)
			$user['summ_wait'] += $summ['summ'];

		$clients = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '{$user['code']}'");
		$user['clients'] = $clients->num_rows;

		$array[] = $user;
	}
else
	foreach ($users as $client) {
		$id = $client['ID'];

		// get name
		$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'first_name'");
		if ($meta_array)
			$client['first_name'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

		// get second name
		$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'last_name'");
		if ($meta_array)
			$client['last_name'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

		$client['name'] = $client['first_name'].' '.$client['last_name'];

		// get city
		$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'billing_city'");
		if ($meta_array)
			$client['city'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

		// get picture
		$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'profile_pic'");
		if ($meta_array) {
			$client['picture'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];
			$meta_array = $dbc_shop->query("SELECT * FROM `wp_posts` WHERE `ID` = {$client['picture']}");
			if ($meta_array)
				$client['picture'] = 'http://bbt-online.ru/wp-content/uploads/' . end(explode('/', $meta_array->fetch_array(MYSQLI_ASSOC)['guid']));
		}
		if ($client['picture'] == '')
			$client['picture'] = '/avatars/avatar.png';

		// get clients
		$clients = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '{$client['code']}'");
		$client['clients'] = $clients->num_rows;

		// get bought summ
		$client['bought'] = 0;
		$bought = $dbc->query("SELECT * FROM `sold` WHERE `client` = $id AND $period");
		if ($bought)
			foreach ($bought as $value)
				$client['bought'] += $value['summ'];
		
		// get sold summ
		$client['sold'] = 0;
		if ($clients)
			foreach ($clients as $value) {
				$bought = $dbc->query("SELECT * FROM `sold` WHERE `client` = {$value['ID']} AND $period");
				if ($bought)
				foreach ($bought as $value2)
					$client['sold'] += $value2['summ'];
			}

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
