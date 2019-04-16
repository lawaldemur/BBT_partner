<!-- ADD PARTNER FORM -->
<div class="form_add_partner">
	<div class="close_form"><img src="/img/close_form.svg" alt="close_form" height="18"></div>

	<p class="modal_title">Добавить нового партнера</p>
	<form id="add_partner_form" method="POST">
		<input type="text" id="partner_name" placeholder="Фамилия, Имя и Отчество партнера">
		<input type="text" id="partner_region" placeholder="Населенный пункт">
		<label>Выручка за эл. книги<input type="text" id="get_digital"><span class="get_percent">%</span></label>
		<label>Выручка за аудиокниги<input type="text" id="get_audio"><span class="get_percent">%</span></label>
		<input type="text" id="partner_email" placeholder="Эл. почта">
	</form>
	<p class="add_partner_desc">На указанный адрес эл. почты<br>будут отправлены данные для входа в аккаунт</p>
	<button id="add_partner_submit" type="button">Добавить</button>
</div>
<!-- END ADD PARTNER FORM -->