<div class="col-6">
	<div class="info">
		<div class="info_title">Общие данные</div>
		<div class="info_value">
			<div class="info_value_desc">ФИО</div>
			<div class="info_value_val"><?=$data->general_name?></div>
		</div>
		<div class="info_value">
			<div class="info_value_desc">Духовное имя</div>
			<div class="info_value_val"><?=$data->general_soul_name?></div>
		</div>
		<div class="info_value">
			<div class="info_value_desc">Адрес</div>
			<div class="info_value_val"><?=$data->general_address?></div>
		</div>
	</div>
	<div class="info">
		<div class="info_title">Контактные данные</div>
		<div class="info_value">
			<div class="info_value_desc">Телефон</div>
			<div class="info_value_val"><?=$data->contact_phone?></div>
		</div>
		<div class="info_value">
			<div class="info_value_desc">Эл. почта</div>
			<div class="info_value_val"><?=$data->contact_email?></div>
		</div>
	</div>
</div>
<div class="col-6">
	<div class="info">
		<div class="info_title">Паспортные данные</div>
		<div class="info_value">
			<div class="info_value_desc">Номер / Серия</div>
			<div class="info_value_val"><?=$data->pasport_seria.' '.$data->pasport_number?></div>
		</div>
		<div class="info_value">
			<div class="info_value_desc">Кем выдан</div>
			<div class="info_value_val"><?=$data->pasport_gave?></div>
		</div>
		<div class="info_value">
			<div class="info_value_desc">Дата выдачи</div>
			<div class="info_value_val"><?=$data->pasport_date?></div>
		</div>
		<div class="info_passports">
			<?php if ($data->passport): ?>
				<?php foreach ($data->passport as $passport): ?>
					<div class="passport_view" data-passport='<?=$passport?>'>
						<img src="/img/passport_icon.svg" alt="passport_icon">
						<span>Фото паспорта</span>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
	<div class="info">
		<div class="info_title">Другие данные</div>
		<div class="info_value">
			<div class="info_value_desc">ИНН</div>
			<div class="info_value_val"><?=$data->other_inn?></div>
		</div>
		<div class="info_value">
			<div class="info_value_desc">СНиЛС</div>
			<div class="info_value_val"><?=$data->other_snils?></div>
		</div>
	</div>
</div>