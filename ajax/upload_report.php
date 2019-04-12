<?php
require '../db.php';
$dbc->set_charset("utf8");
session_start();


$purpose = $_GET['purpose'] == 2 ? 'report' : 'act';
$id = $_GET['id'];
$date = date("m.y", strtotime($_GET['date']));

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


move_uploaded_file($_FILES['file']['tmp_name'], '/home/h809274500/partner.bbt-online.ru/docs/service'.'/'.$purpose.'s/'.$date.'_done/'.$_FILES['file']['name']);


$query = $dbc->query("UPDATE `reports` SET `{$purpose}_done` = '{$_FILES['file']['name']}' WHERE `id` = $id");
	
if ($query == true)
	echo 'SUCCESS';
else
	echo "error save document";

mysqli_close($dbc);
exit();


