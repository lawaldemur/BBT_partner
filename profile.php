<?php include 'header.php'; ?>

<div id="notification">
	<span id="notif_icon">&#10004;</span>
	<span id="notification_text">Данные успешно сохранены</span>
</div>

<?php
$data = $dbc->query("SELECT * FROM `users` WHERE `id` = $user_id");
$data = $data->fetch_array(MYSQLI_ASSOC);
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
?>

<div id="document_viewer">
	<div class="close_document_viewer"><img src="/img/close_document_viewer.svg" alt="close_form" height="18"></div>

	<img src="" alt="document">
</div>

<div class="container">
	<div class="row">
		<div class="col profile_list_col">
			<h1>Профиль</h1>
			<input type="hidden" id="user_id" value="<?=$user_id?>">
			<input type="hidden" id="user_status" value="<?=$role?>">
		</div>
	</div>

	<!-- SETTINGS WRAPPER -->
	<?php if ($role == 'ББТ'): ?>
		<div class="row">
			<div class="col-8">
				<p class="profile_title">Общая информация</p>
				<div class="field full_field">
					<span class="field_desc">Полное наименование</span>
					<input type="text" id="general_name" value="<?php echo $data['general_name']; ?>">
				</div>
				<div class="field full_field">
					<span class="field_desc">Адрес</span>
					<input type="text" id="general_address" value="<?php echo $data['general_address']; ?>">
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Телефон</span>
						<input type="text" id="general_phone" value="<?php echo $data['general_phone']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">Эл. почта</span>
						<input type="text" id="general_email" value="<?php echo $data['general_email']; ?>">
					</div>
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">ОГРН</span>
						<input type="text" id="general_ogrn" value="<?php echo $data['general_ogrn']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">ИНН / КПП</span>
						<input type="text" id="general_inn_kpp" value="<?php echo $data['general_inn_kpp']; ?>">
					</div>
				</div>
			</div>

			<div class="col-8">
				<p class="profile_title">Банковские реквизиты</p>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Наименование банка</span>
						<input type="text" id="bank_name" value="<?php echo $data['bank_name']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">Расчетный счет</span>
						<input type="text" id="bank_bill" value="<?php echo $data['bank_bill']; ?>">
					</div>
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Корреспондентский счет</span>
						<input type="text" id="bank_chet" value="<?php echo $data['bank_chet']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">БИК</span>
						<input type="text" id="bank_bik" value="<?php echo $data['bank_bik']; ?>">
					</div>
				</div>
			</div>

			<div class="col-8">
				<p class="profile_title">Руководитель</p>
				<div class="field full_field">
					<span class="field_desc">Фамилия, Имя, Отчество</span>
					<input type="text" id="organizator_name" value="<?php echo $data['organizator_name']; ?>">
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Должность</span>
						<input type="text" id="organizator_position" value="<?php echo $data['organizator_position']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">Телефон</span>
						<input type="text" id="organizator_phone" value="<?php echo $data['organizator_phone']; ?>">
					</div>
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Эл. почта</span>
						<input type="text" id="organizator_email" value="<?php echo $data['organizator_email']; ?>">
					</div>
				</div>
			</div>

			<div class="col-8">
				<p class="profile_title">Бухгалтер</p>
				<div class="field full_field">
					<span class="field_desc">Фамилия, Имя, Отчество</span>
					<input type="text" id="accountant_name" value="<?php echo $data['accountant_name']; ?>">
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Телефон</span>
						<input type="text" id="accountant_phone" value="<?php echo $data['accountant_phone']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">Эл. почта</span>
						<input type="text" id="accountant_email" value="<?php echo $data['accountant_email']; ?>">
					</div>
				</div>
			</div>

			<div class="col-8">
				<p class="profile_title">Менеджер проекта</p>
				<div class="field full_field">
					<span class="field_desc">Фамилия, Имя, Отчество</span>
					<input type="text" id="manager_name" value="<?php echo $data['manager_name']; ?>">
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Телефон</span>
						<input type="text" id="manager_phone" value="<?php echo $data['manager_phone']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">Эл. почта</span>
						<input type="text" id="manager_email" value="<?php echo $data['manager_email']; ?>">
					</div>
				</div>
			</div>

			<div class="col-12">
				<button id="profile_submit" class="default_btn" disabled="disabled">Сохранить</button>
			</div>

		</div>
	<?php elseif($role == 'Команда'): ?>
		<div class="row">
			<div class="col-8">
				<p class="profile_title">Общая информация</p>
				<div class="field full_field">
					<span class="field_desc">Короткое название (будет отображаться в общем списке всех команда)</span>
					<input type="text" id="general_short_name" value="<?php echo $name; ?>">
				</div>
				<div class="field full_field">
					<span class="field_desc">Полное наименование (указанное при регистрации организация)</span>
					<input type="text" id="general_name" value="<?php echo $data['general_name']; ?>">
				</div>
				<div class="field full_field">
					<span class="field_desc">Адрес</span>
					<input type="text" id="general_address" value="<?php echo $data['general_address']; ?>">
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Телефон</span>
						<input type="text" id="general_phone" value="<?php echo $data['general_phone']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">Эл. почта</span>
						<input type="text" id="general_email" value="<?php echo $data['general_email']; ?>">
					</div>
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">ОГРН</span>
						<input type="text" id="general_ogrn" value="<?php echo $data['general_ogrn']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">ИНН / КПП</span>
						<input type="text" id="general_inn_kpp" value="<?php echo $data['general_inn_kpp']; ?>">
					</div>
				</div>
				<div class="two_field">
					<div class="field block_field field_50">
						<span class="field_desc">Процент с цифровых книг</span>
						<div class="block_input" id="block_digital"><?=$digit_perc?></div>
					</div>
					<div class="field block_field field_50">
						<span class="field_desc">Процент с аудиокниг</span>
						<div class="block_input" id="block_audio"><?=$audio_perc?></div>
					</div>
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Номер договора</span>
						<input type="text" id="dogovor_number" value="<?php echo $data['dogovor_number']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">Дата договора</span>
						<input type="text" id="dogovor_date" value="<?php echo $data['dogovor_date']; ?>">
					</div>
				</div>
			</div>

			<!-- picture -->
			<div class="col-2 offset-2 picture_col">
				<label for="upload_picture" style="background-image: url(/avatars/<?=$picture?>);">
					<?php if ($picture == 'avatar.png'): ?>
					<span class="upload_picture_overlay" data-task="upload_photo">
					<?php else: ?>
					<span class="upload_picture_overlay" data-task="replace_photo">
					<?php endif ?>
					
						<img src="/img/add_photo.svg" alt="add photo" class="upload_picture_overlay_plus">
						<span class="upload_picture_overlay_text">Добавить<br>фото</span>
					
						<span class="has_photo_line">
							<img src="/img/replace_photo.svg" alt="replace photo" height="18">
							<span>Заменить</span>
						</span>
						<span class="has_photo_line remove_photo">
							<img src="/img/remove_photo.svg" alt="remove photo" height="13">
							<span>Удалить</span>
						</span>
					</span>
				</label>
				<input type="file" id="upload_picture">
			</div>

			<div class="col-8">
				<p class="profile_title">Банковские реквизиты</p>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Наименование банка</span>
						<input type="text" id="bank_name" value="<?php echo $data['bank_name']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">Расчетный счет</span>
						<input type="text" id="bank_bill" value="<?php echo $data['bank_bill']; ?>">
					</div>
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Корреспондентский счет</span>
						<input type="text" id="bank_chet" value="<?php echo $data['bank_chet']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">БИК</span>
						<input type="text" id="bank_bik" value="<?php echo $data['bank_bik']; ?>">
					</div>
				</div>
			</div>

			<div class="col-8">
				<p class="profile_title">Руководитель</p>
				<div class="field full_field">
					<span class="field_desc">Фамилия, Имя, Отчество</span>
					<input type="text" id="organizator_name" value="<?php echo $data['organizator_name']; ?>">
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Должность</span>
						<input type="text" id="organizator_position" value="<?php echo $data['organizator_position']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">Телефон</span>
						<input type="text" id="organizator_phone" value="<?php echo $data['organizator_phone']; ?>">
					</div>
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Эл. почта</span>
						<input type="text" id="organizator_email" value="<?php echo $data['organizator_email']; ?>">
					</div>
				</div>
			</div>

			<div class="col-8">
				<p class="profile_title">Бухгалтер</p>
				<div class="field full_field">
					<span class="field_desc">Фамилия, Имя, Отчество</span>
					<input type="text" id="accountant_name" value="<?php echo $data['accountant_name']; ?>">
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Телефон</span>
						<input type="text" id="accountant_phone" value="<?php echo $data['accountant_phone']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">Эл. почта</span>
						<input type="text" id="accountant_email" value="<?php echo $data['accountant_email']; ?>">
					</div>
				</div>
			</div>

			<div class="col-8">
				<p class="profile_title">Менеджер проекта</p>
				<div class="field full_field">
					<span class="field_desc">Фамилия, Имя, Отчество</span>
					<input type="text" id="manager_name" value="<?php echo $data['manager_name']; ?>">
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Телефон</span>
						<input type="text" id="manager_phone" value="<?php echo $data['manager_phone']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">Эл. почта</span>
						<input type="text" id="manager_email" value="<?php echo $data['manager_email']; ?>">
					</div>
				</div>
			</div>

			<div class="col-12">
				<button id="profile_submit" class="default_btn" disabled="disabled">Сохранить</button>
			</div>

		</div>
	<?php elseif($role == 'Партнер'): ?>
		<div class="row">
			<div class="col-8">
				<p class="profile_title">Промо-код распространителя</p>
				<div class="partner_code">
					<div class="wrapper_code">
						<div class="code"><?=$code?></div>
						<div class="link">bbt-online.ru/promo<?=$code?></div>
						<input type="hidden" id="code" value="<?=$code?>">
					</div>
					<div class="code_desc">Делитесь кодом или ссылкой с другими и получайте выручку с их покупок :)</div>
				</div>
			</div>

			<!-- picture -->
			<div class="col-2 offset-2 picture_col">
				<label for="upload_picture" style="background-image: url(/avatars/<?=$picture?>);">
					<?php if ($picture == 'avatar.png'): ?>
					<span class="upload_picture_overlay" data-task="upload_photo">
					<?php else: ?>
					<span class="upload_picture_overlay" data-task="replace_photo">
					<?php endif ?>
					
						<img src="/img/add_photo.svg" alt="add photo" class="upload_picture_overlay_plus">
						<span class="upload_picture_overlay_text">Добавить<br>фото</span>
					
						<span class="has_photo_line">
							<img src="/img/replace_photo.svg" alt="replace photo" height="18">
							<span>Заменить</span>
						</span>
						<span class="has_photo_line remove_photo">
							<img src="/img/remove_photo.svg" alt="remove photo" height="13">
							<span>Удалить</span>
						</span>
					</span>
				</label>
				<input type="file" id="upload_picture">
			</div>

			<div class="col-8">
				<p class="profile_title">Общая информация</p>
				<div class="field full_field">
					<span class="field_desc">Фамилия, Имя и Отчество</span>
					<input type="text" id="general_name" value="<?php echo $name; ?>">
				</div>
				<div class="field full_field">
					<span class="field_desc">Духовное имя</span>
					<input type="text" id="general_soul_name" value="<?php echo $data['general_soul_name']; ?>">
				</div>
				<div class="field full_field">
					<span class="field_desc">Адрес</span>
					<input type="text" id="general_address" value="<?php echo $data['general_address']; ?>">
				</div>
				<div class="two_field">
					<div class="field block_field field_50">
						<span class="field_desc">Процент с цифровых книг</span>
						<div class="block_input" id="block_digital"><?=$digit_perc?></div>
					</div>
					<div class="field block_field field_50">
						<span class="field_desc">Процент с аудиокниг</span>
						<div class="block_input" id="block_audio"><?=$audio_perc?></div>
					</div>
				</div>
			</div>

			<div class="col-8">
				<p class="profile_title">Контактные данные</p>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Телефон</span>
						<input type="text" id="contact_phone" value="<?php echo $data['contact_phone']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">Эл. почта</span>
						<input type="text" id="contact_email" value="<?php echo $data['contact_email']; ?>">
					</div>
				</div>
			</div>

			<div class="col-8">
				<p class="profile_title">Паспортные данные</p>
				<div class="triple_field">
					<div class="field seria_field">
						<span class="field_desc">Серия</span>
						<input type="text" id="pasport_seria" value="<?php echo $data['pasport_seria']; ?>">
					</div>
					<div class="field number_field">
						<span class="field_desc">Номер</span>
						<input type="text" id="pasport_number" value="<?php echo $data['pasport_number']; ?>">
					</div>
					<div class="field date_field">
						<span class="field_desc">Дата выдачи</span>
						<input type="text" id="pasport_date" value="<?php echo $data['pasport_date']; ?>">
					</div>					
				</div>
				<div class="field full_field">
					<span class="field_desc">Кем выдан / Код подразделения</span>
					<input type="text" id="pasport_gave" value="<?php echo $data['pasport_gave']; ?>">
				</div>
				<div class="upload_passport_wrapper">
					<div class="upload_passport_wrapper_desc">Фото паспорта (страница личных данных, данные о выдаче и прописка)</div>
					<div class="upload_passport_row">
						<div class="upload_btn">
							<label for="upload_passport">Загрузить</label>
							<input type="file" id="upload_passport">
						</div>
						<div class="upload_passport_files">

							<?php if ($data['passport'])
							foreach ($data['passport'] as $img): ?>
								<div class="passport_file" data-img="<?=$img?>">
									<img src="/img/passport_icon.svg" alt="passport_icon">
									<span>Фото паспорта</span>
									<img src="/img/remove_passport.svg" alt="remove_passport" class="remove_passport">
								</div>
							<? endforeach; ?>

						</div>
					</div>
				</div>
			</div>

			<div class="col-8">
				<p class="profile_title">Банковские реквизиты</p>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Наименование банка</span>
						<input type="text" id="bank_name" value="<?php echo $data['bank_name']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">Расчетный счет</span>
						<input type="text" id="bank_bill" value="<?php echo $data['bank_bill']; ?>">
					</div>
				</div>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">Корреспондентский счет</span>
						<input type="text" id="bank_chet" value="<?php echo $data['bank_chet']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">БИК</span>
						<input type="text" id="bank_bik" value="<?php echo $data['bank_bik']; ?>">
					</div>
				</div>
			</div>

			<div class="col-8">
				<p class="profile_title">Другие данные</p>
				<div class="two_field">
					<div class="field field_50">
						<span class="field_desc">ИНН</span>
						<input type="text" id="other_inn" value="<?php echo $data['other_inn']; ?>">
					</div>
					<div class="field field_50">
						<span class="field_desc">СНиЛС</span>
						<input type="text" id="other_snils" value="<?php echo $data['other_snils']; ?>">
					</div>
				</div>
			</div>

			<div class="col-12">
				<button id="profile_submit" class="default_btn" disabled="disabled">Сохранить</button>
			</div>

		</div>
	<?php endif ?>
	<!-- END SETTINGS WRAPPER -->
</div>

<?php include 'footer.php'; ?>