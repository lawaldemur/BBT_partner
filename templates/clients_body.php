<div class="container">
	<?php page_title('Клиенты', $count, false); ?>

	<div class="row table_row users_row">
		<div class="col-12 analitics_col">
			<?php
			// выбор промежутка дат
			change_date($period, $_COOKIE['calendarText']);

			// поиск по таблице
			search_table('search_table_command', $role == 'Команда' ? 'Введите имя клиента или нас. пункт' : 'Введите имя клиента или команду', $_GET['search']);
			?>
		</div>
		
		<div class="col-12">
			<table id="users_table" data-position="client" data-role="<?=$role?>">
				<thead>
					<tr>
						<?php
						table_th('Имя клиента <span class="sort_upper sortColumn_type">&#9660;</span>', 'name');
						table_th('Принадлежность', 'parent');
						table_th('Кол-во<br>клиентов', 'clients', 'table_align_center');
						table_th('Сумма<br>продаж', 'sold', 'table_align_center');
						table_th('Сумма<br>покупок', 'bought', 'table_align_center');
						?>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=$offset; $i < $limit && $i < count($array); $i++)
						clients_tbody_tr($array[$i]);
					?>
				</tbody>
			</table>
		</div>
		<?php
		// hidden inputs for js, pagination and table_size
		after_table_filters(
			[
				['role', $role],
				['parent', $user_id],
				['active_page', $page],
			],
			[$page, $pages, 'clients.php', $search],
			[$rows]
		);
		?>
	</div>
</div>