<?php
require '../db.php';
session_start();

$id = $_POST['id'];
$pass = $_POST['pass'];

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

$auth = get_hash_password($id, $pass);
$dbc->query("UPDATE `users` SET `password` = '$pass', `auth` = '$auth' WHERE `id` = $id");

if (isset($_SESSION['logged']))
	$_SESSION['logged'] = $auth;
elseif (isset($_COOKIE['logged']))
	$_COOKIE['logged'] = $auth;

mysqli_close($dbc);