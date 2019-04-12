<div class="sort_date_or_book">
	<?php if (!$sort || $sort == 'bydate'): ?>
		<div class="sort_date sort_active">По дате</div>
	<?php else: ?>
		<div class="sort_date">По дате</div>
	<?php endif ?>

	<?php if ($sort == 'bybook'): ?>
		<div class="sort_book sort_active">По книгам</div>
	<?php else: ?>
		<div class="sort_book">По книгам</div>
	<?php endif ?>
</div>
