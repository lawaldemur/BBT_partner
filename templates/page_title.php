<div class="row">
	<div class="col command_list_col">
		<h1><?=$logo?> <span class="command_count"><?=$count?></span></h1>

		<?php if ($access): ?>
			<button id="add_<?=$role?>_btn" class="transparent_btn"><img src="/img/add_command_plus.svg" alt="add_command_plus" class="add_command_plus" width="11" height="11"><img src="/img/add_command_plus_white.svg" alt="add_command_plus_white" class="add_command_plus_white" width="11" height="11"><?=$btn_text?></button>
		<?php endif; ?>
	</div>
</div>