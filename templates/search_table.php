<div class="search_table">
	<?php if ($get_rid_autocomplete): ?>
		<input type="email" class="email_fake" id="email">
	<?php endif; ?>
	
	<input type="text" id="<?=$id?>" placeholder="<?=$placeholder?>" value="<?=$value?>">
	<img src="/img/search_icon.svg" alt="search_icon" width="12" height="12">
	<img src="/img/active_search_icon.svg" alt="active_search_icon" width="12" height="12">
</div>