<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['parent']), $db))
	exit('отказано в доступе');

$db->set_table('users');
$db->set_where(['id' => $_POST['id']]);
$res = $db->select('i')->fetch_array(MYSQLI_ASSOC);
if ($res['parent'] == $_POST['parent']) {
	$db->delete('i');
}

