<?php
require '../db.php';
require '../crypt.php';

$login = $_POST['login'];
$password = $_POST['password'];
$remember = $_POST['remember'] == 'true'; # recieve true/false string

$db->set_table('users');
$db->set_where(['login' => $login]);
$exist = $db->select('s');
// if user not found then exit
if (!$exist || $exist->num_rows === 0) {
	exit('incorrect data');
}

// find and decrypt password
$access = false;
foreach ($exist as $level) {
	$auth = $level['auth'];

	$db->set_table('passwords');
	$db->set_where([]);
	$passes = $db->select();

	$pass = '';
	if ($passes)
	foreach ($passes as $passwor) {
		if (password_verify($auth, $passwor['id']))
			$pass = $passwor['password'];
	}
	$pass = mc_decrypt($pass, SECRET_KEY);

	// if password is right
	if ($pass == $password && strlen($password) > 0) {
		$access = true;
		break;
	}
}

if (!$access) {
	exit('incorrect data');
}


// set cookie or session
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
