<?php
require '../db.php';

$id = $_POST['id'];
$accepted_report = (int) ($_POST['accepted_report'] == 'true' ? 1 : 0);
$accepted_paid = (int) ($_POST['accepted_paid'] == 'true' ? 1 : 0);

$dbc->query("UPDATE `reports` SET `accepted` = $accepted_report, `paid` = $accepted_paid WHERE `id` = $id");
