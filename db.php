<?php
require_once 'php/db.php';
require 'php/hash_password.php';

$cfg = parse_ini_file('/home/h809274500/partner.bbt-online.ru/php/secure.ini');
$db = new db($cfg['SERVER_NAME'], $cfg['LOGIN'], $cfg['PASSWORD'], $cfg['DATABASE']);


if (strpos($_POST['period'], 'BETWEEN') !== false)
	$_POST['period'] = substr($_POST['period'], 0, 21).'\''.substr($_POST['period'], 21, 10).'\''.substr($_POST['period'], 31, 5).'\''.substr($_POST['period'], 36, 10).'\'';
