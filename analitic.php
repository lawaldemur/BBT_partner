<?php
include 'header.php';
require 'db_shop.php';


$sort = $_COOKIE['sort'] ? $_COOKIE['sort'] : 'bydate';
$period = $_COOKIE['period'] ? $_COOKIE['period'] : '`date` >= CURDATE()';
$format = $_COOKIE['format'] ? $_COOKIE['format'] : 'all';

$where = 'WHERE '.$period;
if ($format != 'all')
	$where .= " AND `format` = '$format'";

if ($role == 'ББТ') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where");
} elseif ($role == 'Команда') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_command_id` = $user_id");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_command_id` = $user_id");
} elseif ($role == 'Партнер') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_partner_id` = $user_id");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_partner_id` = $user_id");
}


// pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$rows = isset($_COOKIE['rows']) ? intval($_COOKIE['rows']) : 20;
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil($books->num_rows / $rows) + 1;


$array = array();
if ($books)
foreach ($books as $book) {
	$book['img'] = $dbc_shop->query("SELECT * FROM `wp_posts` WHERE `post_parent` = {$book['product']} AND `post_type` = 'attachment'");
	$book['img'] = $book['img']->fetch_array(MYSQLI_ASSOC)['guid'];

	if ($book['format'] == 'digital')
		$book['format'] = '<img src="/img/format_digital.svg" alt="digital_format" width="14" height="18">';
	elseif ($book['format'] == 'audio')
		$book['format'] = '<img src="/img/format_audio.svg" alt="audio_format" width="16" height="18">';

	$book['price'] = $dbc_shop->query("SELECT * FROM `wp_postmeta` WHERE `post_id` = {$book['variation']} AND `meta_key` = '_price'");
	$book['price'] = $book['price']->fetch_array(MYSQLI_ASSOC)['meta_value'];

	if ($sort == 'bybook') {
		$total = 0;
		$summ = unserialize($book['sold']);

		if ($period == '`date` >= CURDATE()')
			$date = date('Y-m-d');

		foreach ($summ as $sum) {
			if (strtotime($sum[0]) >= $date) $total += $sum[1];
		}
		$book['summ'] = $total;
	}

	$array[] = $book;
}

// sort array by date
for ($i=0; $i < count($array); $i++) { 
	for ($x=$i + 1; $x < count($array); $x++) { 
		if ($array[$i]['date'] < $array[$x]['date']) {
			$temp = $array[$x];
			$array[$x] = $array[$i];
           	$array[$i] = $temp;
		}
	}
}
?>

<div class="container">
	<?php
	// заголовок, вкладки выбора всех книг/цифровых/аудио, вкладки книги/просмотры
	include 'templates/analitics_tabs.php';
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
						
						if ($role == 'ББТ')
							table_th('Выручка<br>ББТ', 'to_bbt', 'books');
						elseif ($role == 'Команда')
							table_th('Выручка<br>команды', 'to_command', 'books');
						elseif ($role == 'Партнер')
							table_th('Выручка<br>партнера', 'to_partner', 'books');

						table_th('Наименование книг', 'name', 'views');
						table_th('Просмотры', 'views', 'views');
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

							if ($role == 'ББТ')
								simple_td(strval($array[$i]['to_bbt']).$rub);
							elseif ($role == 'Команда')
								simple_td(strval($array[$i]['to_command']).$rub);
							elseif ($role == 'Партнер')
								simple_td(strval($array[$i]['to_partner']).$rub);
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
				['user_id', $user_id],
				['role', $role],
				['page_table', $_GET['table']],
			],
			[$page, $pages, basename(__FILE__, '.php')],
			[$rows]
		);
		?>
	</div>
</div>




<?php include 'footer.php'; ?>