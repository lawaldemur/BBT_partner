<?php
require '../db.php';
setlocale(LC_ALL, 'ru_RU.UTF-8');

$view = $dbc->query("UPDATE `reports` SET `viewed` = 0 WHERE `from_id` = {$_POST['id']}");

$view = $dbc->query("SELECT * FROM `users` WHERE `id` = {$_POST['id']}");
$view = $view->fetch_array(MYSQLI_ASSOC);
if ($view['position'] == 'command') {
	$children = $dbc->query("SELECT * FROM `users` WHERE `parent` = {$_POST['id']}");
	$count = $children->num_rows;
}

$address = json_decode($view['data'])->general_address;
$address = $address == '' ? $view['city'] : $address;

echo $view['picture'].'|0|'.$view['name'].'|0|'.$count.'|0|'.$address;
?>

////////=============////////

<?php
$reports = $dbc->query("SELECT * FROM `reports` WHERE `from_id` = {$_POST['id']}");
$array = [];
foreach ($reports as $report)
	$array[] = $report;

if (!isset($_POST['page'])) $_POST['page'] = 1;
if (!isset($_POST['rows_size'])) $rows = 20;
else $rows = $_POST['rows_size'];
$offset = $_POST['page'] * $rows - $rows;
$limit = $_POST['page'] * $rows;
$pages = ceil(count($array) / $rows) + 1;

// sort array
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


for ($i=$offset; $i < $limit && $i < count($array); $i++):
	$class = $array[$i]['accepted'] == 1 && $array[$i]['paid'] == 1 ? 'class="tr_done"' : '';
	?>
	<tr data-id="<?=$array[$i]['id']?>" <?=$class?>>
		<td><img src="/img/check.svg" width="10" height="8"><?=strftime('%B %Y', strtotime($array[$i]['date']))?></td>
		<td class="table_align_center"><?=$array[$i]['sum']?> &#8381;</td>

		<!-- <td class="table_align_center"><?=$array[$i]['report_done'] == '' ? '<span class="wait_report">Ожидается</span>' : '<button class="finance_open_report" data-document="'.$array[$i]["report_done"].'" data-file="/service/reports/'.date("m.y", strtotime($array[$i]['date'])).'_done/'.$array[$i]['report_done'].'">Открыть</button>'?></td> -->
		<td class="table_align_center"><?=$array[$i]['report_done'] == '' ? '<span class="wait_report">Ожидается</span>' : '<a href="/service/reports/'.date("m.y", strtotime($array[$i]['date'])).'_done/'.$array[$i]['report_done'].'" target="_blank" class="finance_open_report">Открыть</a>'?></td>

		<!-- <td class="table_align_center"><?=$array[$i]['act_done'] == '' ? '<span class="wait_report">Ожидается</span>' : '<button class="finance_open_report" data-document="'.$array[$i]["act_done"].'" data-file="/service/acts/'.date("m.y", strtotime($array[$i]['date'])).'_done/'.$array[$i]['act_done'].'">Открыть</button>'?></td> -->
		<td class="table_align_center"><?=$array[$i]['act_done'] == '' ? '<span class="wait_report">Ожидается</span>' : '<a href="/service/acts/'.date("m.y", strtotime($array[$i]['date'])).'_done/'.$array[$i]['act_done'].'" target="_blank" class="finance_open_report">Открыть</a>'?></td>

		<td class="table_align_center">
			<label>
				<input type="checkbox" <?=$array[$i]['accepted'] == 0 ? '' : 'checked="checked"'?> id="accepted_report" style="display: none;">
				<span class="view_user_check"><img src="/img/check.svg" alt="check" ></span>
			</label>
		</td>

		<td class="table_align_center">
			<label>
				<input type="checkbox" <?=$array[$i]['paid'] == 0 ? '' : 'checked="checked"'?> id="accepted_paid" style="display: none;">
				<span class="view_user_check"><img src="/img/check.svg" alt="check" ></span>
			</label>
		</td>

	</tr>
<?php endfor; ?>

////////=============////////

<?php $search = '';

if ($pages <= 10) {
	for ($i=1; $i < $pages; $i++) {
		if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
			<a class="page active_page" href="/finance.php?page=<?=$i?>&view=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
		<?php else: ?>
			<a class="page" href="/finance.php?page=<?=$i?>&view=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
		<?php endif ?>
	<?php }
} else {
	if ($_POST['page'] < 7) {
		for ($i=1; $i < 8; $i++) {
			if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/finance.php?page=<?=$i?>&view=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/finance.php?page=<?=$i?>&view=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/finance.php?page=<?=$pages - 1?>&view=<?=$_POST['id']?><?=$search?>"><?=$pages - 1?></a> <?php
	} elseif ($_POST['page'] >= $pages - 6) { ?>
		<a class="page" href="/finance.php?page=1&view=<?=$_POST['id']?><?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$pages - 7; $i < $pages; $i++) {
			if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/finance.php?page=<?=$i?>&view=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/finance.php?page=<?=$i?>&view=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php }
	} else { ?>
		<a class="page" href="/finance.php?page=1&view=<?=$_POST['id']?><?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$_POST['page'] - 3; $i < $_POST['page'] + 4; $i++) {
			if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/finance.php?page=<?=$i?>&view=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/finance.php?page=<?=$i?>&view=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/finance.php?page=<?=$pages - 1?>&view=<?=$_POST['id']?><?=$search?>"><?=$pages - 1?></a> <?php
	}
} ?>

<script>
	$('#accepted_report, #accepted_paid').change(function() {
		$.ajax({
			url: '/ajax/update_report.php',
			type: 'POST',
			dataType: 'html',
			data: {
				id: $(this).parent().parent().parent().data('id'),
				accepted_report: $('#accepted_report').prop('checked'),
				accepted_paid: $('#accepted_paid').prop('checked'),
			},
		});
		
	});

	// $(".finance_open_report").on("click",function(){$("#overlay_document").show(),$("#overlay_document .name").text($(this).data("document")),console.log($(this).parent().parent()),$("#overlay_document .download a").attr("href",$(this).data("file"))});

	if ($('.user_view_tbody_after_table_filters .active_page').length == 0)
		$('.user_view_tbody_after_table_filters .page').first().addClass('active_page');
</script>

////////=============////////

<?php echo $_POST['token']; ?>