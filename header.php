<?php
require_once 'functions.php';
?>

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

	<!-- HEADER -->
	<header class="container-fluid">
		<div class="container">
			<div class="row">
				<?php if (!$role): ?>
					<div class="col-7">
						<div class="not_logged_header_logo">
							<img src="/img/logo.svg" alt="logo" width="48">
							<div class="logo_title">
								<div>
									<span class="bbt">BBT</span>
									<span class="online">ONLINE</span>
								</div>
								<div class="logo_title_desc">партнерская программа</div>
							</div>
						</div>
					</div>
					<div class="offset-4 col-1">
						<div class="login_link">
							<a href="<?=$pages['entrance']?>">
								<img src="/img/link-lk.svg" alt="entrance_icon" width="16" height="17">
								<span class="entrance_icon_title">ВОЙТИ</span>
							</a>
						</div>
					</div>
				<?php else: ?>
					<div class="col-7">
						<div class="logged_header_logo">
							<img src="/img/logo.svg" alt="logo" width="48">
							<div class="logo_title">
								<div>
									<span class="bbt">BBT</span>
									<span class="online">ONLINE</span>
								</div>
								<div class="logo_title_desc">партнерская программа</div>
							</div>
						</div>
						<div class="vertical_line"></div>
						<div class="header_user_role"><?=$user_name?></div>
					</div>
					<div class="col-5">
						<?php if ($active_page == $pages['analitic']): ?>
							<a href="<?=$pages['analitic']?>" class="active_header_link">Аналитика</a>
						<?php else: ?>
							<a href="<?=$pages['analitic']?>">Аналитика</a>
						<?php endif ?>
						
						<?php if ($role == 'ББТ'): ?>	
							<?php if ($active_page == $pages['commands']): ?>
								<a href="<?=$pages['commands']?>" class="active_header_link">Команды</a>
							<?php else: ?>
								<a href="<?=$pages['commands']?>">Команды</a>
							<?php endif ?>		
						<?php endif ?>

						<?php if ($role == 'ББТ' || $role == 'Команда'): ?>	
							<?php if ($active_page == $pages['partners']): ?>
								<a href="<?=$pages['partners']?>" class="active_header_link">Партнеры</a>
							<?php else: ?>
								<a href="<?=$pages['partners']?>">Партнеры</a>
							<?php endif ?>	
						<?php endif ?>

						<?php if ($active_page == $pages['clients']): ?>
							<a href="<?=$pages['clients']?>" class="active_header_link">Клиенты</a>
						<?php else: ?>
							<a href="<?=$pages['clients']?>">Клиенты</a>
						<?php endif ?>

						<?php if ($active_page == $pages['finance']): ?>
							<a href="<?=$pages['finance']?>" class="active_header_link">Финансы</a>
						<?php else: ?>
							<a href="<?=$pages['finance']?>">Финансы</a>
						<?php endif ?>
						<img src="/img/link-lk.svg" alt="entrance_icon" width="16" height="17">
					</div>

					<div class="dropdown_entrance_icon">
						<div class="dropdown_entrance_icon_link"><a href="<?=$pages['profile']?>">Профиль</a></div>
						<div class="dropdown_entrance_icon_link"><a href="<?=$pages['settings']?>">Настройки</a></div>
						<div class="dropdown_entrance_icon_line"></div>
						<div class="dropdown_entrance_icon_logout">Выйти</div>
					</div>
				<?php endif ?>
				
			</div>
		</div>
	</header>
	<!-- END HEADER -->

	<!-- CONTENT WRAPPER -->
	<div class="content_wrapper <?=$add_class_to_content_wrapper?>">