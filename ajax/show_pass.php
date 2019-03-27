<?php
require '../db.php';
session_start();

if (isset($_SESSION['logged']))
	$user = $_SESSION['logged'];
elseif (isset($_COOKIE['logged']))
	$user = $_COOKIE['logged'];
$user_id = explode('|', $user)[2];

$pass = $dbc->query("SELECT * FROM `users` WHERE `id` = {$_POST['id']}");
$pass = $pass->fetch_array(MYSQLI_ASSOC);
if ($pass['parent'] == $user_id)
	echo $pass['password'];
