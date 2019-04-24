<?php
require '../db.php';
require '../db_shop.php';
require '../php/access.php';
require '../crypt.php';

if (!access($_POST['id'], $dbc))
	exit('отказано в доступе');

// get user (parent)
$correct = $dbc->query("SELECT * FROM `users` WHERE `id` = '".$_POST['id']."'");

// pass correct, let's continue
$name = $_POST['name'];
$region = $_POST['region'];
$get_digital = $_POST['get_digital'];
$get_audio = $_POST['get_audio'];
$email = $_POST['email'];
// definite parent
$parent_array = $correct->fetch_array(MYSQLI_ASSOC);
$parent = $parent_array['id'];
$parent_name = $parent_array['name'];

// generate password
require '../php/generate_pass.php';
$pass = generatePassword();
// generate code
require '../php/generate_code.php';
$new_code = true;
do {
	$code = generateCode();

	$already = $dbc->query("SELECT * FROM `users` WHERE `code` = '$code'");
	$already2 = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `code` = '$code'");
	if ($already->num_rows === 0 && $already2->num_rows === 0)
		$new_code = false;
} while ($new_code);

// send email to partner
$message = "<b><a href='http://partner.bbt-online.ru/'>Партнерская программа ББТ</a></b><br>".
			"Команда \"$parent_name\" добавила вас в список своих партнеров<br>" .
			"Вы \"$name\"<br>" .
			"Из населеного пункта: $region<br>" .
			"Ваш логин: $email<br>" .
			"Ваш пароль: $pass<br>" .
			"Ваш партнерский код: $code<br>" .
			"Ссылка: <a href='http://bbt-online.ru/promo$code'>bbt-online.ru/promo$code</a>";
$headers = 'From: bbt@online.ru' . "\r\n" .
    'Reply-To: bbt@online.ru' . "\r\n" .
    'Content-type: text/html; charset=iso-8859-1' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($email, 'Вы новый партнер в партнерской программе ББТ', $message, $headers);

// add partner to database
$result = $dbc->query("INSERT INTO `users` (`login`, `position`, `code`, `parent`, `audio_percent`, `digital_percent`, `name`, `city`) VALUES ('$email', 'partner', '$code', $parent, $get_audio, $get_digital, '$name', '$region')");

$id = $dbc->query("SELECT * FROM `users` WHERE `login` = '$email' && `position` = 'partner'");
$id = $id->fetch_array(MYSQLI_ASSOC)['id'];

$auth = get_hash_password($id, $pass);
$result = $dbc->query("UPDATE `users` SET `auth` = '$auth' WHERE `id` = $id");

$id = password_hash($auth, PASSWORD_DEFAULT);
$password = mc_encrypt($pass, SECRET_KEY);
$dbc->query("INSERT INTO `passwords` (`id`, `password`) VALUES ('$id', '$password')");



echo $result;

mysqli_close($dbc);
mysqli_close($dbc_shop);