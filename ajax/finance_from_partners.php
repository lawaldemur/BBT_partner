<?php
require '../db.php';
$user_id = $_POST['id'];

$array = array();
$reports_array = $dbc->query("SELECT * FROM `reports` WHERE `to_id` = $user_id");

foreach ($reports_array as $report) {
	$from = $dbc->query("SELECT * FROM `users` WHERE `id` = {$report['from_id']}");
	$from = $from->fetch_array(MYSQLI_ASSOC);

	if ($_POST['search'] != '' && stristr(strtolower($from['name']), strtolower($_POST['search'])) === FALSE &&
	stristr(strtolower($from['city']), strtolower($_POST['search'])) === FALSE) {
		continue;
	}

	if (isset($array[$from['id']])) {
		if ($report['paid'] == 0)
			$array[$from['id']]['count']++;
		if ($report['viewed'] == 1)
			$array[$from['id']]['view'] = 1;
	} else {
		$array[$from['id']] = array(
			'id' => $from['id'],
			'name' => $from['name'],
			'city' => $from['city'],
			'picture' => $from['picture'],
			'count' => $report['paid'] == 0 ? 1 : 0,
			'view' => $report['viewed'],
		);
	}

}

// ассоциативный массив в список
$array = array_values($array);

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

// pagination
if (!isset($_POST['page'])) $_POST['page'] = 1;
if (!isset($_POST['rows_size'])) $rows = 20;
else $rows = $_POST['rows_size'];
$offset = $_POST['page'] * $rows - $rows;
$limit = $_POST['page'] * $rows;
$pages = ceil(count($array) / $rows) + 1;
?>


<?php for ($i=$offset; $i < $limit && $i < count($array); $i++): ?>
	<tr data-id="<?=$array[$i]['id']?>">
		<td class="table_command_name">
			<?php if ($array[$i]['view'] == 1): ?>
				<div class="viewed_icon"></div>
			<?php endif ?>
			<div class="command_picture_wrapp" style="background-image: url(/avatars/<?=$array[$i]['picture']?>);"></div>
			<div class="command_name_wrap">
				<span class="command_name"><?=$array[$i]['name']?></span>
				<span class="command_city"><?=$array[$i]['city']?></span>
			</div>
		</td>
		<td class="table_align_center"><?=$array[$i]['count']?></td>
	</tr>
<?php endfor; ?>

////////=============////////

<?php $search = '';

if ($pages <= 10) {
	for ($i=1; $i < $pages; $i++) {
		if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
			<a class="page active_page" href="/finance.php?page=<?=$i?>&viewpartners=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
		<?php else: ?>
			<a class="page" href="/finance.php?page=<?=$i?>&viewpartners=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
		<?php endif ?>
	<?php }
} else {
	if ($_POST['page'] < 7) {
		for ($i=1; $i < 8; $i++) {
			if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/finance.php?page=<?=$i?>&viewpartners=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/finance.php?page=<?=$i?>&viewpartners=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/finance.php?page=<?=$pages - 1?>&viewpartners=<?=$_POST['id']?><?=$search?>"><?=$pages - 1?></a> <?php
	} elseif ($_POST['page'] >= $pages - 6) { ?>
		<a class="page" href="/finance.php?page=1&viewpartners=<?=$_POST['id']?><?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$pages - 7; $i < $pages; $i++) {
			if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/finance.php?page=<?=$i?>&viewpartners=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/finance.php?page=<?=$i?>&viewpartners=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php }
	} else { ?>
		<a class="page" href="/finance.php?page=1&viewpartners=<?=$_POST['id']?><?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$_POST['page'] - 3; $i < $_POST['page'] + 4; $i++) {
			if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/finance.php?page=<?=$i?>&viewpartners=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/finance.php?page=<?=$i?>&viewpartners=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/finance.php?page=<?=$pages - 1?>&viewpartners=<?=$_POST['id']?><?=$search?>"><?=$pages - 1?></a> <?php
	}
} ?>