<?php document_viewer(); ?>

<div class="container">
	<?php
	bread_cumbs_row($cumbs);

	view_info_row($_SERVER['HTTP_REFERER'], $picture, $view['name'],
		$address, $view_position,
		[
			'digital_percent' => $view['digital_percent'],
			'children_quantity' => $children->num_rows,
			'get_today' => $get_today,
			'get_week' => $get_week,
			'get_month' => $get_month,
			'get_year' => $get_year,
		]
	);

	view_tabs($view_position, $role);
	?>
</div>


<div class="container view_content_container">
	<div class="row books_or_children_row books">
		<div class="col-12 analitics_col">
			<?php
			// выбор промежутка дат
			change_date($period, $_COOKIE['calendarText'], true);

			// выбор формата книг
			choose_format($format);

			// выбор сортировки по дате или по книгам
			sort_date_or_book($sort);

			// поиск по таблице
			search_table('search_table_command', 'Введите имя партнера или нас. пункт', $_GET['search']);
			?>
		</div>

		<div class="col-12">
			<?php
			if ($view_position != 'client')
				graph_view();
			?>

			<table id="book" data-task="<?=$sort?>" data-table="all">
				<thead>
					<tr>
						<?php
						if ($sort == 'bydate') {
							table_th('Дата <span class="sort_upper sortColumn_type">&#9660;</span>', 'date', 'books');
							table_th('Наименование книг', 'name', 'books');
						} else
							table_th('Наименование книг <span class="sort_upper sortColumn_type">&#9660;</span>', 'name', 'books');

						table_th('Формат', 'format', 'books');
						table_th('Кол-во', 'count', 'books');
						table_th('Цена<br>за единицу', 'price', 'books');
						table_th('Общая<br>стоимость', 'summ', 'books');
						
						if ($view_position != 'client')
							table_th('Сумма<br>вознагражд.', 'to_'.$view_position, 'books', 'padding-right: 20px;');
						
						if ($view_position == 'command') {
							table_th('Имя партнера', 'name', 'children', 'padding-right: 20px;');
							table_th('Кол-во<br>клиентов', 'clients', 'children');
							table_th('Сумма<br>продаж', 'summ_sold', 'children');
							table_th('Сумма<br>вознагражд.', 'summ_get', 'children');
							table_th('Выплаты', 'summ_wait', 'children');
						} elseif ($view_position == 'partner') {
							table_th('Имя клиента', 'name', 'children', 'padding-left: 20px; width: 860px;');
							table_th('Сумма покупок', 'bought', 'children', 'width: 100px;');
						}
						?>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=$offset; $i < $limit && $i < count($array); $i++): ?>
						<tr>
							<?php
							if ($sort == 'bydate')
								simple_td(date("d.m.Y", strtotime($array[$i]['date'])));

							product_name_td($array[$i]['img'], $array[$i]['name'], $array[$i]['other']);

							$rub = ' &#8381;';
							simple_td($array[$i]['format']);
							simple_td(strval($array[$i]['summ'] / $array[$i]['price']));
							simple_td(strval($array[$i]['price']).$rub);
							simple_td(strval($array[$i]['summ']).$rub);
							
							if ($view_position != 'client')
								simple_td(strval($array[$i]['to_'.$view_position]).$rub);
							?>
						</tr>
					<?php endfor; ?>
				</tbody>
			</table>

		</div>
		<?php
		// hidden inputs for js, pagination and table_size
		after_table_filters(
			[
				['active_page', $page],
				['user_id', $id],
				['role', $view_position],
				['page_table', $_GET['table']],
			],
			[$page, $pages, 'view.php'],
			[$rows]
		);
		?>
	</div>

	<?php
	// вкладка о команде/партнере/клиенте
	$data = json_decode($view['data']);
	if ($view_position == 'client')
		$data = [
			'billing_phone' => $billing_phone,
			'billing_email' => $billing_email,			
		];
	view_about_row($data, $view_position);
	?>

</div>