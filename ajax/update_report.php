<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['to']), $db))
	exit('отказано в доступе');

$id = $_POST['id'];
$accepted_report = (int) ($_POST['accepted_report'] == 'true' ? 1 : 0);
$accepted_paid = (int) ($_POST['accepted_paid'] == 'true' ? 1 : 0);

$db->set_table('reports');
$db->set_where(['id' => $id]);
$db->set_update(['accepted' => $accepted_report, 'paid' => $accepted_paid]);
$db->update('iii');
