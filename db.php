<?php
$dbc = mysqli_connect('h809274500.mysql','h809274500_dev','Bbtonline1','h809274500_partner') or die('no db');
$dbc->set_charset("utf8");

// prevent sql injection
$_POST['period'] = str_replace('\'', '', $_POST['period']);
foreach ($_POST as $key => $value) {
	$_POST[$key] = str_replace(';', '', $dbc->real_escape_string($value));
}
foreach ($_GET as $key => $value) {
	$_GET[$key] = str_replace(';', '', $dbc->real_escape_string($value));
}

if (strpos($_POST['period'], 'BETWEEN') !== false)
	$_POST['period'] = substr($_POST['period'], 0, 21).'\''.substr($_POST['period'], 21, 10).'\''.substr($_POST['period'], 31, 5).'\''.substr($_POST['period'], 36, 10).'\'';

include 'php/hash_password.php';
?>