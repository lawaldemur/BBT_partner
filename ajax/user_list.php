<?php
require '../db.php';
require '../db_shop.php';
require '../connect_templates.php';
require '../php/access.php';

if (!access(intval($_POST['user_id']), $db))
	exit('отказано в доступе');

$search = $_POST['search'] != '' ? $_POST['search'] : '';
$_GET['page'] = $_POST['page'];
$period = $_POST['period'];

// get from db
$db->set_table('users');
$db->set_where(['position' => $_POST['table']] + ($_POST['command_partners'] == '' ? [] : ['parent' => $_POST['command_partners']]));
$commands_array = $db->select('s' . ($_POST['command_partners'] == '' ? '' : 'i'));


if ($_POST['table'] == 'command') {
	require '../php/get_commands_list.php';
	for ($i=$offset; $i < $limit && $i < count($array); $i++)
		commands_tbody_tr($array[$i]);
} else {
	$partners_array = $commands_array;
	require '../php/get_partners_list.php';
	for ($i=$offset; $i < $limit && $i < count($array); $i++)
		partners_tbody_tr($array[$i], $_POST['role']);
}
?>
===================================================================================================
<?php
$page_file_name = $_POST['table'] == 'command' ? 'commands.php' : 'partners.php';
include '../php/pagination.php';
?>
===================================================================================================
<?php echo $_POST['token']; ?>