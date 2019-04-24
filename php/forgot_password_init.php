<?php
require_once './db.php';

if (isset($_GET['reset'])) {
	$reset = explode('_', $_GET['reset']);

	$correct = $dbc->query("SELECT * FROM users WHERE login = '{$reset[0]}'");
	$correct = $correct->fetch_array(MYSQLI_ASSOC);

	if ($reset[1] != md5($correct['auth'])) {
		header('Location: '.$pages['entrance']);
		exit();
	}
}

include 'header.php';


