<?php
require '../db.php';
session_start();

if (0 < $_FILES['file']['error'] ) {
	echo 'Error';
	exit();
}

if (!isset($_FILES['file'])) {
	echo "file not found";
	exit();
}

$id = $_GET['id'];

$user = false;
if (isset($_SESSION['logged']))
	$user = $_SESSION['logged'];
elseif (isset($_COOKIE['logged']))
	$user = $_COOKIE['logged'];
// transform hash to str
if (!$user)
	exit('не авторизованный пользователь');
$user = str_replace(';', '', $dbc->real_escape_string($user));
$user = $dbc->query("SELECT * FROM `users` WHERE `auth` = '$user'");
if (!$user || $user->num_rows === 0)
	exit('не авторизованный пользователь');
$user = $user->fetch_array(MYSQLI_ASSOC);

if ($user['id'] != $id)
	exit('не авторизованный пользователь');


$format = explode('.', $_FILES['file']['name']);
$format = $format[count($format) - 1];

if ($format != 'jpg' && $format != 'png') {
	echo "Недопустимый формат";
	exit();
}

$file_name = time() . '.' . $format;
move_uploaded_file($_FILES['file']['tmp_name'], '../avatars/' . $file_name);

echo $file_name;


$dbc->query("UPDATE `users` SET `picture` = '$file_name' WHERE `id` = $id");

mysqli_close($dbc);
