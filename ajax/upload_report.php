<?php
$purpose = $_GET['purpose'] == 2 ? 'report' : 'act';
$id = $_GET['id'];
$date = date("m.y", strtotime($_GET['date']));


move_uploaded_file($_FILES['file']['tmp_name'], '/home/h809274500/partner.bbt-online.ru/docs/service'.'/'.$purpose.'s/'.$date.'_done/'.$_FILES['file']['name']);


require '../db.php';
$dbc->set_charset("utf8");

$query = $dbc->query("UPDATE `reports` SET `{$purpose}_done` = '{$_FILES['file']['name']}' WHERE `id` = $id");
	
if ($query == true)
	echo 'SUCCESS';
else
	echo "error save document";

mysqli_close($dbc);
exit();


