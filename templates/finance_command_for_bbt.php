<div class="col-12 reports_for_bbt">
	<table id="reports_for_bbt_table">
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
		<tbody class="reports_for_bbt_tbody">
			<!-- js (ajax) will set data -->
		</tbody>
	</table>
</div>