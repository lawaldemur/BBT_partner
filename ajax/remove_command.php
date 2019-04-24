<?php
require '../db.php';
require '../php/access.php';

if (!access(1, $dbc))
	exit('отказано в доступе');

$id = intval($_POST['command_id']);
$partners = $dbc->query("SELECT * FROM `users` WHERE `position` = 'partner' AND `parent` = $id");

if ($partners->num_rows !== 0)
	echo 'the command has partners';
