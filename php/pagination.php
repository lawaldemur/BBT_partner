<?php

if ($pages <= 10) {
	for ($i=1; $i < $pages; $i++) {
		if (($_SERVER['REQUEST_URI'] == "/analitic.php" && $i == 1) || ($_GET['page'] == $i)): ?>
			<a class="page active_page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
		<?php else: ?>
			<a class="page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
		<?php endif ?>
	<?php }
} else {
	if ($_GET['page'] <= 4) {
		for ($i=1; $i < 4; $i++) {
			if (($_SERVER['REQUEST_URI'] == "/analitic.php" && $i == 1) || ($_GET['page'] == $i)): ?>
				<a class="page active_page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/analitic.php?page=<?=$pages?>"><?=$pages?></a> <?php
	} elseif ($_GET['page'] >= $pages - 4) { ?>
		<a class="page" href="/analitic.php?page=1">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$pages - 4; $i < $pages; $i++) {
			if (($_SERVER['REQUEST_URI'] == "/analitic.php" && $i == 1) || ($_GET['page'] == $i)): ?>
				<a class="page active_page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		
		<?php
	} else { ?>
		<a class="page" href="/analitic.php?page=1">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$_GET['page'] - 4; $i < $_GET['page'] + 4; $i++) {
			if (($_SERVER['REQUEST_URI'] == "/analitic.php" && $i == 1) || ($_GET['page'] == $i)): ?>
				<a class="page active_page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/analitic.php?page=<?=$i?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/analitic.php?page=<?=$pages?>"><?=$pages?></a> <?php
	}
}





