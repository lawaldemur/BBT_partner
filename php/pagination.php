<?php
$search = $search == '' ? '' : '&search='.$search;

if ($pages <= 10) { // if total quantity of pages less or equal 10
	// print all pages list
	for ($i=1; $i < $pages; $i++) {
		$active = ($_SERVER['REQUEST_URI'] == "/$page_file_name" && $i == 1) || ($page == $i); ?>
		<a class="page <?=$active ? 'active_page' : '';?>" href="/<?=$page_file_name?>?page=<?=$i?><?=$search?>"><?=$i?></a>
	<?php }
} else { // if quantity of pages more than 10
	if ($page < 7) { // if number of current page < 7
		// print first 7 pages
		for ($i=1; $i < 8; $i++) {
			$active = ($_SERVER['REQUEST_URI'] == "/$page_file_name" && $i == 1) || ($page == $i); ?>
			<a class="page <?=$active ? 'active_page' : '';?>" href="/<?=$page_file_name?>?page=<?=$i?><?=$search?>"><?=$i?></a>
		<?php } ?>

		<span class="triple_dots">...</span>

		<?php // print last page ?>
		<a class="page" href="/<?=$page_file_name?>?page=<?=$pages - 1?><?=$search?>"><?=$pages - 1?></a> <?php
	} elseif ($page >= $pages - 6) { // if number of current page more than total quantity - 6
		// print first page ?>
		<a class="page" href="/<?=$page_file_name?>?page=1<?=$search?>">1</a>

		<span class="triple_dots">...</span>

		<?php // print last 7 pages
		for ($i=$pages - 7; $i < $pages; $i++) {
			$active = ($_SERVER['REQUEST_URI'] == "/$page_file_name" && $i == 1) || ($page == $i); ?>
			<a class="page <?=$active ? 'active_page' : '';?>" href="/<?=$page_file_name?>?page=<?=$i?><?=$search?>"><?=$i?></a>
		<?php }
	} else { // if current page in the middle of total quantity
		// print first page ?>
		<a class="page" href="/<?=$page_file_name?>?page=1<?=$search?>">1</a>

		<span class="triple_dots">...</span>
		
		<?php
		// print 3 pages before current and 3 after
		for ($i=$page - 3; $i < $page + 4; $i++) {
			$active = ($_SERVER['REQUEST_URI'] == "/$page_file_name" && $i == 1) || ($page == $i); ?>
			<a class="page <?=$active ? 'active_page' : '';?>" href="/<?=$page_file_name?>?page=<?=$i?><?=$search?>"><?=$i?></a>
		<?php } ?>

		<span class="triple_dots">...</span>

		<?php // print last page ?>
		<a class="page" href="/<?=$page_file_name?>?page=<?=$pages - 1?><?=$search?>"><?=$pages - 1?></a> <?php
	}
}
