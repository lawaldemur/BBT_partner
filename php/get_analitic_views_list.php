<?php
if ($period == '`date` >= CURDATE()')
	$date = new DateTime('today');
elseif ($period == 'DATE(`date`) = DATE(NOW() - INTERVAL 1 DAY)')
	$date = new DateTime('yesterday');
elseif ($period == 'WEEK(`date`) = WEEK(CURDATE())')
	$date = new DateTime('first day of this week');
elseif ($period == 'MONTH(`date`) = MONTH(CURDATE())')
	$date = new DateTime('first day of this month');
elseif ($period == 'QUARTER(`date`) = QUARTER(CURDATE())') {
	$month = intval(date('m'));
	if ($month < 4)
        $date = new DateTime('first day of january ' . date('Y'));
    elseif ($month > 3 && $month < 7)
        $date = new DateTime('first day of april ' . date('Y'));
    elseif ($month > 6 && $month < 10)
        $date = new DateTime('first day of july ' . date('Y'));
    elseif ($month > 9)
        $date = new DateTime('first day of october ' . date('Y'));
}
elseif ($period == 'YEAR(`date`) = YEAR(CURDATE())')
	$date = new DateTime('first day of this year');
else // custom date
	$date = [date(substr($period, 21, 10)), date(substr($period, 36, 10))];

if (!is_array($date))
	$date = $date->format('Y-m-d');

$array = array();
if ($books)
foreach ($books as $book) {
	$book['img'] = $dbc_shop->query("SELECT * FROM `wp_posts` WHERE `post_parent` = {$book['product']} AND `post_type` = 'attachment'");
	$book['img'] = $book['img']->fetch_array(MYSQLI_ASSOC)['guid'];

	$book['views'] = 0;
	$views = unserialize($book['watched']);

	if (is_array($date)) {
		foreach ($views as $view)
			if ($date[0] <= $view && $view <= $date[1])
				$book['views']++;
	} else {
		foreach ($views as $view)
			if (date($view) >= $date)
				$book['views']++;
	}
	
	if ($book['views'] > 0)
		$array[] = $book;
}
// pagination
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = $page * $rows - $rows;
$limit = $page * $rows;
$pages = ceil(count($array) / $rows) + 1;
// sort
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
