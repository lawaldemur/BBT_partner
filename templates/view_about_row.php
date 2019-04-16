<div class="row about_view">
	<?php
	if ($view_position == 'command')
		view_about_command($data);
	elseif ($view_position == 'partner')
		view_about_partner($data);
	else
		view_about_client($data);
	?>
</div>