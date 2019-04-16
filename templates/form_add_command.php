<!-- ADD COMMAND -->
<div class="form_add_command">
	<div class="close_form"><img src="/img/close_form.svg" alt="close_form" height="18"></div>

	<p class="modal_title">Добавить новую команду</p>
	<form id="add_command_form" method="POST">
		<input type="text" id="command_name" placeholder="Название команды">
		<input type="text" id="command_region" placeholder="Населенный пункт">
		<label>Выручка за эл. книги<input type="text" id="get_digital"><span class="get_percent">%</span></label>
		<label>Выручка за аудиокниги<input type="text" id="get_audio"><span class="get_percent">%</span></label>
		<input type="text" id="command_email" placeholder="Эл. почта">
	</form>
	<p class="add_command_desc">На указанный адрес эл. почты<br>будут отправлены данные для входа в аккаунт</p>
	<button id="add_command_submit" type="button">Добавить</button>

</div>
<!-- END ADD COMMAND -->