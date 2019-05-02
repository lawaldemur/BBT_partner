<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['parent']), $db))
	exit('отказано в доступе');

$db->set_table('users');
$db->set_where(['id' => $_POST['id']]);
$res = $db->select('i')->fetch_array(MYSQLI_ASSOC);
if ($res['parent'] == $_POST['parent']) {
	// remove old data in passwords table
	$auth = $res['auth'];
	$db->set_table('passwords');
	$db->set_where([]);
	$passes = $db->select();
	foreach ($passes as $passwor) {
		if (password_verify($auth, $passwor['id'])) {
			$db->set_where(['id' => $passwor['id']]);
			$db->delete('s');
		}
	}

	// finally delete
	$db->set_table('users');
	$db->set_where(['id' => $_POST['id']]);
	$db->delete('i');
}

