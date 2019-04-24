<?php
require '../db.php';
require '../db_shop.php';
require '../php/access.php';
require '../connect_templates.php';

if (!access(intval($_POST['user_id']), $dbc))
	exit('отказано в доступе');

$user_id = intval($_POST['user_id']);
$sort = $_POST['sortType'];
$rows = intval($_POST['rows_size']);
$period = $_POST['period'];
$role = $_POST['role'];
$format = $_POST['format'] ? $_POST['format'] : 'all';
$where = 'WHERE '.$period . ($format == 'all' ? '' : " AND `format` = '$format'");

if ($_POST['search'] == '')
	$books = $dbc_shop->query("SELECT * FROM `wp_watched` $where");
else
	$books = $dbc_shop->query("SELECT * FROM `wp_watched` $where AND (`name` LIKE '%{$_POST['search']}%' OR `author` LIKE '%{$_POST['search']}%')");

$table = $_POST['table'];
if (($_POST['format'] == 'audio' && $_POST['get_table'] != 'views.audio') ||
	($_POST['format'] == 'digital' && $_POST['get_table'] != 'views.digital'))
	$_POST['page'] = 1;

require '../php/get_analitic_views_list.php';

for ($i=$offset; $i < $limit && $i < count($array); $i++)
	analitic_views_tr($array[$i]);
?>
===================================================================================================
<?php
$page_file_name = 'analitic.php';
if ($format == 'digital')
	$table_prefix = '&table=views.digital';
elseif ($format == 'audio')
	$table_prefix = '&table=views.audio';

require '../php/pagination.php';
?>