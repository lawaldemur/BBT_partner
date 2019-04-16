<div class="row finance_tabs_row">
	<div class="col-12 col_choose_tab_finance">
		<div class="choose_tab_finance">
			<div class="tab_finance active_tab_finance" data-col="profit">Выручка</div>
			
			<?php if ($role == 'ББТ'): ?>
				<div class="tab_finance" data-col="reports_from_command">Отчёты от команд</div>
			<?php elseif($role == 'Команда'): ?>
				<div class="tab_finance" data-col="reports_for_bbt">Отчёты для ББТ</div>
				<div class="tab_finance" data-col="reports_from_partner">Отчёты от партнеров</div>
			<?php elseif($role == 'Партнер'): ?>
				<div class="tab_finance" data-col="reports_for_command">Отчёты для команд</div>
			<?php endif ?>
		</div>
	</div>		
</div>