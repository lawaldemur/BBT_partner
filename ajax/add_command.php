<?php
require '../db.php';
require '../php/access.php';
require '../crypt.php';

if (!access(intval($_POST['id']), $db))
	exit('отказано в доступе');

// check password
$pass = '';
$db->set_table('users');
$db->set_where(['id' => intval($_POST['id'])]);
$auth = $db->select('i')->fetch_array(MYSQLI_ASSOC)['auth'];

$db->set_table('passwords');
$db->set_where([]);
$passwords = $db->select();
if ($passwords)
foreach ($passwords as $passw) {
	if (password_verify($auth, $passw['id']))
		$pass = $passw['password'];
}
$pass = mc_decrypt($pass, SECRET_KEY);
// if user not found then exit
if ($pass != $_POST['request_pass'] || strlen($_POST['request_pass']) == 0)
	exit('incorrect password');


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
$db->set_table('users');

$db->set_insert([
	'login' => $email,
	'position' => 'command',
	'parent' => 1,
	'audio_percent' => $get_audio,
	'digital_percent' => $get_digital,
	'name' => $name,
	'city' => $region,
]);
$db->insert('ssiiiss');

$db->set_where(['login' => $email, 'position' => 'command', 'name' => $name]);
$id = $db->select('sss')->fetch_array(MYSQLI_ASSOC)['id'];

$auth = get_hash_password($id, $pass);
$db->set_update(['auth' => $auth]);
$db->set_where(['id' => $id]);
$db->update('si');

$id = password_hash($auth, PASSWORD_DEFAULT);
$password = mc_encrypt($pass, SECRET_KEY);

$db->set_table('passwords');
$db->set_insert([
	'id' => $id,
	'password' => $password,
]);
$db->insert('ss');

echo true;
