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


$pass = $dbc->query("SELECT * FROM `users` WHERE `id` = {$_POST['id']}");
$pass = $pass->fetch_array(MYSQLI_ASSOC);
if ($pass['parent'] == $user_id)
	echo $pass['password'];
