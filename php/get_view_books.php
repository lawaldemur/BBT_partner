<?php
$sort = $_POST['sortType'];
$period = $_POST['period'];
$role = $_POST['role'];
$user_id = $_POST['user_id'];

$where = 'WHERE '.$period;
if (isset($_POST['format']) && $_POST['format'] != '' && $_POST['format'] != 'all')
	$where .= ' AND `format` = \''.$_POST['format']."'";

if ($role == 'command') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_command_id` = $user_id");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_command_id` = $user_id");
} elseif ($role == 'partner') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_partner_id` = $user_id");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_partner_id` = $user_id");
} else {
	$books = $dbc->query("SELECT * FROM `sold` $where AND `client` = $user_id");
}

// pagination
$rows = isset($_POST['rows_size']) ? intval($_POST['rows_size']) : 20;
$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil($books->num_rows / $rows) + 1;

if ($_POST['get_table'] != '') $page = 1;

while ($offset > count($array)) {
	$page--;
	$offset = $page * $rows - $rows;
	$limit = $page * $rows;
}

$array = array();
foreach ($books as $book) {
	$book['img'] = $dbc_shop->query("SELECT * FROM `wp_posts` WHERE `post_parent` = {$book['product']} AND `post_type` = 'attachment'");
	$book['img'] = $book['img']->fetch_array(MYSQLI_ASSOC)['guid'];

	if ($book['format'] == 'digital')
		$book['format'] = '<img src="/img/format_digital.svg" alt="digital_format" width="14" height="18">';
	elseif ($book['format'] == 'audio')
		$book['format'] = '<img src="/img/format_audio.svg" alt="audio_format" width="16" height="18">';

	$book['price'] = $dbc_shop->query("SELECT * FROM `wp_postmeta` WHERE `post_id` = {$book['variation']} AND `meta_key` = '_price'");
	$book['price'] = $book['price']->fetch_array(MYSQLI_ASSOC)['meta_value'];

	if ($sort == 'bybook' && ($role == 'command' || $role == 'partner')) {
		$total = 0;
		$summ = unserialize($book['sold']);

		if ($period == '`date` >= CURDATE()')
			$date = date('Y-m-d');

		foreach ($summ as $sum) {
			if (strtotime($sum[0]) >= $date) $total += $sum[1];
		}
		$book['summ'] = $total;
	}

	$book['count'] = $book['summ'] / $book['price'];

	if (!($role == 'command' || $role == 'partner')) {
		$book['name'] = $dbc_shop->query("SELECT * FROM `wp_posts` WHERE `ID` = {$book['product']}");
		$book['name'] = $book['name']->fetch_array(MYSQLI_ASSOC)['post_title'];

		$book['other'] = $dbc_shop->query("SELECT * FROM `wp_postmeta` WHERE `post_id` = {$book['variation']} AND `meta_key` = 'attribute_pa_writer'");
		$book['other'] = $book['other']->fetch_array(MYSQLI_ASSOC)['meta_value'];
		$book['other'] = $dbc_shop->query("SELECT * FROM `wp_terms` WHERE `slug` = '{$book['other']}'");
		$book['other'] = $book['other']->fetch_array(MYSQLI_ASSOC)['name'];

		if ($book['other'] == '') {
			$other = $dbc->query("SELECT * FROM `analitic` WHERE `product` = {$book['product']}");
			if ($other)
				foreach ($other as $author) {
					$book['other'] = $author['other'];
					break;
				}
		}
	}

	$array[] = $book;
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
