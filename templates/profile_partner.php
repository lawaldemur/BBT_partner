<div class="row">
	<div class="col-8">
		<p class="profile_title">Промо-код распространителя</p>
		<div class="partner_code">
			<div class="wrapper_code">
				<div class="code"><?=$data['code']?></div>
				<div class="link">bbt-online.ru/promo<?=$data['code']?></div>
				<input type="hidden" id="code" value="<?=$data['code']?>">
			</div>
			<div class="code_desc">Делитесь кодом или ссылкой с другими и получайте выручку с их покупок :)</div>
		</div>
	</div>

	<!-- picture -->
	<div class="col-2 offset-2 picture_col">
		<label for="upload_picture" style="background-image: url(/avatars/<?=$data['picture']?>);">
			<?php if ($data['picture'] == 'avatar.png'): ?>
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
			<input type="text" id="general_name" value="<?php echo $data['name']; ?>">
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
				<div class="block_input" id="block_digital"><?=$data['digit_perc']?></div>
			</div>
			<div class="field block_field field_50">
				<span class="field_desc">Процент с аудиокниг</span>
				<div class="block_input" id="block_audio"><?=$data['audio_perc']?></div>
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