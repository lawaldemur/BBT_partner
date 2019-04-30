<?php
require '../db.php';
require '../php/access.php';
require '../crypt.php';

if (!access(intval($_POST['id']), $db))
	exit('отказано в доступе');

$id = intval($_POST['id']);
$login = $_POST['login'];
$request_pass = $_POST['request_pass'];
// check password
$db->set_table('users');
$db->set_where(['login' => $login, 'id' => $id]);
$auth = $db->select('si')->fetch_array(MYSQLI_ASSOC)['auth'];

$pass = '';

$db->set_table('passwords');
$db->set_where([]);
$passes = $db->select();
if ($passes)
foreach ($passes as $passwor) {
	if (password_verify($auth, $passwor['id']))
		$pass = mc_decrypt($passwor['password'], SECRET_KEY);
}

if ($pass == $request_pass)
	echo true;

