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
	$db->set_table('users');
	$address = json_decode($view['data'])->general_address;
	$address = $address == '' ? 'г. '.$view['city'] : $address;

	$picture = '/avatars' . '/' . $view['picture'];

	if ($role == 'ББТ') {
		$db->set_where(['position' => 'command']);
		$commands = $db->select('s');
	}

	if ($view_position == 'command') {
		$db->set_where(['parent' => $id]);
		$children = $db->select('i');
	}
	elseif ($view_position == 'partner') {
		// get code
		$db->set_where(['id' => $id]);
		$code = $db->select('i')->fetch_array(MYSQLI_ASSOC)['code'];

		$db_shop->set_table('wp_users');
		$db_shop->set_where(['parent' => $code]);
		$children = $db_shop->select('s');
	}


	$get_today = 0;
	$get_week = 0;
	$get_month = 0;
	$get_year = 0;
	$column = 'to_'.$view_position;

	// get today
	$db->set_table('sold');
	$db->set_where([$column.'_id' => $id, 'date' => '`date` >= CURDATE()']);
	$todays = $db->select('i');
	if ($todays)
		foreach ($todays as $today)
			$get_today += $today[$column];
	// get this week
	$db->set_where([$column.'_id' => $id, 'date' => '`date` >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)']);
	$weeks = $db->select('i');
	if ($weeks)
		foreach ($weeks as $week)
			$get_week += $week[$column];
	// get this month
	$db->set_where([$column.'_id' => $id, 'date' => '`date` >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)']);
	$months = $db->select('i');
	if ($months)
		foreach ($months as $month)
			$get_month += $month[$column];		
	// get this week
	$db->set_where([$column.'_id' => $id, 'date' => '`date` >= DATE_SUB(CURRENT_DATE, INTERVAL 365 DAY)']);
	$years = $db->select('i');
	if ($years)
		foreach ($years as $year)
			$get_year += $year[$column];	

} else {
	// get picture
	$db_shop->set_table('wp_usermeta');
	$db_shop->set_where(['user_id' => $id, 'meta_key' => 'profile_pic']);
	$picture = $db_shop->select('is')->fetch_array(MYSQLI_ASSOC)['meta_value'];

	$db_shop->set_table('wp_posts');
	$db_shop->set_where(['ID' => $picture]);
	$picture = $db_shop->select('i');
	if ($picture && $picture->num_rows !== 0) {
		$picture = $picture->fetch_array(MYSQLI_ASSOC)['guid'];
		$picture = explode('/', $picture);
		$picture = 'http://bbt-online.ru/wp-content/uploads/' . $picture[count($picture) - 1];
	} else {
		$picture = '/avatars/avatar.png';
	}
	

	// get phone and email for about tab
	$db_shop->set_table('wp_usermeta');
	$db_shop->set_where(['user_id' => $id, 'meta_key' => 'billing_phone']);

	$billing_phone = $db_shop->select('is');
	if ($billing_phone && $billing_phone->num_rows !== 0)
		$billing_phone = $billing_phone->fetch_array(MYSQLI_ASSOC)['meta_value'];
	else
		$billing_phone = '';

	$db_shop->set_where(['user_id' => $id, 'meta_key' => 'billing_email']);
	$billing_email = $db_shop->select('is');
	if ($billing_email && $billing_email->num_rows !== 0)
		$billing_email = $billing_email->fetch_array(MYSQLI_ASSOC)['meta_value'];
	else
		$billing_email = '';

	$db_shop->set_table('wp_users');
	$db_shop->set_where(['ID' => $id]);
	$billing_email2 = $db_shop->select('i');
	if ($billing_email2 && $billing_email2->num_rows !== 0) {
		$billing_email2 = $billing_email2->fetch_array(MYSQLI_ASSOC);
		if (strpos($billing_email2['user_email'], '@phone') === false)
			$billing_email = $billing_email2['user_email'];
	}
}


$role = $view['position'];
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$rows = isset($_COOKIE['rows']) ? intval($_COOKIE['rows']) : 20;
$_POST['sortColumn'] = $sort == 'bydate' ? 'date' : 'name';
$_POST['sortColumnType'] = 'default';
require 'get_view_books.php';
