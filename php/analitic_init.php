<?php
include 'header.php';
require 'db_shop.php';

$sort = $_COOKIE['sort'] ? $_COOKIE['sort'] : 'bydate';
$rows = isset($_COOKIE['rows']) ? intval($_COOKIE['rows']) : 20;
$period = $_COOKIE['period'] ? $_COOKIE['period'] : '`date` >= CURDATE()';
$format = 'all';
$types = '';
$_POST['sortColumn'] = $sort == 'bydate' ? 'date' : 'name';
$_POST['sortColumnType'] = 'default';

$where = ['date' => $period];
if ($format != 'all') {
	$where['format'] = $format;
	$types .= 's';
}

if ($role == 'Команда') {
	$where['to_command_id'] = $user_id;
	$types .= 'i';
}
elseif ($role == 'Партнер') {
	$where['to_partner_id'] = $user_id;
	$types .= 'i';
}

$db->set_where($where);
$db->set_table($sort == 'bydate' ? 'analitic' : 'analitic_bybook');
$books = $db->select($types);

require 'get_analitic_books_list.php';
