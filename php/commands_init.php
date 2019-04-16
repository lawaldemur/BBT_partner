<?php
include 'header.php';

$period = $_COOKIE['period'] ? $_COOKIE['period'] : '`date` >= CURDATE()';
$search = $_GET['search'];
			
// get all commands
$commands_array = $dbc->query("SELECT * FROM `users` WHERE `position` = 'command'");

require 'get_commands_list.php';
