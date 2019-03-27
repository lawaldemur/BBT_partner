<?php
require '../db.php';
$id = $_POST['id'];

$array = [];
$reports = $dbc->query("SELECT * FROM `reports` WHERE `from_id` = $id");
foreach ($reports as $report) {
	$array[] = $report;
}

// pagination
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

$months = array(
	'Январь',
	'Февраль',
	'Март',
	'Апрель',
	'Май',
	'Июнь',
	'Июль',
	'Август',
	'Сентябрь',
	'Октябрь',
	'Ноябрь',
	'Декабрь'
);

for ($i=$offset; $i < $limit && $i < count($array); $i++):
	$class = $array[$i]['accepted'] == 1 && $array[$i]['paid'] == 1 ? 'class="tr_done"' : '';
	?>
	<tr data-id="<?=$array[$i]['id']?>" data-date="<?=$array[$i]['date']?>" <?=$class?>>
		<!-- <td><img src="/img/check.svg" width="10" height="8"><?=strftime('%B %Y', strtotime($array[$i]['date']))?></td> -->
		<td><img src="/img/check.svg" width="10" height="8"><?=$months[intval(date('n', strtotime($array[$i]['date'])))- 1].' '.strftime('%Y', strtotime($array[$i]['date']))?></td>
		<td class="table_align_center"><?=$array[$i]['sum']?> &#8381;</td>

		<td class="table_align_center">
			<!-- <button class="finance_open_report" data-document="<?=$array[$i]['report_raw']?>" data-file="/service/reports/<?=date("m.y", strtotime($array[$i]['date']))?>_raw/<?=$array[$i]['report_raw']?>">Открыть</button> -->
			<a href="/service/reports/<?=date("m.y", strtotime($array[$i]['date']))?>_raw/<?=$array[$i]['report_raw']?>.pdf" target="_blank" class="finance_open_report">Открыть</a>
			<?=$array[$i]['report_done'] == '' ? '<button class="finance_upload_report">Загрузить</button>' : "<button class='finance_upload_report' data-document='{$array[$i]['report_done']}'><img src='/img/check.svg' width='10' height='8'> Готово</button>"?>
		</td>

		<td class="table_align_center">
			<!-- <button class="finance_open_report" data-document="<?=$array[$i]['act_raw']?>" data-file="/service/acts/<?=date("m.y", strtotime($array[$i]['date']))?>_raw/<?=$array[$i]['act_raw']?>">Открыть</button> -->
			<a href="/service/acts/<?=date("m.y", strtotime($array[$i]['date']))?>_raw/<?=$array[$i]['act_raw']?>.pdf" target="_blank" class="finance_open_report">Открыть</a>
			<?=$array[$i]['act_done'] == '' ? '<button class="finance_upload_report">Загрузить</button>' : '<button class="finance_upload_report"><img src="/img/check.svg" width="10" height="8"> Готово</button>'?>
		</td>

		<td class="table_align_center">
			<?php if ($array[$i]['accepted'] == '1'): ?>
				<img src="/img/checked.svg" width="10" height="8">
			<?php endif ?>
		</td>

		<td class="table_align_center">
			<?php if ($array[$i]['paid'] == '1'): ?>
				<img src="/img/checked.svg" width="10" height="8">
			<?php endif ?>
		</td>
	</tr>
<?php endfor; ?>

////////=============////////

<?php $search = '';

if ($pages <= 10) {
	for ($i=1; $i < $pages; $i++) {
		if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
			<a class="page active_page" href="/finance.php?page=<?=$i?>&viewbbt=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
		<?php else: ?>
			<a class="page" href="/finance.php?page=<?=$i?>&viewbbt=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
		<?php endif ?>
	<?php }
} else {
	if ($_POST['page'] < 7) {
		for ($i=1; $i < 8; $i++) {
			if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/finance.php?page=<?=$i?>&viewbbt=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/finance.php?page=<?=$i?>&viewbbt=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/finance.php?page=<?=$pages - 1?>&viewbbt=<?=$_POST['id']?><?=$search?>"><?=$pages - 1?></a> <?php
	} elseif ($_POST['page'] >= $pages - 6) { ?>
		<a class="page" href="/finance.php?page=1&viewbbt=<?=$_POST['id']?><?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$pages - 7; $i < $pages; $i++) {
			if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/finance.php?page=<?=$i?>&viewbbt=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/finance.php?page=<?=$i?>&viewbbt=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php }
	} else { ?>
		<a class="page" href="/finance.php?page=1&viewbbt=<?=$_POST['id']?><?=$search?>">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$_POST['page'] - 3; $i < $_POST['page'] + 4; $i++) {
			if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['page'] == $i)): ?>
				<a class="page active_page" href="/finance.php?page=<?=$i?>&viewbbt=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php else: ?>
				<a class="page" href="/finance.php?page=<?=$i?>&viewbbt=<?=$_POST['id']?><?=$search?>"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page" href="/finance.php?page=<?=$pages - 1?>&viewbbt=<?=$_POST['id']?><?=$search?>"><?=$pages - 1?></a> <?php
	}
} ?>