<?php
require '../db.php';

$id = $_POST['id'];

if ($_POST['status'] == 'ББТ')
	$data = array(
		'general_name' => $_POST['general_name'],
		'general_address' => $_POST['general_address'],
		'general_phone' => $_POST['general_phone'],
		'general_email' => $_POST['general_email'],
		'general_ogrn' => $_POST['general_ogrn'],
		'general_inn_kpp' => $_POST['general_inn_kpp'],
		'bank_name' => $_POST['bank_name'],
		'bank_bill' => $_POST['bank_bill'],
		'bank_chet' => $_POST['bank_chet'],
		'bank_bik' => $_POST['bank_bik'],
		'organizator_name' => $_POST['organizator_name'],
		'organizator_position' => $_POST['organizator_position'],
		'organizator_phone' => $_POST['organizator_phone'],
		'organizator_email' => $_POST['organizator_email'],
		'accountant_name' => $_POST['accountant_name'],
		'accountant_phone' => $_POST['accountant_phone'],
		'accountant_email' => $_POST['accountant_email'],
		'manager_name' => $_POST['manager_name'],
		'manager_phone' => $_POST['manager_phone'],
		'manager_email' => $_POST['manager_email']
	);
elseif ($_POST['status'] == 'Команда')
	$data = array(
		'general_short_name' => $_POST['general_short_name'],
		'general_name' => $_POST['general_name'],
		'general_address' => $_POST['general_address'],
		'general_phone' => $_POST['general_phone'],
		'general_email' => $_POST['general_email'],
		'general_ogrn' => $_POST['general_ogrn'],
		'general_inn_kpp' => $_POST['general_inn_kpp'],
		'dogovor_number' => $_POST['dogovor_number'],
		'dogovor_date' => $_POST['dogovor_date'],
		'bank_name' => $_POST['bank_name'],
		'bank_bill' => $_POST['bank_bill'],
		'bank_chet' => $_POST['bank_chet'],
		'bank_bik' => $_POST['bank_bik'],
		'organizator_name' => $_POST['organizator_name'],
		'organizator_position' => $_POST['organizator_position'],
		'organizator_phone' => $_POST['organizator_phone'],
		'organizator_email' => $_POST['organizator_email'],
		'accountant_name' => $_POST['accountant_name'],
		'accountant_phone' => $_POST['accountant_phone'],
		'accountant_email' => $_POST['accountant_email'],
		'manager_name' => $_POST['manager_name'],
		'manager_phone' => $_POST['manager_phone'],
		'manager_email' => $_POST['manager_email']
	);
elseif ($_POST['status'] == 'Партнер') {
	$passport = $dbc->query("SELECT * FROM `users` WHERE `id` = $id");
	$passport = $passport->fetch_array(MYSQLI_ASSOC);
	$passport = json_decode($passport['data'], true)['passport'];
	$data = array(
		'general_name' => $_POST['general_name'],
		'general_soul_name' => $_POST['general_soul_name'],
		'general_address' => $_POST['general_address'],
		'contact_phone' => $_POST['contact_phone'],
		'contact_email' => $_POST['contact_email'],
		'pasport_seria' => $_POST['pasport_seria'],
		'pasport_number' => $_POST['pasport_number'],
		'pasport_date' => $_POST['pasport_date'],
		'pasport_gave' => $_POST['pasport_gave'],
		'bank_name' => $_POST['bank_name'],
		'bank_bill' => $_POST['bank_bill'],
		'bank_chet' => $_POST['bank_chet'],
		'bank_bik' => $_POST['bank_bik'],
		'other_inn' => $_POST['other_inn'],
		'other_snils' => $_POST['other_snils'],
		'passport' => $passport
	);
}

$data = json_encode($data, JSON_UNESCAPED_UNICODE);

if ($_POST['status'] == 'Команда')
	$dbc->query("UPDATE `users` SET `data` = '$data' WHERE `id` = $id");
elseif ($_POST['status'] == 'Партнер')
	$dbc->query("UPDATE `users` SET `data` = '$data' WHERE `id` = $id");
else
	$dbc->query("UPDATE `users` SET `data` = '$data' WHERE `id` = $id");

mysqli_close($dbc);