$(document).ready(function() {

	$('.about_view').addClass('hidden');
	//$('.about_view').css('margin-top','-220px');
	$('.about_view').css('margin-bottom','100px');
	$('.page_wrapper').addClass('grey_bg');
	
	$('.tab_1, .tab_2, .tab_3').click(function(){
		$('.about_view').css('display','none');
		$('.grey_bg').addClass('content_wrapper');
	});
	$('.tab_4').click(function(){
		$('.grey_bg').removeClass('content_wrapper');
		$('.about_view').css('margin-bottom','100px');
	});
	
	//click on client
	$('.view_tabs').click(function(){
		if($('.active_tab').attr('data-tab') == 'about_view'){
				$('.about_view').css('display','flex');
				$('.about_view').removeClass('hidden');
				$('.about_view').css('margin-bottom','42vh');
				$('.content_wrapper').css('min-height','0');				
		}
		if($('.active_tab').attr('data-tab') == 'books'){
				$('.about_view').css('display','none');
				$('.about_view').addClass('hidden');
				$('.about_view').css('margin-bottom','100px');
				$('.content_wrapper').css('min-height','calc(100vh - 98px - 92px)');
				
		}
		if($('.active_tab').hasClass('tab_4')){
			$('.grey_bg').removeClass('content_wrapper');
			$('.grey_bg').css('min-height','0');
			$('.about_view').css('margin-bottom','100px');
			$('.about_view').css('min-height','42vh');
		}
		if($('.active_tab').hasClass('tab_3')){
			$('.grey_bg').addClass('content_wrapper');
			$('.content_wrapper').css('min-height','calc(100vh - 98px - 92px)');
			$('.about_view').css('display','none');
			$('.about_view').css('margin-bottom','100px');
		}
	});
	
	
	
	
	
	
	
	//получение id 
	var $_GET = {};
	document.location.search.replace(/\??(?:([^=]+)=([^&]*)&?)/g, function () {
	    function decode(s) {
	        return decodeURIComponent(s.split("+").join(" "));
	    }

	    $_GET[decode(arguments[1])] = decode(arguments[2]);
	});	
	var id = $_GET["id"];
	
	$('.change_date1').click(function(){
		$('#quartal').removeClass('act');
	});
	
	var table = 'all';
	// main tabs
	$('#all_books').click(function() {
		table = 'all';
		$('.analitics_tab_active').removeClass('analitics_tab_active');
		$(this).addClass('analitics_tab_active');
		$('.analitics_row').attr('data-table', 'all');

		$('.tabs_format_col').hide();

		$('#audio').addClass('choose_active1');
		$('#digital').addClass('choose_active1');

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

		uploadAllBooks();
	});
	$('#tab_format_views').click(function() {
		table = 'views';
		$('.analitics_row').attr('data-table', table);

		$('.active_tab_format').removeClass('active_tab_format');
		$(this).addClass('active_tab_format');

		uploadAllBooks();
	});

	function uploadDigitalBooks() {
		$('.analitics_row').attr('data-table', 'digital');

		$('#digital').addClass('choose_active1');
		$('#audio').removeClass('choose_active1');

		$('#tab_format_books').trigger('click');
		// uploadAllBooks();
	}
	function uploadAudioBooks() {
		$('.analitics_row').attr('data-table', 'audio');

		$('#digital').removeClass('choose_active1');
		$('#audio').addClass('choose_active1');

		$('#tab_format_books').trigger('click');
		// uploadAllBooks();
	}	
	
	function uploadAllBooks() {
		var format = '';
		if (!$('#digital.choose1').hasClass('choose_active1') && !$('#audio.choose1').hasClass('choose_active1')) {
			$('.choose1').toggleClass('choose_active1');
			format = 'all';
		} else if ($('#digital.choose1').hasClass('choose_active1') && $('#audio.choose1').hasClass('choose_active1'))
			format = 'all';
		else
			format = $('.choose_active1').attr('id');

		var period = '';
		if ($('#custom').hasClass('change_active1')) {

		} else
			period = $('.change_active1').data('val');

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
				url: '/ajax/finance_e.php',
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
					id: id,
					
				},
//				success:function(response){
//					console.log($('#role').val());
//				}
			})
			.done(function(res) {
				res = res.split('===================================================================================================');
				$('.fin_money').html(res[0]);
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
				url: '/ajax/finance_e.php',
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
					id: id,
				},
			})
			.done(function(res) {
				res = res.split('===================================================================================================');
				$('.fin_money').html(res[0]);
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
	$('.change_date1 > *').click(function() {
		if (!$(this).hasClass('change_active1')) {
			$('.change_active1').removeClass('change_active1');
			$(this).addClass('change_active1');

			uploadAllBooks();
		}
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
	$('.choose_format1 .choose1').click(function() {
		$(this).toggleClass('choose_active1');

		uploadAllBooks();
	});
	// choose1 sort by date or by books
	$('.sort_date_or_book > *').click(function() {
		$(this).parent().find('.sort_active').removeClass('sort_active');
		$(this).addClass('sort_active');

		var sortType = '';
		if ($('.sort_date').hasClass('sort_active')) sortType = 'bydate';
		else sortType = 'bybook';
		if (sortType == 'bybook' && $('th[data-column="date"]').length == 1) {
			$('th[data-column="date"]').remove();
			$('th[data-column="name"]').append(' <span class="sort_down sortColumn_type">&#9660;</span>');
		} else if (sortType == 'bydate' && $('th[data-column="date"]').length == 0) {
			$('.sortColumn_type').remove();
			$('thead tr').prepend('<th class="books" data-column="date">Дата <span class="sort_upper sortColumn_type">&#9660;</span></th>');
		}

		uploadAllBooks();
	});
	// change sort column
	$('thead th').click(function() {
		if ($(this).data('column') != undefined) {
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

			uploadAllBooks();
		}
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