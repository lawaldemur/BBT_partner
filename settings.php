<?php include 'header.php'; ?>

<div id="notification">
	<span id="notif_icon">&#10004;</span>
	<span id="notification_text">Данные успешно сохранены</span>
</div>

<div class="container">
	<div class="row">
		<div class="col setting_list_col">
			<h1>Настройки</h1>
		</div>
	</div>

	<!-- SETTINGS WRAPPER -->
	<div class="row">
		<div class="col-4">
			<p class="account_title">Учётная запись</p>
			<div class="field">
				<span class="field_desc">Эл. почта / Логин</span>
				<input type="text" id="change_email" value="<?=explode('|', $user)[1]?>">
				<input type="hidden" id="user_id" value="<?=$user_id?>">
				<input type="hidden" id="user_position" value="<?=explode('|', $user)[0]?>">
				<?php
				if (isset($_SESSION['logged']))
					echo '<input type="hidden" id="auth_method" value="session">';
				elseif (isset($_COOKIE['logged']))
					echo '<input type="hidden" id="auth_method" value="cookie">';
				?>
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
	<!-- END SETTINGS WRAPPER -->
</div>

<?php include 'footer.php'; ?>