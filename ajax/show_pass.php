<?php
require '../db.php';
require '../php/access.php';
require '../crypt.php';

if (!access(intval($_POST['user_id']), $db))
	exit('отказано в доступе');

$id = intval($_POST['id']);
$user_id = intval($_POST['user_id']);

$db->set_table('users');
$db->set_where(['id' => $id]);
$pass = $db->select('i')->fetch_array(MYSQLI_ASSOC);

if ($pass['parent'] == $user_id) {
	$auth = $pass['auth'];

	$db->set_table('passwords');
	$db->set_where([]);
	$passes = $db->select();

	if ($passes)
	foreach ($passes as $pass) {
		if (password_verify($auth, $pass['id']))
			echo mc_decrypt($pass['password'], SECRET_KEY);
	}
}

