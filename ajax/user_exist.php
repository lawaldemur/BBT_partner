<?php
require '../db.php';

$login = $_POST['login'];
$password = str_replace('\'', '', $_POST['password']);
$remember = $_POST['remember'] == 'true'; # recieve true/false string


$exist = $dbc->query("SELECT * FROM `users` WHERE `login` = '$login' && `password` = '$password'");

// if user not found then exit
if (!$exist || $exist->num_rows === 0) {
	mysqli_close($dbc);
	exit('incorrect data');
}

// set cookie or session
$level = $exist->fetch_array(MYSQLI_ASSOC);
$position = $level['position'];
$id = $level['id'];
$logged = $level['logged'];
if ($position == 'partner' && $logged == 0) {
	if ($remember) {
		// send result to js | cookie will set in js
		echo $position.'|'.'cookie|'.$login.'|'.$id;
	} else {
		// send result to js | session will set in js
		echo $position.'|'.'session|'.$login.'|'.$id;
	}
} else {
	if ($remember) {
		// send result to js | cookie will set in js
		echo $position.'|'.$login.'|'.$id;
	} else {
		session_start();
		$_SESSION["logged"] = $position.'|'.$login.'|'.$id;
		// send result to js
		echo 'session';
	}
}

// close connect to db
mysqli_close($dbc);
