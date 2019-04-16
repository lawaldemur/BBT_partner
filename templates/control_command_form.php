<!-- CONTROL COMMAND FORM -->
<div class="form_control_command">
	<div class="close_form"><img src="/img/close_form.svg" alt="close_form" height="18"></div>

	<p class="modal_title">Управление командой</p>
	<form id="control_command_form" method="POST">
		<input type="hidden" id="new_command_id">
		<input type="text" id="new_command_name" placeholder="Название команды">
		<input type="text" id="new_command_region" placeholder="Населенный пункт">
		<label>Выручка за эл. книги<input type="text" id="new_get_digital"><span class="get_percent">%</span></label>
		<label>Выручка за аудиокниги<input type="text" id="new_get_audio"><span class="get_percent">%</span></label>
		<input type="text" id="new_command_email" placeholder="Эл. почта">
		<div class="control_command_form_pass">
			<input type="password" id="new_command_password" placeholder="Пароль">
			<img src="/img/eye.svg" alt="show password" class="pass_eye">
		</div>
	</form>
	<button id="control_command_submit" type="button">Сохранить</button>
	<p class="delete_command"><span id="delete_command">Удалить команду</span></p>

</div>
<!-- END CONTROL COMMAND FORM -->