<?php
require_once './db.php';

if (isset($_GET['reset'])) {
	$reset = explode('_', $_GET['reset']);

	$db->set_where(['login' => $reset[0]]);
	$db->set_table('users');
	$correct = $db->select('s')->fetch_array(MYSQLI_ASSOC);

	if ($reset[1] != md5($correct['auth'])) {
		header('Location: '.$pages['entrance']);
		exit();
	}
}

include 'header.php';


