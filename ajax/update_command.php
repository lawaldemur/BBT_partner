<?php
require '../db.php';
require '../php/access.php';
require '../crypt.php';

if (!access(intval($_POST['user_id']), $dbc))
	exit('отказано в доступе');

$command_id = intval($_POST['command_id']);
$command_name = $_POST['command_name'];
$command_region = $_POST['command_region'];
$get_audio = intval($_POST['get_audio']);
$get_digital = intval($_POST['get_digital']);
$command_email = $_POST['command_email'];
$command_password = $_POST['command_password'];

// if password not changed
if (strlen($command_password) == substr_count($command_password, '#'))
	echo $dbc->query("UPDATE `users` SET `login` = '$command_email', `audio_percent` = $get_audio, `digital_percent` = $get_digital, `name` = '$command_name', `city` = '$command_region' WHERE `id` = $command_id");
else {
	$auth = get_hash_password($command_id, $command_password);
	echo $dbc->query("UPDATE `users` SET `login` = '$command_email', `audio_percent` = $get_audio, `digital_percent` = $get_digital, `name` = '$command_name', `city` = '$command_region', `auth` = '$auth' WHERE `id` = $command_id");

	$id = password_hash($auth, PASSWORD_DEFAULT);
	$password = mc_encrypt($command_password, SECRET_KEY);
	$dbc->query("INSERT INTO `passwords` (`id`, `password`) VALUES ('$id', '$password')");

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