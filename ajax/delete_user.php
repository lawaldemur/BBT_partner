<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['parent']), $dbc))
	exit('отказано в доступе');

$res = $dbc->query("SELECT * FROM `users` WHERE `id` = {$_POST['id']}");
$res = $res->fetch_array(MYSQLI_ASSOC);
if ($res['parent'] == $_POST['parent'])
	$dbc->query("DELETE FROM `users` WHERE `id` = {$_POST['id']}");

