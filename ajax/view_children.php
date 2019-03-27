<?php
require '../db.php';
require '../db_shop.php';

$sort = $_POST['sortType'];
$period = $_POST['period'];
$role = $_POST['role'];
$user_id = $_POST['user_id'];

if ($role == 'partner') {
	$code = $dbc->query("SELECT * FROM `users` WHERE `id` = $user_id");
	$code = $code->fetch_array(MYSQLI_ASSOC)['code'];
}

if ($_POST['search'] == '' && $role == 'command')
	$users = $dbc->query("SELECT * FROM `users` WHERE `parent` = $user_id");
elseif ($_POST['search'] != '' && $role == 'command')
	$users = $dbc->query("SELECT * FROM `users` WHERE `parent` = $user_id AND `name` LIKE '%{$_POST['search']}%' OR `city` LIKE '%{$_POST['search']}%'");
elseif ($role == 'partner')
	$users = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '$code'");

// pagination
if ($_POST['rows_size'] == '') $rows = 20;
else $rows = $_POST['rows_size'];

if ($_POST['get_table'] != 'children') $_POST['page'] = 1;

$offset = $_POST['page'] * $rows - $rows;
$limit = $_POST['page'] * $rows;
$pages = ceil($users->num_rows / $rows) + 1;



$array = array();
if ($users)
if ($role == 'command')
	foreach ($users as $user) {
		$user['summ_sold'] = 0;
		$user['summ_get'] = 0;
		$user['summ_wait'] = 0;

		$summ_sold = $dbc->query("SELECT * FROM `sold` WHERE `to_partner_id` = {$user['id']} AND $period");
		if ($summ_sold)
		foreach ($summ_sold as $summ) {
			$user['summ_sold'] += $summ['summ'];
			$user['summ_get'] += $summ['to_partner'];
		}

		$summ_wait = $dbc->query("SELECT * FROM `reports` WHERE `from_id` = {$user['id']} AND `paid` = 0");
		if ($summ_wait)
		foreach ($summ_wait as $summ)
			$user['summ_wait'] += $summ['summ'];

		$clients = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '{$user['code']}'");
		$user['clients'] = $clients->num_rows;

		$array[] = $user;
	}
else
	foreach ($users as $client) {
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

		// get clients
		$clients = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `parent` = '{$client['code']}'");
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

		if ($_POST['search'] != '' && stristr(strtolower($client['parent']), strtolower($_POST['search'])) === FALSE &&
		stristr(strtolower($client['first_name'].' '.$client['last_name']), strtolower($_POST['search'])) === FALSE)
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

while ($offset > count($array)) {
	$_POST['page']--;
	$offset = $_POST['page'] * $rows - $rows;
	$limit = $_POST['page'] * $rows;
}

if ($role == 'command') {
	for ($i=$offset; $i < $limit && $i < count($array); $i++): ?>
		<tr data-id="<?=$array[$i]['id']?>">
			<td class="table_command_name">
				<div class="command_picture_wrapp" style="background-image: url(/avatars/<?=$array[$i]['picture']?>);"></div>
				<div class="command_name_wrap">
					<span class="command_name"><?=$array[$i]['name']?></span>
					<span class="command_city"><?=$array[$i]['city']?></span>
				</div>
			</td>
			<td class="table_align_center"><?=$array[$i]['clients']?></td>
			<td class="table_align_center"><?=$array[$i]['summ_sold']?> &#8381;</td>
			<td class="table_align_center"><?=$array[$i]['summ_get']?> &#8381;</td>
			<?php if ($array[$i]['summ_wait'] != 0): ?>
				<td class="table_align_center" style="color: #ff441f;"><?=$array[$i]['summ_wait']?> &#8381;</td>
			<?php else: ?>
				<td class="table_align_center"><?=$array[$i]['summ_wait']?> &#8381;</td>
			<?php endif ?>
		</tr>
	<?php endfor;
} else {
	for ($i=$offset; $i < $limit && $i < count($array); $i++): ?>
		<tr data-id="<?=$array[$i]['ID']?>">
			<td class="table_command_name">
				<div class="command_picture_wrapp" style="background-image: url(<?=$array[$i]['picture']?>);"></div>
				<div class="command_name_wrap">
					<span class="command_name"><?=$array[$i]['name']?></span>
					<span class="command_city"><?=$array[$i]['city']?></span>
				</div>
			</td>
			<td class="table_align_center"><?=$array[$i]['clients']?></td>
			<td class="table_align_center"><?=$array[$i]['sold']?> &#8381;</td>
			<td class="table_align_center"><?=$array[$i]['bought']?> &#8381;</td>
		</tr>
	<?php endfor;
}
mysqli_close($dbc);
?>
===================================================================================================

<!-- <?php for ($i=1; $i < $pages; $i++) {
	if (($_POST['request_uri'] == "/view.php?id=$user_id" && $i == 1) || ($_POST['page'] == $i && $_POST['get_table'] == 'children') || ($_POST['get_table'] != 'children' && $i == 1)): ?>
		<a class="page active_page" href="/view.php?id=<?=$user_id?>&page=<?=$i?>&table=children"><?=$i?></a>
	<?php else: ?>
		<a class="page" href="/view.php?id=<?=$user_id?>&page=<?=$i?>&table=children"><?=$i?></a>
	<?php endif ?>
<?php } ?> -->



<?php if ($_POST['search'] != '')
	$search = '&search='.$_POST['search'];

if ($pages <= 10) {
	for ($i=1; $i < $pages; $i++) {
		if (($_POST['request_uri'] == "/view.php?id=$user_id" && $i == 1) || ($_POST['page'] == $i)): ?>
			<a class="page active_page" href="/view.php?id=<?=$user_id?>&page=<?=$i?>&table=children<?=$search?>"><?=$i?></a>
		<?php else: ?>
			<a class="page" href="/view.php?id=<?=$user_id?>&page=<?=$i?>&table=children<?=$search?>"><?=$i?></a>
		<?php endif ?>
	<?php }
} else {
	if ($_POST['page'] < 7) {
		for ($i=1; $i < 8; $i++) {
			if (($_POST['request_uri'] == "/view.php?id=$user_id" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/view.php?id=<?=$user_id?>&page=<?=$i?>&table=children<?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/view.php?id=<?=$user_id?>&page=<?=$i?>&table=children<?=$search?>"><?=$i?></a>
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
				<a class="page active_page" href="/view.php?id=<?=$user_id?>&page=<?=$i?>&table=children<?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/view.php?id=<?=$user_id?>&page=<?=$i?>&table=children<?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php }
	} else { ?>
		<a class="page" href="/view.php?id=<?=$user_id?>&page=1<?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$_POST['page'] - 3; $i < $_POST['page'] + 4; $i++) {
			if (($_POST['request_uri'] == "/view.php?id=$user_id" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/view.php?id=<?=$user_id?>&page=<?=$i?>&table=children<?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/view.php?id=<?=$user_id?>&page=<?=$i?>&table=children<?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/view.php?id=<?=$user_id?>&page=<?=$pages - 1?><?=$search?>"><?=$pages - 1?></a> <?php
	}
} ?>
