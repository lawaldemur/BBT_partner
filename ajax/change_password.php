<?php
require '../db.php';
require '../php/access.php';
require '../crypt.php';

if (!access(intval($_POST['id']), $db))
	exit('отказано в доступе');

$id = intval($_POST['id']);
$pass = $_POST['pass'];
$auth = get_hash_password($id, $pass);

// remove old data in passwords table
$db->set_table('users');
$db->set_where(['id' => $id]);
$auth_old = $db->select('i')->fetch_array(MYSQLI_ASSOC)['auth'];

$db->set_table('passwords');
$db->set_where([]);
$passes = $db->select();
foreach ($passes as $passwor) {
	if (password_verify($auth_old, $passwor['id'])) {
		$db->set_where(['id' => $passwor['id']]);
		$db->delete('s');
	}
}


$db->set_table('users');
$db->set_update(['auth' => $auth]);
$db->set_where(['id' => $id]);
$db->update('si');

$id = password_hash($auth, PASSWORD_DEFAULT);
$password = mc_encrypt($pass, SECRET_KEY);

$db->set_table('passwords');
$db->set_insert([
	'id' => $id,
	'password' => $password,
]);
$db->insert('ss');


if (isset($_SESSION['logged']))
	$_SESSION['logged'] = $auth;
elseif (isset($_COOKIE['logged']))
	$_COOKIE['logged'] = $auth;

