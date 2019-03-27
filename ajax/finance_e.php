<?php
require '../db.php';
require '../db_shop.php';

$role = $_POST['role'];

$id = $_POST["id"];

$period = $_POST['period'];
$where = 'WHERE '.$period;
if (isset($_POST['format']) && $_POST['format'] != '' && $_POST['format'] != 'all')
	$where .= ' AND `format` = \''.$_POST['format']."'";

//$books_query = $dbc_shop->query("SELECT * FROM `wp_watched` $where");

// pagination
if (!isset($_POST['page'])) $_POST['page'] = 1;
if ($_POST['rows_size'] == '') $rows = 20;
else $rows = $_POST['rows_size'];
$offset = $_POST['page'] * $rows - $rows;
$limit = $_POST['page'] * $rows;

$dogovor = ("SELECT year(date),month(date),SUM(to_bbt) FROM sold WHERE to_partner_id <> 0 GROUP BY month(date) DESC");
$pages = ceil($dogovor->num_rows / $rows) + 1;

// content for BBT - all books
if ($_POST['role'] == 'command')
	$books_query = $dbc->query("SELECT SUM(to_bbt) FROM sold $where AND `to_command_id` = $id");
elseif ($_POST['role'] == 'partner')
	$books_query = $dbc->query("SELECT SUM(to_bbt) FROM sold $where AND `to_partner_id` = $id");
else
	$books_query = $dbc->query("SELECT SUM(to_bbt) FROM sold $where AND `to_partner_id` <> 0");
	$books_query2 = $dbc->query("SELECT SUM(to_bbt) FROM sold $where AND `to_partner_id` = '0'");
	
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
if ($_POST['role'] == 'command'){
	foreach ($books_query as $book) {?>
			<span class="fin_m_span1"><?=round($book['SUM(to_bbt)'], 2)?> &#8381;</span></br>
			<img alt="" src="img/Ellipse3.png"><span class="fin_m_span2">Заработано по договорам</span>
<?php }
}
elseif ($_POST['role'] == 'partner'){
	foreach ($books_query as $book) {?>
				<span class="fin_m_span1"><?=round($book['SUM(to_bbt)'], 2)?> &#8381;</span></br>
				<img alt="" src="img/Ellipse3.png"><span class="fin_m_span2">Заработано по договорам</span>			
	<?php }
}
else {?>
		<div class="col-4">
			<span class="fin_m_span1 fin_m_span_dogovor">
			<?php 
			foreach ($books_query as $money){
					echo round($money['SUM(to_bbt)'], 2);
				}
			?> &#8381;</span></br>
			<img alt="" src="img/Ellipse3.png"><span class="fin_m_span2">Заработано по договорам</span>
		</div>
		<hr class="h-line">
		<div class="col-4">
			<span class="fin_m_span1 fin_m_span_bonus">
			<?php 
			foreach ($books_query2 as $money2){
					echo round($money2['SUM(to_bbt)'], 2);
				}
			?> &#8381;</span></br>
			<img alt="" src="img/Ellipse31.png"><span class="fin_m_span2">Заработано на бонусах</span>
		</div>
		<hr class="h-line">
		<div class="col-4">
			<span class="fin_m_span1 fin_m_span_all">
			<?php 
				echo round($money['SUM(to_bbt)'] + $money2['SUM(to_bbt)'], 2);							
			?> &#8381;</span></br>
			<img alt="" src="img/Ellipse32.png"><span class="fin_m_span2">Итого </span>
		</div>
<?php }



mysqli_close($dbc);
?>
===================================================================================================

<?php /*for ($i=1; $i < $pages; $i++) {
	if (($_POST['request_uri'] == "/finance.php" && $i == 1) || ($_POST['request_uri'] == "/finance.php?page=$i")): ?>
		<a class="page active_page" href="/finance.php?page=<?=$i?>"><?=$i?></a>
	<?php else: ?>
		<a class="page" href="/finance.php?page=<?=$i?>"><?=$i?></a>
	<?php endif ?>
<?php } */ ?>
