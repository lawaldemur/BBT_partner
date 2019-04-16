<?php
if ($role == 'ББТ') {
	// get all clients
	$clients_arr = $dbc_shop->query("SELECT * FROM `wp_users`");
	$count = $clients_arr->num_rows;
} elseif ($role == 'Команда') {
	// get partners of command, and after get clients of each partner
	$clients_arr = array();
	$partners_array = $dbc->query("SELECT * FROM `users` WHERE `position` = 'partner' AND `parent` = $user_id");
	foreach ($partners_array as $partner) {
		$clients_array = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '".$partner['code']."'");
		foreach ($clients_array as $client)
			$clients_arr[] = $client;
	}
	$count = count($clients_arr);
} else {
	// get clients of partner
	$code = $dbc->query("SELECT * FROM `users` WHERE `id` = $user_id");
	$code = $code->fetch_array(MYSQLI_ASSOC);
	$code = $code['code'];

	$clients_arr = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '$code'");
	$count = $clients_arr->num_rows;
}

$array = [];
foreach ($clients_arr as $client) {
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
		

	// get parent
	$parent = $dbc->query("SELECT * FROM `users` WHERE `code` = '{$client['parent']}'");
	if ($parent) {
		$parent = $parent->fetch_array(MYSQLI_ASSOC)['parent'];
		$parent = $dbc->query("SELECT * FROM `users` WHERE `id` = '$parent'");
		$client['parent'] = $parent->fetch_array(MYSQLI_ASSOC)['name'];
	}
	if ($client['parent'] == '')
		$client['parent'] = 'ББТ';

	// get clients
	$clients = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `code` = '{$client['parent']}'");
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

	if ($search != '' &&
		stripos(mb_strtolower($client['parent'], 'UTF-8'), mb_strtolower($search, 'UTF-8')) === false &&
		stripos(mb_strtolower($client['first_name'].' '.$client['last_name'], 'UTF-8'), mb_strtolower($search, 'UTF-8')) === false)
		continue;

	$array[] = $client;
}

for ($i=0; $i < count($array); $i++) { 
	for ($x=$i + 1; $x < count($array); $x++) { 
		if ($array[$i]['name'] > $array[$x]['name']) {
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
