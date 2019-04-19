<?php
require '../db.php';
require '../db_shop.php';
require '../connect_templates.php';

$sort = $_POST['sortType'];
$rows = intval($_POST['rows_size']);
$period = $_POST['period'];
$role = $_POST['role'];
$format = $_POST['format'] ? $_POST['format'] : 'all';
$where = 'WHERE '.$period . ($format == 'all' ? '' : " AND `format` = '$format'");

require '../php/access.php';

if ($role == 'ББТ') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where");
} elseif ($role == 'Команда') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_command_id` = $user_id");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_command_id` = $user_id");
} elseif ($role == 'Партнер') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_partner_id` = $user_id");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_partner_id` = $user_id");
}

$table = $_POST['table'];
if ($table == 'all' && $_POST['get_table'] != '') $_POST['page'] = 1;
elseif ($table == 'books' && $_POST['format'] == 'digital' && $_POST['get_table'] != 'digital') $_POST['page'] = 1;
elseif ($table == 'books' && $_POST['format'] == 'audio' && $_POST['get_table'] != 'audio') $_POST['page'] = 1;
$_GET['page'] = $_POST['page'];


require '../php/get_analitic_books_list.php';

for ($i=$offset; $i < $limit && $i < count($array); $i++)
	analitic_books_tr($sort, $array[$i], $role);
?>
===================================================================================================
<?php
$page_file_name = 'analitic.php';
if ($table == 'books' && $format == 'digital')
	$table_prefix = '&table=digital';
elseif ($table == 'books' && $format == 'audio')
	$table_prefix = '&table=audio';

require '../php/pagination.php';
