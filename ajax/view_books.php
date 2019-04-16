<?php
require '../db.php';
require '../db_shop.php';

// if ($_POST['table'] == 'digital' || $_POST['table'] == 'audio')
// 	$_POST['format'] = $_POST['table'];

$sort = $_POST['sortType'];
$period = $_POST['period'];
$role = $_POST['role'];
$user_id = $_POST['user_id'];

$where = 'WHERE '.$period;
if (isset($_POST['format']) && $_POST['format'] != '' && $_POST['format'] != 'all')
	$where .= ' AND `format` = \''.$_POST['format']."'";

// if ($sort == 'bydate')
// 	$books = $dbc->query("SELECT * FROM `analitic` $where");
// else
// 	$books = $dbc->query("SELECT * FROM `analitic_bybook` $where");
if ($role == 'command') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_command_id` = $user_id");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_command_id` = $user_id");
} elseif ($role == 'partner') {
	if ($sort == 'bydate')
		$books = $dbc->query("SELECT * FROM `analitic` $where AND `to_partner_id` = $user_id");
	else
		$books = $dbc->query("SELECT * FROM `analitic_bybook` $where AND `to_partner_id` = $user_id");
} else {
	$books = $dbc->query("SELECT * FROM `sold` $where AND `client` = $user_id");
}

// pagination
if ($_POST['rows_size'] == '') $rows = 20;
else $rows = $_POST['rows_size'];

if ($_POST['get_table'] != '') $_POST['page'] = 1;

$offset = $_POST['page'] * $rows - $rows;
$limit = $_POST['page'] * $rows;
$pages = ceil($books->num_rows / $rows) + 1;

while ($offset > count($array)) {
	$_POST['page']--;
	$offset = $_POST['page'] * $rows - $rows;
	$limit = $_POST['page'] * $rows;
}

// content for BBT - all books
// if ($sort == 'bydate')
// 	$books = $dbc->query("SELECT * FROM `analitic` $where");
// else
// 	$books = $dbc->query("SELECT * FROM `analitic_bybook` $where");

$array = array();
foreach ($books as $book) {
	$book['img'] = $dbc_shop->query("SELECT * FROM `wp_posts` WHERE `post_parent` = {$book['product']} AND `post_type` = 'attachment'");
	$book['img'] = $book['img']->fetch_array(MYSQLI_ASSOC)['guid'];

	if ($book['format'] == 'digital')
		$book['format'] = '<img src="/img/format_digital.svg" alt="digital_format" width="14" height="18">';
	elseif ($book['format'] == 'audio')
		$book['format'] = '<img src="/img/format_audio.svg" alt="audio_format" width="16" height="18">';

	$book['price'] = $dbc_shop->query("SELECT * FROM `wp_postmeta` WHERE `post_id` = {$book['variation']} AND `meta_key` = '_price'");
	$book['price'] = $book['price']->fetch_array(MYSQLI_ASSOC)['meta_value'];

	if ($sort == 'bybook' && ($role == 'command' || $role == 'partner')) {
		$total = 0;
		$summ = unserialize($book['sold']);

		if ($period == '`date` >= CURDATE()')
			$date = date('Y-m-d');

		foreach ($summ as $sum) {
			if (strtotime($sum[0]) >= $date) $total += $sum[1];
		}
		$book['summ'] = $total;
	}

	$book['count'] = $book['summ'] / $book['price'];

	if (!($role == 'command' || $role == 'partner')) {
		$book['name'] = $dbc_shop->query("SELECT * FROM `wp_posts` WHERE `ID` = {$book['product']}");
		$book['name'] = $book['name']->fetch_array(MYSQLI_ASSOC)['post_title'];

		$book['other'] = $dbc_shop->query("SELECT * FROM `wp_postmeta` WHERE `post_id` = {$book['variation']} AND `meta_key` = 'attribute_pa_writer'");
		$book['other'] = $book['other']->fetch_array(MYSQLI_ASSOC)['meta_value'];
		$book['other'] = $dbc_shop->query("SELECT * FROM `wp_terms` WHERE `slug` = '{$book['other']}'");
		$book['other'] = $book['other']->fetch_array(MYSQLI_ASSOC)['name'];

		if ($book['other'] == '') {
			$other = $dbc->query("SELECT * FROM `analitic` WHERE `product` = {$book['product']}");
			if ($other)
				foreach ($other as $author) {
					$book['other'] = $author['other'];
					break;
				}
		}
	}

	$array[] = $book;
}

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

for ($i=$offset; $i < $limit && $i < count($array); $i++): ?>
	<tr>
		<?php if ($sort == 'bydate'): ?>
			<td><?=date("d.m.Y", strtotime($array[$i]['date']))?></td>
		<?php endif; ?>
		<td class="table_product_name">
			<img src="<?=$array[$i]['img']?>" alt="product_picture">
			<div class="author_name">
				<span class="book_name"><?=$array[$i]['name']?></span>
				<span class="book_author"><?=$array[$i]['other']?></span>
			</div>
		</td>
		<td><?=$array[$i]['format']?></td>
		<td><?=$array[$i]['count']?></td>
		<td><?=$array[$i]['price']?> &#8381;</td>
		<td><?=$array[$i]['summ']?> &#8381;</td>
		<?php if ($role == 'command' || $role == 'partner'): ?>
			<td><?=$array[$i]['to_'.$role]?> &#8381;</td>
		<?php endif ?>
	</tr>
<?php endfor;

mysqli_close($dbc);
?>
===================================================================================================

<!-- <?php for ($i=1; $i < $pages; $i++) {
	if (($_POST['request_uri'] == "/view.php?id=$user_id" && $i == 1) || ($_POST['page'] == $i && $_POST['get_table'] == '') || ($_POST['get_table'] != '' && $i == 1)): ?>
		<a class="page active_page" href="/view.php?id=<?=$user_id?>&page=<?=$i?>"><?=$i?></a>
	<?php else: ?>
		<a class="page" href="/view.php?id=<?=$user_id?>&page=<?=$i?>"><?=$i?></a>
	<?php endif ?>
<?php } ?> -->

<?php if ($_POST['search'] != '')
	$search = '&search='.$_POST['search'];

if ($pages <= 10) {
	for ($i=1; $i < $pages; $i++) {
		if (($_POST['request_uri'] == "/view.php?id=$user_id" && $i == 1) || ($_POST['page'] == $i)): ?>
			<a class="page active_page" href="/view.php?id=<?=$user_id?>&page=<?=$i?><?=$search?>"><?=$i?></a>
		<?php else: ?>
			<a class="page" href="/view.php?id=<?=$user_id?>&page=<?=$i?><?=$search?>"><?=$i?></a>
		<?php endif ?>
	<?php }
} else {
	if ($_POST['page'] < 7) {
		for ($i=1; $i < 8; $i++) {
			if (($_POST['request_uri'] == "/view.php?id=$user_id" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/view.php?id=<?=$user_id?>&page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/view.php?id=<?=$user_id?>&page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/view.php?id=<?=$user_id?>&page=<?=$pages - 1?><?=$search?>"><?=$pages - 1?></a> <?php
	} elseif ($_POST['page'] >= $pages - 6) { ?>
		<a class="page" href="/view.php?id=<?=$user_id?>&page=1<?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$pages - 7; $i < $pages; $i++) {
			if (($_POST['request_uri'] == "/view.php?id=$user_id" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/view.php?id=<?=$user_id?>&page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/view.php?id=<?=$user_id?>&page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php }
	} else { ?>
		<a class="page" href="/view.php?id=<?=$user_id?>&page=1<?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$_POST['page'] - 3; $i < $_POST['page'] + 4; $i++) {
			if (($_POST['request_uri'] == "/view.php?id=$user_id" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/view.php?id=<?=$user_id?>&page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/view.php?id=<?=$user_id?>&page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/view.php?id=<?=$user_id?>&page=<?=$pages - 1?><?=$search?>"><?=$pages - 1?></a> <?php
	}
} ?>
