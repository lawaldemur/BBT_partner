<?php
require '../db.php';
require '../php/access.php';
require '../crypt.php';

if (!access(intval($_POST['user_id']), $dbc))
	exit('отказано в доступе');

$id = intval($_POST['id']);
$user_id = intval($_POST['user_id']);

$pass = $dbc->query("SELECT * FROM `users` WHERE `id` = $id");
$pass = $pass->fetch_array(MYSQLI_ASSOC);
if ($pass['parent'] == $user_id) {
	$auth = $pass['auth'];
	$passes = $dbc->query("SELECT * FROM `passwords`");

	if ($passes)
	foreach ($passes as $pass) {
		if (password_verify($auth, $pass['id']))
			echo mc_decrypt($pass['password'], SECRET_KEY);
	}
}

