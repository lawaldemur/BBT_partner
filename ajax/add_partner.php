<?php
require '../db.php';
require '../db_shop.php';
require '../php/access.php';
require '../crypt.php';

if (!access($_POST['id'], $db))
	exit('отказано в доступе');

$name = $_POST['name'];
$region = $_POST['region'];
$get_digital = intval($_POST['get_digital']);
$get_audio = intval($_POST['get_audio']);

if ($get_audio < 0)
	$get_audio = 0;
elseif ($get_audio > 100)
	$get_audio = 100;
if ($get_digital < 0)
	$get_digital = 0;
elseif ($get_digital > 100)
	$get_digital = 100;

$email = $_POST['email'];
// definite parent
$db->set_table('users');
$db->set_where(['id' => intval($_POST['id'])]);
$correct = $db->select('i')->fetch_array(MYSQLI_ASSOC);

$parent_array = $correct;
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

	$db->set_table('users');
	$db->set_where(['code' => $code]);
	$already = $db->select('s');

	$db_shop->set_table('wp_users');
	$db_shop->set_where(['code' => $code]);
	$already2 = $db_shop->select('s');

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
$db->set_table('users');

$db->set_insert([
	'login' => $email,
	'position' => 'partner',
	'code' => $code,
	'parent' => $parent,
	'audio_percent' => $get_audio,
	'digital_percent' => $get_digital,
	'name' => $name,
	'city' => $region,
]);
$db->insert('sssiiiss');

$db->set_where(['login' => $email, 'position' => 'partner', 'name' => $name]);
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
