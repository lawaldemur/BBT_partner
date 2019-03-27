<?php include 'header.php'; ?>
<?php require 'db_shop.php'; ?>

<?php
if (!isset($_COOKIE['sort'])) {
	$sort = 'bydate';
	echo '<script>document.cookie = "sort=bydate";</script>';
} else $sort = $_COOKIE['sort'];

// period
if (isset($_COOKIE['period']))
	$period = $_COOKIE['period'];
else {
	$period = '`date` >= CURDATE()';
	echo '<script>document.cookie = "period=`date` >= CURDATE()";</script>';
}

$where = 'WHERE '.$period;
if (isset($_COOKIE['format']) && $_COOKIE['format'] != '' && $_COOKIE['format'] != 'all')
	$where .= ' AND `format` = \''.$_COOKIE['format']."'";
else
	echo '<script>document.cookie = "format=all";</script>';

// if ($role == 'ББТ')
// 	$books = $dbc->query("SELECT * FROM `analitic` $where");
// elseif ($role == 'Команда')
// 	$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_command_id` = $user_id");
// elseif ($role == 'Партнер')
// 	$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_partner_id` = $user_id");
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
if (!isset($_GET['page'])) $_GET['page'] = 1;
if (!isset($_COOKIE['rows'])) $rows = 20;
else $rows = $_COOKIE['rows'];
$offset = $_GET['page'] * $rows - $rows;
$limit = $_GET['page'] * $rows;
$pages = ceil($books->num_rows / $rows) + 1;



// content for BBT - all books
// if ($role == 'ББТ') {
// 	if ($sort == 'bydate')
// 		$books = $dbc->query("SELECT * FROM `analitic` $where");
// 	else
// 		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where");
// } elseif ($role == 'Команда') {
// 	if ($sort == 'bydate')
// 		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_command_id` = $user_id");
// 	else
// 		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_command_id` = $user_id");
// } elseif ($role == 'Партнер') {
// 	if ($sort == 'bydate')
// 		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_partner_id` = $user_id");
// 	else
// 		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_partner_id` = $user_id");
// }

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
	<div class="row">
		<div class="col-2 command_list_col">
			<h1>Аналитика</h1>
		</div>

		<div class="analitics_tabs col-9 offset-1">
			<div class="analitics_tab analitics_tab_active" id="all_books">
				<img src="/img/tab_all_0.svg" alt="all_books" class="analitic_tab_default_img"><img src="/img/tab_all_1.svg" alt="all_books_active" class="analitic_tab_active_img">
				<span>Все книги</span>
			</div>
			<div class="analitics_tab" id="digital_books">
				<img src="/img/tab_digit_0.svg" alt="digital_books" class="analitic_tab_default_img"><img src="/img/tab_digit_1.svg" alt="digital_books_active" class="analitic_tab_active_img">
				<span>Электронные книги</span>
			</div>
			<div class="analitics_tab" id="audio_books">
				<img src="/img/tab_audio_0.svg" alt="audio_books" class="analitic_tab_default_img"><img src="/img/tab_audio_1.svg" alt="audio_books_active" class="analitic_tab_active_img">
				<span>Аудио-книги</span>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-12 tabs_format_col">
			<div class="tab_format active_tab_format" id="tab_format_books">Книги</div>
			<div class="tab_format" id="tab_format_views">Просмотры</div>
			<!-- <div class="tab_format">Прочитано</div> -->
		</div>
	</div>
<!-- 	<div class="row">
		<input type="text" id="datepicker"/>
				<p id="result-5"></p>
	</div> -->
	<div class="row table_row analitics_row active_table_row" data-table="all">
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

			<div class="choose_format">
				<?php if (!isset($_COOKIE['format']) || $_COOKIE['format'] == 'all' || $_COOKIE['format'] == 'digital'): ?>
					<div class="choose choose_active" id="digital">
				<?php else: ?>
					<div class="choose" id="digital">
				<?php endif ?>
					<img src="/img/choose_digit.svg" alt="choose">
					<img src="/img/choose_digit_1.svg" alt="choose_active">
				</div>

				<?php if (!isset($_COOKIE['format']) || $_COOKIE['format'] == 'all' || $_COOKIE['format'] == 'audio'): ?>
					<div class="choose choose_active" id="audio">
				<?php else: ?>
					<div class="choose" id="audio">
				<?php endif ?>
					<img src="/img/choose_audio.svg" alt="choose">
					<img src="/img/choose_audio_1.svg" alt="choose_active">
				</div>
			</div>

			<div class="sort_date_or_book">
				<?php if (!isset($_COOKIE['sort']) || $_COOKIE['sort'] == 'bydate'): ?>
					<div class="sort_date sort_active">По дате</div>
				<?php else: ?>
					<div class="sort_date">По дате</div>
				<?php endif ?>

				<?php if ($_COOKIE['sort'] == 'bybook'): ?>
					<div class="sort_book sort_active">По книгам</div>
				<?php else: ?>
					<div class="sort_book">По книгам</div>
				<?php endif ?>
			</div>

			<div class="search_table">
				<input type="text" id="search_table_command" placeholder="Введите название книги или автора" value="<?=$_GET['search']?>">
				<img src="/img/search_icon.svg" alt="search_icon" width="12" height="12">
				<img src="/img/active_search_icon.svg" alt="active_search_icon" width="12" height="12">
			</div>

		</div>

		<div class="col-12">
			<table id="book" data-task="<?=$sort?>" data-table="all">
				<thead>
					<tr>
						<?php if ($sort == 'bydate'): ?>
							<th class="books" data-column="date">Дата <span class="sort_upper sortColumn_type">&#9660;</span></th>
							<th class="books" data-column="name">Наименование книг</th>
						<?php else: ?>
							<th class="books" data-column="name">Наименование книг <span class="sort_upper sortColumn_type">&#9660;</span></th>
						<?php endif; ?>
						<th class="books" data-column="format">Формат</th>
						<th class="books" data-column="count">Кол-во</th>
						<th class="books" data-column="price">Цена<br>за единицу</th>
						<th class="books" data-column="summ">Общая<br>стоимость</th>
						<?php if ($role == 'ББТ') { ?>
							<th class="books" data-column="to_bbt">Выручка<br>ББТ</th>
						<?php } elseif ($role == 'Команда') { ?>
							<th class="books" data-column="to_command">Выручка<br>команды</th>
						<?php } elseif ($role == 'Партнер') { ?>
							<th class="books" data-column="to_partner">Выручка<br>партнера</th>
						<?php } ?>

						<th data-column="name" class="views">Наименование книг</th>
						<th data-column="views" class="views">Просмотры</th>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=$offset; $i < $limit && $i < count($array); $i++): ?>
						<tr>
							<?php if ($sort == 'bydate'): ?>
								<td><?=date("d.m.Y", strtotime($array[$i]['date']))?></td>
							<?php endif; ?>
							<td class="table_product_name">
								<img src="<?=$array[$i]['img']?>" alt="product_picture">
								<div class="author_name">
									<span class="book_name"><?=$array[$i]['name']?></span>
									<span class="book_author"><?=$array[$i]['other']?></span>
								</div>
							</td>
							<td><?=$array[$i]['format']?></td>
							<td><?=$array[$i]['summ'] / $array[$i]['price']?></td>
							<td><?=$array[$i]['price']?> &#8381;</td>
							<td><?=$array[$i]['summ']?> &#8381;</td>
							<?php if ($role == 'ББТ') { ?>
								<td><?=$array[$i]['to_bbt']?> &#8381;</td>
							<?php } elseif ($role == 'Команда') { ?>
								<td><?=$array[$i]['to_command']?> &#8381;</td>
							<?php } elseif ($role == 'Партнер') { ?>
								<td><?=$array[$i]['to_partner']?> &#8381;</td>
							<?php } ?>
						</tr>
					<?php endfor; ?>

				</tbody>
			</table>

		</div>

		<div class="col-12 after_table_filters">
			<input type="hidden" id="active_page" value="<?=$_GET['page']?>">
			<input type="hidden" id="user_id" value="<?=$user_id?>">
			<input type="hidden" id="role" value="<?=$role?>">
			<input type="hidden" id="page_table" value="<?=$_GET['table']?>">

			<div class="pagination_list">
				<div class="prev_page"><img src="/img/prev_page.svg" alt="prev_page"></div>
				<div class="pages_list">
					<!-- <?php for ($i=1; $i < $pages; $i++) {
						if (($_SERVER['REQUEST_URI'] == "/analitic.php" && $i == 1) || ($_GET['page'] == $i)): ?>
							<a class="page active_page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
						<?php else: ?>
							<a class="page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
						<?php endif ?>
					<?php } ?> -->

					<?php if ($pages <= 10) {
						for ($i=1; $i < $pages; $i++) {
							if (($_SERVER['REQUEST_URI'] == "/analitic.php" && $i == 1) || ($_GET['page'] == $i)): ?>
								<a class="page active_page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
							<?php else: ?>
								<a class="page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
							<?php endif ?>
						<?php }
					} else {
						if ($_GET['page'] < 7) {
							for ($i=1; $i < 8; $i++) {
								if (($_SERVER['REQUEST_URI'] == "/analitic.php" && $i == 1) || ($_GET['page'] == $i)): ?>
									<a class="page active_page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
								<?php else: ?>
									<a class="page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
								<?php endif ?>
							<?php } ?>
							<span class="triple_dots">...</span>
							<a class="page" href="/analitic.php?page=<?=$pages - 1?>"><?=$pages - 1?></a> <?php
						} elseif ($_GET['page'] >= $pages - 6) { ?>
							<a class="page" href="/analitic.php?page=1">1</a>
							<span class="triple_dots">...</span>
							<?php
							for ($i=$pages - 7; $i < $pages; $i++) {
								if (($_SERVER['REQUEST_URI'] == "/analitic.php" && $i == 1) || ($_GET['page'] == $i)): ?>
									<a class="page active_page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
								<?php else: ?>
									<a class="page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
								<?php endif ?>
							<?php }
						} else { ?>
							<a class="page" href="/analitic.php?page=1">1</a>
							<span class="triple_dots">...</span>
							<?php
							for ($i=$_GET['page'] - 3; $i < $_GET['page'] + 4; $i++) {
								if (($_SERVER['REQUEST_URI'] == "/analitic.php" && $i == 1) || ($_GET['page'] == $i)): ?>
									<a class="page active_page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
								<?php else: ?>
									<a class="page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
								<?php endif ?>
							<?php } ?>
							<span class="triple_dots">...</span>
							<a class="page" href="/analitic.php?page=<?=$pages - 1?>"><?=$pages - 1?></a> <?php
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




<?php include 'footer.php'; ?>