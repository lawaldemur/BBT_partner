<?php include 'header.php'; ?>

<?php
$array = array();
if ($role == 'ББТ') {
	// $reports_array = $dbc->query("SELECT * FROM `reports` WHERE `to_id` = $user_id");

	// if ($reports_array)
	// foreach ($reports_array as $report) {
	// 	$from = $dbc->query("SELECT * FROM `users` WHERE `id` = {$report['from_id']}");
	// 	$from = $from->fetch_array(MYSQLI_ASSOC);

	// 	if (isset($array[$from['id']])) {
	// 		if ($report['paid'] == 0)
	// 			$array[$from['id']]['count']++;
	// 		if ($report['viewed'] == 1)
	// 			$array[$from['id']]['view'] = 1;
	// 	} else {
	// 		$array[$from['id']] = array(
	// 			'id' => $from['id'],
	// 			'name' => $from['name'],
	// 			'city' => $from['city'],
	// 			'picture' => $from['picture'],
	// 			'count' => $report['paid'] == 0 ? 1 : 0,
	// 			'view' => $report['viewed'],
	// 		);
	// 	}

	// }
} elseif ($role == 'Команда') {
	// $reports_for_bbt = $dbc->query("SELECT * FROM `reports` WHERE `from_id` = $user_id");
	// foreach ($reports_for_bbt as $report) {
	// 	$array[] = $report;
	// }
	// $reports_for_bbt = $array;
	
	// $array = [];
	// $reports_array = $dbc->query("SELECT * FROM `reports` WHERE `to_id` = $user_id");
	// foreach ($reports_array as $report) {
	// 	$from = $dbc->query("SELECT * FROM `users` WHERE `id` = {$report['from_id']}");
	// 	$from = $from->fetch_array(MYSQLI_ASSOC);

	// 	if (isset($array[$from['id']])) {
	// 		if ($report['paid'] == 0)
	// 			$array[$from['id']]['count']++;
	// 		if ($report['viewed'] == 1)
	// 			$array[$from['id']]['view'] = 1;
	// 	} else {
	// 		$array[$from['id']] = array(
	// 			'id' => $from['id'],
	// 			'name' => $from['name'],
	// 			'city' => $from['city'],
	// 			'picture' => $from['picture'],
	// 			'view' => $report['viewed'],
	// 			'count' => $report['paid'] == 0 ? 1 : 0,
	// 		);
	// 	}
	// }
} else {
	$reports_for_commands = $dbc->query("SELECT * FROM `reports` WHERE `from_id` = $user_id");
}

// ассоциативный массив в список
$array = array_values($array);

// pagination
if (!isset($_GET['page'])) $_GET['page'] = 1;
if (!isset($_COOKIE['rows'])) $rows = 20;
else $rows = $_COOKIE['rows'];
$offset = $_GET['page'] * $rows - $rows;
$limit = $_GET['page'] * $rows;
$pages = ceil(count($array) / $rows);
?>

<div id="overlay_document" style="display: none;">
	<div id="close_overlay_document"><img src="/img/close_viewer.svg" alt="close_viewer"></div>
	<div class="document_info">
		<div class="line_1">
			<div class="name"></div>
			<div class="print"><img src="/img/print_doc.svg" alt="print_doc" width="20"><span>Распечатать</span></div>
			<div class="download"><a href="" download><img src="/img/load_doc.svg" alt="load_doc" width="15"><span>Скачать</span></a></div>
		</div>
		<div class="line_document" style="background: #fff; text-align: center;">
			**pdf миниатюра документа** (вы можете скачать документ)
		</div>
	</div>
</div>
<div id="notification">
	<span id="notif_icon">&#10004;</span>
	<span id="notification_text">Данные успешно сохранены</span>
</div>
<input type="file" class="finance_open_report_file" style="display: none;">

<div class="container">
	<div class="row">
		<div class="col command_list_col">
			<h1>Финансы</h1>
		</div>
	</div>

	<!-- partners TABLE -->
	<div class="row finance_tabs_row">
		<div class="col-12 col_choose_tab_finance">
			<div class="choose_tab_finance">
				<?php if ($role == 'ББТ'): ?>
					<div class="tab_finance active_tab_finance" data-col="profit">Выручка</div>
					<div class="tab_finance" data-col="reports_from_command">Отчёты от команд</div>
				<?php elseif($role == 'Команда'): ?>
					<div class="tab_finance active_tab_finance" data-col="profit">Выручка</div>
					<div class="tab_finance" data-col="reports_for_bbt">Отчёты для ББТ</div>
					<div class="tab_finance" data-col="reports_from_partner">Отчёты от партнеров</div>
				<?php elseif($role == 'Партнер'): ?>
					<div class="tab_finance active_tab_finance" data-col="profit">Выручка</div>
					<div class="tab_finance" data-col="reports_for_command">Отчёты для команд</div>
				<?php endif ?>
			</div>
		</div>		
	</div>
</div>	


<!-- конец выручки -->
<div class="container">
	<div class="row finance_row">

		<div class="col-12 profit active_col_finance">
			<?php require 'php/finance_profit.php'; ?>
		</div>

		<div class="col-12 analitics_col reports_from_partner" style="justify-content: flex-end;">
			<div class="search_table">
				<input type="text" id="search_table_command" placeholder="Введите название команды или город">
				<img src="/img/search_icon.svg" alt="search_icon" width="12" height="12">
				<img src="/img/active_search_icon.svg" alt="active_search_icon" width="12" height="12">
			</div>
			<input type="hidden" id="to" value="<?=$user_id?>">
			<input type="hidden" id="role" value="<?=$role?>">
			<input type="hidden" id="active_page" value="<?=$_GET['page']?>">
		</div>
		
		<?php if ($role == 'ББТ'): ?>
			<div class="col-12 reports_from_command">
				<table id="reports_table" data-table="link">
					<thead>
						<tr>
							<th data-column="name">Команда <span class="sort_upper sortColumn_type">&#9660;</span></th>
							<th class="table_align_center" data-column="count">Ожидают оплаты</th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		<?php elseif($role == 'Команда'): ?>
			<input type="hidden" id="user_id" value="<?=$user_id?>">
			<input type="hidden" id="table" value="<?=$_GET['viewbbt']?>">
			<input type="hidden" id="table_2" value="<?=$_GET['viewpartners']?>">
			<input type="hidden" id="table_3" value="<?=$_GET['view']?>">
			<input type="hidden" id="active_page" value="<?=$_GET['page']?>">
			<div class="col-12 reports_for_bbt">
				<table id="reports_for_bbt_table">
					<thead>
						<tr>
							<th data-column="date">Отчёт <span class="sort_upper sortColumn_type">&#9660;</span></th>
							<th class="table_align_center" data-column="sum">Сумма выплаты</th>
							<th class="table_align_center">Отчёт</th>
							<th class="table_align_center">Акт</th>
							<th class="table_align_center">Принято</th>
							<th class="table_align_center">Оплачено</th>
						</tr>
					</thead>
					<tbody class="reports_for_bbt_tbody">

					</tbody>
				</table>
			</div>

			<div class="col-12 reports_from_partner">
				<table id="reports_from_partner_table">
					<thead>
						<tr>
							<th data-column="name">Партнер <span class="sort_upper sortColumn_type">&#9660;</span></th>
							<th class="table_align_center" data-column="count">Ожидают оплаты</th>
						</tr>
					</thead>
					<tbody class="reports_from_partner_tbody">

					</tbody>
				</table>
			</div>
		<?php elseif($role == 'Партнер'): ?>
			<div class="col-12 reports_for_command">
				<input type="hidden" id="user_id" value="<?=$user_id?>">
				<input type="hidden" id="table" value="<?=$_GET['viewbbt']?>">
				<input type="hidden" id="table_2" value="<?=$_GET['viewpartners']?>">
				<input type="hidden" id="active_page" value="<?=$_GET['page']?>">
				<style>.reports_from_command, .user_view_about { display: none !important; }</style>
				<table id="reports_for_bbt_table">
					<thead>
						<tr>
							<th data-column="date">Отчёт <span class="sort_upper sortColumn_type">&#9660;</span></th>
							<th class="table_align_center" data-column="sum">Сумма выплаты</th>
							<th class="table_align_center">Отчёт</th>
							<th class="table_align_center">Акт</th>
							<th class="table_align_center">Принято</th>
							<th class="table_align_center">Оплачено</th>
						</tr>
					</thead>
					<tbody class="reports_for_bbt_tbody">

					</tbody>
				</table>
			</div>
		<?php endif ?>

		<div class="col-12 after_table_filters finance_after_table_filters">
			<input type="hidden" id="active_page" value="<?=$_GET['page']?>">
			<div class="pagination_list">
				<div class="prev_page"><img src="/img/prev_page.svg" alt="prev_page"></div>
				<div class="pages_list">
					<?php $search = '';

					if ($pages <= 10) {
						for ($i=1; $i < $pages; $i++) {
							if (($_SERVER['REQUEST_URI'] == "/finance.php" && $i == 1) || ($_GET['page'] == $i)): ?>
								<a class="page active_page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
							<?php else: ?>
								<a class="page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
							<?php endif ?>
						<?php }
					} else {
						if ($_GET['page'] < 7) {
							for ($i=1; $i < 8; $i++) {
								if (($_SERVER['REQUEST_URI'] == "/finance.php" && $i == 1) || ($_GET['page'] == $i)): ?>
									<a class="page active_page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
								<?php else: ?>
									<a class="page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
								<?php endif ?>
							<?php } ?>
							<span class="triple_dots">...</span>
							<a class="page" href="/finance.php?page=<?=$pages - 1?>&table=from_commands<?=$search?>"><?=$pages - 1?></a> <?php
						} elseif ($_GET['page'] >= $pages - 6) { ?>
							<a class="page" href="/finance.php?page=1&table=from_commands<?=$search?>">1</a>
							<span class="triple_dots">...</span>
							<?php
							for ($i=$pages - 7; $i < $pages; $i++) {
								if (($_SERVER['REQUEST_URI'] == "/finance.php" && $i == 1) || ($_GET['page'] == $i)): ?>
									<a class="page active_page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
								<?php else: ?>
									<a class="page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
								<?php endif ?>
							<?php }
						} else { ?>
							<a class="page" href="/finance.php?page=1&table=from_commands<?=$search?>">1</a>
							<span class="triple_dots">...</span>
							<?php
							for ($i=$_GET['page'] - 3; $i < $_GET['page'] + 4; $i++) {
								if (($_SERVER['REQUEST_URI'] == "/finance.php" && $i == 1) || ($_GET['page'] == $i)): ?>
									<a class="page active_page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
								<?php else: ?>
									<a class="page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
								<?php endif ?>
							<?php } ?>
							<span class="triple_dots">...</span>
							<a class="page" href="/finance.php?page=<?=$pages - 1?>&table=from_commands<?=$search?>"><?=$pages - 1?></a> <?php
						}
					} ?>
				</div>
				<div class="next_page"><img src="/img/next_page.svg" alt="next_page"></div>
			</div>

			<div class="table_sizes to_bbt_table_sizes">
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

	<div class="user_view row" data-id="">
		<div class="col-12 user_view_about about_col">
			<div class="referer"><img src="/img/referer.svg" alt="referer"></div>
			<img src="" alt="avatar" class="avatar">
			<div class="finance_view_name">
				<div class="name"><span class="count"></span></div>
				<div class="address"></div>
			</div>
		</div>
		
		<div class="col-12 reports_from_command">
			<table id="user_view_table" data-table="link">
				<thead>
					<tr>
						<th data-column="date">Отчёт <span class="sort_upper sortColumn_type">&#9660;</span></th>
						<th class="table_align_center" data-column="sum">Сумма выплаты</th>
						<th class="table_align_center">Отчёт</th>
						<th class="table_align_center">Акт</th>
						<th class="table_align_center">Принято</th>
						<th class="table_align_center">Оплачено</th>
					</tr>
				</thead>
				<tbody class="user_view_tbody">
					<!-- js (ajax) will set data -->
				</tbody>
			</table>
		</div>

		<div class="col-12 after_table_filters user_view_tbody_after_table_filters">
			<div class="pagination_list user_view_pagination_list">
				<div class="prev_page"><img src="/img/prev_page.svg" alt="prev_page"></div>
				<div class="pages_list">
					<?php for ($i=1; $i < $pages; $i++) {
						if ($i == 1): ?>
							<a class="page active_page" href="/finance.php?page=<?=$i?>"><?=$i?></a>
						<?php else: ?>
							<a class="page" href="/finance.php?page=<?=$i?>"><?=$i?></a>
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
	<!-- END partners TABLE -->
</div>

<input type="hidden" id="table" value="<?=$_GET['table']?>">
<input type="hidden" id="view" value="<?=$_GET['view']?>">

<?php include 'footer.php'; ?>
