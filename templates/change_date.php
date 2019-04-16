<div class="change_date">
	<?php $sql_date = "`date` >= CURDATE()"; ?>
	<div class="change <?=(!$period || $period == $sql_date) ? 'change_active' : '';?>" id="today" data-val="<?=$sql_date?>">Сегодня</div>

	<?php $sql_date = "DATE(`date`) = DATE(NOW() - INTERVAL 1 DAY)"; ?>
	<div class="change <?=($period == $sql_date) ? 'change_active' : '';?>" id="yesterday" data-val="<?=$sql_date?>">Вчера</div>
	
	<?php $sql_date = "DATE(`date`) = DATE(NOW() - INTERVAL 1 DAY)"; ?>
	<div class="change <?=($period == "WEEK(`date`) = WEEK(CURDATE())") ? 'change_active' : '';?>" id="week" data-val="<?=$sql_date?>">Неделя</div>
	
	<?php $sql_date = "MONTH(`date`) = MONTH(CURDATE())"; ?>
	<div class="change <?=($period == $sql_date) ? 'change_active' : '';?>" id="month" data-val="<?=$sql_date?>">Месяц</div>
	
	<?php $sql_date = "QUARTER(`date`) = QUARTER(CURDATE())"; ?>
	<div class="change <?=($period == $sql_date) ? 'change_active' : '';?>" id="quartal" data-val="<?=$sql_date?>">Квартал</div>
	
	<?php $sql_date = "YEAR(`date`) = YEAR(CURDATE())"; ?>
	<div class="change <?=($period == $sql_date) ? 'change_active' : '';?>" id="year" data-val="<?=$sql_date?>">Год</div>

	<?php if (stripos($period, 'BETWEEN') !== false): ?>
	<div class="change change_active custom_date_change" id="custom" data-val="<?=$period?>">
	<?php else: ?>
	<div class="change custom_date_change" id="custom">
	<?php endif; ?>
		<img src="/img/custom.svg" alt="custom"><img src="/img/custom_active.svg" alt="custom_active">
		<span class="custom_date"><?=$calendarText ? $calendarText : '1 июн 2016 – 23 авг 2018';?></span>
	</div>

</div>


<div class="calendar_overlay"></div>
	<div class="calendar">
	<div class="prev_cal"><img src="/img/back_calendar.svg" alt="back arrow"></div>
	<div class="next_cal"><img src="/img/next_calendar.svg" alt="next_caxt arrow"></div>

	<div class="cal_col cal_col_1"></div>
	<div class="cal_col cal_col_2"></div>
	<div class="cal_col cal_col_3"></div>

	<div class="today_cal">Сегодня</div>
	<div class="reset_cal">Сбросить</div>
	<button class="done_cal">Показать</button>
</div>


<?php if ($drop_list): ?>
	<div class="month_drop_list">
		<select id="month_drop_list">
			<option value="0">Детализация по дням <img alt="" src="/img/month_drop_list.png"></option>								
			<option value="1">Детализация по неделям <img alt="" src="/img/month_drop_list.png"></option>
			<option value="2">Детализация по месяцам <img alt="" src="/img/month_drop_list.png"></option>
		</select>
		<img src="img/drop_arrow.svg" alt="drop_arrow">
	</div>
<?php endif; ?>
