<?php
// overlay
overlay_form();

// блок уведомления
notification();
?>

<div class="container">
	<?php
	// заголовок
	page_title('Партнеры', $partners_array->num_rows, $role == 'Команда', 'partner', 'Добавить партнера');
	?>

	<div class="row table_row users_row">
		<div class="col-12 analitics_col">
			<?php
			// выбор промежутка дат
			change_date($period, $_COOKIE['calendarText']);
			
			// поиск по таблице
			search_table('search_table_command', 'Введите имя партнера или нас. пункт', $_GET['search']);
			?>
		</div>
		
		<div class="col-12">
			<table id="users_table" data-position="partner" data-role="<?=$role?>">
				<thead>
					<tr>
						<?php
						table_th('Имя партнера <span class="sort_upper sortColumn_type">&#9660;</span>', 'name');
						table_th('Кол-во<br>клиентов', 'clients', 'table_align_center');
						table_th('Сумма<br>продаж', 'summ_sold', 'table_align_center');
						table_th('Процент<br>вознагражд.', 'digital_percent', 'table_align_center');
						table_th('Сумма<br>вознагражд.', 'summ_get', 'table_align_center');
						table_th('Ожидает<br>выплаты', 'summ_wait', 'table_align_center');

						if ($role == 'Команда')
							table_th('Управление<br>партнером <img src="" class="icon_date">', '', 'table_align_center');
						?>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=$offset; $i < $limit && $i < count($array); $i++)
						partners_tbody_tr($array[$i]);
					?>
				</tbody>
			</table>
		</div>
		<?php
		// hidden inputs for js, pagination and table_size
		after_table_filters(
			[
				['active_page', $page],
				['role', $role],
				['command_partners', $role == 'Команда' ? $user_id : ''],
			],
			[$page, $pages, 'partners.php', $search],
			[$rows]
		);
		?>
	</div>
</div>

<?php
if ($role == 'Команда') {
	// форма проверки пароля
	check_password_form('form_control_partner_pass_request', explode('|', $user)[1], $user_id);
	
	// форма создания партнера
	form_add_partner();

	// форма контроля партнера
	control_partner_form();
}
?>