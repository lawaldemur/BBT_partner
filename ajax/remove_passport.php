<?php
require '../db.php';
require '../php/access.php';

if (!access(intval($_POST['id']), $db))
	exit('отказано в доступе');

$id = $_POST['id'];
$img = $_POST['passport_img'];
// get data
$db->set_table('users');
$db->set_where(['id' => $id]);
$data = $db->select('i')->fetch_array(MYSQLI_ASSOC);
// decode to array
$data = json_decode($data['data'], true);
// append to passport item
unset($data['passport'][array_search($img, $data['passport'])]);
// encode back to json
$data = json_encode($data, JSON_UNESCAPED_UNICODE);
// send to db
$db->set_update(['data' => $data]);
$db->update('si');

