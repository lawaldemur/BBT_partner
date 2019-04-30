<?php
require '../db.php';
require '../php/access.php';

if (0 < $_FILES['file']['error'] ) {
	echo 'Error';
	exit();
}

if (!isset($_FILES['file'])) {
	echo "file not found";
	exit();
}

$id = intval($_GET['id']);

if (!access($id, $db))
	exit('отказано в доступе');

$format = explode('.', $_FILES['file']['name']);
$format = $format[count($format) - 1];

if ($format != 'jpg' && $format != 'png') {
	echo "Недопустимый формат";
	exit();
}

$file_name = time() . '.' . $format;
move_uploaded_file($_FILES['file']['tmp_name'], '../avatars/' . $file_name);

echo $file_name;

$db->set_table('users');
$db->set_update(['picture' => $file_name]);
$db->set_where(['id' => $id]);
$db->update('si');
