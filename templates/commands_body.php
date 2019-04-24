<?php
// overlay
overlay_form();

// блок уведомления
notification();
?>

<div class="container">
	<?php
	// заголовок
	page_title('Команды', $commands_array->num_rows, true, 'command', 'Добавить команду');
	?>

	<div class="row table_row users_row">
		<div class="col-12 analitics_col">
			<?php
			// выбор промежутка дат
			change_date($period, $_COOKIE['calendarText']);
			
			// поиск по таблице
			search_table('search_table_command', 'Введите название команды или город', $_GET['search'], true);
			?>
		</div>
		
		<div class="col-12">
			<table id="users_table" data-position="command" data-role="<?=$role?>">
				<thead>
					<tr>
						<?php
						table_th('Команда <span class="sort_upper sortColumn_type">&#9660;</span>', 'name');
						table_th('Сумма<br>продаж', 'summ_sold', 'table_align_center');
						table_th('Процент<br>вознагражд.', 'digital_percent', 'table_align_center');
						table_th('Сумма<br>вознагражд.', 'summ_get', 'table_align_center');
						table_th('Ожидает<br>выплаты', 'summ_wait', 'table_align_center');
						table_th('Управление<br>командой', '', 'table_align_center');
						?>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=$offset; $i < $limit && $i < count($array); $i++)
						commands_tbody_tr($array[$i]);
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
				['user_id', $user_id]
			],
			[$page, $pages, 'commands.php', $search],
			[$rows]
		);
		?>
	</div>

</div>


<?php
// форма контроля команды
control_command_form();

// форма создания команды
form_add_command();

// форма проверки пароля
check_password_form('form_add_command_pass_request', explode('|', $user)[1], $user_id);

// форма подтверждения удаления команды
delete_command_form();
?>
