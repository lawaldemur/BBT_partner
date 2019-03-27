<?php
require '../db.php';
require '../db_shop.php';

$period = $_POST['period'];
$where = 'WHERE '.$period;
if (isset($_POST['format']) && $_POST['format'] != '' && $_POST['format'] != 'all')
	$where .= ' AND `format` = \''.$_POST['format']."'";

$books = $dbc_shop->query("SELECT * FROM `wp_watched` $where");

// pagination
if (!isset($_POST['page'])) $_POST['page'] = 1;
if ($_POST['rows_size'] == '') $rows = 20;
else $rows = $_POST['rows_size'];

$table = $_POST['table'];
if ($_POST['format'] == 'audio' && $_POST['get_table'] != 'views.audio') $_POST['page'] = 1;
elseif ($_POST['format'] == 'digital' && $_POST['get_table'] != 'views.digital') $_POST['page'] = 1;

$offset = $_POST['page'] * $rows - $rows;
$limit = $_POST['page'] * $rows;
$pages = ceil($books->num_rows / $rows) + 1;

// content for BBT - all books
if ($_POST['search'] == '')
	$books = $dbc_shop->query("SELECT * FROM `wp_watched` $where");
else
	$books = $dbc_shop->query("SELECT * FROM `wp_watched` $where AND (`name` LIKE '%{$_POST['search']}%' OR `author` LIKE '%{$_POST['search']}%')");

if ($period == '`date` >= CURDATE()')
	$date = date('Y-m-d');
elseif ($period == 'DATE(`date`) = DATE(NOW() - INTERVAL 1 DAY)')
	$date = date('Y-m-d', time() - 60 * 60 * 24);
elseif ($period == 'WEEK(`date`) = WEEK(CURDATE())')
	$date = date('Y-m-d', time() - 60 * 60 * 24 * 7);
elseif ($period == 'MONTH(`date`) = MONTH(CURDATE())')
	$date = date('Y-m-d', time() - 60 * 60 * 24 * 30);
elseif ($period == 'QUARTER(`date`) = QUARTER(CURDATE())')
	$date = date('Y-m-d', time() - 60 * 60 * 24 * 30 * 3);
elseif ($period == 'YEAR(`date`) = YEAR(CURDATE())')
	$date = date('Y-m-d', time() - 60 * 60 * 24 * 365);
else // custom date
	$date = '';

$array = array();
foreach ($books as $book) {
	$book['img'] = $dbc_shop->query("SELECT * FROM `wp_posts` WHERE `post_parent` = {$book['product']} AND `post_type` = 'attachment'");
	$book['img'] = $book['img']->fetch_array(MYSQLI_ASSOC)['guid'];

	$book['views'] = 0;
	$views = unserialize($book['watched']);

	if ($period == 'DATE(`date`) = DATE(NOW() - INTERVAL 1 DAY)') {
		foreach ($views as $view)
			if ($view == $date)
				$book['views']++;
	} else {
		foreach ($views as $view)
			if ($view >= $date)
				$book['views']++;
	}
	
	if ($book['views'] > 0)
		$array[] = $book;
}

// sort
for ($i=0; $i < count($array); $i++) { 
	for ($x=$i + 1; $x < count($array); $x++) { 
		if ($_POST['sortColumnType'] == 'default')
			$bool = $array[$i][$_POST['sortColumn']] < $array[$x][$_POST['sortColumn']];
		else
			$bool = $array[$i][$_POST['sortColumn']] > $array[$x][$_POST['sortColumn']];
		if ($_POST['sortColumn'] == 'name')
			$bool = !$bool;

		if ($bool) {
			$temp = $array[$x];
			$array[$x] = $array[$i];
           	$array[$i] = $temp;
		}
	}
}

while ($offset > count($array)) {
	$_POST['page']--;
	$offset = $_POST['page'] * $rows - $rows;
	$limit = $_POST['page'] * $rows;
}

for ($i=$offset; $i < $limit && $i < count($array); $i++): ?>
	<tr>
		<td class="table_product_name">
			<img src="<?=$array[$i]['img']?>" alt="product_picture">
			<div class="author_name">
				<span class="book_name"><?=$array[$i]['name']?></span>
				<span class="book_author"><?=$array[$i]['author']?></span>
			</div>
		</td>

		<td><?=$array[$i]['views']?></td>
	</tr>
<?php endfor;

mysqli_close($dbc);
?>
===================================================================================================
<?php if ($_POST['search'] != '')
	$search = '&search='.$_POST['search'];


if ($_POST['format'] == 'digital'): ?>

	<?php if ($pages <= 10) {
		for ($i=1; $i < $pages; $i++) {
			if (($_POST['page'] == $i && $_POST['get_table'] == 'views.digital') || ($_POST['get_table'] != 'views.digital' && $i == 1)): ?>
				<a class="page active_page" href="/analitic.php?page=<?=$i?>&table=views.digital<?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/analitic.php?page=<?=$i?>&table=views.digital<?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php }
	} else {
		if ($_POST['page'] < 7) {
			for ($i=1; $i < 8; $i++) {
				if (($_POST['page'] == $i && $_POST['get_table'] == 'views.digital') || ($_POST['get_table'] != 'views.digital' && $i == 1)): ?>
					<a class="page active_page" href="/analitic.php?page=<?=$i?>&table=views.digital<?=$search?>"><?=$i?></a>
				<?php else: ?>
					<a class="page" href="/analitic.php?page=<?=$i?>&table=views.digital<?=$search?>"><?=$i?></a>
				<?php endif ?>
			<?php } ?>
			<span class="triple_dots">...</span>
			<a class="page" href="/analitic.php?page=<?=$pages - 1?>&table=views.digital<?=$search?>"><?=$pages - 1?></a> <?php
		} elseif ($_POST['page'] >= $pages - 5) { ?>
			<a class="page" href="/analitic.php?page=1&table=views.digital<?=$search?>">1</a>
			<span class="triple_dots">...</span>
			<?php
			for ($i=$pages - 6; $i < $pages; $i++) {
				if (($_POST['page'] == $i && $_POST['get_table'] == 'views.digital') || ($_POST['get_table'] != 'views.digital' && $i == 1)): ?>
					<a class="page active_page" href="/analitic.php?page=<?=$i?>&table=views.digital<?=$search?>"><?=$i?></a>
				<?php else: ?>
					<a class="page" href="/analitic.php?page=<?=$i?>&table=views.digital<?=$search?>"><?=$i?></a>
				<?php endif ?>
			<?php }
		} else { ?>
			<a class="page" href="/analitic.php?page=1&table=views.digital<?=$search?>">1</a>
			<span class="triple_dots">...</span>
			<?php
			for ($i=$_POST['page'] - 3; $i < $_POST['page'] + 4; $i++) {
				if (($_POST['page'] == $i && $_POST['get_table'] == 'views.digital') || ($_POST['get_table'] != 'views.digital' && $i == 1)): ?>
					<a class="page active_page" href="/analitic.php?page=<?=$i?>&table=views.digital<?=$search?>"><?=$i?></a>
				<?php else: ?>
					<a class="page" href="/analitic.php?page=<?=$i?>&table=views.digital<?=$search?>"><?=$i?></a>
				<?php endif ?>
			<?php } ?>
			<span class="triple_dots">...</span>
			<a class="page" href="/analitic.php?page=<?=$pages - 1?>&table=views.digital<?=$search?>"><?=$pages - 1?></a> <?php
		}
	} ?>

<?php elseif ($_POST['format'] == 'audio'): ?>
	<?php if ($pages <= 10) {
		for ($i=1; $i < $pages; $i++) {
			if (($_POST['page'] == $i && $_POST['get_table'] == 'views.audio') || ($_POST['get_table'] != 'views.audio' && $i == 1)): ?>
				<a class="page active_page" href="/analitic.php?page=<?=$i?>&table=views.audio<?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/analitic.php?page=<?=$i?>&table=views.audio<?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php }
	} else {
		if ($_POST['page'] < 7) {
			for ($i=1; $i < 8; $i++) {
				if (($_POST['page'] == $i && $_POST['get_table'] == 'views.audio') || ($_POST['get_table'] != 'views.audio' && $i == 1)): ?>
					<a class="page active_page" href="/analitic.php?page=<?=$i?>&table=views.audio<?=$search?>"><?=$i?></a>
				<?php else: ?>
					<a class="page" href="/analitic.php?page=<?=$i?>&table=views.audio<?=$search?>"><?=$i?></a>
				<?php endif ?>
			<?php } ?>
			<span class="triple_dots">...</span>
			<a class="page" href="/analitic.php?page=<?=$pages - 1?>&table=views.audio<?=$search?>"><?=$pages - 1?></a> <?php
		} elseif ($_POST['page'] >= $pages - 6) { ?>
			<a class="page" href="/analitic.php?page=1&table=views.audio<?=$search?>">1</a>
			<span class="triple_dots">...</span>
			<?php
			for ($i=$pages - 7; $i < $pages; $i++) {
				if (($_POST['page'] == $i && $_POST['get_table'] == 'views.audio') || ($_POST['get_table'] != 'views.audio' && $i == 1)): ?>
					<a class="page active_page" href="/analitic.php?page=<?=$i?>&table=views.audio<?=$search?>"><?=$i?></a>
				<?php else: ?>
					<a class="page" href="/analitic.php?page=<?=$i?>&table=views.audio<?=$search?>"><?=$i?></a>
				<?php endif ?>
			<?php }
		} else { ?>
			<a class="page" href="/analitic.php?page=1&table=views.audio<?=$search?>">1</a>
			<span class="triple_dots">...</span>
			<?php
			for ($i=$_POST['page'] - 3; $i < $_POST['page'] + 4; $i++) {
				if (($_POST['page'] == $i && $_POST['get_table'] == 'views.audio') || ($_POST['get_table'] != 'views.audio' && $i == 1)): ?>
					<a class="page active_page" href="/analitic.php?page=<?=$i?>&table=views.audio<?=$search?>"><?=$i?></a>
				<?php else: ?>
					<a class="page" href="/analitic.php?page=<?=$i?>&table=views.audio<?=$search?>"><?=$i?></a>
				<?php endif ?>
			<?php } ?>
			<span class="triple_dots">...</span>
			<a class="page" href="/analitic.php?page=<?=$pages - 1?>&table=views.audio<?=$search?>"><?=$pages - 1?></a> <?php
		}
	} ?>
<?php endif ?>





















