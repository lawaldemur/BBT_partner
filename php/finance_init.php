<?php
require 'header.php';

$period = $_COOKIE['period'] ? $_COOKIE['period'] : '`date` >= CURDATE()';
$format = $_COOKIE['format'] ? $_COOKIE['format'] : 'all';
$date = $period . ($format == 'all' ? '' : " AND `format` = '$format'");

// данные для блока над графиком
if ($role == 'ББТ') {
	$n1 = $dbc->query("SELECT SUM(to_bbt) FROM sold WHERE to_partner_id <> 0 AND $date");
	$n1 = $n1->fetch_array(MYSQLI_ASSOC)['SUM(to_bbt)'];

	$n2 = $dbc->query("SELECT SUM(to_bbt) FROM sold WHERE to_partner_id = 0 AND $date");
	$n2 = $n2->fetch_array(MYSQLI_ASSOC)['SUM(to_bbt)'];
} else {
	$role_l = $role == 'Команда' ? 'command' : 'partner';

	$n = $dbc->query("SELECT SUM(to_$role_l) FROM sold WHERE to_{$role_l}_id = $user_id AND $date");
	$n = $n->fetch_array(MYSQLI_ASSOC)["SUM(to_$role_l)"];
}