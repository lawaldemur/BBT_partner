$(document).ready(function() {
	
	if($('.change_active').attr('id') == 'year'){
		$('.statistic_month').removeClass('hidden');
		$('.after_table_filters_e').css('display','flex');
	}
	else if($('.change_active').attr('id') == 'quartal'){
		$('.statistic_month').removeClass('hidden');
		$('.after_table_filters_e').css('display','flex');
	}
	else if($('.change_active').attr('id') == 'custom'){
		$('.statistic_month').removeClass('hidden');
		$('.after_table_filters_e').css('display','flex');
	}
	else{
		$('.statistic_month').addClass('hidden');
		$('.after_table_filters_e').css('display','none');
	}
	
	//финансы ббт помесячно при клике
	$('.change').click(function(){
		if($(this).attr('id') == 'year'){
			$('.statistic_month').removeClass('hidden');
			$('.after_table_filters_e').css('display','flex');
		}
		else if($(this).attr('id') == 'quartal'){
			$('.statistic_month').removeClass('hidden');
			$('.after_table_filters_e').css('display','flex');
		}
		else if($(this).attr('id') == 'custom'){
			$('.statistic_month').removeClass('hidden');
			$('.after_table_filters_e').css('display','flex');
		}
		else{
			$('.statistic_month').addClass('hidden');
			$('.after_table_filters_e').css('display','none');
		}
	});
	
	//график день,неделя,месяц
	$('#month_drop_list').click(function(){
		var option = ($('option:selected').val());
		if(option == 'Детализация по дням'){
			$('.cd_s').removeClass('hidden');
			$('.cd_e').removeClass('hidden');
			$('#chartdiv').removeClass('hidden');
			
			$('.cd_s_week').addClass('hidden');
			$('.cd_e_week').addClass('hidden');
			$('#chartdiv_week').addClass('hidden');
			$('.cd_s_month').addClass('hidden');
			$('.cd_e_month').addClass('hidden');
			$('#chartdiv_month').addClass('hidden');
		}
		if(option == 'Детализация по неделям'){
			$('.cd_s_week').removeClass('hidden');
			$('.cd_e_week').removeClass('hidden');
			$('#chartdiv_week').removeClass('hidden');
			
			$('.cd_s').addClass('hidden');
			$('.cd_e').addClass('hidden');
			$('#chartdiv').addClass('hidden');
			$('.cd_s_month').addClass('hidden');
			$('.cd_e_month').addClass('hidden');
			$('#chartdiv_month').addClass('hidden');
		}
		if(option == 'Детализация по месяцам'){
			$('.cd_s_month').removeClass('hidden');
			$('.cd_e_month').removeClass('hidden');
			$('#chartdiv_month').removeClass('hidden');
			
			$('.cd_s_week').addClass('hidden');
			$('.cd_e_week').addClass('hidden');
			$('#chartdiv_week').addClass('hidden');
			$('.cd_s').addClass('hidden');
			$('.cd_e').addClass('hidden');
			$('#chartdiv').addClass('hidden');
		}
		
		//console.log(option);
	});
	
	
	//при загрузке с 2,3 ... страниц
	if(($('.active_tab_finance').attr('data-col') == 'reports_from_command')){
		$('.money').addClass('hidden');
		$('.analitics_col_e').addClass('hidden');
		$('.fin_money').addClass('hidden');
		$('#chartdiv').addClass('hidden');
		$('.statistic_month').addClass('hidden');
		$('.after_table_filters_e').css('display', 'none');
		$('.finance_row').removeClass('hidden');
		$('.page_wrapper').css('min-height','0');
		$('.content_wrapper').css('min-height','0');
		$('#chartdiv').addClass('hidden');
		$('#chartdiv_comm').addClass('hidden');
		$('.cd_s').addClass('hidden');
		$('.cd_e').addClass('hidden');
		if(($('#reports_table').height() <= '500')){
			$('.after_table_filters ').css('margin-bottom','400px');
		}
		else{
			$('.after_table_filters ').css('margin-bottom','100px');
		}
		if(($('#reports_for_bbt_table').height() <= '500')){
			$('.after_table_filters ').css('margin-bottom','400px');
		}
		else{
			$('.after_table_filters ').css('margin-bottom','100px');
		}
	}
	else if(($('.active_tab_finance').attr('data-col') == 'profit')){
		$('.money').removeClass('hidden');
		$('.analitics_col_e').removeClass('hidden');
		$('.fin_money').removeClass('hidden');
		$('#chartdiv').removeClass('hidden');
		$('#chartdiv_comm').removeClass('hidden');
		//$('.statistic_month').removeClass('hidden');
		$('.after_table_filters_e').css('display', 'flex');
		$('.finance_row').addClass('hidden');
		$('.page_wrapper').css('min-height','100hv');
		$('.content_wrapper').css('min-height','100hv');
		$('#chartdiv').removeClass('hidden');
		$('.cd_s').removeClass('hidden');
		$('.cd_e').removeClass('hidden');
		$('.prof_click').addClass('hidden');
		
		if(($('#reports_table').height() <= '500')){
			$('.after_table_filters ').css('margin-bottom','400px');
		}
		else{
			$('.after_table_filters ').css('margin-bottom','100px');
		}
		if(($('#reports_for_bbt_table').height() <= '500')){
			$('.after_table_filters ').css('margin-bottom','400px');
		}
		else{
			$('.after_table_filters ').css('margin-bottom','100px');
		}
		
		if($('.change_active').attr('id') == 'year'){
			$('.statistic_month').removeClass('hidden');
			$('.after_table_filters_e').css('display','flex');
		}
		else if($('.change_active').attr('id') == 'quartal'){
			$('.statistic_month').removeClass('hidden');
			$('.after_table_filters_e').css('display','flex');
		}
		else{
			$('.statistic_month').addClass('hidden');
			$('.after_table_filters_e').css('display','none');
		}
	}
	else if(($('.active_tab_finance').attr('data-col') == 'reports_for_bbt')){
		$('.money').addClass('hidden');
		$('.analitics_col_e').addClass('hidden');
		$('.fin_money').addClass('hidden');
		$('.user_view').css('display','none');
		$('.user_view').css('margin-top','40px');
		$('.finance_row').removeClass('hidden');
		$('.page_wrapper').css('min-height','0');
		$('.content_wrapper').css('min-height','0');
		$('#chartdiv').addClass('hidden');
		$('#chartdiv_comm').addClass('hidden');
		$('.cd_s').addClass('hidden');
		$('.cd_e').addClass('hidden');
		if(($('#reports_table').height() <= '500')){
			$('.after_table_filters ').css('margin-bottom','400px');
		}
		else{
			$('.after_table_filters ').css('margin-bottom','100px');
		}
		if(($('#reports_for_bbt_table').height() <= '500')){
			$('.after_table_filters ').css('margin-bottom','400px');
		}
		else{
			$('.after_table_filters ').css('margin-bottom','100px');
		}
	}
	else if(($('.active_tab_finance').attr('data-col') == 'reports_from_partner')){
		$('.money').addClass('hidden');
		$('.analitics_col_e').addClass('hidden');
		$('.fin_money').addClass('hidden');
		$('.finance_row').addClass('hidden');
		$('.user_view').css('display','block');
		$('.user_view').css('margin-top','40px');
		$('.page_wrapper').css('min-height','0');
		$('.content_wrapper').css('min-height','0');
		$('#chartdiv').addClass('hidden');
		$('#chartdiv_comm').addClass('hidden');
		$('.cd_s').addClass('hidden');
		$('.cd_e').addClass('hidden');
		if(($('.user_view_tbody').height() <= '500')){
			$('.after_table_filters ').css('margin-bottom','400px');
		}
		else{
			$('.after_table_filters ').css('margin-bottom','100px');
		}
		if(($('#reports_for_bbt_table').height() <= '500')){
			$('.after_table_filters ').css('margin-bottom','400px');
		}
		else{
			$('.after_table_filters ').css('margin-bottom','100px');
		}
	}
	// при нажатии на меню
	$('.tab_finance').click(function(){
		if($(this).attr('data-col') == 'profit'){
			$('.tab_finance').removeClass('active_tab_finance');
			$(this).addClass('active_tab_finance');
			$('.money').removeClass('hidden');
			$('.analitics_col_e').removeClass('hidden');
			$('.fin_money').removeClass('hidden');
			$('#chartdiv').removeClass('hidden');
			$('.statistic_month').removeClass('hidden');
			$('.after_table_filters_e').css('display', 'flex');
			$('.finance_row').addClass('hidden');
			$('.page_wrapper').css('min-height','100vh');
			$('.content_wrapper').css('min-height','100vh');
			$('#chartdiv_comm').removeClass('hidden');
			$('.cd_s').removeClass('hidden');
			$('.cd_e').removeClass('hidden');
			$('.prof_click').addClass('hidden');
			
			$('.user_view').css('display','none');
			$('.user_view').css('margin-top','0px');
			$('#chartdiv').removeClass('hidden');
			
			if(($('#reports_table').height() <= '500')){
				$('.after_table_filters ').css('margin-bottom','400px');
			}
			else{
				$('.after_table_filters ').css('margin-bottom','40vh');
			}
			if(($('#reports_for_bbt_table').height() <= '500')){
				$('.after_table_filters ').css('margin-bottom','400px');
			}
			else{
				$('.after_table_filters ').css('margin-bottom','40vh');
			}
		}
		if($(this).attr('data-col') == 'reports_from_command'){
			$('.money').addClass('hidden');
			$('.analitics_col_e').addClass('hidden');
			$('.fin_money').addClass('hidden');
			$('#chartdiv').addClass('hidden');
			$('#chartdiv_comm').addClass('hidden');
			$('.statistic_month').addClass('hidden');
			$('.after_table_filters_e').css('display', 'none');
			$('.finance_row').removeClass('hidden');
			$('.page_wrapper').css('min-height','0');
			$('.content_wrapper').css('min-height','0');
			$('#chartdiv').addClass('hidden');
			$('.cd_s').addClass('hidden');
			$('.cd_e').addClass('hidden');
			$('.prof_click').removeClass('hidden');
			//$('.finance_after_table_filters').css('margin-bottom','50vh');
			if(($('#reports_table').height() <= '500')){
				$('.after_table_filters ').css('margin-bottom','400px');
			}
			else{
				$('.after_table_filters ').css('margin-bottom','40vh');
			}
			if(($('#reports_for_bbt_table').height() <= '500')){
				$('.after_table_filters ').css('margin-bottom','400px');
			}
			else{
				$('.after_table_filters ').css('margin-bottom','100px');
			}
			
			if(($('.reports_from_command ').height() <= '400')){
				$('.user_view_tbody_after_table_filters ').css('margin-bottom','32vh');
			}
			else{
				$('.user_view_tbody_after_table_filters ').css('margin-bottom','100px');
			}
		}
		if($(this).attr('data-col') == 'reports_for_command'){
			$('.tab_finance').removeClass('active_tab_finance');
			$(this).addClass('active_tab_finance');
			
			$('.money').addClass('hidden');
			$('.analitics_col_e').addClass('hidden');
			$('.fin_money').addClass('hidden');
			$('.page_wrapper').css('min-height','0');
			$('.content_wrapper').css('min-height','0');
			$('.user_view_tbody_after_table_filters').css('margin-bottom','50vh');
			$('.user_view').css('display','block');
			$('.user_view').css('margin-top','40px');
			$('#chartdiv').addClass('hidden');
			$('#chartdiv_comm').addClass('hidden');
			$('.cd_s').addClass('hidden');
			$('.cd_e').addClass('hidden');
			$('.prof_click').removeClass('hidden');
			if(($('.user_view_tbody').height() <= '500')){
				$('.after_table_filters ').css('margin-bottom','40vh');
			}
			else{
				$('.after_table_filters ').css('margin-bottom','40vh');
			}
			if(($('#reports_for_bbt_table').height() <= '500')){
				$('.after_table_filters ').css('margin-bottom','40vh');
			}
			else{
				$('.after_table_filters ').css('margin-bottom','40vh');
			}
		}
		
		if($(this).attr('data-col') == 'reports_for_bbt'){
			$('.money').addClass('hidden');
			$('.analitics_col_e').addClass('hidden');
			$('.fin_money').addClass('hidden');
			$('.user_view').css('display','none');
			$('.user_view').css('margin-top','0px');
			$('.finance_row').removeClass('hidden');
			$('.page_wrapper').css('min-height','0');
			$('.content_wrapper').css('min-height','0');
			$('#chartdiv').addClass('hidden');
			$('#chartdiv_comm').addClass('hidden');
			$('.cd_s').addClass('hidden');
			$('.cd_e').addClass('hidden');
			$('.prof_click').removeClass('hidden');
			if(($('#reports_table').height() <= '500')){
				$('.after_table_filters ').css('margin-bottom','400px');
			}
			else{
				$('.after_table_filters ').css('margin-bottom','100px');
			}
			if(($('#reports_for_bbt_table').height() <= '500')){
				$('.after_table_filters ').css('margin-bottom','100px');
			}
			else{
				$('.after_table_filters ').css('margin-bottom','100px');
			}
		}
		if($(this).attr('data-col') == 'reports_from_partner'){
			$('.money').addClass('hidden');
			$('.analitics_col_e').addClass('hidden');
			$('.fin_money').addClass('hidden');
			$('.finance_row').removeClass('hidden');
			
			$('.user_view').css('display','none');
			$('.user_view').css('margin-top','40px');
			$('.page_wrapper').css('min-height','0');
			$('.content_wrapper').css('min-height','0');
			$('.user_view_tbody_after_table_filters').css('margin-bottom','50vh');
			$('#chartdiv').addClass('hidden');
			$('#chartdiv_comm').addClass('hidden');
			$('.cd_s').addClass('hidden');
			$('.cd_e').addClass('hidden');
			$('.prof_click').removeClass('hidden');
			if(($('.user_view_tbody').height() <= '500')){
				$('.after_table_filters ').css('margin-bottom','400px');
			}
			else{
				$('.after_table_filters ').css('margin-bottom','100px');
			}
			if(($('#reports_for_bbt_table').height() <= '500')){
				$('.after_table_filters ').css('margin-bottom','100px');
			}
			else{
				$('.after_table_filters ').css('margin-bottom','100px');
			}
		}
	});
	
	//
//	$('.reports_from_partner_tbody>tr').click(function(){
//		//console.log($(this).attr('data-id'));
//		$('.reports_from_partner_tbody').html('asdsa');
//	});
	
	
	
	
	
	
	$('.change_date').click(function(){
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
				url: '/ajax/finance_money.php',
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
				url: '/ajax/finance_money.php',
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
	$('.change_date > *:not(.custom_date_change)').click(function(){
		if (!$(this).hasClass('change_active')) {
			$('.change_active').removeClass('change_active');
			$(this).addClass('change_active');
			//console.log($('.change_active').attr('data-val'));

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

	
	$('.done_cal').click(function () {
		if ($('.range_control').length != 2)
			return;

		$('.custom_date_change').attr('data-val', 'DATE(`date`) BETWEEN '+$('.range_control').first().attr('data-date')+' AND '+$('.range_control').last().attr('data-date'));
		$('.custom_date_change span').text($('.range_control').first().text() + ' ' + $('.range_control').first().parent().prev().prev().attr('data-month_name') + ' ' + $('.range_control').first().parent().prev().prev().attr('data-year') + ' – ' + $('.range_control').last().text() + ' ' + $('.range_control').last().parent().prev().prev().attr('data-month_name') + ' ' + $('.range_control').last().parent().prev().prev().attr('data-year'));

		$('.calendar_overlay').hide();
		$('.calendar').css('display', 'none');

		$('.page_wrapper').after('<script>document.cookie = "calendarText='+$('.custom_date_change span').text()+'";</script>');
		$('.page_wrapper').after('<script>document.cookie = "period='+$('.custom_date_change').attr('data-val')+'";</script>');

		// код из события $('.change_date > *:not(.custom_date_change)’).click(function() и обратить внимание если есть в этом коде $(this), то убрать эти строчки совсем
	});
});