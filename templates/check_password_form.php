<!-- CHECK PASSWORD FORM -->
<div class="<?=$class?>">
	<div class="close_form"><img src="/img/close_form.svg" alt="close_form" height="18"></div>

	<p class="modal_title">Введите пароль</p>
	<input type="password" id="request_pass" placeholder="Введите пароль от личного кабинета">
	<input type="hidden" id="user_login" value="<?=$login?>">
	<input type="hidden" id="user_id" value="<?=$id?>">
	<div class="request_pass_button_row">
		<button class="request_pass_ok">OK</button>
		<button class="request_pass_cancel">Отменить</button>
	</div>
</div>
<!-- END CHECK PASSWORD FORM -->