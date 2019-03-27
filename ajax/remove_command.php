<?php
require '../db.php';

$partners = $dbc->query("SELECT * FROM `users` WHERE `position` = 'partner' AND `parent` = {$_POST['command_id']}");

if ($partners->num_rows !== 0)
	echo 'the command has partners';
