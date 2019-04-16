<?php
require '../db.php';
require '../db_shop.php';
require '../connect_templates.php';
// require './php/access.php';

$period = $_POST['period'];
$role = $_POST['role'];
$user_id = intval($_POST['parent']);
$search = $_POST['search'];
$_GET['page'] = intval($_POST['page']);


require '../php/get_clients_list.php';


for ($i=$offset; $i < $limit && $i < count($array); $i++)
	clients_tbody_tr($array[$i]);
?>
===================================================================================================
<?php include '../php/pagination.php'; ?>
===================================================================================================
<?php echo $_POST['token']; ?>