<?php
include 'header.php';
include 'db_shop.php';

$period = $_COOKIE['period'] ? $_COOKIE['period'] : '`date` >= CURDATE()';
$search = $_GET['search'];

require 'get_clients_list.php';
