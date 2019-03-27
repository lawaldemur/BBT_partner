<?php
include 'date_list.php';

if ($_POST['months'] == 'now')
	$variants = array(
		[intval(date("Y")), intval(date( "n" )) - 3],
		[intval(date("Y")), intval(date( "n" )) - 2],
		[intval(date("Y")), intval(date( "n" )) - 1]
	);
elseif ($_POST['months'] != 'months' && $_POST['months'] != 'years')
	$variants = array(
		[intval($_POST['year']), intval($_POST['months']) - 3],
		[intval($_POST['year']), intval($_POST['months']) - 2],
		[intval($_POST['year']), intval($_POST['months']) - 1]
	);

if ($_POST['months'] != 'months' && $_POST['months'] != 'years') {
	$block_id = 0;
	foreach ($variants as $var) {
		$year = $var[0];
		$month = $var[1];
		$m_year = false;

		// include 'date_list.php';
		$count = 0;
		if ($month < 0) {
			$month = 12 + $month;
			$year--;
		}
		if ($month > 11) {
			$month = 12 - $month;
			$year++;
		}

		$custom_month_name = mb_strtolower(substr($months[$month], 0, 6), 'UTF-8');
		$box = "<div class=\"month_name\" data-month=\"$month\" data-month_name=\"$custom_month_name\" data-year=\"$year\">{$months[$month]} $year</div>";
		$box .= '<div class="week_days"><div class="week_day">ПН</div><div class="week_day">ВТ</div><div class="week_day">СР</div><div class="week_day">ЧТ</div><div class="week_day">ПТ</div><div class="week_day">СБ</div><div class="week_day">ВС</div></div>';
		$box .= '<div class="days_wrapper">';
			$custom_month = $month - 1;
			if ($custom_month < 0) {
				$custom_month = 12 + $custom_month;
				$year--;
				$m_year = true;
			}
			$last_week_prev_month = end($dates[$year][$months[$custom_month]]);
			if ($m_year) $year++;
			foreach ($last_week_prev_month as $day) {
				$box .= '<div class="day not_month_day">'.$day.'</div>';
				$count++;
			}
			foreach ($dates[$year][$months[$month]] as $week => $value) {
				foreach ($value as $weekday => $day) {
					$block_id++;
					if ($month == intval(date( "n" )) - 1 && $day == date('j'))
						$today = 'today';
					else
						$today = '';

					$box .= '<div class="day month_day '.$today.'" data-day="'.$day.'" data-blockid="'.$block_id.'" data-date="'.date("Y-m-d", strtotime("$year-".($month + 1)."-$day")).'">'.$day.'</div>';
					$count++;
				}
			}
			$y = 1;
			while ($count < 42) {
				$box .= '<div class="day not_month_day">'.$y.'</div>';
				$y++;
				$count++;
			}
		$box .= '</div>';


		echo $box.'========================================';
	}
} elseif ($_POST['months'] == 'months') {
	include 'date_list.php';
	$box = "<div class=\"year_name\" data-year='{$_POST['year']}'>{$_POST['year']}</div>";
	$box .= '<div class="months_wrapper">';
		foreach ($months as $value) {
			if ($months[intval(date( "n" )) - 1] == $value && $_POST['year'] == date("Y"))
				$box .= '<div class="month recent_month">'.substr($value, 0, 6).'</div>';
			else
				$box .= '<div class="month">'.substr($value, 0, 6).'</div>';
		}
	$box .= '</div>';

	echo $box.'========================================';
} elseif ($_POST['months'] == 'years') {
	include 'date_list.php';

	$box = "<div class=\"year_range\">".(intval(date("Y")) - 11).' - '.(intval(date("Y")))."</div>";
	$box .= '<div class="years_wrapper">';
		for ($i=intval(date("Y")) - 11; $i < intval(date("Y")); $i++)
			$box .= '<div class="year">'.$i.'</div>';
		$box .= '<div class="year recent_year">'.intval(date("Y")).'</div>';
	$box .= '</div>';

	echo $box.'========================================';
}
?>
<script>
	$('.month_day').click(function() {
		if ($('.range_control').length == 2) {
			$('.range_control').removeClass('range_control');
			$('.range_member').removeClass('range_member');
		}
		$(this).addClass('range_control');
		
		if ($('.done_cal').css('display') != 'none')
			$('.done_cal').slideToggle();

		if ($('.range_control').length == 2) {
			var minId = Math.min(parseInt($('.range_control').first().attr('data-blockid')), parseInt($('.range_control').last().attr('data-blockid')));
			var maxId = Math.max(parseInt($('.range_control').first().attr('data-blockid')), parseInt($('.range_control').last().attr('data-blockid')));
			for (var i = minId + 1; i < maxId; i++)
				$('.day[data-blockid="'+i+'"]').addClass('range_member');
			$('.range_control').first().css({
				'border-top-right-radius': 0,
				'border-bottom-right-radius': 0
			});
			$('.range_control').last().css({
				'border-top-left-radius': 0,
				'border-bottom-left-radius': 0
			});

			if ($('.done_cal').css('display') == 'none')
				$('.done_cal').slideToggle();
		}

	});

	// months view
	$('.month_name').click(function() {
		var year = $(this).attr('data-year');
		var list_col = $(this).parent();

		$.ajax({
			url: 'ajax/dates.php',
			type: 'POST',
			dataType: 'html',
			data: {
				months: 'months',
				year: year
			},
		})
		.done(function(res) {
			res = res.split('==================='+'=====================');
			list_col.html(res[0])
			list_col.after(res[1]);
		});
	});
	$('.month').click(function() {
		$.ajax({
			url: 'ajax/dates.php',
			type: 'POST',
			dataType: 'html',
			data: {
				months: $(this).index() + 1,
				year: $(this).parent().prev().attr('data-year')
			},
		})
		.done(function(res) {
			res = res.split('==================='+'=====================');
			$('.cal_col_1').html(res[0]);
			$('.cal_col_2').html(res[1]);
			$('.cal_col_3').html(res[2]);
			$('.cal_col_3').after(res[3]);
		});
	});

	// years view
	$('.year_name').click(function() {
		var year = $(this).attr('data-year');
		var list_col = $(this).parent();

		$.ajax({
			url: 'ajax/dates.php',
			type: 'POST',
			dataType: 'html',
			data: {
				months: 'years',
				year: year
			},
		})
		.done(function(res) {
			res = res.split('==================='+'=====================');
			list_col.html(res[0])
			list_col.after(res[1]);
		});
	});
	$('.year').click(function() {
		var year = $(this).text();
		var list_col = $(this).parent().parent();

		$.ajax({
			url: 'ajax/dates.php',
			type: 'POST',
			dataType: 'html',
			data: {
				months: 'months',
				year: year
			},
		})
		.done(function(res) {
			res = res.split('==================='+'=====================');
			list_col.html(res[0])
			list_col.after(res[1]);
		});
	});
</script>















