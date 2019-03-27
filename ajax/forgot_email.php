<?php
require '../db.php';
$email = $_POST['email'];

$correct = $dbc->query("SELECT * FROM users WHERE login = '$email'");
if (!$correct || $correct->num_rows === 0) {
	echo "no";
} else {
	echo 'yes';

	$correct = $correct->fetch_array(MYSQLI_ASSOC);
	$password = $correct['password'];

	$message = "<b><a href='http://partner.bbt-online.ru/'>Партнерская программа ББТ</a></b><br>".
				"Чтобы сбросить пароль для аккаунта $email перейдите по ссылке:<br>" .
				"http://partner.bbt-online.ru/forgot_password.php?reset={$email}_".md5($password);
	$headers = 'From: bbt@online.ru' . "\r\n" .
	    'Reply-To: bbt@online.ru' . "\r\n" .
	    'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();
	mail($email, 'Сброс пароля', $message, $headers);
}

mysqli_close($dbc);