<?php include 'header.php'; ?>
<?php
if (isset($_GET['reset'])) {
	$reset = explode('_', $_GET['reset']);
	$correct = $dbc->query("SELECT * FROM users WHERE login = '{$reset[0]}'");
	$correct = $correct->fetch_array(MYSQLI_ASSOC);
	if ($reset[1] != md5($correct['password'])) {
		echo '<script>window.location = "http://partner.bbt-online.ru";</script>';
		exit();
	}
}
?>


<div class="container">
	<div class="row">
		<div class="col-12">
			<?php if (!isset($_GET['reset'])): ?>
				<div class="step1 step">
					<p class="title_forgot">Сбросить ваш пароль</p>
					<p class="desc_forgot">Введите адрес эл. почты, на который будет выслано<br>письмо с дальнейшей инструкцией</p>
					<input type="text" id="email_forgot" placeholder="Эл. почта">
					<button class="next_step">Далее</button>
				</div>

				<div class="step2 step">
					<p class="title_forgot">Сбросить ваш пароль</p>
					<p class="desc_forgot">Мы отправили на вашу почту письмо<br>с дальнешей инструкцией по восстановлению пароля.</p>
				</div>
			<?php else: ?>
				<div class="step3 step">
					<input type="hidden" id="id" value="<?=$correct['id']?>">
					<input type="hidden" id="old_pass_md5" value="<?=md5($correct['password'])?>">
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
			<?php endif ?>

		</div>
	</div>
</div>



<?php include 'footer.php'; ?>
