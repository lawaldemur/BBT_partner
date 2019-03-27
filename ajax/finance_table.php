<?php
require '../db.php';

$role = $_POST['role'];
$user_id = $_POST['to'];

$array = array();
if ($role == 'ББТ') {
	$reports_array = $dbc->query("SELECT * FROM `reports` WHERE `to_id` = $user_id");

	foreach ($reports_array as $report) {
		$from = $dbc->query("SELECT * FROM `users` WHERE `id` = {$report['from_id']}");
		$from = $from->fetch_array(MYSQLI_ASSOC);

		if ($_POST['search'] != '' && stristr(mb_strtolower($from['name'], 'UTF-8'), mb_strtolower($_POST['search'], 'UTF-8')) === FALSE &&
		stristr(mb_strtolower($from['city'], 'UTF-8'), mb_strtolower($_POST['search'], 'UTF-8')) === FALSE) {
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
} elseif ($role == 'Команда') {
	$reports_for_bbt = $dbc->query("SELECT * FROM `reports` WHERE `from_id` = $user_id");
	
	$reports_array = $dbc->query("SELECT * FROM `reports` WHERE `to_id` = $user_id");
	foreach ($reports_array as $report) {
		$from = $dbc->query("SELECT * FROM `users` WHERE `id` = {$report['from_id']}");
		$from = $from->fetch_array(MYSQLI_ASSOC);

		if (isset($array[$from['id']])) {
			if ($report['paid'] == 0)
				$array[$from['id']]['count']++;
		} else {
			$array[$from['id']] = array(
				'id' => $from['id'],
				'name' => $from['name'],
				'city' => $from['city'],
				'picture' => $from['picture'],
				'count' => $report['paid'] == 0 ? 1 : 0,
				'link' => '/finance_view.php?id='.$from['id'],
			);
		}

	}
} else {
	$reports_for_commands = $dbc->query("SELECT * FROM `reports` WHERE `from_id` = $user_id");
}

// ассоциативный массив в список
$array = array_values($array);

for ($i=0; $i < count($array); $i++) { 
	for ($x=$i + 1; $x < count($array); $x++) { 
		if ($_POST['sortColumnType'] == 'default')
			$bool = $array[$i][$_POST['sortColumn']] < $array[$x][$_POST['sortColumn']];
		else
			$bool = $array[$i][$_POST['sortColumn']] > $array[$x][$_POST['sortColumn']];

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

while ($offset > count($array) && $_POST['page'] > 1) {
	$_POST['page']--;
	$offset = $_POST['page'] * $rows - $rows;
	$limit = $_POST['page'] * $rows;
}
?>


<?php if ($role == 'ББТ'): ?>
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
<?php elseif($role == 'Команда'): ?>
<?php elseif($role == 'Партнер'): ?>
<?php endif ?>

===================================================================================================



<?php $search = '';

if ($pages <= 10) {
	for ($i=1; $i < $pages; $i++) {
		if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
			<a class="page active_page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
		<?php else: ?>
			<a class="page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
		<?php endif ?>
	<?php }
} else {
	if ($_POST['page'] < 7) {
		for ($i=1; $i < 8; $i++) {
			if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/finance.php?page=<?=$pages - 1?>&table=from_commands<?=$search?>"><?=$pages - 1?></a> <?php
	} elseif ($_POST['page'] >= $pages - 6) { ?>
		<a class="page" href="/finance.php?page=1&table=from_commands<?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$pages - 7; $i < $pages; $i++) {
			if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php }
	} else { ?>
		<a class="page" href="/finance.php?page=1&table=from_commands<?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$_POST['page'] - 3; $i < $_POST['page'] + 4; $i++) {
			if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/finance.php?page=<?=$i?>&table=from_commands<?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/finance.php?page=<?=$pages - 1?>&table=from_commands<?=$search?>"><?=$pages - 1?></a> <?php
	}
} ?>



<script>
	$('.finance_after_table_filters .pagination_list .page').last().addClass('last_pagination');

</script>
<script>
	var token = '';
	$('table[data-table="link"] tbody tr').click(function() {
		$('.finance_row').hide();

		var sortColumn = $('#user_view_table .sortColumn_type').parent().data('column');
		if ($('#user_view_table .sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';

		var id = $(this).data('id');
		token = new Date().getUTCMilliseconds();
		if ($(this).find('.viewed_icon').length == 1)
			$(this).find('.viewed_icon').remove();

		$.ajax({
			url: '/ajax/finance_view.php',
			type: 'POST',
			dataType: 'html',
			data: {
				id: id,
				token: token,
				rows_size: $('.user_view_tbody_after_table_filters .table_size_active').text(),
				page: $('#active_page').val(),
				request_uri: location.pathname + location.search,
				sortColumn: sortColumn,
				sortColumnType: sortColumnType,
			},
		})
		.done(function(res) {
			console.log(res);
			res = res.split('////////=============////////');
			if (res[3] == token) {
				$('.user_view_tbody').html(res[1]);

				info = res[0].split('|0|');
				$('img.avatar').attr('src', '/avatars/' + info[0]);
				$('.finance_view_name .name').html(info[1] + '<span class="count">' + info[2] + '</span>');
				$('.finance_view_name .address').html(info[3]);

				$('.user_view.row').attr('data-id', id);
				$('.user_view_pagination_list .pages_list').html(res[2]);
				$('.user_view_pagination_list .page').last().addClass('last_pagination');

				$('.user_view').css('display', 'flex');
			}
		});
		
	});

	
	
</script>

===================================================================================================
<?php echo $_POST['token']; ?>