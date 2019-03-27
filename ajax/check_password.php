<?php
require '../db.php';

// check password
$correct = $dbc->query("SELECT * FROM `users` WHERE `login` = '".$_POST['login']."' && `password` = '".$_POST['request_pass']."'");
// if user not found then exit
if ($correct->num_rows === 0) {
	mysqli_close($dbc);
	exit();
}
echo true;