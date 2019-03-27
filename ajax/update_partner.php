<?php
require '../db.php';

$partner_id = $_POST['partner_id'];
$partner_name = str_replace('\'', '', $_POST['partner_name']);
$partner_region = str_replace('\'', '', $_POST['partner_region']);
$get_audio = str_replace('\'', '', $_POST['get_audio']);
$get_digital = str_replace('\'', '', $_POST['get_digital']);
$partner_email = str_replace('\'', '', $_POST['partner_email']);
// $partner_password = $_POST['partner_password'];

// if password not changed
// if (strlen($partner_password) == substr_count($partner_password, '#'))
	echo $dbc->query("UPDATE `users` SET `login` = '$partner_email', `audio_percent` = $get_audio, `digital_percent` = $get_digital, `name` = '$partner_name', `city` = '$partner_region' WHERE `id` = $partner_id");
// else
// 	echo $dbc->query("UPDATE `users` SET `login` = '$partner_email', `password` = '$partner_password', `audio_percent` = $get_audio, `digital_percent` = $get_digital, `name` = '$partner_name', `city` = '$partner_region' WHERE `id` = $partner_id");

mysqli_close($dbc);