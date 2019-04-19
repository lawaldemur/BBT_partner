<div class="row earn_tables" <?=$show_earn_table ? '' : 'style="display: none;"'?>>
	<table class="month_earn" data-earn_table='2'>
		<thead>
			<tr><?php
				table_th('Месяц <span class="sort_upper sortColumn_type">&#9660;</span>', 'date');
				table_th('Заработано<br>по договорам', 'dogovor');
				table_th('Заработано<br>на бонусах', 'bonus');
				table_th('Итого', 'total');
			?></tr>
		</thead>
		<tbody>
			<?php foreach ($earn as $item): ?>
				<tr><?php
					simple_td($item['date']);
					simple_td(strval($item['n1']).' &#8381;');
					simple_td(strval($item['n2']).' &#8381;');
					simple_td(strval($item['total']).' &#8381;');
				?></tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<div class="row earn_tables_control" <?=$show_earn_table ? '' : 'style="display: none;"'?>>
	<div class="col-12 earn_tables_filters">
		<?php
		// пагинация
		call_user_func_array('pagination_list', $pagination);
		// размеры таблицы
		call_user_func_array('table_sizes', $table_sizes);
		?>
	</div>
</div>