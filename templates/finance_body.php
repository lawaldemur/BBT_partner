<?php
// блок уведомления
notification();

// input:file загрузки отчетов/актов
finance_upload_report();
?>

<div class="container">
	<?php
	// заголовок
	page_title('Финансы', '', false);

	// вкладки
	finance_tabs($role);
	?>	
</div>	


<!-- конец выручки -->
<div class="container">
	<div class="row finance_row">
		<div class="col-12 profit active_col_finance">
			<div class="row">
				<div class="col-12 profit_filters">
					<?php
					// выбор промежутка дат
					change_date($period, $_COOKIE['calendarText'], true);

					// выбор формата книг
					choose_format($format);
					?>
				</div>
			</div>
			<div class="row earn_row">
				<?php
				if ($role == 'ББТ')
					finance_brief_result_bbt($n1, $n2);
				else
					finance_brief_result($n);
				?>
			</div>

			<?php
			if ($role == 'ББТ') {
				// скелет графика
				graph_row();

				// таблица заработка по месяцам
				finance_earn_table($show_earn_table, $earn,
					[$page, $pages, 'finance.php', '', 'earn_pagination_list', 'earn_page'],
					[$rows, 'earn_table_sizes']
				);
			} elseif ($role == 'Команда' || $role == 'Партнер') {
				// скелет графика
				graph_row();
			} ?>
		</div>

		<div class="col-12 analitics_col reports_from_partner" style="justify-content: flex-end;">
			<?php
			// поиск по таблице для ББТ
			search_table('search_table_command', 'Введите название команды или город', $_GET['search']);

			create_hidden('to', $user_id);
			create_hidden('role', $role);
			create_hidden('active_page', $page);
			?>
		</div>
		
		<?php
		if ($role == 'ББТ') {
			// таблица-список команд для ББТ
			finance_bbt_reports_from_command();
		} elseif($role == 'Команда') {
			create_hidden('user_id', $user_id);
			create_hidden('table', $_GET['viewbbt']);
			create_hidden('table_2', $_GET['viewpartners']);
			create_hidden('table_3', $_GET['view']);
			create_hidden('active_page', $page);

			// таблица загрузки отчетов от команд для ББТ
			finance_command_for_bbt();

			// таблица принятия отчетов от партнеров командой
			finance_command_from_partner();
		} elseif($role == 'Партнер') {
			create_hidden('user_id', $user_id);
			create_hidden('table', $_GET['viewbbt']);
			create_hidden('table_2', $_GET['viewpartners']);
			create_hidden('active_page', $page);

			// таблица загрузки отчетов от партнера к команде
			finance_partner_table();
		} ?>

		<?php
		// hidden inputs for js, pagination and table_size
		after_table_filters(
			[
				['active_page', $page],
				['table', $_GET['table']],
				['view', $_GET['view']],
			],
			[$page, $pages, 'finance.php'],
			[$rows, 'to_bbt_table_sizes'],
			'finance_after_table_filters'
		);
		?>
	</div>

	<?php
	// индивидуальная карточка/профиль команды/партнера
	if ($role != 'Партнер')
		finance_user_view_row([
			[
				['active_page', $page],
				['table', $_GET['table']],
				['view', $_GET['view']],
			],
			[$page, $pages, 'finance.php', '', 'user_view_pagination_list'],
			[$rows],
			'user_view_tbody_after_table_filters'
		]); ?>
</div>
