<?php
include '../db.php';
require '../php/access.php';
require '../connect_templates.php';

if (!access(1, $dbc))
	exit('отказано в доступе');

require '../php/get_resort_earn_table.php';

if (count($array) > 0)
for ($i=$offset; $i < $limit && $i < count($array); $i++)
	finance_earn_table_tr($array[$i]['text_date'], $array[$i]['dogovor'], $array[$i]['bonus'], $array[$i]['total']);
?>
=====================================
<?php 
$page_file_name = 'finance.php';
$add_class = 'earn_pagination_list';
$page_class = 'earn_page';

require '../php/pagination.php';
