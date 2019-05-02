<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['id']), $db))
	exit('отказано в доступе');

$db->set_table('users');
$db->set_where(['login' => $_POST['login']]);
$result = $db->select('s');

if ($result->num_rows > 0)
	echo "1";
