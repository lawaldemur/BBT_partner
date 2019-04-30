<?php
include 'header.php';

$period = $_COOKIE['period'] ? $_COOKIE['period'] : '`date` >= CURDATE()';
$search = $_GET['search'];
$_POST['sortColumn'] = 'name';
$_POST['sortColumnType'] = 'default';
			
// get all commands
$db->set_where(['position' => 'command']);
$db->set_table('users');
$commands_array = $db->select('s');

require 'get_commands_list.php';
