<?php
if (0 < $_FILES['file']['error'] ) {
	echo 'Error';
	exit();
}

if (!isset($_FILES['file'])) {
	echo "file not found";
	exit();
}

$format = explode('.', $_FILES['file']['name']);
$format = $format[count($format) - 1];

if ($format != 'jpg' && $format != 'png') {
	echo "Недопустимый формат";
	exit();
}

$file_name = time() . '.' . $format;
move_uploaded_file($_FILES['file']['tmp_name'], '../avatars/' . $file_name);

echo $file_name;

require '../db.php';

$id = $_GET['id'];
$dbc->query("UPDATE `users` SET `picture` = '$file_name' WHERE `id` = $id");

mysqli_close($dbc);
