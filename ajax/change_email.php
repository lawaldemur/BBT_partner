<?php
require '../db.php';
session_start();

if (isset($_SESSION['logged']))
	$user = $_SESSION['logged'];
elseif (isset($_COOKIE['logged']))
	$user = $_COOKIE['logged'];
$user_id = explode('|', $user)[2];

if ($_POST['id'] != $user_id)
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