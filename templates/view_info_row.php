<div class="row">
	<div class="col-5 about_col">
		<div class="referer"><a href="<?=$referer?>"><img src="/img/referer.svg" alt="referer"></a></div>
		<div class="avatar" style="background-image: url(<?=$picture?>);"></div>
		<div class="finance_view_name">
			<div class="name"><?=$name?><span class="count"></span></div>
			<div class="address"><?=$address?></div>
		</div>
	</div>

	<?php if ($view_position != 'client'): ?>
		<div class="col-7">
			<div class="about_info">
				<div class="about">
					<div class="about_value"><?=$earn['digital_percent']?>%</div>
					<div class="about_desc">% <?=$view_position == 'command' ? 'команды' : 'партнера';?></div>
				</div>
				<div class="about_border"></div>
				<div class="about">
					<div class="about_value"><?=$earn['children_quantity'];?></div>
					<div class="about_desc"><?=$view_position == 'command' ? 'Партнеры' : 'Клиенты';?></div>
				</div>
				<div class="about_border"></div>
				<div class="about">
					<div class="about_value"><?=$earn['get_today']?> ₽</div>
					<div class="about_desc">Выручка<br>за сегодня</div>
				</div>
				<div class="about_border"></div>
				<div class="about">
					<div class="about_value"><?=$earn['get_week']?> ₽</div>
					<div class="about_desc">Выручка<br>за неделю</div>
				</div>
				<div class="about_border"></div>
				<div class="about">
					<div class="about_value"><?=$earn['get_month']?> ₽</div>
					<div class="about_desc">Выручка<br>за месяц</div>
				</div>
				<div class="about_border"></div>
				<div class="about">
					<div class="about_value"><?=$earn['get_year']?> ₽</div>
					<div class="about_desc">Выручка<br>за год</div>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>