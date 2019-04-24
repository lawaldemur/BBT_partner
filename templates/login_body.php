<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<link rel="icon" type="image/png" href="/img/favicon.png">
	<title><?=$title?></title>

	<!-- conncect css -->
	<?=$header_connect?>
</head>
<body>
<!-- PAGE WRAPPER -->
<div class="page_wrapper">
	<!-- CONTENT WRAPPER -->
	<div class="content_wrapper entrance_wrapper">

	<div class="container">

		<div id="overlay_form"></div>

		<div class="entrance_logo">
			<img src="/img/logo.svg" alt="logo">
			<div class="logo_p">
				<span class="bbt">BBT</span> <span class="online">ONLINE</span>
				<span>партнерская программа</span>
			</div>
		</div>

		<div class="entrance_form">
			<p class="entrance_title">Вход</p>
			<form id="entrance" method="POST">
				<input type="text" id="login" name="login" placeholder="Эл. почта">
				<input type="password" id="password" name="password" placeholder="Пароль">
				<button type="submit" id="entrance_btn">Войти</button>
				<div class="append_block">
					<div class="remember_me">
						<input type="checkbox" id="remember_me" style="display: none;">
						<label for="remember_me">
							<span id="fake_remember_me"><span>&#10004;</span></span>
							Запомнить меня
						</label>
					</div>
					<a href="<?=$pages['forgot_pass']?>" class="forgot_pass black_link">Забыли пароль?</a>
				</div>
			</form>
		</div>

		<div class="partner_accept_form" data-method='' data-login=''>
			<div class="close_form"><img src="/img/close_form.svg" alt="close_form" height="18"></div>

			<p class="modal_title">Добро пожаловать<br>в партнерскую программу!</p>
			<div class="delete_confirm_desc">Прежде чем приступить к работе<br>предлагаем вам ознакомиться с правилами<br>пользования партнерской программы</div>
			<div class="document_row">
				<span class="document_partner" id="document_partner_1">Документ 1</span>
				<span class="document_partner" id="document_partner_2">Документ 2</span>
				<span class="document_partner" id="document_partner_3">Документ 3</span>
			</div>
			<input type="checkbox" id="accepted_document" style="display: none;">
			<label for="accepted_document">
				<span id="fake_accepted_document"><span>&#10004;</span></span>
				Я согласен с условия партнерской программы
			</label>
			<button id="partner_login" disabled="disabled">Начать работу →</button>
		</div>

		<div class="partner_document" data-document='1'>
			<div class="close_form entrance_close"><img src="/img/close_form.svg" alt="close_form" height="18"></div>

			<p class="modal_title">Правовая информация</p>
			<div class="document_content">
				1.1. Покупатель (Пользователь) – пользователь сети Интернет, принявший условия Договора и/или зарегистрировавшийся на Сайте Продавца и/или осуществивший авансовый платеж за скачивание Произведений и/или скачавший Произведение и/или начавший пользоваться любыми услугами Продавца.<br>
				1.2. Произведения (Контент) – тексты либо аудиозаписи (фонограммы) литературных произведений (включая обложки, иллюстрации, пр.), представленные в электронном виде в сети Интернет в различных форматах, размещенные на Сайте Продавца, доступные Пользователям посредством Сайта Продавца и/или Мобильных приложений.<br>
				1.3. Каталог – совокупность Произведений.<br>
				1.4. Сайт Продавца (Сайт) – информационный ресурс в сети Интернет, принадлежащий Продавцу и администрируемый Продавцом, расположенный на одном из следующих доменов: на одном из следующих доменов:<br>
				1.5. Скачивание – запись (копирование) Покупателем Произведений на свой компьютер, смартфон или иное устройство.<br>
				1.6. Биллинг – система учета платежей.<br>
				1.7. Учетная запись пользователя – Аутентификационные и Личные данные пользователя, хранящиеся на серверах Сайта Продавца.Учетная запись создается в результате прохождения пользователем процедуры регистрации и может потребоваться для того, чтобы воспользоваться некоторыми возможностями или отдельными функциями Сайта.<br>
				1.8. Логин и Пароль – два уникальных набора символов, идентифицирующих Покупателя, позволяющих Покупателю осуществлять доступ к Контенту.<br>
				1.9. Личный кабинет – личная страница Покупателя на Сайте Продавца, где Покупатель может хранить информацию о себе, хранить Пользовательский Контент, настраивать отображение, задавать параметры, видеть свой статус, состояние Биллинга, и т.п.Личный кабинет привязан к Учетной записи, доступ к нему закрыт Логином и Паролем.<br>
			</div>
		</div>
	</div>