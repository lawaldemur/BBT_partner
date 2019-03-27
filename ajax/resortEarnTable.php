<?php
include '../db.php';
$months_list = array(
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
$months_list2 = array(
	'января',
	'февраля',
	'марта',
	'апреля',
	'мая',
	'инюня',
	'июля',
	'августа',
	'сентября',
	'октября',
	'ноября',
	'декабря'
);

if ($_POST['format'] != 'all') {
	$where = 'WHERE `format` = \'' . $_POST['format'] . "'";
} else 
	$where = '';
if ($_POST['format'] != 'all') {
	$where2 = 'AND `format` = \'' . $_POST['format'] . "'";
} else 
	$where2 = '';

if ($_POST['table'] == '0') {
	$list = $dbc->query("SELECT date
			FROM `sold` $where GROUP BY date ORDER BY date DESC");
} elseif ($_POST['table'] == '1') {
	$list = $dbc->query("SELECT WEEK(`date`,1) AS WEEK_NUM, DATE_SUB(`date`, INTERVAL WEEKDAY(`date`) DAY) 
			AS WEEK_MON, DATE_SUB(`date`, INTERVAL (WEEKDAY(`date`)-6) DAY) AS WEEK_SUN, SUM(to_bbt)
			FROM `sold` $where GROUP BY WEEK_MON, WEEK_SUN, WEEK_NUM ORDER BY date DESC");
} elseif ($_POST['table'] == '2') {
	$list = $dbc->query("SELECT year(date), month(date), date
			FROM sold $where GROUP BY month(date) ORDER BY date DESC");
}

$array = [];
foreach ($list as $item)
	$array[] = $item;

// pagination
if (!isset($_POST['page'])) $_POST['page'] = 1;
if (!isset($_POST['rows'])) $rows = 20;
else $rows = $_POST['rows'];
$offset = $_POST['page'] * $rows - $rows;
$limit = $_POST['page'] * $rows;
$pages = ceil(count($array) / $rows) + 1;


// set all values to items
if ($_POST['table'] == '0'):
	for ($i=0; $i < count($array); $i++) { 
		$result_b = $dbc->query("SELECT SUM(to_bbt)
		FROM sold WHERE to_partner_id > 0 AND DATE(`date`) = '{$array[$i]['date']}' $where2
		GROUP BY month(date)");
		if ($result_b && $result_b->num_rows > 0) {
			foreach ($result_b as $item3)
				$array[$i]['dogovor'] = round($item3['SUM(to_bbt)'], 2);
		} else
			$array[$i]['dogovor'] = 0;

		$result_b = $dbc->query("SELECT year(date),month(date),SUM(to_bbt)
		FROM sold WHERE to_partner_id = '0' AND DATE(`date`) = '{$array[$i]['date']}' $where2
		GROUP BY month(date)");
		if ($result_b && $result_b->num_rows > 0)
			foreach ($result_b as $item2)
				$array[$i]['bonus'] = round($item2['SUM(to_bbt)'], 2);
		else
			$array[$i]['bonus'] = 0;


		$array[$i]['total'] = round($array[$i]['dogovor'] + $array[$i]['bonus'], 2);

		$array[$i]['text_date'] = date('j', strtotime($array[$i]['date'])).' '.$months_list2[intval(date('m', strtotime($array[$i]['date']))) - 1].' '.date('Y', strtotime($array[$i]['date']));
	}
elseif($_POST['table'] == '1'):
	for ($i=0; $i < count($array); $i++) {
		$result_b = $dbc->query("SELECT SUM(to_bbt)
		FROM sold WHERE to_partner_id > 0 AND DATE(`date`) BETWEEN '{$array[$i]['WEEK_MON']}' AND '{$array[$i]['WEEK_SUN']}'
		GROUP BY month(date)");
		if ($result_b && $result_b->num_rows > 0) {
			foreach ($result_b as $item3)
				$array[$i]['dogovor'] = round($item3['SUM(to_bbt)'], 2);
		} else
			$array[$i]['dogovor'] = 0;

		$result_b = $dbc->query("SELECT year(date),month(date),SUM(to_bbt)
		FROM sold WHERE to_partner_id = '0' AND DATE(`date`) BETWEEN '{$array[$i]['WEEK_MON']}' AND '{$array[$i]['WEEK_SUN']}'
		GROUP BY month(date)");
		if ($result_b && $result_b->num_rows > 0)
			foreach ($result_b as $item2)
				$array[$i]['bonus'] = round($item2['SUM(to_bbt)'], 2);
		else
			$array[$i]['bonus'] = 0;


		$array[$i]['total'] = round($array[$i]['dogovor'] + $array[$i]['bonus'], 2);

		$array[$i]['text_date'] = date('j', strtotime($array[$i]['WEEK_MON'])).' '.$months_list2[intval(date('m', strtotime($array[$i]['WEEK_MON']))) - 1].' '.date('Y', strtotime($array[$i]['WEEK_MON'])).' &mdash; '.date('j', strtotime($array[$i]['WEEK_SUN'])).' '.$months_list2[intval(date('m', strtotime($array[$i]['WEEK_SUN']))) - 1].' '.date('Y', strtotime($array[$i]['WEEK_SUN']));
	}
elseif($_POST['table'] == '2'):
	for ($i=0; $i < count($array); $i++) {
		$year = $array[$i]['year(date)'];
		$month = $months_list[intval($array[$i]['month(date)']) - 1];

		$result_b = $dbc->query("SELECT year(date),month(date),SUM(to_bbt)
		FROM sold WHERE to_partner_id > 0 AND year(date) = $year AND month(date) = {$array[$i]['month(date)']} $where2
		GROUP BY month(date)");
		if ($result_b && $result_b->num_rows > 0) {
			foreach ($result_b as $item3)
				$array[$i]['dogovor'] = round($item3['SUM(to_bbt)'], 2);
		} else
			$array[$i]['dogovor'] = 0;

		$result_b = $dbc->query("SELECT year(date),month(date),SUM(to_bbt)
		FROM sold WHERE to_partner_id = '0' AND year(date) = $year AND month(date) = {$array[$i]['month(date)']} $where2
		GROUP BY month(date)");
		if ($result_b && $result_b->num_rows > 0)
			foreach ($result_b as $item2)
				$array[$i]['bonus'] = round($item2['SUM(to_bbt)'], 2);
		else
			$array[$i]['bonus'] = 0;


		$array[$i]['total'] = round($array[$i]['dogovor'] + $array[$i]['bonus'], 2);
		
		$array[$i]['text_date'] = $month.' '.$year;


	}
endif;


// sort array by column
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



for ($i=$offset; $i < $limit && $i < count($array); $i++) { 
	?>
	<tr>
		<td><?=$array[$i]['text_date']?></td>
		<td><?=$array[$i]['dogovor']?> &#8381;</td>
		<td><?=$array[$i]['bonus']?> &#8381;</td>
		<td><?=$array[$i]['total']?> &#8381;</td>
	</tr>
	<?php
}

?>

=====================================

<?php
if ($pages <= 10) {
	for ($i=1; $i < $pages; $i++) {
		if ($i == $_POST['page']): ?>
			<a class="page earn_page active_page" href="/finance.php"><?=$i?></a>
		<?php else: ?>
			<a class="page earn_page" href="/finance.php"><?=$i?></a>
		<?php endif ?>
	<?php }
} else {
	if ($_POST['page'] < 7) {
		for ($i=1; $i < 8; $i++) {
			if ($i == $_POST['page']): ?>
				<a class="page earn_page active_page" href="/finance.php"><?=$i?></a>
			<?php else: ?>
				<a class="page earn_page" href="/finance.php"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page earn_page" href="/finance.php"><?=$pages - 1?></a><?php
	} elseif ($_POST['page'] >= $pages - 6) { ?>
		<a class="page earn_page" href="/finance.php">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$pages - 7; $i < $pages; $i++) {
			if ($i == $_POST['page']): ?>
				<a class="page earn_page active_page" href="/finance.php"><?=$i?></a>
			<?php else: ?>
				<a class="page earn_page" href="/finance.php"><?=$i?></a>
			<?php endif ?>
		<?php }
	} else { ?>
		<a class="page earn_page" href="/finance.php">1</a>
		<span class="triple_dots">...</span>
		<?php
		for ($i=$_POST['page'] - 3; $i < $_POST['page'] + 4; $i++) {
			if ($i == $_POST['page']): ?>
				<a class="page earn_page active_page" href="/finance.php"><?=$i?></a>
			<?php else: ?>
				<a class="page earn_page" href="/finance.php"><?=$i?></a>
			<?php endif ?>
		<?php } ?>
		<span class="triple_dots">...</span>
		<a class="page earn_page" href="/finance.php"><?=$pages - 1?></a><?php
	}
} ?>
