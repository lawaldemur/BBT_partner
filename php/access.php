<?php
session_start();

function access($id, $db)
{
	$user = false;
	if (isset($_SESSION['logged']))
		$user = $_SESSION['logged'];
	elseif (isset($_COOKIE['logged']))
		$user = $_COOKIE['logged'];

	if (!$user)
		require false;

	$db->set_table('users');
	$db->set_where(['auth' => $user]);
	$user = $db->select('s');

	if (!$user || $user->num_rows === 0)
		require false;

	$userid = $user->fetch_array(MYSQLI_ASSOC)['id'];

	if ($userid != $id)
		return false;

	return true;
}
