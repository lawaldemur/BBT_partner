<?php
$array = array();
if ($books)
foreach ($books as $book) {
	$db_shop->set_table('wp_posts');
	$db_shop->set_where(['post_parent' => $book['product'], 'post_type' => 'attachment']);
	$book['img'] = $db_shop->select('is')->fetch_array(MYSQLI_ASSOC)['guid'];

	if ($book['format'] == 'digital')
		$book['format'] = '<img src="/img/format_digital.svg" alt="digital_format" width="14" height="18">';
	elseif ($book['format'] == 'audio')
		$book['format'] = '<img src="/img/format_audio.svg" alt="audio_format" width="16" height="18">';

	$db_shop->set_table('wp_postmeta');
	$db_shop->set_where(['post_id' => $book['variation'], 'meta_key' => '_price']);
	$book['price'] = $db_shop->select('is')->fetch_array(MYSQLI_ASSOC)['meta_value'];

	if ($sort == 'bybook')
		$book['other'] = $book['author'];

	if ($sort == 'bybook') {
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

	$array[] = $book;
}
// pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil(count($array) / $rows) + 1;


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
	$page--;
	$offset = $page * $rows - $rows;
	$limit = $page * $rows;
}
