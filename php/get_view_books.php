<?php
if ($role == 'command' || $role == 'partner') {
	$db->set_where(['date' => $period, 'to_'.$role.'_id' => $id] + ($format == 'all' ? [] : ['format' => $format]));
	$db->set_table($sort == 'bydate' ? 'analitic' : 'analitic_bybook');
	$books = $db->select('i' . ($format == 'all' ? '' : 's'));
} else {
	$db->set_where(['date' => $period, 'client' => $id] + ($format == 'all' ? [] : ['format' => $format]));
	$db->set_table('sold');
	$books = $db->select('i' . ($format == 'all' ? '' : 's'));
}

// pagination
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil($books->num_rows / $rows) + 1;


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

	if ($sort == 'bybook' && $role != 'client') {
		$total = 0;
		$summ = unserialize($book['sold']);

		if ($period == '`date` >= CURDATE()')
			$date = date('Y-m-d');

		if ($summ)
		foreach ($summ as $sum) {
			if (strtotime($sum[0]) >= $date) $total += $sum[1];
		}
		$book['summ'] = $total;
	}

	$book['count'] = $book['summ'] / $book['price'];
	if ($sort == 'bybook')
		$book['other'] = $book['author'];

	if ($role != 'command' && $role != 'partner') {
		$db_shop->set_table('wp_posts');
		$db_shop->set_where(['ID' => $book['product']]);
		$book['name'] = $db_shop->select('i')->fetch_array(MYSQLI_ASSOC)['post_title'];

		$db_shop->set_table('wp_postmeta');
		$db_shop->set_where(['post_id' => $book['variation'], 'meta_key' => 'attribute_pa_writer']);
		$book['other'] = $db_shop->fetch_array(MYSQLI_ASSOC)['meta_value'];

		$db_shop->set_table('wp_terms');
		$db_shop->set_where(['slug' => $book['other']]);
		$book['other'] = $db_shop->select('s')->fetch_array(MYSQLI_ASSOC)['name'];

		if ($book['other'] == '') {
			$db->set_table('analitic');
			$db->set_where(['product' => $book['product']]);
			$other = $db->select('i');
			if ($other)
				foreach ($other as $author) {
					$book['other'] = $author['other'];
					break;
				}
		}
		
	}

	$array[] = $book;
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
