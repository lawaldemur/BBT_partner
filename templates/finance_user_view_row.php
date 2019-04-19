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
				<tr><?php
					table_th('Отчёт <span class="sort_upper sortColumn_type">&#9660;</span>', 'date');
					table_th('Сумма выплаты', 'sum', 'table_align_center');
					table_th('Отчёт', '', 'table_align_center');
					table_th('Акт', '', 'table_align_center');
					table_th('Принято', '', 'table_align_center');
					table_th('Оплачено', '', 'table_align_center');
				?></tr>
			</thead>
			<tbody class="user_view_tbody">
				<!-- js (ajax) will set data -->
			</tbody>
		</table>
	</div>

	<?php call_user_func_array('after_table_filters', $after_table_filters); ?>
</div>