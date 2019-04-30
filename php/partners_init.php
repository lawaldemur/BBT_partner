<?php
include 'header.php';
require 'db_shop.php';

$period = $_COOKIE['period'] ? $_COOKIE['period'] : '`date` >= CURDATE()';
$search = $_GET['search'];
$_POST['sortColumn'] = 'name';
$_POST['sortColumnType'] = 'default';

// get all partners
$db->set_where(['position' => 'partner'] + ($role == 'ББТ' ? [] : ['parent' => $user_id]));
$db->set_table('users');
$partners_array = $db->select('s' . ($role == 'ББТ' ? '' : 'i'));

require 'get_partners_list.php';
