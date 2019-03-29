<?php
require '../db.php';
session_start();

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
$user_id = $user['id'];

if ($user_id != $_POST['id'])
	exit('не авторизованный пользователь');

$id = $_POST['id'];
$email = $_POST['email'];
$auth_method = $_POST['auth_method'];
$position = $_POST['position'];

$dbc->query("UPDATE `users` SET `login` = '$email' WHERE `id` = $id");

if ($auth_method == 'session') {
	// session_start();
	$_SESSION['logged'] = $position.'|'.$email.'|'.$id;
	echo 'session';
} elseif ($auth_method == 'cookie') {
	echo $position.'|'.$email.'|'.$id;
}

mysqli_close($dbc);