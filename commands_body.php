<?php
// overlay
overlay_form();

// блок уведомления
notification();
?>


<div class="container">
	<div class="row">
		<div class="col command_list_col">
			<h1>Команды <span class="command_count"><?php echo $commands_array->num_rows; ?></span></h1>

			<button id="add_command_btn" class="transparent_btn"><img src="/img/add_command_plus.svg" alt="add_command_plus" class="add_command_plus" width="11" height="11"><img src="/img/add_command_plus_white.svg" alt="add_command_plus_white" class="add_command_plus_white" width="11" height="11">Добавить команду</button>
		</div>
	</div>

	<div class="row table_row users_row">
		<div class="col-12 analitics_col">
			<div class="change_date">
				<?php if (!isset($_COOKIE['period']) || $_COOKIE['period'] == "`date` >= CURDATE()"): ?>
					<div class="change change_active" id="today" data-val="`date` >= CURDATE()">Сегодня</div>
				<?php else: ?>
					<div class="change" id="today" data-val="`date` >= CURDATE()">Сегодня</div>
				<?php endif ?>

				<?php if ($_COOKIE['period'] == "DATE(`date`) = DATE(NOW() - INTERVAL 1 DAY)"): ?>
					<div class="change change_active" id="yesterday" data-val="DATE(`date`) = DATE(NOW() - INTERVAL 1 DAY)">Вчера</div>
				<?php else: ?>
					<div class="change" id="yesterday" data-val="DATE(`date`) = DATE(NOW() - INTERVAL 1 DAY)">Вчера</div>
				<?php endif ?>
				
				<?php if ($_COOKIE['period'] == "WEEK(`date`) = WEEK(CURDATE())"): ?>
					<div class="change change_active" id="week" data-val="WEEK(`date`) = WEEK(CURDATE())">Неделя</div>
				<?php else: ?>
					<div class="change" id="week" data-val="WEEK(`date`) = WEEK(CURDATE())">Неделя</div>
				<?php endif ?>
				
				<?php if ($_COOKIE['period'] == "MONTH(`date`) = MONTH(CURDATE())"): ?>
					<div class="change change_active" id="month" data-val="MONTH(`date`) = MONTH(CURDATE())">Месяц</div>
				<?php else: ?>
					<div class="change" id="month" data-val="MONTH(`date`) = MONTH(CURDATE())">Месяц</div>
				<?php endif ?>
				
				<?php if ($_COOKIE['period'] == "QUARTER(`date`) = QUARTER(CURDATE())"): ?>
					<div class="change change_active" id="quartal" data-val="QUARTER(`date`) = QUARTER(CURDATE())">Квартал</div>
				<?php else: ?>
					<div class="change" id="quartal" data-val="QUARTER(`date`) = QUARTER(CURDATE())">Квартал</div>
				<?php endif ?>
				
				<?php if ($_COOKIE['period'] == "YEAR(`date`) = YEAR(CURDATE())"): ?>
					<div class="change change_active" id="year" data-val="YEAR(`date`) = YEAR(CURDATE())">Год</div>
				<?php else: ?>
					<div class="change" id="year" data-val="YEAR(`date`) = YEAR(CURDATE())">Год</div>
				<?php endif ?>
				
				
				<?php if (stripos($_COOKIE['period'], 'BETWEEN') !== false): ?>
					<div class="change change_active custom_date_change" id="custom" data-val="<?=$_COOKIE['period']?>">
				<?php else: ?>
					<div class="change custom_date_change" id="custom">
				<?php endif ?>
					<img src="/img/custom.svg" alt="custom"><img src="/img/custom_active.svg" alt="custom_active">
					<?php if ($_COOKIE['calendarText'] == ''): ?>
						<span class="custom_date">1 июн 2016 – 23 авг 2018</span>
					<?php else: ?>
						<span class="custom_date"><?=$_COOKIE['calendarText']?></span>
					<?php endif ?>
				</div>


			</div>

			<?php printCalendar(); ?>

			<div class="search_table">
				<input type="text" style="display: none;" name="search">
				<input type="email" class="email_fake" id="email">
				<input type="text" id="search_table_command" name="search" placeholder="Введите название команды или город" autocomplete="new-password">
				<img src="/img/search_icon.svg" alt="search_icon" width="12" height="12">
				<img src="/img/active_search_icon.svg" alt="active_search_icon" width="12" height="12">
			</div>
		</div>
		
		<div class="col-12">
			<table id="users_table" data-position="command" data-role="<?=$role?>">
				<thead>
					<tr>
						<th data-column="name">Команда <span class="sort_upper sortColumn_type">&#9660;</span></th>
						<th class="table_align_center" data-column="summ_sold">Сумма<br>продаж</th>
						<th class="table_align_center" data-column="digital_percent">Процент<br>вознагражд.</th>
						<th class="table_align_center" data-column="summ_get">Сумма<br>вознагражд.</th>
						<th class="table_align_center" data-column="summ_wait">Ожидает<br>выплаты</th>
						<th class="table_align_center">Управление<br>командой</th>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=$offset; $i < $limit && $i < count($array); $i++): ?>
						<tr data-id="<?=$array[$i]['id']?>" data-email="<?=$array[$i]['login']?>" data-digital_percent="<?=$array[$i]['digital_percent']?>" data-audio_percent="<?=$array[$i]['audio_percent']?>" data-pass_length="<?=strlen($array[$i]['password'])?>" data-name="<?=$array[$i]['name']?>" data-region="<?=$array[$i]['city']?>">
							<td class="table_command_name">
								<div class="command_picture_wrapp" style="background-image: url(/avatars/<?=$array[$i]['picture']?>);"></div>
								<div class="command_name_wrap">
									<span class="command_name"><?=$array[$i]['name']?></span>
									<span class="command_city"><?=$array[$i]['city']?></span>
								</div>
							</td>
							<td class="table_align_center"><?=$array[$i]['summ_sold']?> &#8381;</td>
							<td class="table_align_center"><?=$array[$i]['digital_percent']?>%</td>
							<td class="table_align_center"><?=$array[$i]['summ_get']?> &#8381;</td>
							<?php if ($array[$i]['summ_wait'] != 0): ?>
								<td class="table_align_center" style="color: #ff441f;"><?=$array[$i]['summ_wait']?> &#8381;</td>
							<?php else: ?>
								<td class="table_align_center"><?=$array[$i]['summ_wait']?> &#8381;</td>
							<?php endif ?>
							<td class="table_align_center"><img src="/img/control.svg" alt="control_command" class="control_command"></td>
						</tr>
					<?php endfor; ?>
				</tbody>
			</table>
		</div>

		<div class="col-12 after_table_filters">
			<input type="hidden" id="active_page" value="<?=$_GET['page']?>">
			<div class="pagination_list">
				<div class="prev_page"><img src="/img/prev_page.svg" alt="prev_page"></div>
				<div class="pages_list">
					<?php if ($_POST['search'] != '')
						$search = '&search='.$_POST['search']; ?>

					<?php if ($pages <= 10) {
						for ($i=1; $i < $pages; $i++) {
							if (($_SERVER['REQUEST_URI'] == "/commands.php" && $i == 1) || ($_GET['page'] == $i)): ?>
								<a class="page active_page" href="/commands.php?page=<?=$i?><?=$search?>"><?=$i?></a>
							<?php else: ?>
								<a class="page" href="/commands.php?page=<?=$i?><?=$search?>"><?=$i?></a>
							<?php endif ?>
						<?php }
					} else {
						if ($_GET['page'] < 7) {
							for ($i=1; $i < 8; $i++) {
								if (($_SERVER['REQUEST_URI'] == "/commands.php" && $i == 1) || ($_GET['page'] == $i)): ?>
									<a class="page active_page" href="/commands.php?page=<?=$i?><?=$search?>"><?=$i?></a>
								<?php else: ?>
									<a class="page" href="/commands.php?page=<?=$i?><?=$search?>"><?=$i?></a>
								<?php endif ?>
							<?php } ?>
							<span class="triple_dots">...</span>
							<a class="page" href="/commands.php?page=<?=$pages - 1?><?=$search?>"><?=$pages - 1?></a> <?php
						} elseif ($_GET['page'] >= $pages - 6) { ?>
							<a class="page" href="/commands.php?page=1<?=$search?>">1</a>
							<span class="triple_dots">...</span>
							<?php
							for ($i=$pages - 7; $i < $pages; $i++) {
								if (($_SERVER['REQUEST_URI'] == "/commands.php" && $i == 1) || ($_GET['page'] == $i)): ?>
									<a class="page active_page" href="/commands.php?page=<?=$i?><?=$search?>"><?=$i?></a>
								<?php else: ?>
									<a class="page" href="/commands.php?page=<?=$i?><?=$search?>"><?=$i?></a>
								<?php endif ?>
							<?php }
						} else { ?>
							<a class="page" href="/commands.php?page=1<?=$search?>">1</a>
							<span class="triple_dots">...</span>
							<?php
							for ($i=$_GET['page'] - 3; $i < $_GET['page'] + 4; $i++) {
								if (($_SERVER['REQUEST_URI'] == "/commands.php" && $i == 1) || ($_GET['page'] == $i)): ?>
									<a class="page active_page" href="/commands.php?page=<?=$i?><?=$search?>"><?=$i?></a>
								<?php else: ?>
									<a class="page" href="/commands.php?page=<?=$i?><?=$search?>"><?=$i?></a>
								<?php endif ?>
							<?php } ?>
							<span class="triple_dots">...</span>
							<a class="page" href="/commands.php?page=<?=$pages - 1?><?=$search?>"><?=$pages - 1?></a> <?php
						}
					} ?>
				</div>
				<div class="next_page"><img src="/img/next_page.svg" alt="next_page"></div>
			</div>

			<div class="table_sizes">
				<?php if ($rows == 20): ?>
					<div class="table_size table_size_active">20</div>
				<?php else: ?>
					<div class="table_size">20</div>
				<?php endif ?>

				<?php if ($rows == 50): ?>
					<div class="table_size table_size_active">50</div>
				<?php else: ?>
					<div class="table_size">50</div>
				<?php endif ?>

				<?php if ($rows == 100): ?>
					<div class="table_size table_size_active">100</div>
				<?php else: ?>
					<div class="table_size">100</div>
				<?php endif ?>
			</div>
		</div>
	</div>

</div>


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


<!-- CHECK PASSWORD FORM -->
<div class="form_add_command_pass_request">
	<div class="close_form"><img src="/img/close_form.svg" alt="close_form" height="18"></div>

	<p class="modal_title">Введите пароль</p>
	<input type="password" id="request_pass" placeholder="Введите пароль от личного кабинета">
	<input type="hidden" id="user_login" value="<?php echo explode('|', $user)[1]; ?>">
	<input type="hidden" id="user_id" value="<?php echo $user_id; ?>">
	<div class="request_pass_button_row">
		<button class="request_pass_ok">OK</button>
		<button class="request_pass_cancel">Отменить</button>
	</div>
</div>
<!-- END CHECK PASSWORD FORM -->


<!-- DELETE COMMAND ERROR FORM -->
<div class="form_delete_command_error">
	<div class="close_form"><img src="/img/close_form.svg" alt="close_form" height="18"></div>

	<p class="modal_title">Невозможно удалить</p>
	<div class="delete_desc">Для того чтобы удалить команду нужно сначали удалить<br>всех партнеров вашей команды</div>
	<button class="delete_command_error_ok">OK</button>
</div>
<!-- END DELETE COMMAND ERROR FORM -->

<!-- CONFIRM DELETE COMMAND FORM -->
<div class="form_confirm_delete">
	<div class="close_form"><img src="/img/close_form.svg" alt="close_form" height="18"></div>

	<p class="modal_title">Удалить команду</p>
	<div class="delete_confirm_desc">После удаления команды вся история покупок и аналитика<br>будут удалены без возможности восстановления</div>
	<div class="confirm_delete_button_row">
		<button class="confirm_delete_ok">Удалить</button>
		<button class="confirm_delete_cancel">Отменить</button>
	</div>
</div>
<!-- END CONFIRM DELETE COMMAND FORM -->