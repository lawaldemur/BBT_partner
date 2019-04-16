<div class="row">
	<div class="col-12">
		<div class="view_tabs">
			<div class="tab_1 tab active_tab" data-tab="books">Книги</div>
			<?php
			if ($view_position == 'command')
				view_tabs_command();
			elseif ($view_position == 'partner')
				view_tabs_partner();
			elseif ($view_position == 'client' && $role != 'Партнер')
				view_tabs_client();
			?>
		</div>
	</div>
</div>