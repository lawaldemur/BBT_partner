<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['to']), $dbc))
	exit('отказано в доступе');

$id = $_POST['id'];
$accepted_report = (int) ($_POST['accepted_report'] == 'true' ? 1 : 0);
$accepted_paid = (int) ($_POST['accepted_paid'] == 'true' ? 1 : 0);

$dbc->query("UPDATE `reports` SET `accepted` = $accepted_report, `paid` = $accepted_paid WHERE `id` = $id");
