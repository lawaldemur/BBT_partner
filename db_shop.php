<?php
require_once 'php/db.php';

$cfg = parse_ini_file('/home/h809274500/partner.bbt-online.ru/php/secure.ini');
$db_shop = new db($cfg['SERVER_NAME_SHOP'], $cfg['LOGIN_SHOP'], $cfg['PASSWORD_SHOP'], $cfg['DATABASE_SHOP']);
