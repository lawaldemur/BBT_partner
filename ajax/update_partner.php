<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['user_id']), $dbc))
	exit('отказано в доступе');

$partner_id = intval($_POST['partner_id']);
$partner_name = str_replace('\'', '', $_POST['partner_name']);
$partner_region = str_replace('\'', '', $_POST['partner_region']);
$get_audio = str_replace('\'', '', $_POST['get_audio']);
$get_digital = str_replace('\'', '', $_POST['get_digital']);
$partner_email = str_replace('\'', '', $_POST['partner_email']);

echo $dbc->query("UPDATE `users` SET `login` = '$partner_email', `audio_percent` = $get_audio, `digital_percent` = $get_digital, `name` = '$partner_name', `city` = '$partner_region' WHERE `id` = $partner_id");

mysqli_close($dbc);