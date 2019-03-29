<?php
require '../db.php';

$command_id = $_POST['command_id'];
$command_name = $_POST['command_name'];
$command_region = $_POST['command_region'];
$get_audio = $_POST['get_audio'];
$get_digital = $_POST['get_digital'];
$command_email = $_POST['command_email'];
$command_password = $_POST['command_password'];

// if password not changed
if (strlen($command_password) == substr_count($command_password, '#'))
	echo $dbc->query("UPDATE `users` SET `login` = '$command_email', `audio_percent` = $get_audio, `digital_percent` = $get_digital, `name` = '$command_name', `city` = '$command_region' WHERE `id` = $command_id");
else {
	echo $dbc->query("UPDATE `users` SET `login` = '$command_email', `password` = '$command_password', `audio_percent` = $get_audio, `digital_percent` = $get_digital, `name` = '$command_name', `city` = '$command_region', `auth` = '".get_hash_password($command_id, $command_password)."' WHERE `id` = $command_id");

	// send email to command
	$message = "<b><a href='http://partner.bbt-online.ru/'>Партнерская программа ББТ</a></b><br>".
				"Ваш новый пароль: $command_password<br>";
	$headers = 'From: bbt@online.ru' . "\r\n" .
	    'Reply-To: bbt@online.ru' . "\r\n" .
	    'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();
	mail($email, 'ББТ обновила ваш пароль.', $message, $headers);
}

mysqli_close($dbc);