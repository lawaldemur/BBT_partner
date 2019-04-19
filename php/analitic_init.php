<?php
include 'header.php';
require 'db_shop.php';

$sort = $_COOKIE['sort'] ? $_COOKIE['sort'] : 'bydate';
$rows = isset($_COOKIE['rows']) ? intval($_COOKIE['rows']) : 20;
$period = $_COOKIE['period'] ? $_COOKIE['period'] : '`date` >= CURDATE()';
$format = $_COOKIE['format'] ? $_COOKIE['format'] : 'all';
$where = 'WHERE '.$period . ($format == 'all' ? '' : " AND `format` = '$format'");

if ($role == 'ББТ') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where");
} elseif ($role == 'Команда') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_command_id` = $user_id");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_command_id` = $user_id");
} elseif ($role == 'Партнер') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_partner_id` = $user_id");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_partner_id` = $user_id");
}


require 'get_analitic_books_list.php';
