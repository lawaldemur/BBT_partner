<?php
session_start();

function access($id, $dbc)
{
	$user = false;
	if (isset($_SESSION['logged']))
		$user = $_SESSION['logged'];
	elseif (isset($_COOKIE['logged']))
		$user = $_COOKIE['logged'];

	if (!$user)
		require false;

	$user = str_replace(';', '', $dbc->real_escape_string($user));
	$user = $dbc->query("SELECT * FROM `users` WHERE `auth` = '$user'");

	if (!$user || $user->num_rows === 0)
		require false;

	$userid = $user->fetch_array(MYSQLI_ASSOC)['id'];

	if ($userid != $id)
		return false;

	return true;
}
