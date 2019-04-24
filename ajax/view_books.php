<?php
require '../db.php';
require '../db_shop.php';
require '../php/access.php';

if (!access(intval($_POST['user_id']), $dbc))
	exit('отказано в доступе');

require '../php/get_view_books.php';

for ($i=$offset; $i < $limit && $i < count($array); $i++)
	analitic_books_tr($sort, $array[$i], $role == 'command' ? 'Команда' : 'Партнер');
?>
===================================================================================================
<?php
$page_file_name = 'view.php';
$table_prefix = '&id='.$user_id;

require '../php/pagination.php';
