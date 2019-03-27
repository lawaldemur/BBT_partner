<?php
require '../db.php';
session_start();

$id = $_POST['id'];
$pass = $_POST['pass'];

if (isset($_SESSION['logged']))
	$user = $_SESSION['logged'];
elseif (isset($_COOKIE['logged']))
	$user = $_COOKIE['logged'];
$user_id = explode('|', $user)[2];

if ($user_id != $id)
	exit('не авторизованный пользователь');

$dbc->query("UPDATE `users` SET `password` = '$pass' WHERE `id` = $id");

mysqli_close($dbc);