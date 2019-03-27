<?php
require '../db.php';

// check password
$correct = $dbc->query("SELECT * FROM `users` WHERE `id` = '".$_POST['id']."' && `password` = '".$_POST['request_pass']."'");
// if user not found then exit
if ($correct->num_rows === 0) {
	mysqli_close($dbc);
	exit('incorrect password');
}

// pass correct, let's continue
$name = $_POST['name'];
$region = $_POST['region'];
$get_digital = $_POST['get_digital'];
$get_audio = $_POST['get_audio'];
$email = $_POST['email'];

// generate password
require '../php/generate_pass.php';
$pass = generatePassword();

// send email to command
$message = "<b><a href='http://partner.bbt-online.ru/'>Партнерская программа ББТ</a></b><br>".
			"ББТ добавила вас в список команд<br>" .
			"Вы команда \"$name\"<br>" .
			"Из населеного пункта: $region<br>" .
			"Ваш логин: $email<br>" .
			"Ваш пароль: $pass";
$headers = 'From: bbt@online.ru' . "\r\n" .
    'Reply-To: bbt@online.ru' . "\r\n" .
    'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($email, 'Вы новая команда ББТ!', $message, $headers);

// add command to database
$result = $dbc->query("INSERT INTO `users` (`login`, `password`, `position`, `parent`, `audio_percent`, `digital_percent`, `name`, `city`) VALUES ('$email', '$pass', 'command', 1, $get_audio, $get_digital, '$name', '$region')");

echo $result;

mysqli_close($dbc);