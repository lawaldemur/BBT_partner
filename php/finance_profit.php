<div class="row">
	<div class="col-12 profit_filters">
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


			<!-- place_for_drop_donw -->
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

	</div>
</div>

<div class="row earn_row">
	<!-- EARN -->
	<?php if(isset($_COOKIE['period'])){
				$date = $_COOKIE['period'];
				if (isset($_COOKIE['format']) && $_COOKIE['format'] != 'all'){
					//echo $_COOKIE['format'];
					$date .= ' AND `format` = \'' . $_COOKIE['format'] . "'";
				}
			}
			else
				$date = "YEAR(`date`) = YEAR(CURDATE())";
		?>

	<?php if ($role == 'ББТ'): ?>
		<div class="col-4">
			<span class="fin_m_span1 fin_m_span_dogovor">
			<?php
				$result_on_d = $dbc->query("SELECT SUM(to_bbt) FROM sold WHERE to_partner_id <> 0 AND $date");
				foreach ($result_on_d as $money)
					echo round($money['SUM(to_bbt)'], 2);
			?> &#8381;</span>
			<span class="fin_m_span2"><img alt="" src="img/Ellipse3.png"> Заработано по договорам</span>
		</div>
		<div class="col-4">
			<span class="fin_m_span1 fin_m_span_bonus">
			<?php 
				$result_on_d = $dbc->query("SELECT SUM(to_bbt) FROM sold WHERE to_partner_id = 0 AND $date");
				foreach ($result_on_d as $money2)
					echo round($money2['SUM(to_bbt)'], 2);
			?> &#8381;</span>
			<span class="fin_m_span2"><img alt="" src="img/Ellipse31.png"> Заработано на бонусах</span>
		</div>
		<div class="col-4">
			<span class="fin_m_span1 fin_m_span_all">
			<?=round($money['SUM(to_bbt)'] + $money2['SUM(to_bbt)'], 2)?> &#8381;</span>
			<span class="fin_m_span2"><img alt="" src="img/Ellipse32.png"> Итого</span>
		</div>
	<?php elseif ($role == 'Команда'): ?>
		<div class="col-12">
			<span class="fin_m_span1 fin_m_span_dogovor">
			<?php
				$result_on_d = $dbc->query("SELECT SUM(to_command) FROM sold WHERE to_command_id = $user_id AND $date");
				foreach ($result_on_d as $money)
					echo round($money['SUM(to_command)'], 2);
			?> &#8381;</span>
			<span class="fin_m_span2"><img alt="" src="img/Ellipse3.png"> Заработано по договорам</span>
		</div>
	<?php elseif ($role == 'Партнер'): ?>
		<div class="col-12">
			<span class="fin_m_span1 fin_m_span_dogovor">
			<?php
				$result_on_d = $dbc->query("SELECT SUM(to_partner) FROM sold WHERE to_partner_id = $user_id AND $date");
				foreach ($result_on_d as $money)
					echo round($money['SUM(to_partner)'], 2);
			?> &#8381;</span>
			<span class="fin_m_span2"><img alt="" src="img/Ellipse3.png"> Заработано по договорам</span>
		</div>
	<?php endif; ?>
</div>


<?php if ($role == 'ББТ'): ?>
	<?php include 'egor_graph.php'; ?>
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