<?php
include 'header.php';
require 'db_shop.php';

$period = $_COOKIE['period'] ? $_COOKIE['period'] : '`date` >= CURDATE()';
$search = $_GET['search'];

if ($role == 'ББТ')
	$partners_array = $dbc->query("SELECT * FROM `users` WHERE `position` = 'partner'");
else
	$partners_array = $dbc->query("SELECT * FROM `users` WHERE `position` = 'partner' AND `parent` = $user_id");


require 'get_partners_list.php';