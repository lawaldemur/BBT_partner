<?php
include 'header.php';
include 'db_shop.php';

$period = $_COOKIE['period'] ? $_COOKIE['period'] : '`date` >= CURDATE()';
$search = $_GET['search'];
$_POST['sortColumn'] = 'name';
$_POST['sortColumnType'] = 'default';

require 'get_clients_list.php';
