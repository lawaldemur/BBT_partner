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
