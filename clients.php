<?php include 'header.php'; ?>
<?php include 'db_shop.php'; ?>

<?php
$period = $_COOKIE['period'];
$_POST['search'] = $_GET['search'];

if ($role == 'ББТ') {
	// get all clients
	$clients_arr = $dbc_shop->query("SELECT * FROM `wp_users`");
	$count = $clients_arr->num_rows;
} elseif ($role == 'Команда') {
	// get partners of command, and after get clients of each partner
	$clients_arr = array();
	$partners_array = $dbc->query("SELECT * FROM `users` WHERE `position` = 'partner' AND `parent` = $user_id");
	foreach ($partners_array as $partner) {
		$clients_array = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '".$partner['code']."'");
		foreach ($clients_array as $client)
			$clients_arr[] = $client;
	}
	$count = count($clients_arr);
} else {
	// get clients of partner
	$code = $dbc->query("SELECT * FROM `users` WHERE `id` = $user_id");
	$code = $code->fetch_array(MYSQLI_ASSOC);
	$code = $code['code'];

	$clients_arr = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '$code'");
	$count = $clients_arr->num_rows;
}

$array = [];
foreach ($clients_arr as $client) {
	$id = $client['ID'];

	// get name
	$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'first_name'");
	if ($meta_array)
		$client['first_name'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

	// get second name
	$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'last_name'");
	if ($meta_array)
		$client['last_name'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

	$client['name'] = $client['first_name'].' '.$client['last_name'];

	// get city
	$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'billing_city'");
	if ($meta_array)
		$client['city'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

	// get picture
	$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'profile_pic'");
	if ($meta_array) {
		$client['picture'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];
		$meta_array = $dbc_shop->query("SELECT * FROM `wp_posts` WHERE `ID` = {$client['picture']}");
		if ($meta_array)
			$client['picture'] = 'http://bbt-online.ru/wp-content/uploads/' . end(explode('/', $meta_array->fetch_array(MYSQLI_ASSOC)['guid']));
	}
	if ($client['picture'] == '')
		$client['picture'] = '/avatars/avatar.png';
		

	// get parent
	$parent = $dbc->query("SELECT * FROM `users` WHERE `code` = '{$client['parent']}'");
	if ($parent) {
		$parent = $parent->fetch_array(MYSQLI_ASSOC)['parent'];
		$parent = $dbc->query("SELECT * FROM `users` WHERE `id` = '$parent'");
		$client['parent'] = $parent->fetch_array(MYSQLI_ASSOC)['name'];
	}
	if ($client['parent'] == '')
		$client['parent'] = 'ББТ';

	// get clients
	$clients = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `code` = '{$client['parent']}'");
	$client['clients'] = $clients->num_rows;

	// get bought summ
	$client['bought'] = 0;
	$bought = $dbc->query("SELECT * FROM `sold` WHERE `client` = $id AND $period");
	if ($bought)
		foreach ($bought as $value)
			$client['bought'] += $value['summ'];
	
	// get sold summ
	$client['sold'] = 0;
	if ($clients)
		foreach ($clients as $value) {
			$bought = $dbc->query("SELECT * FROM `sold` WHERE `client` = {$value['ID']} AND $period");
			if ($bought)
			foreach ($bought as $value2)
				$client['sold'] += $value2['summ'];
		}

	if ($_POST['search'] != '' &&
		stripos(mb_strtolower($client['parent'], 'UTF-8'), mb_strtolower($_POST['search'], 'UTF-8')) === false &&
		stripos(mb_strtolower($client['first_name'].' '.$client['last_name'], 'UTF-8'), mb_strtolower($_POST['search'], 'UTF-8')) === false)
		continue;

	$array[] = $client;
}

for ($i=0; $i < count($array); $i++) { 
	for ($x=$i + 1; $x < count($array); $x++) { 
		if ($array[$i]['name'] > $array[$x]['name']) {
			$temp = $array[$x];
			$array[$x] = $array[$i];
           	$array[$i] = $temp;
		}
	}
}


// pagination
if (!isset($_GET['page'])) $_GET['page'] = 1;
if (!isset($_COOKIE['rows'])) $rows = 20;
else $rows = $_COOKIE['rows'];
$offset = $_GET['page'] * $rows - $rows;
$limit = $_GET['page'] * $rows;
$pages = ceil($count / $rows) + 1;
?>


<div class="container">
	<div class="row">
		<div class="col command_list_col">
			<h1>Клиенты <span class="command_count"><?=$count?></span></h1>
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
				<?php if ($role == 'Команда'): ?>
					<input type="text" id="search_table_command" placeholder="Введите имя клиента или нас. пункт" value="<?=$_GET['search']?>">
				<?php else: ?>
					<input type="text" id="search_table_command" placeholder="Введите имя клиента или команду" value="<?=$_GET['search']?>">
				<?php endif ?>
				
				<img src="/img/search_icon.svg" alt="search_icon" width="12" height="12">
				<img src="/img/active_search_icon.svg" alt="active_search_icon" width="12" height="12">
			</div>
		</div>
		
		<div class="col-12">
			<table id="users_table" data-position="client" data-role="<?=$role?>">
				<thead>
					<tr>
						<th data-column="name">Имя клиента <span class="sort_upper sortColumn_type">&#9660;</span></th>
						<th data-column="parent">Принадлежность</th>
						<th data-column="clients" class="table_align_center">Кол-во<br>клиентов</th>
						<th data-column="sold" class="table_align_center">Сумма<br>продаж</th>
						<th data-column="bought" class="table_align_center">Сумма<br>покупок</th>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=$offset; $i < $limit && $i < count($array); $i++): ?>
						<tr data-id="client<?=$array[$i]['ID']?>">
							<td class="table_command_name">
								<div class="command_picture_wrapp" style="background-image: url(<?=$array[$i]['picture']?>);"></div>
								<div class="command_name_wrap">
									<span class="command_name"><?=$array[$i]['first_name'].' '.$array[$i]['last_name']?></span>
									<span class="command_city"><?=$array[$i]['city']?></span>
								</div>
							</td>
							<td><?=$array[$i]['parent']?></td>
							<td class="table_align_center"><?=$array[$i]['clients']?></td>
							<td class="table_align_center"><?=$array[$i]['sold']?> &#8381;</td>
							<td class="table_align_center"><?=$array[$i]['bought']?> &#8381;</td>
						</tr>
					<?php endfor; ?>
				</tbody>
			</table>
			<?php if ($role != 'ББТ'): ?>
				<style>
					#users_table tr > *:nth-child(2),
					#users_table tr > *:nth-child(3),
					#users_table tr > *:nth-child(4) {
						display: none;
					}
					#users_table tr > *:nth-child(1) {
						width: 880px;
					}
				</style>
			<?php endif; ?>
		</div>

		<div class="col-12 after_table_filters">
			<input type="hidden" id="role" value="<?=$role?>">
			<input type="hidden" id="parent" value="<?=$user_id?>">
			<input type="hidden" id="active_page" value="<?=$_GET['page']?>">
			<div class="pagination_list">
				<div class="prev_page"><img src="/img/prev_page.svg" alt="prev_page"></div>
				<div class="pages_list">
					<?php if ($_POST['search'] != '')
						$search = '&search='.$_POST['search']; ?>
					<!-- <?php for ($i=1; $i < $pages; $i++) {
						if (($_SERVER['REQUEST_URI'] == "/clients.php" && $i == 1) || ($_SERVER['REQUEST_URI'] == "/clients.php?page=$i")): ?>
							<a class="page active_page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
						<?php else: ?>
							<a class="page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
						<?php endif ?> 
					<?php } ?> -->

					<?php if ($pages <= 10) {
						for ($i=1; $i < $pages; $i++) {
							if (($_SERVER['REQUEST_URI'] == "/clients.php" && $i == 1) || ($_GET['page'] == $i)): ?>
								<a class="page active_page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
							<?php else: ?>
								<a class="page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
							<?php endif ?>
						<?php }
					} else {
						if ($_GET['page'] < 7) {
							for ($i=1; $i < 8; $i++) {
								if (($_SERVER['REQUEST_URI'] == "/clients.php" && $i == 1) || ($_GET['page'] == $i)): ?>
									<a class="page active_page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
								<?php else: ?>
									<a class="page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
								<?php endif ?>
							<?php } ?>
							<span class="triple_dots">...</span>
							<a class="page" href="/clients.php?page=<?=$pages - 1?><?=$search?>"><?=$pages - 1?></a> <?php
						} elseif ($_GET['page'] >= $pages - 6) { ?>
							<a class="page" href="/clients.php?page=1<?=$search?>">1</a>
							<span class="triple_dots">...</span>
							<?php
							for ($i=$pages - 7; $i < $pages; $i++) {
								if (($_SERVER['REQUEST_URI'] == "/clients.php" && $i == 1) || ($_GET['page'] == $i)): ?>
									<a class="page active_page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
								<?php else: ?>
									<a class="page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
								<?php endif ?>
							<?php }
						} else { ?>
							<a class="page" href="/clients.php?page=1<?=$search?>">1</a>
							<span class="triple_dots">...</span>
							<?php
							for ($i=$_GET['page'] - 3; $i < $_GET['page'] + 4; $i++) {
								if (($_SERVER['REQUEST_URI'] == "/clients.php" && $i == 1) || ($_GET['page'] == $i)): ?>
									<a class="page active_page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
								<?php else: ?>
									<a class="page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
								<?php endif ?>
							<?php } ?>
							<span class="triple_dots">...</span>
							<a class="page" href="/clients.php?page=<?=$pages - 1?><?=$search?>"><?=$pages - 1?></a> <?php
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