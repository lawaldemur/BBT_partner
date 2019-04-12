<div class="col-12 after_table_filters">
	<?php
	foreach ($hiddens as $value)
		call_user_func_array('create_hidden', $value);
	
	// пагинация
	call_user_func_array('pagination_list', $pagination);

	// выбор кол-ва строк отображаемых в таблице
	call_user_func_array('table_sizes', $table_sizes);
	?>
</div>