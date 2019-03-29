<?php
require '../db.php';
$id = $_POST['id'];

$correct = $dbc->query("SELECT * FROM users WHERE id = $id");
$correct = $correct->fetch_array(MYSQLI_ASSOC);
if ($_POST['old_pass'] != md5($correct['password']))
	exit();

$dbc->query("UPDATE `users` SET `password` = '{$_POST['pass']}', `auth` = '".get_hash_password($id, $_POST['pass'])."' WHERE `id` = $id");

mysqli_close($dbc);