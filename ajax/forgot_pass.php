<?php
require '../db.php';
require '../crypt.php';

$id = intval($_POST['id']);

$db->set_table('users');
$db->set_where(['id' => $id]);
$auth = $db->select('i')->fetch_array(MYSQLI_ASSOC)['auth'];

if ($_POST['old_pass'] != md5($auth)) {
	exit();
}

$new_pass = $_POST['pass'];
$auth = get_hash_password($id, $new_pass);

$db->set_update(['auth' => $auth]);
$db->update('si');

$id = password_hash($auth, PASSWORD_DEFAULT);
$password = mc_encrypt($new_pass, SECRET_KEY);

$db->set_table('passwords');
$db->set_insert(['id' => $id, 'password' => $password]);
$db->insert('ss');
