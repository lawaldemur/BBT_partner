<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['id']), $dbc))
	exit('отказано в доступе');

$id = $_POST['id'];
$img = $_POST['passport_img'];
// get data
$data = $dbc->query("SELECT * FROM `users` WHERE `id` = $id");
$data = $data->fetch_array(MYSQLI_ASSOC);
// decode to array
$data = json_decode($data['data'], true);
// append to passport item
unset($data['passport'][array_search($img, $data['passport'])]);
// encode back to json
$data = json_encode($data, JSON_UNESCAPED_UNICODE);
// send to db
$dbc->query("UPDATE `users` SET `data` = '$data' WHERE `id` = $id");

mysqli_close($dbc);
