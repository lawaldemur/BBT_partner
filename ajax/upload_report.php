<?php
require '../db.php';
require '../php/access.php';

$purpose = $_GET['purpose'] == 2 ? 'report' : 'act';
$id = intval($_GET['id']);
$date = date("m.y", strtotime($_GET['date']));

if (!access($id, $db))
	exit('отказано в доступе');

move_uploaded_file($_FILES['file']['tmp_name'], '/home/h809274500/partner.bbt-online.ru/docs/service'.'/'.$purpose.'s/'.$date.'_done/'.$_FILES['file']['name']);

$db->set_table('reports');
$db->set_update([$purpose.'_done' => $_FILES['file']['name']]);
$db->set_where(['id' => $id]);
$db->update('si');

echo 'SUCCESS';
exit();
