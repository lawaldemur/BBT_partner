<?php
require '../db.php';
require '../db_shop.php';
require '../connect_templates.php';
// require './php/access.php';

$search = $_POST['search'] != '' ? $_POST['search'] : '';
$period = $_POST['period'];

if ($_POST['command_partners'] == '')
	$commands_array = $dbc->query("SELECT * FROM `users` WHERE `position` = '{$_POST['table']}'");
else
	$commands_array = $dbc->query("SELECT * FROM `users` WHERE `position` = '{$_POST['table']}' AND `parent` = {$_POST['command_partners']}");


if ($_POST['table'] == 'command') {
	require '../php/get_commands_list.php';
	for ($i=$offset; $i < $limit && $i < count($array); $i++)
		commands_tbody_tr($array[$i]);
} else {
	$partners_array = $commands_array;
	require '../php/get_partners_list.php';
	for ($i=$offset; $i < $limit && $i < count($array); $i++)
		partners_tbody_tr($array[$i]);
}
?>
===================================================================================================
<?php include '../php/pagination.php'; ?>
===================================================================================================
<?php echo $_POST['token']; ?>