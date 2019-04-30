<?php
require '../db.php';
require '../db_shop.php';
require '../php/access.php';
require '../connect_templates.php';

if (!access(intval($_POST['user_id']), $db))
	exit('отказано в доступе');

$user_id = intval($_POST['user_id']);
$sort = $_POST['sortType'];
$rows = intval($_POST['rows_size']);
$period = $_POST['period'];
$role = $_POST['role'];
$format = $_POST['format'] ? $_POST['format'] : 'all';
$types = '';

$where = ['date' => $period];
if ($format != 'all') {
	$where['format'] = $format;
	$types .= 's';
}

if ($role == 'Команда') {
	$where['to_command_id'] = $user_id;
	$types .= 'i';
}
elseif ($role == 'Партнер') {
	$where['to_partner_id'] = $user_id;
	$types .= 'i';
}

$db->set_where($where);
$db->set_table($sort == 'bydate' ? 'analitic' : 'analitic_bybook');
$books = $db->select($types);

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
