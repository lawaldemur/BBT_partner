<?php
// блок уведомления
notification();

// input:file загрузки отчетов/актов
finance_upload_report();
?>

<div class="container">
	<?php
	// заголовок
	page_title('Финансы', '', false);

	// вкладки
	finance_tabs($role);
	?>	
</div>	


<!-- конец выручки -->
<div class="container">
	<div class="row finance_row">
		<div class="col-12 profit active_col_finance">
			<div class="row">
				<div class="col-12 profit_filters">
					<?php
					// выбор промежутка дат
					change_date($period, $_COOKIE['calendarText'], true);

					// выбор формата книг
					choose_format($format);
					?>
				</div>
			</div>

			<div class="row earn_row">
				<?php
				if ($role == 'ББТ')
					finance_brief_result_bbt($n1, $n2);
				else
					finance_brief_result($n);
				?>
			</div>

<?php
$array = [];

// pagination
if (!isset($_GET['page'])) $_GET['page'] = 1;
if (!isset($_COOKIE['rows'])) $rows = 20;
else $rows = $_COOKIE['rows'];
$offset = $_GET['page'] * $rows - $rows;
$limit = $_GET['page'] * $rows;
$pages = ceil(count($array) / $rows);
?>
		<?php if ($role == 'ББТ'): ?>
				<div class="row_graph row">

				<div class="graph graph_">
					<div class="cd_s_"></div>
						<div id="chartdiv_"></div>
					<div class="cd_e_"></div>
				</div>

			</div>



			<?php if (strpos($_COOKIE['period'], 'BETWEEN') !== false) {
					$date1 = new DateTime(explode('\'', $_COOKIE['period'])[1]);
					$date2 = new DateTime(explode('\'', $_COOKIE['period'])[3]);

					$diff = $date1->diff($date2);
					$diff = intval(($diff->format('%y') * 12) + $diff->format('%m')) >= 2;
				} ?>
			<?php if ($_COOKIE['period'] == 'QUARTER(`date`) = QUARTER(CURDATE())' || $_COOKIE['period'] == 'YEAR(`date`) = YEAR(CURDATE())' ||
					(strpos($_COOKIE['period'], 'BETWEEN') !== false && $diff)): ?>
				<div class="row earn_tables">
			<?php else: ?>
				<div class="row earn_tables" style="display: none;">
			<?php endif ?>
				
					<table class="month_earn" data-earn_table='2'>
					<thead>
						<tr>
							<th data-column="date">Месяц <span class="sort_upper sortColumn_type">&#9660;</span></th>
							<th data-column="dogovor">Заработано<br>по договорам</th>
							<th data-column="bonus">Заработано<br>на бонусах</th>
							<th data-column="total">Итого</th>
						</tr>
					</thead>
					<tbody>
						<?php
						if ($_COOKIE['format'] != 'all') {
							$where = 'WHERE `format` = \'' . $_COOKIE['format'] . "'";
						} else 
							$where = '';
						if ($_COOKIE['format'] != 'all') {
							$where2 = 'AND `format` = \'' . $_COOKIE['format'] . "'";
						} else 
							$where2 = '';

						$months = $dbc->query("SELECT year(date), month(date)
						FROM sold $where GROUP BY month(date) ORDER BY date DESC");
						if ($months)
						foreach ($months as $item) {
							$year = $item['year(date)'];
							$month = $months_list[intval($item['month(date)']) - 1];
							?>
							<tr>
								<td><?=$month?> <?=$year?></td>
								<td><?php
									$result_b = $dbc->query("SELECT year(date),month(date),SUM(to_bbt)
									FROM sold WHERE to_partner_id > 0 AND year(date) = $year AND month(date) = {$item['month(date)']} $where2 
									GROUP BY month(date)");
									if ($result_b && $result_b->num_rows > 0) {
										foreach ($result_b as $item3)
											echo round($item3['SUM(to_bbt)'], 2);
									} else
										echo 0;
								?> &#8381;</td>

								<td><?php
									$result_b = $dbc->query("SELECT year(date),month(date),SUM(to_bbt)
									FROM sold WHERE to_partner_id = '0' AND year(date) = $year AND month(date) = {$item['month(date)']} $where2
									GROUP BY month(date)");
									if ($result_b && $result_b->num_rows > 0)
										foreach ($result_b as $item2)
											echo round($item2['SUM(to_bbt)'], 2);
									else
										echo 0;
								?> &#8381;</td>
								<td><?=round($item3['SUM(to_bbt)'] + $item2['SUM(to_bbt)'], 2)?> &#8381;</td>
							</tr>
							<?php
							$result_b = [];
							$item2 = [];
							$item3 = [];
						}
						?>
					</tbody>
				</table>
			</div>

			<?php if ($_COOKIE['period'] == 'QUARTER(`date`) = QUARTER(CURDATE())' || $_COOKIE['period'] == 'YEAR(`date`) = YEAR(CURDATE())' ||
					(strpos($_COOKIE['period'], 'BETWEEN') !== false && $diff)): ?>
				<div class="row earn_tables_control">
			<?php else: ?>
				<div class="row earn_tables_control" style="display: none;">
			<?php endif ?>
				<div class="col-12 earn_tables_filters">

					<div class="pagination_list earn_pagination_list">
						<div class="prev_page"><img src="/img/prev_page.svg" alt="prev_page"></div>
						<div class="pages_list">
							<a class="page earn_page active_page" href="/finance.php">1</a>
						</div>
						<div class="next_page"><img src="/img/next_page.svg" alt="next_page"></div>
					</div>


					<?php
					if (!isset($_COOKIE['rows'])) $rows = 20;
					else $rows = $_COOKIE['rows'];
					?>
					<div class="table_sizes earn_table_sizes">
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
		<?php elseif ($role == 'Команда'): ?>
			<div class="row_graph row">
				<div class="graph graph_">
					<div class="cd_s_"></div>
						<div id="chartdiv_"></div>
					<div class="cd_e_"></div>
				</div>
			</div>
		<?php elseif ($role == 'Партнер'): ?>
			<div class="row_graph row">
				<div class="graph graph_">
					<div class="cd_s_"></div>
						<div id="chartdiv_"></div>
					<div class="cd_e_"></div>
				</div>
			</div>
		<?php endif; ?>
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