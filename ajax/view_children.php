<?php
require '../db.php';
require '../db_shop.php';
require '../php/access.php';
require '../connect_templates.php';

$sort = $_POST['sortType'];
$period = $_POST['period'];
$role = $_POST['role'];
$user_id = $_POST['user_id'];

require '../php/get_view_children.php';

if ($role == 'command')
	for ($i=$offset; $i < $limit && $i < count($array); $i++)
		view_children_partners_tr($array[$i]);
else
	for ($i=$offset; $i < $limit && $i < count($array); $i++)
		view_children_clients_tr($array[$i]);

?>
===================================================================================================
<?php
$page_file_name = 'view.php';
$table_prefix = '&id='.$user_id.'&table=children';

require '../php/pagination.php';
