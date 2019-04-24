<?php
require '../db.php';
require '../php/access.php';
require '../crypt.php';

if (!access(intval($_POST['id']), $dbc))
	exit('отказано в доступе');

$id = intval($_POST['id']);
$pass = $_POST['pass'];
$auth = get_hash_password($id, $pass);
$dbc->query("UPDATE `users` SET `auth` = '$auth' WHERE `id` = $id");

$id = password_hash($auth, PASSWORD_DEFAULT);
$password = mc_encrypt($pass, SECRET_KEY);
$dbc->query("INSERT INTO `passwords` (`id`, `password`) VALUES ('$id', '$password')");

if (isset($_SESSION['logged']))
	$_SESSION['logged'] = $auth;
elseif (isset($_COOKIE['logged']))
	$_COOKIE['logged'] = $auth;

mysqli_close($dbc);