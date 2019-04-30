<?php
include 'header.php';

$db->set_where(['id' => $user_id]);
$db->set_table('users');
$data = $db->select('i')->fetch_array(MYSQLI_ASSOC);

$picture = $data['picture'];
$name = $data['name'];
if ($role == 'Партнер') {
	$code = $data['code'];
	$digit_perc = $data['digital_percent'];
	$audio_perc = $data['audio_percent'];
} elseif ($role == 'Команда') {
	$digit_perc = $data['digital_percent'];
	$audio_perc = $data['audio_percent'];
}
$data = json_decode($data['data'], true);
