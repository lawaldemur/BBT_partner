<?php
require '../db.php';

$id = $_POST['id'];
$dbc->query("UPDATE `users` SET `picture` = 'avatar.png' WHERE `id` = $id");

mysqli_close($dbc);
