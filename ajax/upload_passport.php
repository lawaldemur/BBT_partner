<?php
require '../db.php';
require '../php/access.php';

if (0 < $_FILES['file']['error'] ) {
	echo 'Error';
	exit();
}
if (!isset($_FILES['file'])) {
	echo "file not found";
	exit();
}

$id = intval($_GET['id']);

if (!access($id, $dbc))
	exit('отказано в доступе');

$format = explode('.', $_FILES['file']['name']);
$format = $format[count($format) - 1];

if ($format != 'jpg' && $format != 'png') {
	echo "Недопустимый формат";
	exit();
}

$file_name = time() . '.' . $format;
move_uploaded_file($_FILES['file']['tmp_name'], '../service/passports/' . $file_name);

echo $file_name;

// get data
$data = $dbc->query("SELECT * FROM `users` WHERE `id` = $id");
$data = $data->fetch_array(MYSQLI_ASSOC);
// decode to array
$data = json_decode($data['data'], true);
// append to passport item
$data['passport'][] = $file_name;
// encode back to json
$data = json_encode($data, JSON_UNESCAPED_UNICODE);
// send to db
$dbc->query("UPDATE `users` SET `data` = '$data' WHERE `id` = $id");

mysqli_close($dbc);
