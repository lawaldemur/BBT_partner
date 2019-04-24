<?php
require '../db.php';
require '../php/access.php';
require '../crypt.php';

if (!access(intval($_POST['id']), $dbc))
	exit('отказано в доступе');

$id = intval($_POST['id']);
$login = $_POST['login'];
$request_pass = $_POST['request_pass'];
// check password
$auth = $dbc->query("SELECT * FROM `users` WHERE `login` = '$login' AND `id` = $id");
$auth = $auth->fetch_array(MYSQLI_ASSOC)['auth'];
$pass = '';

$passes = $dbc->query("SELECT * FROM `passwords`");
if ($passes)
foreach ($passes as $passwor) {
	if (password_verify($auth, $passwor['id']))
		$pass = mc_decrypt($passwor['password'], SECRET_KEY);
}

if ($pass == $request_pass)
	echo true;

