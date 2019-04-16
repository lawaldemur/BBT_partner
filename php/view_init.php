<?php
include 'header.php';
require 'db_shop.php';

// init main vars
$sort = $_COOKIE['sort'] ? $_COOKIE['sort'] : 'bydate';
$period = $_COOKIE['period'] ? $_COOKIE['period'] : '`date` >= CURDATE()';
$format = $_COOKIE['format'] ? $_COOKIE['format'] : 'all';

$where = 'WHERE '.$period;
if ($format != 'all')
	$where .= " AND `format` = '$format'";


$view_position = $view_position ? $view_position : $view['position'];
// get info about user
if ($view_position != 'client') {
	$address = json_decode($view['data'])->general_address;
	$address = $address == '' ? 'г. '.$view['city'] : $address;

	$picture = '/avatars' . '/' . $view['picture'];

	if ($role == 'ББТ')
		$commands = $dbc->query("SELECT * FROM `users` WHERE `position` = 'command'");

	if ($view_position == 'command')
		$children = $dbc->query("SELECT * FROM `users` WHERE `parent` = $id");
	elseif ($view_position == 'partner') {
		// get code
		$code = $dbc->query("SELECT * FROM `users` WHERE `id` = $id");
		$code = $code->fetch_array(MYSQLI_ASSOC)['code'];

		$children = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '$code'");
	}


	$get_today = 0;
	$get_week = 0;
	$get_month = 0;
	$get_year = 0;
	$column = 'to_'.$view_position;

	// get today
	$todays = $dbc->query("SELECT * FROM `sold` WHERE `{$column}_id` = $id AND `date` >= CURDATE()");
	if ($todays)
		foreach ($todays as $today)
			$get_today += $today[$column];
	// get this week
	$weeks = $dbc->query("SELECT * FROM `sold` WHERE `{$column}_id` = $id AND `date` >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)");
	if ($weeks)
		foreach ($weeks as $week)
			$get_week += $week[$column];
	// get this month
	$months = $dbc->query("SELECT * FROM `sold` WHERE `{$column}_id` = $id AND `date` >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)");
	if ($months)
		foreach ($months as $month)
			$get_month += $month[$column];		
	// get this week
	$years = $dbc->query("SELECT * FROM `sold` WHERE `{$column}_id` = $id AND `date` >= DATE_SUB(CURRENT_DATE, INTERVAL 365 DAY)");
	if ($years)
		foreach ($years as $year)
			$get_year += $year[$column];	

} else {
	// get picture
	$picture = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'profile_pic'");
	$picture = $picture->fetch_array(MYSQLI_ASSOC)['meta_value'];
	$picture = $dbc_shop->query("SELECT * FROM `wp_posts` WHERE `ID` = $picture");
	if ($picture) {
		$picture = $picture->fetch_array(MYSQLI_ASSOC)['guid'];
		$picture = explode('/', $picture);
		$picture = 'http://bbt-online.ru/wp-content/uploads/' . $picture[count($picture) - 1];
	} else {
		$picture = '/avatars/avatar.png';
	}
	

	// get phone and email for about tab
	$billing_phone = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'billing_phone'");
	if ($billing_phone)
		$billing_phone = $billing_phone->fetch_array(MYSQLI_ASSOC)['meta_value'];
	$billing_email = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'billing_email'");
	if ($billing_email)
		$billing_email = $billing_email->fetch_array(MYSQLI_ASSOC)['meta_value'];
	$billing_email2 = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `ID` = $id");
	if ($billing_email2) {
		$billing_email2 = $billing_email2->fetch_array(MYSQLI_ASSOC);
		if (strpos($billing_email2['user_email'], '@phone') === false)
			$billing_email = $billing_email2['user_email'];
	}
}



if ($view['position'] == 'command') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_command_id` = $id");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_command_id` = $id");
} elseif ($view['position'] == 'partner') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_partner_id` = $id");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_partner_id` = $id");
} else {
	$books = $dbc->query("SELECT * FROM `sold` $where AND `client` = $id");
}


// pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$rows = isset($_COOKIE['rows']) ? intval($_COOKIE['rows']) : 20;
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil($books->num_rows / $rows) + 1;


$array = array();
if ($books)
foreach ($books as $book) {
	$book['img'] = $dbc_shop->query("SELECT * FROM `wp_posts` WHERE `post_parent` = {$book['product']} AND `post_type` = 'attachment'");
	$book['img'] = $book['img']->fetch_array(MYSQLI_ASSOC)['guid'];

	if ($book['format'] == 'digital')
		$book['format'] = '<img src="/img/format_digital.svg" alt="digital_format" width="14" height="18">';
	elseif ($book['format'] == 'audio')
		$book['format'] = '<img src="/img/format_audio.svg" alt="audio_format" width="16" height="18">';

	$book['price'] = $dbc_shop->query("SELECT * FROM `wp_postmeta` WHERE `post_id` = {$book['variation']} AND `meta_key` = '_price'");
	$book['price'] = $book['price']->fetch_array(MYSQLI_ASSOC)['meta_value'];

	if ($sort == 'bybook' && $view_position != 'client') {
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

// sort array by date
for ($i=0; $i < count($array); $i++) { 
	for ($x=$i + 1; $x < count($array); $x++) { 
		if ($array[$i]['date'] < $array[$x]['date']) {
			$temp = $array[$x];
			$array[$x] = $array[$i];
           	$array[$i] = $temp;
		}
	}
}
