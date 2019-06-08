<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['user_id']), $db))
	exit('отказано в доступе');

$partner_id = intval($_POST['partner_id']);
$partner_name = str_replace('\'', '', $_POST['partner_name']);
$partner_region = str_replace('\'', '', $_POST['partner_region']);
$get_audio = intval(str_replace('\'', '', $_POST['get_audio']));
$get_digital = intval(str_replace('\'', '', $_POST['get_digital']));
$partner_email = str_replace('\'', '', $_POST['partner_email']);

if ($get_audio < 0)
	$get_audio = 0;
elseif ($get_audio > 100)
	$get_audio = 100;

if ($get_digital < 0)
	$get_digital = 0;
elseif ($get_digital > 100)
	$get_digital = 100;

$db->set_table('users');
$db->set_update([
	'login' => $partner_email,
	'audio_percent' => $get_audio,
	'digital_percent' => $get_audio,
	'name' => $partner_name,
	'city' => $partner_region,
]);
$db->set_where(['id' => $partner_id]);
$db->update('siissi');
