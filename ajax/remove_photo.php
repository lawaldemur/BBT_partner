<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['id']), $dbc))
	exit('отказано в доступе');

$id = $_POST['id'];
$dbc->query("UPDATE `users` SET `picture` = 'avatar.png' WHERE `id` = $id");

mysqli_close($dbc);
