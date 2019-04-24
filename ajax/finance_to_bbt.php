<?php
require '../db.php';
require '../php/access.php';
require '../connect_templates.php';

if (!access(intval($_POST['id']), $dbc))
	exit('отказано в доступе');

require '../php/get_finance_to_bbt.php';

for ($i=$offset; $i < $limit && $i < count($array); $i++)
	finance_to_bbt_tr($array[$i], $months);
?>
////////=============////////
<?php 
$page_file_name = 'finance.php';
$table_prefix = '&viewbbt='.$_POST['id'];

require '../php/pagination.php';
