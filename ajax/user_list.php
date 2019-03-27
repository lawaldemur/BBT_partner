<?php
require '../db.php';
require '../db_shop.php';
session_start();

if (isset($_SESSION['logged']))
	$user = $_SESSION['logged'];
elseif (isset($_COOKIE['logged']))
	$user = $_COOKIE['logged'];
$user_id = explode('|', $user)[2];
if ($_POST['table'] == 'command') {
	if ($user_id != '1')
		exit();
} else {
	if ($user_id != $_POST['command_partners'])
		exit();
}

if ($_POST['command_partners'] == '')
	$commands_array = $dbc->query("SELECT * FROM `users` WHERE `position` = '{$_POST['table']}'");
else
	$commands_array = $dbc->query("SELECT * FROM `users` WHERE `position` = '{$_POST['table']}' AND `parent` = {$_POST['command_partners']}");


$array = [];
$period = $_POST['period'];
foreach ($commands_array as $command) {
	$command['summ_sold'] = 0;
	$command['summ_get'] = 0;
	$command['summ_wait'] = 0;

	$summ_sold = $dbc->query("SELECT * FROM `sold` WHERE `to_{$_POST['table']}_id` = {$command['id']} AND $period");
	if ($summ_sold)
	foreach ($summ_sold as $summ) {
		$command['summ_sold'] += $summ['summ'];
		$command['summ_get'] += $summ['to_'.$_POST['table']];
	}

	$summ_wait = $dbc->query("SELECT * FROM `reports` WHERE `from_id` = {$command['id']} AND `paid` = 0");
	if ($summ_wait)
	foreach ($summ_wait as $summ)
		$command['summ_wait'] += $summ['sum'];

	if ($_POST['table'] == 'partner') {
		$clients = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '{$command['code']}'");
		$command['clients'] = $clients->num_rows;

		if ($command['parent'] == 1) {
			continue;
		}
		$command['parent'] = $dbc->query("SELECT * FROM `users` WHERE `id` = {$command['parent']}");
		$command['parent'] = $command['parent']->fetch_array(MYSQLI_ASSOC)['name'];
	}

	if ($_POST['role'] == 'Команда') {
		if ($_POST['search'] != '' &&
			stripos(mb_strtolower($command['parent'], 'UTF-8'), mb_strtolower($_POST['search'], 'UTF-8')) === false &&
			stripos(mb_strtolower($command['name'], 'UTF-8'), mb_strtolower($_POST['search'], 'UTF-8')) === false &&
			stripos(mb_strtolower($command['city'], 'UTF-8'), mb_strtolower($_POST['search'], 'UTF-8')) === false)
			continue;
	} else {
		if ($_POST['search'] != '' &&
			stripos(mb_strtolower($command['name'], 'UTF-8'), mb_strtolower($_POST['search'], 'UTF-8')) === false &&
			stripos(mb_strtolower($command['city'], 'UTF-8'), mb_strtolower($_POST['search'], 'UTF-8')) === false)
			continue;
	}


	$array[] = $command;
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

// pagination
if (!isset($_POST['page'])) $_POST['page'] = 1;
if ($_POST['rows_size'] == '') $rows = 20;
else $rows = $_POST['rows_size'];
$offset = $_POST['page'] * $rows - $rows;
$limit = $_POST['page'] * $rows;
$pages = ceil($commands_array->num_rows / $rows) + 1;

while ($offset > count($array)) {
	$_POST['page']--;
	$offset = $_POST['page'] * $rows - $rows;
	$limit = $_POST['page'] * $rows;
}

if ($_POST['table'] == 'command') {
	for ($i=$offset; $i < $limit && $i < count($array); $i++): ?>
		<tr data-id="<?=$array[$i]['id']?>" data-email="<?=$array[$i]['login']?>" data-digital_percent="<?=$array[$i]['digital_percent']?>" data-audio_percent="<?=$array[$i]['audio_percent']?>" data-pass_length="<?=strlen($array[$i]['password'])?>" data-name="<?=$array[$i]['name']?>" data-region="<?=$array[$i]['city']?>">
			<td class="table_command_name">
				<div class="command_picture_wrapp" style="background-image: url(/avatars/<?=$array[$i]['picture']?>);"></div>
				<div class="command_name_wrap">
					<span class="command_name"><?=$array[$i]['name']?></span>
					<span class="command_city"><?=$array[$i]['city']?></span>
				</div>
			</td>
			<td class="table_align_center"><?=$array[$i]['summ_sold']?> &#8381;</td>
			<td class="table_align_center"><?=$array[$i]['digital_percent']?>%</td>
			<td class="table_align_center"><?=$array[$i]['summ_get']?> &#8381;</td>
			<?php if ($array[$i]['summ_wait'] != 0): ?>
				<td class="table_align_center" style="color: #ff441f;"><?=$array[$i]['summ_wait']?> &#8381;</td>
			<?php else: ?>
				<td class="table_align_center"><?=$array[$i]['summ_wait']?> &#8381;</td>
			<?php endif ?>
			<td class="table_align_center"><img src="/img/control.svg" alt="control_command" class="control_command"></td>
		</tr>
	<?php endfor;
} else {
	for ($i=$offset; $i < $limit && $i < count($array); $i++): ?>
		<tr data-id="<?=$array[$i]['id']?>" data-email="<?=$array[$i]['login']?>" data-digital_percent="<?=$array[$i]['digital_percent']?>" data-audio_percent="<?=$array[$i]['audio_percent']?>" data-pass_length="<?=strlen($array[$i]['password'])?>" data-name="<?=$array[$i]['name']?>" data-region="<?=$array[$i]['city']?>">
			<td class="table_command_name">
				<div class="command_picture_wrapp" style="background-image: url(/avatars/<?=$array[$i]['picture']?>);"></div>
				<div class="command_name_wrap">
					<span class="command_name"><?=$array[$i]['name']?></span>
					<?php if ($_POST['role'] == 'Команда'): ?>
						<span class="command_city"><?=$array[$i]['city']?></span>
					<?php else: ?>
						<span class="command_city"><?=$array[$i]['parent']?></span>
					<?php endif ?>
				</div>
			</td>
			<td class="table_align_center"><?=$array[$i]['clients']?></td>
			<td class="table_align_center"><?=$array[$i]['summ_sold']?> &#8381;</td>
			<td class="table_align_center"><?=$array[$i]['digital_percent']?>%</td>
			<td class="table_align_center"><?=$array[$i]['summ_get']?> &#8381;</td>
			<?php if ($array[$i]['summ_wait'] != 0): ?>
				<td class="table_align_center" style="color: #ff441f;"><?=$array[$i]['summ_wait']?> &#8381;</td>
			<?php else: ?>
				<td class="table_align_center"><?=$array[$i]['summ_wait']?> &#8381;</td>
			<?php endif ?>
			<?php if ($_POST['role'] == 'Команда'): ?>
				<td class="table_align_center"><img src="/img/control.svg" alt="control_partner" class="control_partner"></td>
			<?php endif ?>
		</tr>
	<?php endfor;
}
mysqli_close($dbc);
?>
===================================================================================================
<?php if ($_POST['search'] != '')
	$search = '&search='.$_POST['search'];

if ($pages <= 10) {
	for ($i=1; $i < $pages; $i++) {
		if (($_POST['request_uri'] == "/{$_POST['table']}?>s.php" && $i == 1) || ($_GET['page'] == $i)): ?>
			<a class="page active_page" href="/<?=$_POST['table']?>s.php?page=<?=$i?><?=$search?>"><?=$i?></a>
		<?php else: ?>
			<a class="page" href="/<?=$_POST['table']?>s.php?page=<?=$i?><?=$search?>"><?=$i?></a>
		<?php endif ?>
	<?php }
} else {
	if ($_GET['page'] < 7) {
		for ($i=1; $i < 8; $i++) {
			if (($_POST['request_uri'] == "/{$_POST['table']}?>s.php" && $i == 1) || ($_GET['page'] == $i)): ?>
				<a class="page active_page" href="/<?=$_POST['table']?>s.php?page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/<?=$_POST['table']?>s.php?page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/<?=$_POST['table']?>s.php?page=<?=$pages - 1?><?=$search?>"><?=$pages - 1?></a> <?php
	} elseif ($_GET['page'] >= $pages - 6) { ?>
		<a class="page" href="/<?=$_POST['table']?>s.php?page=1<?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$pages - 7; $i < $pages; $i++) {
			if (($_POST['request_uri'] == "/{$_POST['table']}?>s.php" && $i == 1) || ($_GET['page'] == $i)): ?>
				<a class="page active_page" href="/<?=$_POST['table']?>s.php?page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/<?=$_POST['table']?>s.php?page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php }
	} else { ?>
		<a class="page" href="/<?=$_POST['table']?>s.php?page=1<?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$_GET['page'] - 3; $i < $_GET['page'] + 4; $i++) {
			if (($_POST['request_uri'] == "/{$_POST['table']}?>s.php" && $i == 1) || ($_GET['page'] == $i)): ?>
				<a class="page active_page" href="/<?=$_POST['table']?>s.php?page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/<?=$_POST['table']?>s.php?page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/<?=$_POST['table']?>s.php?page=<?=$pages - 1?><?=$search?>"><?=$pages - 1?></a> <?php
	}
} ?>


===================================================================================================

<?php echo $_POST['token']; ?>