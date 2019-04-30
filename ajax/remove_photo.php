<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['id']), $db))
	exit('отказано в доступе');

$id = $_POST['id'];

$db->set_table('users');
$db->set_where(['id' => $id]);
$db->set_update(['picture' => 'avatar.png']);
$db->update('si');
