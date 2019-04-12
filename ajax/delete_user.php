<?php
require '../db.php';

$id = $_POST['id'];
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

$res = $dbc->query("SELECT * FROM `users` WHERE `id` = {$_POST['id']}");
$res = $res->fetch_array(MYSQLI_ASSOC);
if ($res['parent'] == $_POST['parent'])
	$dbc->query("DELETE FROM `users` WHERE `id` = {$_POST['id']}");

