jQuery(document).ready(function($) {

	$('tbody tr td:first-child').click(function() {
		document.location.href = '/view.php?id=' + $(this).parent().data('id');
	});
	
	var token = '';
	$('.table_sizes > *').click(function() {
		if (!$(this).hasClass('table_size_active')) {
			$('.table_size_active').removeClass('table_size_active');
			$(this).addClass('table_size_active');

			var period = $('.change_active').attr('data-val');

			var rows_size = $('.table_size_active').text();
			var page = $('#active_page').val();
			var sortColumn = $('.sortColumn_type').parent().data('column');
			if ($('.sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
			else  var sortColumnType = 'reverse';
			token = new Date().getUTCMilliseconds();

			$.ajax({
				url: '/ajax/clients.php',
				type: 'POST',
				dataType: 'html',
				data: {
					rows_size: rows_size,
					page: page,
					period: period,
					sortColumn: sortColumn,
					sortColumnType: sortColumnType,
					search: $('#search_table_command').val(),
					request_uri: location.pathname + location.search,
					token: token,
					role: $('#role').val(),
					parent: $('#parent').val(),
				},
			})
			.done(function(res) {
				res = res.split('===================================================================================================');
				if (res[2] == token) {
					$('table tbody').html(res[0]);
					$('.pages_list').html(res[1]);
				
					$('table').after('<script>document.cookie = "rows='+rows_size+'";</script>');
					$('.pagination_list .page').last().addClass('last_pagination');

					$('#users_table').after('<script>$("tbody tr td:first-child").click(function(){document.location.href="/view.php?id="+$(this).parent().data("id")});</script>');
				}
			});
			
		}
	});

	$('#search_table_command').on('input', function(event) {
		var period = '';
		if ($('#custom').hasClass('change_active')) {

		} else
			period = $('.change_active').attr('data-val');

		var rows_size = $('.table_size_active').text();
		var page = $('#active_page').val();
		var sortColumn = $('.sortColumn_type').parent().data('column');
		if ($('.sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';
		token = new Date().getUTCMilliseconds();

		$.ajax({
			url: '/ajax/clients.php',
			type: 'POST',
			dataType: 'html',
			data: {
				rows_size: rows_size,
				page: page,
				period: period,
				sortColumn: sortColumn,
				sortColumnType: sortColumnType,
				search: $('#search_table_command').val(),
				request_uri: location.pathname + location.search,
				token: token,
				role: $('#role').val(),
				parent: $('#parent').val(),
			},
		})
		.done(function(res) {
			res = res.split('===================================================================================================');
			if (res[2] == token) {
				$('table tbody').html(res[0]);
				$('.pages_list').html(res[1]);
				$('.pagination_list .page').last().addClass('last_pagination');

				$('#users_table').after('<script>$("tbody tr td:first-child").click(function(){document.location.href="/view.php?id="+$(this).parent().data("id")});</script>');
			}
		});
			
	});




	$('.change_date > *:not(.custom_date_change)').click(function() {
		if (!$(this).hasClass('change_active')) {
			$('.change_active').removeClass('change_active');
			$(this).addClass('change_active');

			var period = $('.change_active').attr('data-val');

			var rows_size = $('.table_size_active').text();
			var page = $('#active_page').val();
			var sortColumn = $('.sortColumn_type').parent().data('column');
			if ($('.sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
			else  var sortColumnType = 'reverse';
			token = new Date().getUTCMilliseconds();

			$.ajax({
				url: '/ajax/clients.php',
				type: 'POST',
				dataType: 'html',
				data: {
					rows_size: rows_size,
					page: page,
					period: period,
					sortColumn: sortColumn,
					sortColumnType: sortColumnType,
					search: $('#search_table_command').val(),
					request_uri: location.pathname + location.search,
					token: token,
					role: $('#role').val(),
					parent: $('#parent').val(),
				},
			})
			.done(function(res) {
				res = res.split('===================================================================================================');
				if (res[2] == token) {
					$('table tbody').html(res[0]);
					$('.pages_list').html(res[1]);
				
					$('table').after('<script>document.cookie = "period='+period+'";</script>');
					$('.pagination_list .page').last().addClass('last_pagination');

					$('#users_table').after('<script>$("tbody tr td:first-child").click(function(){document.location.href="/view.php?id="+$(this).parent().data("id")});</script>');
				}
			});

		}
	});




	$('.done_cal').click(function() {

		if ($('.range_control').length != 2)
			return;

		$('.custom_date_change').attr('data-val', 'DATE(`date`) BETWEEN '+$('.range_control').first().attr('data-date')+' AND '+$('.range_control').last().attr('data-date'));
		$('.custom_date_change span').text($('.range_control').first().text() + ' ' + $('.range_control').first().parent().prev().prev().attr('data-month_name') + ' ' + $('.range_control').first().parent().prev().prev().attr('data-year') + ' – ' + $('.range_control').last().text() + ' ' + $('.range_control').last().parent().prev().prev().attr('data-month_name') + ' ' + $('.range_control').last().parent().prev().prev().attr('data-year'));

		$('.calendar_overlay').hide();
		$('.calendar').css('display', 'none');

		$('.page_wrapper').after('<script>document.cookie = "calendarText='+encodeURI($('.custom_date_change span').text())+'";</script>');
		$('.page_wrapper').after('<script>document.cookie = "period='+$('.custom_date_change').attr('data-val')+'";</script>');


		var period = $('.change_active').attr('data-val');

		var rows_size = $('.table_size_active').text();
		var page = $('#active_page').val();
		var sortColumn = $('.sortColumn_type').parent().data('column');
		if ($('.sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';
		token = new Date().getUTCMilliseconds();

		$.ajax({
			url: '/ajax/clients.php',
			type: 'POST',
			dataType: 'html',
			data: {
				rows_size: rows_size,
				page: page,
				period: period,
				sortColumn: sortColumn,
				sortColumnType: sortColumnType,
				search: $('#search_table_command').val(),
				request_uri: location.pathname + location.search,
				token: token,
				role: $('#role').val(),
				parent: $('#parent').val(),
			},
		})
		.done(function(res) {
			res = res.split('===================================================================================================');
			if (res[2] == token) {
				$('table tbody').html(res[0]);
				$('.pages_list').html(res[1]);
			
				$('table').after('<script>document.cookie = "period='+period+'";</script>');
				$('.pagination_list .page').last().addClass('last_pagination');

				$('#users_table').after('<script>$("tbody tr td:first-child").click(function(){document.location.href="/view.php?id="+$(this).parent().data("id")});</script>');
			}
		});

	});


	$('thead th').click(function() {
		if ($(this).data('column') != '') {
			if ($(this).find('.sort_upper').length == 1) {
				$(this).find('.sortColumn_type').remove();
				$(this).append(' <span class="sort_down sortColumn_type">&#9650;</span>');
			} else if ($(this).find('.sort_down').length == 1) {
				$(this).find('.sortColumn_type').remove();
				$(this).append(' <span class="sort_upper sortColumn_type">&#9660;</span>');
			} else {
				$(this).parent().find('.sortColumn_type').remove();
				$(this).append(' <span class="sort_upper sortColumn_type">&#9660;</span>');
			}

			var period = $('.change_active').attr('data-val');

			var rows_size = $('.table_size_active').text();
			var page = $('#active_page').val();
			var sortColumn = $('.sortColumn_type').parent().data('column');
			if ($('.sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
			else  var sortColumnType = 'reverse';
			token = new Date().getUTCMilliseconds();

			$.ajax({
				url: '/ajax/clients.php',
				type: 'POST',
				dataType: 'html',
				data: {
					rows_size: rows_size,
					page: page,
					period: period,
					sortColumn: sortColumn,
					sortColumnType: sortColumnType,
					search: $('#search_table_command').val(),
					request_uri: location.pathname + location.search,
					token: token,
					role: $('#role').val(),
					parent: $('#parent').val(),
				},
			})
			.done(function(res) {
				res = res.split('===================================================================================================');
				if (res[2] == token) {
					$('table tbody').html(res[0]);
					$('.pages_list').html(res[1]);
					$('.pagination_list .page').last().addClass('last_pagination');

					$('#users_table').after('<script>$("tbody tr td:first-child").click(function(){document.location.href="/view.php?id="+$(this).parent().data("id")});</script>');
				}
			});
		}
	});




});