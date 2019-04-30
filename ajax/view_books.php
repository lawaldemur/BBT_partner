<?php
require '../db.php';
require '../db_shop.php';
require '../php/access.php';
require '../connect_templates.php';

$sort = $_POST['sortType'];
$period = $_POST['period'];
$role = $_POST['role'];
$id = intval($_POST['user_id']);
$format = $_POST['format'] ? $_POST['format'] : 'all';
$page = intval($_POST['page']);
$rows = intval($_POST['rows_size']);

require '../php/get_view_books.php';

if ($role == 'command')
	$role = 'Команда';
elseif ($role == 'partner')
	$role = 'Партнер';
elseif ($role == 'client')
	$role = 'Клиент';

for ($i=$offset; $i < $limit && $i < count($array); $i++)
	analitic_books_tr($sort, $array[$i], $role);
?>
===================================================================================================
<?php
$page_file_name = 'view.php';
$table_prefix = '&id='.$user_id;

require '../php/pagination.php';
