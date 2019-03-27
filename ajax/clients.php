<?php
require '../db.php';
require '../db_shop.php';

$period = $_POST['period'];
if ($_POST['role'] == 'ББТ') {
	// get all clients
	$clients_arr = $dbc_shop->query("SELECT * FROM `wp_users`");
	$count = $clients_arr->num_rows;
} elseif ($_POST['role'] == 'Команда') {
	// get partners of command, and after get clients of each partner
	$clients_arr = array();
	$partners_array = $dbc->query("SELECT * FROM `users` WHERE `position` = 'partner' AND `parent` = {$_POST['parent']}");
	foreach ($partners_array as $partner) {
		$clients_array = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '".$partner['code']."'");
		foreach ($clients_array as $client)
			$clients_arr[] = $client;
	}
	$count = count($clients_arr);
} else {
	// get clients of partner
	$code = $dbc->query("SELECT * FROM `users` WHERE `id` = {$_POST['parent']}");
	$code = $code->fetch_array(MYSQLI_ASSOC);
	$code = $code['code'];

	$clients_arr = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '$code'");
	$count = $clients_arr->num_rows;
}

$array = [];
foreach ($clients_arr as $client) {
	$id = $client['ID'];

	// get name
	$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'first_name'");
	if ($meta_array)
		$client['first_name'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

	// get second name
	$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'last_name'");
	if ($meta_array)
		$client['last_name'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

	$client['name'] = $client['first_name'].' '.$client['last_name'];

	// get city
	$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'billing_city'");
	if ($meta_array)
		$client['city'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

	// get picture
	$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'profile_pic'");
	if ($meta_array) {
		$client['picture'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];
		$meta_array = $dbc_shop->query("SELECT * FROM `wp_posts` WHERE `ID` = {$client['picture']}");
		if ($meta_array)
			$client['picture'] = 'http://bbt-online.ru/wp-content/uploads/' . end(explode('/', $meta_array->fetch_array(MYSQLI_ASSOC)['guid']));
	}
	if ($client['picture'] == '')
		$client['picture'] = '/avatars/avatar.png';
		

	// get parent
	$parent = $dbc->query("SELECT * FROM `users` WHERE `code` = '{$client['parent']}'");
	if ($parent) {
		$parent = $parent->fetch_array(MYSQLI_ASSOC)['parent'];
		$parent = $dbc->query("SELECT * FROM `users` WHERE `id` = '$parent'");
		$client['parent'] = $parent->fetch_array(MYSQLI_ASSOC)['name'];
	}
	if ($client['parent'] == '')
		$client['parent'] = 'ББТ';

	// get clients
	$clients = $dbc->query("SELECT * FROM `users` WHERE `code` = '{$client['parent']}'");
	$client['clients'] = $clients->num_rows;

	// get bought summ
	$client['bought'] = 0;
	$bought = $dbc->query("SELECT * FROM `sold` WHERE `client` = $id AND $period");
	if ($bought)
		foreach ($bought as $value)
			$client['bought'] += $value['summ'];
	
	// get sold summ
	$client['sold'] = 0;
	if ($clients)
		foreach ($clients as $value) {
			$bought = $dbc->query("SELECT * FROM `sold` WHERE `client` = {$value['ID']} AND $period");
			if ($bought)
				foreach ($bought as $value2)
					$client['sold'] += $value2['summ'];
		}

	if ($_POST['search'] != '' &&
		stripos(mb_strtolower($client['parent'], 'UTF-8'), mb_strtolower($_POST['search'], 'UTF-8')) === false &&
		stripos(mb_strtolower($client['first_name'].' '.$client['last_name'], 'UTF-8'), mb_strtolower($_POST['search'], 'UTF-8')) === false)
		continue;

	$array[] = $client;
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
if (!isset($_POST['rows_size'])) $rows = 20;
else $rows = $_POST['rows_size'];
$offset = $_POST['page'] * $rows - $rows;
$limit = $_POST['page'] * $rows;
$pages = ceil($count / $rows) + 1;

while ($offset > count($array)) {
	$_POST['page']--;
	$offset = $_POST['page'] * $rows - $rows;
	$limit = $_POST['page'] * $rows;
}


for ($i=$offset; $i < $limit && $i < count($array); $i++): ?>
	<tr data-id="client<?=$array[$i]['ID']?>">
		<td class="table_command_name">
			<div class="command_picture_wrapp" style="background-image: url(<?=$array[$i]['picture']?>);"></div>
			<div class="command_name_wrap">
				<span class="command_name"><?=$array[$i]['first_name'].' '.$array[$i]['last_name']?></span>
				<span class="command_city"><?=$array[$i]['city']?></span>
			</div>
		</td>
		<td><?=$array[$i]['parent']?></td>
		<td class="table_align_center"><?=$array[$i]['clients']?></td>
		<td class="table_align_center"><?=$array[$i]['sold']?> &#8381;</td>
		<td class="table_align_center"><?=$array[$i]['bought']?> &#8381;</td>
	</tr>
<?php endfor;

mysqli_close($dbc);
?>
===================================================================================================

<?php if ($_POST['search'] != '')
	$search = '&search='.$_POST['search'];


if ($pages <= 10) {
	for ($i=1; $i < $pages; $i++) {
		if (($_POST['request_uri'] == "/clients.php" && $i == 1) || ($_POST['page'] == $i)): ?>
			<a class="page active_page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
		<?php else: ?>
			<a class="page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
		<?php endif ?>
	<?php }
} else {
	if ($_POST['page'] < 7) {
		for ($i=1; $i < 8; $i++) {
			if (($_POST['request_uri'] == "/clients.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/clients.php?page=<?=$pages - 1?><?=$search?>"><?=$pages - 1?></a> <?php
	} elseif ($_POST['page'] >= $pages - 6) { ?>
		<a class="page" href="/clients.php?page=1<?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$pages - 7; $i < $pages; $i++) {
			if (($_POST['request_uri'] == "/clients.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php }
	} else { ?>
		<a class="page" href="/clients.php?page=1<?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$_POST['page'] - 3; $i < $_POST['page'] + 4; $i++) {
			if (($_POST['request_uri'] == "/clients.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/clients.php?page=<?=$i?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/clients.php?page=<?=$pages - 1?><?=$search?>"><?=$pages - 1?></a> <?php
	}
} ?>



===================================================================================================

<?php echo $_POST['token']; ?>