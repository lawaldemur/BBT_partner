jQuery(document).ready(function($) {

	var table = 'all';
	// main tabs
	$('#all_books').click(function() {
		table = 'all';
		$('.analitics_tab_active').removeClass('analitics_tab_active');
		$(this).addClass('analitics_tab_active');
		$('.analitics_row').attr('data-table', 'all');

		$('.tabs_format_col').hide();

		$('#audio').addClass('choose_active');
		$('#digital').addClass('choose_active');

		uploadAllBooks();
	});
	$('#digital_books').click(function() {
		$('.analitics_tab_active').removeClass('analitics_tab_active');
		$(this).addClass('analitics_tab_active');
		$('.tabs_format_col').css('display', 'flex');

		uploadDigitalBooks();
	});
	$('#audio_books').click(function() {
		$('.analitics_tab_active').removeClass('analitics_tab_active');
		$(this).addClass('analitics_tab_active');
		$('.tabs_format_col').css('display', 'flex');

		uploadAudioBooks();
	});
	// other tabs
	$('#tab_format_books').click(function() {
		table = 'books';
		$('.analitics_row').attr('data-table', table);

		$('.active_tab_format').removeClass('active_tab_format');
		$(this).addClass('active_tab_format');

		if ($('.sortColumn_type').parent().is('th[data-column="views"]') && $('.sort_date').hasClass('sort_active')) {
			$('.sortColumn_type').remove();
			$('th[data-column="date"]').append(' <span class="sort_upper sortColumn_type">&#9660;</span>');
		} else if ($('.sortColumn_type').parent().is('th[data-column="views"]') && !$('.sort_date').hasClass('sort_active')) {
			$('.sortColumn_type').remove();
			$('th[data-column="name"]').append(' <span class="sort_upper sortColumn_type">&#9660;</span>');
		}


		uploadAllBooks();
	});
	$('#tab_format_views').click(function() {
		table = 'views';
		$('.analitics_row').attr('data-table', table);

		$('.active_tab_format').removeClass('active_tab_format');
		$(this).addClass('active_tab_format');

		$('.sortColumn_type').remove();
		$('th[data-column="views"]').append(' <span class="sort_upper sortColumn_type">&#9660;</span>');

		uploadAllBooks();
	});

	function uploadDigitalBooks() {
		$('.analitics_row').attr('data-table', 'digital');

		$('#digital').addClass('choose_active');
		$('#audio').removeClass('choose_active');

		$('#tab_format_books').trigger('click');
		// uploadAllBooks();
	}
	function uploadAudioBooks() {
		$('.analitics_row').attr('data-table', 'audio');

		$('#digital').removeClass('choose_active');
		$('#audio').addClass('choose_active');

		$('#tab_format_books').trigger('click');
		// uploadAllBooks();
	}	
	
	function uploadAllBooks() {
		var format = '';
		if (!$('#digital.choose').hasClass('choose_active') && !$('#audio.choose').hasClass('choose_active')) {
			$('.choose').toggleClass('choose_active');
			format = 'all';
		} else if ($('#digital.choose').hasClass('choose_active') && $('#audio.choose').hasClass('choose_active'))
			format = 'all';
		else
			format = $('.choose_active').attr('id');

		var period = $('.change_active').attr('data-val');

		var rows_size = $('.table_size_active').text();
		var page = $('#active_page').val();
		var sortColumn = $('#book .sortColumn_type').parent().data('column');
		if ($('#book .sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';

		var sortType = '';
		if ($('.sort_date').hasClass('sort_active')) sortType = 'bydate';
		else sortType = 'bybook';

		if (table != 'views') {
			$.ajax({
				url: '/ajax/analitic.php',
				type: 'POST',
				dataType: 'html',
				data: {
					rows_size: rows_size,
					page: page,
					period: period,
					format: format,
					table: table,
					get_table: $('#page_table').val(),
					sortType: sortType,
					sortColumn: sortColumn,
					sortColumnType: sortColumnType,
					request_uri: location.pathname + location.search,
					user_id: $('#user_id').val(),
					role: $('#role').val(),
				},
			})
			.done(function(res) {
				res = res.split('===================================================================================================');
				$('#book tbody').html(res[0]);
				$('.pagination_list .pages_list').html(res[1]);

				$('#book').attr('data-task', sortType);
				$('.pagination_list .page').last().addClass('last_pagination');

				$('.active_table_row table').after('<script>document.cookie = "sort='+sortType+'";</script>');
				$('.active_table_row table').after('<script>document.cookie = "period='+period+'";</script>');
				$('.active_table_row table').after('<script>document.cookie = "rows='+rows_size+'";</script>');
				$('.active_table_row table').after('<script>document.cookie = "format='+format+'";</script>');
			});
		} else {
			$.ajax({
				url: '/ajax/analitic_views.php',
				type: 'POST',
				dataType: 'html',
				data: {
					rows_size: rows_size,
					page: page,
					period: period,
					format: format,
					table: table,
					get_table: $('#page_table').val(),
					sortColumn: sortColumn,
					sortColumnType: sortColumnType,
					request_uri: location.pathname + location.search,
					search: $('#search_table_command').val(),
					user_id: $('#user_id').val(),
					role: $('#role').val(),
				},
			})
			.done(function(res) {
				res = res.split('===================================================================================================');
				$('#book tbody').html(res[0]);
				$('.pagination_list .pages_list').html(res[1]);

				$('#book').attr('data-task', sortType);
				$('.pagination_list .page').last().addClass('last_pagination');

				$('.active_table_row table').after('<script>document.cookie = "sort='+sortType+'";</script>');
				$('.active_table_row table').after('<script>document.cookie = "period='+period+'";</script>');
				$('.active_table_row table').after('<script>document.cookie = "rows='+rows_size+'";</script>');
				$('.active_table_row table').after('<script>document.cookie = "format='+format+'";</script>');
			});
			
		}
	}


	// change date
	$('.change_date > *:not(.custom_date_change)').click(function() {
		if (!$(this).hasClass('change_active')) {
			$('.change_active').removeClass('change_active');
			$(this).addClass('change_active');

			uploadAllBooks();
		}
	});
	$('.done_cal').click(function () {
		if ($('.range_control').length != 2)
			return;

		$('.custom_date_change').attr('data-val', 'DATE(`date`) BETWEEN '+$('.range_control').first().attr('data-date')+' AND '+$('.range_control').last().attr('data-date'));
		$('.custom_date_change span').text($('.range_control').first().text() + ' ' + $('.range_control').first().parent().prev().prev().attr('data-month_name') + ' ' + $('.range_control').first().parent().prev().prev().attr('data-year') + ' – ' + $('.range_control').last().text() + ' ' + $('.range_control').last().parent().prev().prev().attr('data-month_name') + ' ' + $('.range_control').last().parent().prev().prev().attr('data-year'));

		$('.calendar_overlay').hide();
		$('.calendar').css('display', 'none');

		$('.page_wrapper').after('<script>document.cookie = "calendarText='+$('.custom_date_change span').text()+'";</script>');
		$('.page_wrapper').after('<script>document.cookie = "period='+$('.custom_date_change').attr('data-val')+'";</script>');

		uploadAllBooks();
	});
	// change rows quantity
	$('.table_sizes .table_size').click(function() {
		if (!$(this).hasClass('table_size_active')) {
			$('.table_size_active').removeClass('table_size_active');
			$(this).addClass('table_size_active');

			uploadAllBooks();
		}
	});
	// change visible formats
	$('.choose_format .choose').click(function() {
		$(this).toggleClass('choose_active');

		uploadAllBooks();
	});
	// choose sort by date or by books
	$('.sort_date_or_book > *').click(function() {
		$(this).parent().find('.sort_active').removeClass('sort_active');
		$(this).addClass('sort_active');

		var sortType = '';
		if ($('.sort_date').hasClass('sort_active')) sortType = 'bydate';
		else sortType = 'bybook';
		if (sortType == 'bybook' && $('th[data-column="date"]').length == 1) {
			$('th[data-column="date"]').remove();
			$('.sortColumn_type').remove();
			$('th[data-column="name"]').append(' <span class="sort_down sortColumn_type">&#9660;</span>');
		} else if (sortType == 'bydate' && $('th[data-column="date"]').length == 0) {
			$('.sortColumn_type').remove();
			$('thead tr').prepend('<th class="books" data-column="date">Дата <span class="sort_upper sortColumn_type">&#9660;</span></th>');
		}
		$('thead th[data-column="date"]').click(function() {
			updateSortColumn($(this));
		});

		uploadAllBooks();
	});
	// change sort column
	function updateSortColumn(th) {
		if (th.data('column') != undefined) {
			if (th.find('.sort_upper').length == 1) {
				th.find('.sortColumn_type').remove();
				th.append(' <span class="sort_down sortColumn_type">&#9650;</span>');
			} else if (th.find('.sort_down').length == 1) {
				th.find('.sortColumn_type').remove();
				th.append(' <span class="sort_upper sortColumn_type">&#9660;</span>');
			} else {
				th.parent().find('.sortColumn_type').remove();
				th.append(' <span class="sort_upper sortColumn_type">&#9660;</span>');
			}

			uploadAllBooks();
		}
	}
	$('thead th').click(function() {
		updateSortColumn($(this));
	});
	// change serch value
	$('#search_table_command').on('input', function() {
		uploadAllBooks();
	});


	if ($('#page_table').val() == 'digital')
		$('#digital_books').trigger('click');
	else if ($('#page_table').val() == 'audio')
		$('#audio_books').trigger('click');
	else if ($('#page_table').val() == 'views.digital') {
		$('#digital_books').trigger('click');
		$('#tab_format_views').trigger('click');
	}
	else if ($('#page_table').val() == 'views.audio') {
		$('#audio_books').trigger('click');
		$('#tab_format_views').trigger('click');
	}

});