<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['to']), $db))
	exit('отказано в доступе');

$id = intval($_POST['id']);
$accepted_report = $_POST['accepted_report'] == 'true' ? 1 : 0;
$accepted_paid = $_POST['accepted_paid'] == 'true' ? 1 : 0;

$db->set_table('reports');
$db->set_update(['accepted' => $accepted_report, 'paid' => $accepted_paid]);
$db->set_where(['id' => $id]);
$db->update('iii');
