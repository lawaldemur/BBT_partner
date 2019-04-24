<?php
require '../db.php';
require '../crypt.php';

$id = intval($_POST['id']);

$auth = $dbc->query("SELECT * FROM users WHERE id = $id");
$auth = $auth->fetch_array(MYSQLI_ASSOC)['auth'];

if ($_POST['old_pass'] != md5($auth))
	exit();

$new_pass = $_POST['pass'];
$auth = get_hash_password($id, $new_pass);

$dbc->query("UPDATE `users` SET `auth` = '$auth' WHERE `id` = $id");
$id = password_hash($auth, PASSWORD_DEFAULT);
$password = mc_encrypt($new_pass, SECRET_KEY);
$dbc->query("INSERT INTO `passwords` (`id`, `password`) VALUES ('$id', '$password')");

mysqli_close($dbc);