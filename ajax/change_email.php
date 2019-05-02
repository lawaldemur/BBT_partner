<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['id']), $db))
	exit('отказано в доступе');

$id = intval($_POST['id']);
$email = $_POST['email'];

$db->set_table('users');
$db->set_where(['login' => $email]);
$result = $db->select('s');
if ($result->num_rows > 0)
	echo "already";

$db->set_table('users');
$db->set_update(['login' => $email]);
$db->set_where(['id' => $id]);
$db->update('si');
