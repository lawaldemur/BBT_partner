<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Calendar</title>
	<link rel="stylesheet" href="calendar.css">
</head>
<body>

<!-- <div class="change custom_date_change change_active" id="custom" style="">
	<span class="custom_date">1 июн 2016 – 23 авг 2018</span>
</div> -->

<?php
function getWeekDay($weekday) {
	$weekday %= 7;
	if (floor(($weekday + 1) / 7))
		$weekday -= 7;

	return $weekday;
}

$dates = array();
$weekday = 3; // 1 января 2010 это пятница

for ($i=2000; $i < 2030; $i++) {

	for ($x=1; $x < 32; $x++) {
		$weekday++;
		$dates[$i]['Январь'][floor($weekday / 7)][$weekday % 7] = $x;
	}
	$weekday = getWeekDay($weekday);
	for ($x=1; $x < 29 + ($i % 4 == 0); $x++) { 
		$weekday++;
		$dates[$i]['Февраль'][floor($weekday / 7)][$weekday % 7] = $x;
	}
	$weekday = getWeekDay($weekday);
	for ($x=1; $x < 32; $x++) { 
		$weekday++;
		$dates[$i]['Март'][floor($weekday / 7)][$weekday % 7] = $x;
	}
	$weekday = getWeekDay($weekday);
	for ($x=1; $x < 31; $x++) { 
		$weekday++;
		$dates[$i]['Апрель'][floor($weekday / 7)][$weekday % 7] = $x;
	}
	$weekday = getWeekDay($weekday);
	for ($x=1; $x < 32; $x++) { 
		$weekday++;
		$dates[$i]['Май'][floor($weekday / 7)][$weekday % 7] = $x;
	}
	$weekday = getWeekDay($weekday);
	for ($x=1; $x < 31; $x++) { 
		$weekday++;
		$dates[$i]['Июнь'][floor($weekday / 7)][$weekday % 7] = $x;
	}
	$weekday = getWeekDay($weekday);
	for ($x=1; $x < 32; $x++) { 
		$weekday++;
		$dates[$i]['Июль'][floor($weekday / 7)][$weekday % 7] = $x;
	}
	$weekday = getWeekDay($weekday);
	for ($x=1; $x < 32; $x++) { 
		$weekday++;
		$dates[$i]['Август'][floor($weekday / 7)][$weekday % 7] = $x;
	}
	$weekday = getWeekDay($weekday);
	for ($x=1; $x < 31; $x++) { 
		$weekday++;
		$dates[$i]['Сентябрь'][floor($weekday / 7)][$weekday % 7] = $x;
	}
	$weekday = getWeekDay($weekday);
	for ($x=1; $x < 32; $x++) { 
		$weekday++;
		$dates[$i]['Октябрь'][floor($weekday / 7)][$weekday % 7] = $x;
	}
	$weekday = getWeekDay($weekday);
	for ($x=1; $x < 31; $x++) { 
		$weekday++;
		$dates[$i]['Ноябрь'][floor($weekday / 7)][$weekday % 7] = $x;
	}
	$weekday = getWeekDay($weekday);
	for ($x=1; $x < 32; $x++) { 
		$weekday++;
		$dates[$i]['Декабрь'][floor($weekday / 7)][$weekday % 7] = $x;
	}
	$weekday = getWeekDay($weekday);

}

echo serialize($dates);
?>

<div class="calendar">

</div>


<button id="refresh" onclick="location.reload()">Reload</button>
	
<script src="jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="php/dates.json"></script>
<script src="calendar.js"></script>
</body>
</html>


