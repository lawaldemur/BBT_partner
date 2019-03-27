<?php include 'header.php'; ?>
<?php require 'db_shop.php'; ?>

<?php
if ($view_position != 'client') {
	$address = json_decode($view['data'])->general_address;
	$address = $address == '' ? 'г. '.$view['city'] : $address;

	$picture = '/avatars' . '/' . $view['picture'];

	if ($role == 'ББТ')
		$commands = $dbc->query("SELECT * FROM `users` WHERE `position` = 'command'");

	if ($view_position == 'command')
		$children = $dbc->query("SELECT * FROM `users` WHERE `parent` = $id");
	elseif ($view_position == 'partner') {
		// get code
		$code = $dbc->query("SELECT * FROM `users` WHERE `id` = $id");
		$code = $code->fetch_array(MYSQLI_ASSOC)['code'];

		require 'db_shop.php';
		$children = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '$code'");
	}


	$get_today = 0;
	$get_week = 0;
	$get_month = 0;
	$get_year = 0;
	if ($view['position'] == 'command')
		$column = 'to_command';
	elseif ($view['position'] == 'partner')
		$column = 'to_partner';
		

	// get today
	$todays = $dbc->query("SELECT * FROM `sold` WHERE `{$column}_id` = $id AND `date` >= CURDATE()");
	if ($todays)
		foreach ($todays as $today)
			$get_today += $today[$column];
	// get this week
	$weeks = $dbc->query("SELECT * FROM `sold` WHERE `{$column}_id` = $id AND `date` >= DATE_SUB(CURRENT_DATE, INTERVAL 7 DAY)");
	if ($weeks)
		foreach ($weeks as $week)
			$get_week += $week[$column];
	// get this month
	$months = $dbc->query("SELECT * FROM `sold` WHERE `{$column}_id` = $id AND `date` >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)");
	if ($months)
		foreach ($months as $month)
			$get_month += $month[$column];		
	// get this week
	$years = $dbc->query("SELECT * FROM `sold` WHERE `{$column}_id` = $id AND `date` >= DATE_SUB(CURRENT_DATE, INTERVAL 30 DAY)");
	if ($years)
		foreach ($years as $year)
			$get_year += $year[$column];	

} else {
	// get picture
	$picture = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'profile_pic'");
	$picture = $picture->fetch_array(MYSQLI_ASSOC)['meta_value'];
	$picture = $dbc_shop->query("SELECT * FROM `wp_posts` WHERE `ID` = $picture");
	if ($picture) {
		$picture = $picture->fetch_array(MYSQLI_ASSOC)['guid'];
		$picture = explode('/', $picture);
		$picture = 'http://bbt-online.ru/wp-content/uploads/' . $picture[count($picture) - 1];
	} else {
		$picture = '/avatars/avatar.png';
	}
	

	// get phone and email for about tab
	$billing_phone = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'billing_phone'");
	if ($billing_phone)
		$billing_phone = $billing_phone->fetch_array(MYSQLI_ASSOC)['meta_value'];
	$billing_email = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'billing_email'");
	if ($billing_email)
		$billing_email = $billing_email->fetch_array(MYSQLI_ASSOC)['meta_value'];
	$billing_email2 = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `ID` = $id");
	if ($billing_email2) {
		$billing_email2 = $billing_email2->fetch_array(MYSQLI_ASSOC);
		if (strpos($billing_email2['user_email'], '@phone') === false)
			$billing_email = $billing_email2['user_email'];
	}
}


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


if ($view['position'] == 'command') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_command_id` = $id");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_command_id` = $id");
} elseif ($view['position'] == 'partner') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_partner_id` = $id");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_partner_id` = $id");
} else {
	$books = $dbc->query("SELECT * FROM `sold` $where AND `client` = $id");
}


// pagination
if (!isset($_GET['page'])) $_GET['page'] = 1;
if (!isset($_COOKIE['rows'])) $rows = 20;
else $rows = $_COOKIE['rows'];
$offset = $_GET['page'] * $rows - $rows;
$limit = $_GET['page'] * $rows;
$pages = ceil($books->num_rows / $rows) + 1;

while ($offset > count($array)) {
	$_POST['page']--;
	$offset = $_POST['page'] * $rows - $rows;
	$limit = $_POST['page'] * $rows;
}

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

	if ($sort == 'bybook' && $view_position != 'client') {
		$total = 0;
		$summ = unserialize($book['sold']);

		if ($period == '`date` >= CURDATE()')
			$date = date('Y-m-d');

		foreach ($summ as $sum) {
			if (strtotime($sum[0]) >= $date) $total += $sum[1];
		}
		$book['summ'] = $total;
	}

	$book['count'] = $book['summ'] / $book['price'];

	if (!($role == 'command' || $role == 'partner')) {
		$book['name'] = $dbc_shop->query("SELECT * FROM `wp_posts` WHERE `ID` = {$book['product']}");
		$book['name'] = $book['name']->fetch_array(MYSQLI_ASSOC)['post_title'];

		$book['other'] = $dbc_shop->query("SELECT * FROM `wp_postmeta` WHERE `post_id` = {$book['variation']} AND `meta_key` = 'attribute_pa_writer'");
		$book['other'] = $book['other']->fetch_array(MYSQLI_ASSOC)['meta_value'];
		$book['other'] = $dbc_shop->query("SELECT * FROM `wp_terms` WHERE `slug` = '{$book['other']}'");
		$book['other'] = $book['other']->fetch_array(MYSQLI_ASSOC)['name'];

		if ($book['other'] == '') {
			$other = $dbc->query("SELECT * FROM `analitic` WHERE `product` = {$book['product']}");
			if ($other)
				foreach ($other as $author) {
					$book['other'] = $author['other'];
					break;
				}
		}
		
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

<div id="document_viewer">
	<div class="close_document_viewer"><img src="/img/close_document_viewer.svg" alt="close_form" height="18"></div>

	<img src="" alt="document">
</div>

<div class="container">
	<div class="row">
		<div class="col command_list_col">
			<h1 class="bread_cumbs_view">
				<?=$bread_cumb?>
				<?php
				if ($view['position'] == 'partner' && $role == 'ББТ'):
					$command = $dbc->query("SELECT * FROM `users` WHERE `id` = {$view['parent']}");
					$command = $command->fetch_array(MYSQLI_ASSOC); ?>
					<a href="/view.php?id=<?=$command['id']?>"><span class="back_bc">&rarr;</span><?=$command['name']?></a>
				<?php elseif ($view_position == 'client'):
					$code = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `id` = $id");
					$code = $code->fetch_array(MYSQLI_ASSOC);
					$parent = $code['parent'];
					$code = $code['code'];

					$flag = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `code` = '$parent'");
					$breads_links = array();
					while ($flag->num_rows) {
						$flag = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `code` = '$parent'");
						$flag = $flag->fetch_array(MYSQLI_ASSOC);
						$parent = $flag['parent'];

						// get name
						$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = {$flag['ID']} AND `meta_key` = 'first_name'");
						if ($meta_array)
							$client['first_name'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];
						// get second name
						$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = {$flag['ID']} AND `meta_key` = 'last_name'");
						if ($meta_array)
							$client['last_name'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

						$breads_links[] = "<a href=\"/view.php?id=client{$flag['ID']}\"><span class=\"back_bc\">&rarr;</span>{$client['first_name']} {$client['last_name']}</a>";
					}

					$partner = $dbc->query("SELECT * FROM `users` WHERE `code` = '$parent'");
					if ($partner) {
						$partner = $partner->fetch_array(MYSQLI_ASSOC);

						$command = $dbc->query("SELECT * FROM `users` WHERE `id` = {$partner['parent']}");
						if ($command) {
							$command = $command->fetch_array(MYSQLI_ASSOC);

							if ($role == 'ББТ'):?>
								<a href="/view.php?id=<?=$command['id']?>"><span class="back_bc">&rarr;</span><?=$command['name']?></a>
							<?php endif;

							if ($role == 'Команда' || $role == 'ББТ'): ?>
								<a href="/view.php?id=<?=$partner['id']?>"><span class="back_bc">&rarr;</span><?=$partner['name']?></a>
							<?php endif;

							foreach ($breads_links as $bread) {
								echo $bread;
							}
						}
					}
				endif; ?>
			</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-5 about_col">
			<div class="referer"><a href="<?=$_SERVER['HTTP_REFERER']?>"><img src="/img/referer.svg" alt="referer"></a></div>
			<!-- <img src="<?=$picture?>" alt="avatar" class="avatar"> -->
			<div class="avatar" style="background-image: url(<?=$picture?>);"></div>
			<div class="finance_view_name">
				<div class="name"><?=$view['name']?><span class="count"></span></div>
				<div class="address"><?=$address?></div>
			</div>
		</div>
		<?php if ($view_position != 'client'): ?>
		<div class="col-7">
			<div class="about_info">
				<div class="about">
					<div class="about_value"><?=$view['digital_percent']?>%</div>
					<?php if ($view_position == 'command'): ?>
						<div class="about_desc">% команды</div>
					<?php elseif ($view_position == 'partner'): ?>
						<div class="about_desc">% партнера</div>
					<?php endif ?>
				</div>
				<div class="about_border"></div>
				<div class="about">
					<div class="about_value"><?=$children->num_rows;?></div>
					<?php if ($view['position'] == 'command'): ?>
						<div class="about_desc">Партнеры</div>
					<?php else: ?>
						<div class="about_desc">Клиенты</div>
					<?php endif; ?>
				</div>
				<div class="about_border"></div>
				<div class="about">
					<div class="about_value"><?=$get_today?> ₽</div>
					<div class="about_desc">Выручка<br>за сегодня</div>
				</div>
				<div class="about_border"></div>
				<div class="about">
					<div class="about_value"><?=$get_week?> ₽</div>
					<div class="about_desc">Выручка<br>за неделю</div>
				</div>
				<div class="about_border"></div>
				<div class="about">
					<div class="about_value"><?=$get_month?> ₽</div>
					<div class="about_desc">Выручка<br>за месяц</div>
				</div>
				<div class="about_border"></div>
				<div class="about">
					<div class="about_value"><?=$get_year?> ₽</div>
					<div class="about_desc">Выручка<br>за год</div>
				</div>
			</div>
		</div>
		<?php endif ?>
	</div>

	<div class="row">
		<div class="col-12">
			<div class="view_tabs">
				<div class="tab_1 tab active_tab" data-tab="books">Книги</div>
				<?php if ($view_position != 'client'): ?>
				<div class="tab_2 tab" data-tab="profit">Выручка</div>
				<?php endif; ?>
				<?php if ($view['position'] == 'command'): ?>
					<div class="tab_3 tab" data-tab="children">Партнеры</div>
					<div class="tab_4 tab" data-tab="about_view">О команде</div>
				<?php elseif ($view['position'] == 'partner'): ?>
					<div class="tab_3 tab" data-tab="children">Клиенты</div>
					<div class="tab_4 tab" data-tab="about_view">О партнере</div>
				<?php elseif ($view_position == 'client' && $role != 'Партнер'): ?>
					<div class="tab_2 tab" data-tab="about_view">О клиенте</div>
				<?php endif ?>
			</div>
		</div>
	</div>
</div>




<div class="container view_content_container">

	<div class="row books_or_children_row books">
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

			<div class="month_drop_list">
				<select id="month_drop_list">
					<option value="0">Детализация по дням <img alt="" src="/img/month_drop_list.png"></option>								
					<option value="1">Детализация по неделям <img alt="" src="/img/month_drop_list.png"></option>
					<option value="2">Детализация по месяцам <img alt="" src="/img/month_drop_list.png"></option>
				</select>
				<img src="img/drop_arrow.svg" alt="drop_arrow">
			</div>


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
				<input type="text" id="search_table_command" placeholder="Введите имя партнера или нас. пункт">
				<img src="/img/search_icon.svg" alt="search_icon" width="12" height="12">
				<img src="/img/active_search_icon.svg" alt="active_search_icon" width="12" height="12">
			</div>

		</div>

		<div class="col-12">
			<!-- graph -->
			<?php if ($view_position != 'client'): ?>
			<div class="graph_view">
				<!-- EARN -->
				<?php if(isset($_COOKIE['period'])){
						$date = $_COOKIE['period'];
						if (isset($_COOKIE['format']) && $_COOKIE['format'] != 'all'){
							$date .= ' AND `format` = \'' . $_COOKIE['format'] . "'";
						}
					}
					else
						$date = "YEAR(`date`) = YEAR(CURDATE())";
				?>

				<div class="col-12 earn_col">
					<span class="fin_m_span1 fin_m_span_dogovor">
					<?php
						$result_on_d = $dbc->query("SELECT SUM(to_{$view['position']}) FROM `sold` WHERE `to_{$view['position']}_id` = $id AND $date");
						if ($result_on_d)
						foreach ($result_on_d as $money)
							echo round($money["SUM(to_{$view['position']})"], 2);
						else
							echo 0;
					?> &#8381;</span>
					<span class="fin_m_span2"><img alt="" src="img/Ellipse3.png"> Заработано по договорам</span>
				</div>
				<div class="col-12 graph_col">
					<div class="graph graph_">
						<div class="cd_s_"></div>
							<div id="chartdiv_"></div>
						<div class="cd_e_"></div>
					</div>
				</div>
			</div>
		<?php endif; ?>

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
						<?php if ($view_position != 'client'): ?>
							<th class="books" data-column="to_bbt" style="padding-right: 20px;">Выручка<br>ББТ</th>
						<?php endif ?>

						<?php if ($view['position'] == 'command'): ?>
							<th class="children" data-column="name" style="padding-left: 20px;">Имя партнера</th>
							<th class="children" data-column="clients">Кол-во<br>клиентов</th>
							<th class="children" data-column="summ_sold">Сумма<br>продаж</th>
							<th class="children" data-column="summ_get">Сумма<br>вознагражд.</th>
							<th class="children" data-column="summ_wait">Выплаты</th>
						<?php elseif ($view['position'] == 'partner'): ?>
							<th class="children" data-column="name" style="padding-left: 20px; width: 700px;">Имя клиента</th>
							<th class="children" data-column="clients" style="width: 80px;">Кол-во<br>клиентов</th>
							<th class="children" data-column="sold" style="width: 80px;">Сумма<br>продаж</th>
							<th class="children" data-column="bought" style="width: 100px;">Сумма покупок</th>
						<?php endif; ?>
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
							<?php if ($view_position != 'client'): ?>
								<td><?=$array[$i]['to_bbt']?> &#8381;</td>
							<?php endif; ?>
						</tr>
					<?php endfor; ?>
				</tbody>
			</table>
		</div>

		<div class="col-12 after_table_filters">
			<input type="hidden" id="active_page" value="<?=$_GET['page']?>">
			<input type="hidden" id="user_id" value="<?=$id?>">
			<input type="hidden" id="role" value="<?=$view['position']?>">
			<input type="hidden" id="page_table" value="<?=$_GET['table']?>">

			<div class="pagination_list">
				<div class="prev_page"><img src="/img/prev_page.svg" alt="prev_page"></div>
				<div class="pages_list">
					<?php for ($i=1; $i < $pages; $i++) {
						if (($_SERVER['REQUEST_URI'] == "/view.php?id=$id" && $i == 1) || ($_GET['page'] == $i)): ?>
							<a class="page active_page" href="/view.php?id=<?=$id?>&page=<?=$i?>"><?=$i?></a>
						<?php else: ?>
							<a class="page" href="/view.php?id=<?=$id?>&page=<?=$i?>"><?=$i?></a>
						<?php endif ?>
					<?php } ?>
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



	<!-- <div class="row profit">
		1
	</div> -->

	

	<div class="row about_view">
		<?php $data = json_decode($view['data']); ?>
		<?php if ($view_position == 'command'): ?>
		<div class="col-6">
			<div class="info">
				<div class="info_title">Общая информация</div>
				<div class="info_value">
					<div class="info_value_desc">Наименование</div>
					<div class="info_value_val"><?=$data->general_name?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">Адрес</div>
					<div class="info_value_val"><?=$data->general_address?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">Телефон</div>
					<div class="info_value_val"><?=$data->general_phone?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">Эл. почта</div>
					<div class="info_value_val"><?=$data->general_email?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">ОГРН</div>
					<div class="info_value_val"><?=$data->general_ogrn?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">ИНН / КПП</div>
					<div class="info_value_val"><?=$data->general_inn_kpp?></div>
				</div>
			</div>
			<div class="info">
				<div class="info_title">Банковские реквизиты</div>
				<div class="info_value">
					<div class="info_value_desc">Наим-е банка</div>
					<div class="info_value_val"><?=$data->bank_name?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">Расчетный счет</div>
					<div class="info_value_val"><?=$data->bank_bill?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">Корр. счет</div>
					<div class="info_value_val"><?=$data->bank_chet?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">БИК</div>
					<div class="info_value_val"><?=$data->bank_bik?></div>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="info">
				<div class="info_title">Руководитель</div>
				<div class="info_value">
					<div class="info_value_desc">ФИО</div>
					<div class="info_value_val"><?=$data->organizator_name?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">Должность</div>
					<div class="info_value_val"><?=$data->organizator_position?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">Телефон</div>
					<div class="info_value_val"><?=$data->organizator_phone?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">Эл. почта</div>
					<div class="info_value_val"><?=$data->organizator_email?></div>
				</div>
			</div>
			<div class="info">
				<div class="info_title">Бухгалтер</div>
				<div class="info_value">
					<div class="info_value_desc">ФИО</div>
					<div class="info_value_val"><?=$data->accountant_name?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">Телефон</div>
					<div class="info_value_val"><?=$data->accountant_phone?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">Эл. почта</div>
					<div class="info_value_val"><?=$data->accountant_email?></div>
				</div>
			</div>
			<div class="info">
				<div class="info_title">Менеджер проекта</div>
				<div class="info_value">
					<div class="info_value_desc">ФИО</div>
					<div class="info_value_val"><?=$data->manager_name?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">Телефон</div>
					<div class="info_value_val"><?=$data->manager_phone?></div>
				</div>
				<div class="info_value">
					<div class="info_value_desc">Эл. почта</div>
					<div class="info_value_val"><?=$data->manager_email?></div>
				</div>
			</div>
		</div>
		<?php elseif ($view_position == 'partner'): ?>
			<div class="col-6">
				<div class="info">
					<div class="info_title">Общие данные</div>
					<div class="info_value">
						<div class="info_value_desc">ФИО</div>
						<div class="info_value_val"><?=$data->general_name?></div>
					</div>
					<div class="info_value">
						<div class="info_value_desc">Духовное имя</div>
						<div class="info_value_val"><?=$data->general_soul_name?></div>
					</div>
					<div class="info_value">
						<div class="info_value_desc">Адрес</div>
						<div class="info_value_val"><?=$data->general_address?></div>
					</div>
				</div>
				<div class="info">
					<div class="info_title">Контактные данные</div>
					<div class="info_value">
						<div class="info_value_desc">Телефон</div>
						<div class="info_value_val"><?=$data->contact_phone?></div>
					</div>
					<div class="info_value">
						<div class="info_value_desc">Эл. почта</div>
						<div class="info_value_val"><?=$data->contact_email?></div>
					</div>
				</div>
			</div>
			<div class="col-6">
				<div class="info">
					<div class="info_title">Паспортные данные</div>
					<div class="info_value">
						<div class="info_value_desc">Номер / Серия</div>
						<div class="info_value_val"><?=$data->pasport_seria.' '.$data->pasport_number?></div>
					</div>
					<div class="info_value">
						<div class="info_value_desc">Кем выдан</div>
						<div class="info_value_val"><?=$data->pasport_gave?></div>
					</div>
					<div class="info_value">
						<div class="info_value_desc">Дата выдачи</div>
						<div class="info_value_val"><?=$data->pasport_date?></div>
					</div>
					<div class="info_passports">
						<?php if ($data->passport): ?>
							<?php foreach ($data->passport as $passport): ?>
								<div class="passport_view" data-passport='<?=$passport?>'>
									<img src="/img/passport_icon.svg" alt="passport_icon">
									<span>Фото паспорта</span>
								</div>
							<?php endforeach ?>
						<?php endif ?>
					</div>
				</div>
				<div class="info">
					<div class="info_title">Другие данные</div>
					<div class="info_value">
						<div class="info_value_desc">ИНН</div>
						<div class="info_value_val"><?=$data->other_inn?></div>
					</div>
					<div class="info_value">
						<div class="info_value_desc">СНиЛС</div>
						<div class="info_value_val"><?=$data->other_snils?></div>
					</div>
				</div>
			</div>
		<?php else: ?>
			<div class="col-6">
				<div class="info">
					<div class="info_title">Контактные данные</div>
					<div class="info_value">
						<div class="info_value_desc">Телефон</div>
						<div class="info_value_val"><?=$billing_phone?></div>
					</div>
					<div class="info_value">
						<div class="info_value_desc">Эл. почта</div>
						<div class="info_value_val"><?=$billing_email?></div>
					</div>
				</div>
			</div>
		<?php endif ?>
	</div>

</div>













<?php include 'footer.php'; ?>
