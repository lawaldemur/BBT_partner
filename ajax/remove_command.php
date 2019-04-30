<?php
require '../db.php';
require '../php/access.php';

if (!access(1, $db))
	exit('отказано в доступе');

$id = intval($_POST['command_id']);

$db->set_table('users');
$db->set_where(['position' => 'partner', 'parent' => $id]);
$partners = $db->select('si');

if ($partners->num_rows !== 0)
	echo 'the command has partners';
