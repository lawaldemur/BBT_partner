<?php
session_start();

function access($id, $db)
{
	$id = intval($id);

	$user = false;
	if (isset($_SESSION['logged']))
		$user = $_SESSION['logged'];
	elseif (isset($_COOKIE['logged']))
		$user = $_COOKIE['logged'];

	if (!$user)
		return false;

	$db->set_table('users');
	$db->set_where(['auth' => $user]);
	$user = $db->select('s');

	if (!$user || $user->num_rows === 0)
		return false;

	$userid = $user->fetch_array(MYSQLI_ASSOC)['id'];

	if ($userid != $id)
		return false;

	return true;
}
