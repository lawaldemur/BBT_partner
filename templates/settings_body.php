<?php
// блок уведомления
notification();
?>

<div class="container">
	<?php
	// заголовок
	settings_title();

	// поля смены логина/пароля
	settings_content($user_id, explode('|', $user)[1], explode('|', $user)[0], isset($_SESSION['logged']) ? 'session' : 'cookie');
	?>
</div>
