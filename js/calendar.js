jQuery(document).ready(function($) {
	

	function todayView() {
		$.ajax({
			url: 'ajax/dates.php',
			type: 'POST',
			dataType: 'html',
			data: {months: 'now'},
		})
		.done(function(res) {
			res = res.split('========================================');
			$('.cal_col_1').html(res[0]);
			$('.cal_col_2').html(res[1]);
			$('.cal_col_3').html(res[2]);
			$('.cal_col_3').after(res[3]);
		});
	}
	todayView(); // init calendar
	$('.today_cal').click(todayView);



	$('.prev_cal').click(function() {
		var month = parseInt($('.month_name').first().attr('data-month')) + 2;
		var year = parseInt($('.month_name').first().attr('data-year'));
		if (month == 0) {
			month = 12;
			year--;
		}

		$.ajax({
			url: 'ajax/dates.php',
			type: 'POST',
			dataType: 'html',
			data: {
				months: month,
				year: year
			},
		})
		.done(function(res) {
			res = res.split('========================================');
			$('.cal_col_1').html(res[0]);
			$('.cal_col_2').html(res[1]);
			$('.cal_col_3').html(res[2]);
			$('.cal_col_3').after(res[3]);
		});
	});

	$('.next_cal').click(function() {
		var month = parseInt($('.month_name').last().attr('data-month')) + 2;
		var year = parseInt($('.month_name').last().attr('data-year'));
		if (month == 13) {
			month = 1;
			year++;
		}

		$.ajax({
			url: 'ajax/dates.php',
			type: 'POST',
			dataType: 'html',
			data: {
				months: month,
				year: year
			},
		})
		.done(function(res) {
			res = res.split('========================================');
			$('.cal_col_1').html(res[0]);
			$('.cal_col_2').html(res[1]);
			$('.cal_col_3').html(res[2]);
			$('.cal_col_3').after(res[3]);
		});
	});



	$('.reset_cal').click(function() {
		$('.range_control').removeClass('range_control');
		$('.range_member').removeClass('range_member');

		$('.today').css({
			borderRadius: '5px',
			border: '2px solid #3c93ff'
		});

		if ($('.done_cal').css('display') != 'none')
			$('.done_cal').slideToggle();
	});

	$('.custom_date_change').click(function() {
		$('.change_active').removeClass('change_active');
		$(this).addClass('change_active');

		$('.calendar_overlay').show();
		$('.calendar').css('display', 'flex');
	});


	$('.calendar_overlay').click(function() {
		$('.calendar_overlay').hide();
		$('.calendar').css('display', 'none');
	});



});