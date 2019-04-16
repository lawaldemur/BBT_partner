<div class="step3 step">
	<input type="hidden" id="id" value="<?=$id?>">
	<input type="hidden" id="old_pass_md5" value="<?=md5($pass)?>">
	<p class="title_forgot">Сбросить ваш пароль</p>
	<p class="desc_forgot">Введите новый пароль и подтвердите его.</p>
	<input type="text" id="password_forgot" placeholder="Новый пароль">
	<input type="text" id="repeat_forgot" placeholder="Подтвердите пароль">
	<button class="next_step">Изменить</button>
</div>

<div class="step4 step">
	<p class="title_forgot">Готово!</p>
	<p class="desc_forgot">Ваш пароль был успешно изменен.</p>
	<a href="/" class="next_link">Войти</a>
</div>