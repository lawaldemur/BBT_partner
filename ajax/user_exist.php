<?php
require '../db.php';

$login = $_POST['login'];
$password = $_POST['password'];
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
		echo 'cookie|'.get_hash_password($id, $password);
	} else {
		// send result to js | session will set in js
		echo 'session|'.get_hash_password($id, $password);
	}
} else {
	if ($remember) {
		// send result to js | cookie will set in js
		echo get_hash_password($id, $password);
	} else {
		session_start();
		$_SESSION["logged"] = get_hash_password($id, $password);
		// send result to js
		echo 'session';
	}
}

// close connect to db
mysqli_close($dbc);
