<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['id']), $dbc))
	exit('отказано в доступе');

$id = intval($_POST['id']);
$email = $_POST['email'];

$dbc->query("UPDATE `users` SET `login` = '$email' WHERE `id` = $id");

mysqli_close($dbc);