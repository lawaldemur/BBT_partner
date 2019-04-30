<?php
require '../db.php';
require '../php/access.php';
require '../connect_templates.php';

if (!access(intval($_POST['id']), $db))
	exit('отказано в доступе');

require '../php/get_finance_from_partners.php';

for ($i=$offset; $i < $limit && $i < count($array); $i++)
	finance_from_tr($array[$i]);
?>
////////=============////////
<?php
$page_file_name = 'finance.php';
$table_prefix = '&viewpartners='.$_POST['id'];

require '../php/pagination.php';
