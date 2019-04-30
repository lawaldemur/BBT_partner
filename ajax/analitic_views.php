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

$db_shop->set_table('wp_watched');
if ($_POST['search'] == '') {
	$db_shop->set_where(['date' => $period] + ($format == 'all' ? [] : ['format' => $format]));
	$books = $db_shop->select(($format == 'all' ? '' : 's'));
} else {
	$where = ['date' => $period] + ($format == 'all' ? [] : ['format' => $format]);
	$where += ['like' => ["(`name` LIKE ? OR `author` LIKE ?)", '%'.$_POST['search'].'%']];
	$db_shop->set_where($where);
	$books = $db_shop->select(($format == 'all' ? '' : 's') . 'ss');
}

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