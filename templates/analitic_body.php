<div class="container">
	<?php
	// заголовок, вкладки выбора всех книг/цифровых/аудио, вкладки книги/просмотры
	analitics_tabs();
	?>
	<div class="row table_row analitics_row active_table_row" data-table="all">
		<div class="col-12 analitics_col">
			<?php
			// выбор промежутка дат
			change_date($period, $_COOKIE['calendarText']);

			// выбор формата книг
			choose_format($format);

			// выбор сортировки по дате или по книгам
			sort_date_or_book($sort);
			
			// поиск по таблице
			search_table('search_table_command', 'Введите название книги или автора', $_GET['search']);
			?>
		</div>
		<div class="col-12">
			<table id="book" data-task="<?=$sort?>" data-table="all">
				<thead>
					<tr><?php
						if ($sort == 'bydate') {
							table_th('Дата <span class="sort_upper sortColumn_type">&#9660;</span>', 'date', 'books');
							table_th('Наименование книг', 'name', 'books');
						} else
							table_th('Наименование книг <span class="sort_upper sortColumn_type">&#9660;</span>', 'name', 'books');

						table_th('Формат', 'format', 'books');
						table_th('Кол-во', 'count', 'books');
						table_th('Цена<br>за единицу', 'price', 'books');
						table_th('Общая<br>стоимость', 'summ', 'books');
						
						if ($role == 'ББТ')
							table_th('Выручка<br>ББТ', 'to_bbt', 'books');
						elseif ($role == 'Команда')
							table_th('Выручка<br>команды', 'to_command', 'books');
						elseif ($role == 'Партнер')
							table_th('Выручка<br>партнера', 'to_partner', 'books');

						table_th('Наименование книг', 'name', 'views');
						table_th('Просмотры', 'views', 'views');
					?></tr>
				</thead>
				<tbody>
					<?php for ($i=$offset; $i < $limit && $i < count($array); $i++) {
						?><tr><?php
							if ($sort == 'bydate')
								simple_td(date("d.m.Y", strtotime($array[$i]['date'])));

							product_name_td($array[$i]['img'], $array[$i]['name'], $array[$i]['other']);

							$rub = ' &#8381;';
							simple_td($array[$i]['format']);
							simple_td(strval($array[$i]['summ'] / $array[$i]['price']));
							simple_td(strval($array[$i]['price']).$rub);
							simple_td(strval($array[$i]['summ']).$rub);

							if ($role == 'ББТ')
								simple_td(strval($array[$i]['to_bbt']).$rub);
							elseif ($role == 'Команда')
								simple_td(strval($array[$i]['to_command']).$rub);
							elseif ($role == 'Партнер')
								simple_td(strval($array[$i]['to_partner']).$rub);
						?></tr><?php
					} ?>
				</tbody>
			</table>
		</div>
		<?php
		// hidden inputs for js, pagination and table_size
		after_table_filters(
			[
				['active_page', $page],
				['user_id', $user_id],
				['role', $role],
				['page_table', $_GET['table']],
			],
			[$page, $pages, 'analitic.php'],
			[$rows]
		);
		?>
	</div>
</div>
