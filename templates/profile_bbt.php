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