<div class="row">
	<div class="col-4">
		<p class="account_title">Учётная запись</p>
		<div class="field">
			<span class="field_desc">Эл. почта / Логин</span>
			<input type="text" id="change_email" value="<?=$email?>">
			<input type="hidden" id="user_id" value="<?=$id?>">
			<input type="hidden" id="user_position" value="<?=$position?>">
			<input type="hidden" id="auth_method" value="<?=$auth_method?>">
		</div>
		<div id="change_email_submit" class="default_btn" disabled="disabled">Изменить</div>
	</div>
	<div class="col-4">
		<p class="account_title">Смена пароля</p>

		<div class="field">
			<span class="field_desc">Новый пароль</span>
			<input type="password" id="change_pass1">
		</div>
		<div class="field">
			<span class="field_desc">Подтвердите пароль</span>
			<input type="password" id="change_pass2">
		</div>
		<div id="change_pass_submit" class="default_btn" disabled="disabled">Изменить</div>
	</div>
</div>
