$(document).ready(function() {

	// connect graph
	//Themes begin
	am4core.useTheme(am4themes_animated);
	// Themes end
	
	// Create chart instance
	var chart = am4core.create("chartdiv_", am4charts.XYChart);
	chart.language.locale = am4lang_ru_RU;

	chart.colors.list = [
		am4core.color("#b5e5e0"),
	  am4core.color("#3c93fe"),
	  am4core.color("#7ee557"),
	  am4core.color("#FF9671"),
	  am4core.color("#FFC75F"),
	  am4core.color("#F9F871")
	];
	
	// Create axes
	var dateAxis = chart.xAxes.push(new am4charts.DateAxis());
	var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
	
	// Create series
	var series = chart.series.push(new am4charts.LineSeries());
	series.dataFields.valueY = "value";
	series.dataFields.dateX = "date";
	series.tooltipText = "{value}"
	series.strokeWidth = 2;
	series.minBulletDistance = 15;
	
	// Drop-shaped tooltips
	series.tooltip.background.cornerRadius = 20;
	series.tooltip.background.strokeOpacity = 0;
	series.tooltip.pointerOrientation = "vertical";
	series.tooltip.label.minWidth = 40;
	series.tooltip.label.minHeight = 40;
	series.tooltip.label.textAlign = "middle";
	series.tooltip.label.textValign = "middle";


	var series2 = chart.series.push(new am4charts.LineSeries());
	series2.dataFields.valueY = "value2";
	series2.dataFields.dateX = "date";
	series2.tooltipText = "{value2}"
	series2.strokeWidth = 2;
	series2.minBulletDistance = 15;

	
	// Drop-shaped tooltips
	series2.tooltip.background.cornerRadius = 20;
	series2.tooltip.background.strokeOpacity = 0;
	series2.tooltip.pointerOrientation = "vertical";
	series2.tooltip.label.minWidth = 40;
	series2.tooltip.label.minHeight = 40;
	series2.tooltip.label.textAlign = "middle";
	series2.tooltip.label.textValign = "middle";



	var series3 = chart.series.push(new am4charts.LineSeries());
	series3.dataFields.valueY = "value3";
	series3.dataFields.dateX = "date";
	series3.tooltipText = "{value3}"
	series3.strokeWidth = 2;
	series3.minBulletDistance = 15;


	
	// Drop-shaped tooltips
	series3.tooltip.background.cornerRadius = 20;
	series3.tooltip.background.strokeOpacity = 0;
	series3.tooltip.pointerOrientation = "vertical";
	series3.tooltip.label.minWidth = 40;
	series3.tooltip.label.minHeight = 40;
	series3.tooltip.label.textAlign = "middle";
	series3.tooltip.label.textValign = "middle";



	// Make bullets grow on hover
	var bullet = series.bullets.push(new am4charts.CircleBullet());
	bullet.circle.strokeWidth = 2;
	bullet.circle.radius = 4;
	bullet.circle.fill = am4core.color("#fff");
	
	var bullethover = bullet.states.create("hover");
	bullethover.properties.scale = 1.3;

	// Make bullets grow on hover
	var bullet2 = series2.bullets.push(new am4charts.CircleBullet());
	bullet2.circle.strokeWidth = 2;
	bullet2.circle.radius = 4;
	bullet2.circle.fill = am4core.color("#fff");
	
	var bullethover2 = bullet2.states.create("hover");
	bullethover2.properties.scale = 1.3;

	// Make bullets grow on hover
	var bullet3 = series3.bullets.push(new am4charts.CircleBullet());
	bullet3.circle.strokeWidth = 2;
	bullet3.circle.radius = 4;
	bullet3.circle.fill = am4core.color("#fff");
	
	var bullethover3 = bullet3.states.create("hover");
	bullethover3.properties.scale = 1.3;
	
	// Make a panning cursor
	chart.cursor = new am4charts.XYCursor();
	chart.cursor.behavior = "panXY";
	chart.cursor.xAxis = dateAxis;
	// chart.cursor.snapToSeries = series;
	
	// Create vertical scrollbar and place it before the value axis
	chart.scrollbarY = new am4core.Scrollbar();
	chart.scrollbarY.parent = chart.leftAxesContainer;
	chart.scrollbarY.toBack();
	
	// Create a horizontal scrollbar with previe and place it underneath the date axis
	chart.scrollbarX = new am4charts.XYChartScrollbar();
	chart.scrollbarX.series.push(series);
	chart.scrollbarX.parent = chart.bottomAxesContainer;
	
	chart.events.on("ready", function () {
	  dateAxis.zoom({start:0.79, end:1});
	});

	
	$('.tab_finance').click(function() {
		if (!$(this).hasClass('active_tab_finance')) {
			$('.active_tab_finance').removeClass('active_tab_finance');
			$(this).addClass('active_tab_finance');

			$('.active_col_finance').removeClass('active_col_finance');
			$('.'+$(this).attr('data-col')).addClass('active_col_finance');
			if ($(this).attr('data-col') == 'reports_from_command') {
				$('.after_table_filters, .analitics_col').css('display', 'flex');
				if ($('.finance_row').attr('style') == 'display: none;')
					$('.user_view').css('display', 'flex');
			}
			else {
				$('.after_table_filters, .analitics_col, .user_view').hide();
				if ($('.finance_row').attr('style') == 'display: none;')
					$('.finance_row').css('display', 'flex');
			}
		}
	});


	var token = '';
	$('table[data-table="link"] tbody tr').click(function() {
		$('.finance_row').css('display', 'none');

		var sortColumn = $('#user_view_table .sortColumn_type').parent().data('column');
		if ($('#user_view_table .sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';

		var id = $(this).data('id');
		token = new Date().getUTCMilliseconds();
		if ($(this).find('.viewed_icon').length == 1)
			$(this).find('.viewed_icon').remove();

		$.ajax({
			url: '/ajax/finance_view.php',
			type: 'POST',
			dataType: 'html',
			data: {
				id: id,
				token: token,
				rows_size: $('.user_view_tbody_after_table_filters .table_size_active').text(),
				page: $('#active_page').val(),
				request_uri: location.pathname + location.search,
				sortColumn: sortColumn,
				sortColumnType: sortColumnType,
			},
		})
		.done(function(res) {
			res = res.split('////////=============////////');
			if (res[3] == token) {
				$('.user_view_tbody').html(res[1]);

				info = res[0].split('|0|');
				$('img.avatar').attr('src', '/avatars/' + info[0]);
				$('.finance_view_name .name').html(info[1] + '<span class="count">' + info[2] + '</span>');
				$('.finance_view_name .address').html(info[3]);

				$('.user_view.row').attr('data-id', id);
				$('.user_view_pagination_list .pages_list').html(res[2]);
				$('.user_view_pagination_list .page').last().addClass('last_pagination');

				$('.user_view').css('display', 'flex');
			}
		});
		
	});

	$('.referer').click(function() {
		$('.user_view.row').hide();
		$('.finance_row').show();
	});

	// AJAX
	$('#search_table_command').on('input', function(event) {
		var rows_size = $('.finance_after_table_filters .table_size_active').text();
		var page = $('#active_page').val();
		token = new Date().getUTCMilliseconds();

		var sortColumn = $('.sortColumn_type').parent().data('column');
		if ($('.sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';

		$.ajax({
			url: '/ajax/finance_table.php',
			type: 'POST',
			dataType: 'html',
			data: {
				rows_size: rows_size,
				page: page,
				sortColumn: sortColumn,
				sortColumnType: sortColumnType,
				search: $('#search_table_command').val(),
				request_uri: location.pathname + location.search,
				token: token,
				to: $('#to').val(),
				role: $('#role').val(),
			},
		})
		.done(function(res) {
			res = res.split('===================================================================================================');
			if (res[2] == token) {
				$('#reports_table tbody').html(res[0]);
				$('.finance_after_table_filters .pages_list').html(res[1]);
				$('.finance_after_table_filters .pagination_list .page').last().addClass('last_pagination');
			}
		});			
	});
	if ($('#role').val() == 'ББТ') {
		var rows_size = $('.finance_after_table_filters .table_size_active').text();
		var page = $('#active_page').val();
		token = new Date().getUTCMilliseconds();

		var sortColumn = $('.sortColumn_type').parent().data('column');
		if ($('.sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';

		$.ajax({
			url: '/ajax/finance_table.php',
			type: 'POST',
			dataType: 'html',
			data: {
				rows_size: rows_size,
				page: page,
				sortColumn: sortColumn,
				sortColumnType: sortColumnType,
				search: $('#search_table_command').val(),
				request_uri: location.pathname + location.search,
				token: token,
				to: $('#to').val(),
				role: $('#role').val(),
			},
		})
		.done(function(res) {
			res = res.split('===================================================================================================');
			if (res[2] == token) {
				$('#reports_table tbody').html(res[0]);
				$('.finance_after_table_filters .pages_list').html(res[1]);
				$('.finance_after_table_filters .pagination_list .page').last().addClass('last_pagination');
			}
		});
	}


	$('.table_sizes:not(.earn_table_sizes) > *').on('click', function(event) {
		if (!$(this).hasClass('table_size_active')) {
			$(this).parent().find('.table_size').removeClass('table_size_active');
			$(this).addClass('table_size_active');

			var rows_size = $('.finance_after_table_filters .table_size_active').text();
			var page = $('#active_page').val();
			token = new Date().getUTCMilliseconds();

			var sortColumn = $('.sortColumn_type').parent().data('column');
			if ($('.sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
			else  var sortColumnType = 'reverse';

			$.ajax({
				url: '/ajax/finance_table.php',
				type: 'POST',
				dataType: 'html',
				data: {
					rows_size: rows_size,
					page: page,
					sortColumn: sortColumn,
					sortColumnType: sortColumnType,
					search: $('#search_table_command').val(),
					request_uri: location.pathname + location.search,
					token: token,
					to: $('#to').val(),
					role: $('#role').val(),
				},
			})
			.done(function(res) {
				res = res.split('===================================================================================================');
				if (res[2] == token) {
					$('#reports_table tbody').html(res[0]);
					$('.finance_after_table_filters .pages_list').html(res[1]);
					$('.finance_after_table_filters .pagination_list .page').last().addClass('last_pagination');

					$('#reports_table').after('<script>document.cookie = "rows='+rows_size+'";</script>');
				}
			});
		}
	});	

	$('#reports_table thead th').on('click', function(event) {
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


			var rows_size = $('.finance_after_table_filters .table_size_active').text();
			token = new Date().getUTCMilliseconds();

			var sortColumn = $('#reports_table .sortColumn_type').parent().data('column');
			if ($('#reports_table .sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
			else  var sortColumnType = 'reverse';

			$.ajax({
				url: '/ajax/finance_table.php',
				type: 'POST',
				dataType: 'html',
				data: {
					rows_size: rows_size,
					page: $('#active_page').val(),
					sortColumn: sortColumn,
					sortColumnType: sortColumnType,
					search: $('#search_table_command').val(),
					request_uri: location.pathname + location.search,
					token: token,
					to: $('#to').val(),
					role: $('#role').val(),
				},
			})
			.done(function(res) {
				res = res.split('===================================================================================================');
				if (res[2] == token) {
					$('#reports_table tbody').html(res[0]);
					$('.finance_after_table_filters .pages_list').html(res[1]);
					$('.finance_after_table_filters .pagination_list .page').last().addClass('last_pagination');
				}
			});
		}
	});	

	$('.finance_after_table_filters .pagination_list .page').last().addClass('last_pagination');

	if ($('#table').val() == 'from_commands')
		$('div[data-col="reports_from_command"]').trigger('click');



	if ($('#view').val() != '') {
		$('div[data-col="reports_from_command"]').trigger('click');

		$('.finance_row').hide();

		var id = $('#view').val();
		token = new Date().getUTCMilliseconds();

		var sortColumn = $('#user_view_table .sortColumn_type').parent().data('column');
		if ($('#user_view_table .sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';

		$.ajax({
			url: '/ajax/finance_view.php',
			type: 'POST',
			dataType: 'html',
			data: {
				id: id,
				token: token,
				rows_size: $('.user_view_tbody_after_table_filters .table_size_active').text(),
				page: $('#active_page').val(),
				request_uri: location.pathname + location.search,
				sortColumn: sortColumn,
				sortColumnType: sortColumnType,
			},
		})
		.done(function(res) {
			res = res.split('////////=============////////');
			if (res[3] == token) {
				$('.user_view_tbody').html(res[1]);

				info = res[0].split('|0|');
				$('img.avatar').attr('src', '/avatars/' + info[0]);
				$('.finance_view_name .name').html(info[1] + '<span class="count">' + info[2] + '</span>');
				$('.finance_view_name .address').html(info[3]);

				$('.user_view.row').attr('data-id', id);
				$('.user_view_pagination_list .pages_list').html(res[2]);
				$('.user_view_pagination_list .page').last().addClass('last_pagination');

				$('.user_view').css('display', 'flex');
			}
		});





		$.ajax({
			url: '/ajax/finance_table.php',
			type: 'POST',
			dataType: 'html',
			data: {
				rows_size: rows_size,
				page: $('#active_page').val(),
				sortColumn: sortColumn,
				sortColumnType: sortColumnType,
				search: $('#search_table_command').val(),
				request_uri: location.pathname + location.search,
				token: token,
				to: $('#to').val(),
				role: $('#role').val(),
			},
		})
		.done(function(res) {
			res = res.split('===================================================================================================');
			if (res[2] == token) {
				$('#reports_table tbody').html(res[0]);
				$('.finance_after_table_filters .pages_list').html(res[1]);
				$('.finance_after_table_filters .pagination_list .page').last().addClass('last_pagination');
			}
		});


	}


	$('.user_view_tbody_after_table_filters .table_size').click(function() {
		$('.user_view_tbody_after_table_filters .table_size_active').removeClass('table_size_active');
		$(this).addClass('table_size_active');


		$('div[data-col="reports_from_command"]').trigger('click');

		$('.finance_row').hide();

		var id = $('.user_view.row').attr('data-id');
		token = new Date().getUTCMilliseconds();

		var sortColumn = $('#user_view_table .sortColumn_type').parent().data('column');
		if ($('#user_view_table .sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';

		$.ajax({
			url: '/ajax/finance_view.php',
			type: 'POST',
			dataType: 'html',
			data: {
				id: id,
				token: token,
				rows_size: $('.user_view_tbody_after_table_filters .table_size_active').text(),
				page: $('#active_page').val(),
				request_uri: location.pathname + location.search,
				sortColumn: sortColumn,
				sortColumnType: sortColumnType,
			},
		})
		.done(function(res) {
			res = res.split('////////=============////////');
			if (res[3] == token) {
				$('.user_view_tbody').html(res[1]);

				info = res[0].split('|0|');
				$('img.avatar').attr('src', '/avatars/' + info[0]);
				$('.finance_view_name .name').html(info[1] + '<span class="count">' + info[2] + '</span>');
				$('.finance_view_name .address').html(info[3]);

				$('.user_view.row').attr('data-id', id);
				$('.user_view_pagination_list .pages_list').html(res[2]);
				$('.user_view_pagination_list .page').last().addClass('last_pagination');

				$('.user_view').css('display', 'flex');
			}
		});
	});




	$('.user_view #user_view_table th').click(function() {
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

			var id = $('.user_view.row').attr('data-id');
			token = new Date().getUTCMilliseconds();

			var sortColumn = $('#user_view_table .sortColumn_type').parent().data('column');
			if ($('#user_view_table .sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
			else  var sortColumnType = 'reverse';

			$.ajax({
				url: '/ajax/finance_view.php',
				type: 'POST',
				dataType: 'html',
				data: {
					id: id,
					token: token,
					sortColumn: sortColumn,
					sortColumnType: sortColumnType,
					rows_size: $('.user_view_tbody_after_table_filters .table_size_active').text(),
					page: $('#active_page').val(),
					request_uri: location.pathname + location.search,
				},
			})
			.done(function(res) {
				res = res.split('////////=============////////');
				if (res[3] == token) {
					$('.user_view_tbody').html(res[1]);

					$('.user_view_pagination_list .pages_list').html(res[2]);
					$('.user_view_pagination_list .page').last().addClass('last_pagination');
				}
			});
		}
	});


	$('.referer').on('click', function() {
		if ($('.finance_after_table_filters .active_page').length == 0)
			$('.finance_after_table_filters .page:eq('+ (parseInt($('#active_page').val()) - 1) +')').addClass('active_page');
		
	});

	$('.finance_open_report').on('click', function() {
		$('#overlay_document').show();
		$('#overlay_document .name').text($(this).data('document'));

		$('#overlay_document .download a').attr('href', $(this).data('file'));	
	});

	$('#close_overlay_document').click(function() {
		$('#overlay_document').css('display', 'none');
	});

	





	function updateEarn(noUpdateChart=false) {
		var format = '';
		if (!$('#digital.choose').hasClass('choose_active') && !$('#audio.choose').hasClass('choose_active')) {
			format = 'all';
		} else if ($('#digital.choose').hasClass('choose_active') && $('#audio.choose').hasClass('choose_active'))
			format = 'all';
		else
			format = $('.choose_active').attr('id');


		$.ajax({
			url: '/ajax/update_earn_bbt.php',
			type: 'POST',
			dataType: 'html',
			data: {
				format: format,
				period: $('.change_active').attr('data-val'),
				graph: $('#month_drop_list').val(),
			},
		})
		.done(function(res) {
			res = res.split('|');
			
			$('.fin_m_span_dogovor').html(res[0] + ' &#8381;');
			$('.fin_m_span_bonus').html(res[1] + ' &#8381;');
			$('.fin_m_span_all').html(res[2] + ' &#8381;');


			chart.data = JSON.parse(res[3]);

			resortEarnTable();
		});
		
	}
	updateEarn();

	function checkShowTable() {
		$.ajax({
			url: '/ajax/checkShowTable.php',
			type: 'POST',
			dataType: 'html',
			data: {period: $('.change_active').attr('data-val')},
		})
		.done(function(res) {
			if (res == 1) {
				$('.earn_tables, .earn_tables_control').removeAttr('style');
			} else
				$('.earn_tables, .earn_tables_control').css('display', 'none');
		});
		
	}
	

	// change date
	$('.change_date > *:not(.custom_date_change)').click(function(){
		if (!$(this).hasClass('change_active')) {
			$('.change_active').removeClass('change_active');
			$(this).addClass('change_active');

			$('.page_wrapper').after('<script>document.cookie = "period='+$('.change_active').attr('data-val')+'";</script>');

			updateEarn(true);
			checkShowTable();
		}		
	});
	// apply period
	$('.done_cal').click(function () {
		if ($('.range_control').length != 2)
			return;

		$('.custom_date_change').attr('data-val', 'DATE(`date`) BETWEEN '+$('.range_control').first().attr('data-date')+' AND '+$('.range_control').last().attr('data-date'));
		$('.custom_date_change span').text($('.range_control').first().text() + ' ' + $('.range_control').first().parent().prev().prev().attr('data-month_name') + ' ' + $('.range_control').first().parent().prev().prev().attr('data-year') + ' – ' + $('.range_control').last().text() + ' ' + $('.range_control').last().parent().prev().prev().attr('data-month_name') + ' ' + $('.range_control').last().parent().prev().prev().attr('data-year'));

		$('.calendar_overlay').hide();
		$('.calendar').css('display', 'none');

		$('.page_wrapper').after('<script>document.cookie = "calendarText='+$('.custom_date_change span').text()+'";</script>');
		$('.page_wrapper').after('<script>document.cookie = "period='+$('.custom_date_change').attr('data-val')+'";</script>');

		updateEarn(true);
		checkShowTable();
	});
	// change visible formats
	$('.choose_format .choose').click(function() {
		$(this).toggleClass('choose_active');
		if (!$('#digital.choose').hasClass('choose_active') && !$('#audio.choose').hasClass('choose_active'))
			$('.choose').toggleClass('choose_active');

		var format = '';
		if (!$('#digital.choose').hasClass('choose_active') && !$('#audio.choose').hasClass('choose_active')) {
			format = 'all';
		} else if ($('#digital.choose').hasClass('choose_active') && $('#audio.choose').hasClass('choose_active'))
			format = 'all';
		else
			format = $('.choose_active').attr('id');
		$('.page_wrapper').after('<script>document.cookie = "format='+format+'";</script>');

		updateEarn();		
	});	






	$('#month_drop_list').change(function() {
		updateEarn();
	});



	function resortEarnTable() {
		if ($('.month_earn').find('.sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';

		var format = '';
		if (!$('#digital.choose').hasClass('choose_active') && !$('#audio.choose').hasClass('choose_active')) {
			format = 'all';
		} else if ($('#digital.choose').hasClass('choose_active') && $('#audio.choose').hasClass('choose_active'))
			format = 'all';
		else
			format = $('.choose_active').attr('id');

		$.ajax({
			url: '/ajax/resortEarnTable.php',
			type: 'POST',
			dataType: 'html',
			data: {
				sortColumn: $('.month_earn').find('.sortColumn_type').parent().data('column'),
				sortColumnType: sortColumnType,
				page: $('.earn_pagination_list .active_page').text(),
				rows: $('.earn_table_sizes .table_size_active').text(),
				table: 2,
				format: format,
			},
		})
		.done(function(res) {
			res = res.split('=====================================');
			$('.month_earn tbody').html(res[0]);
			$('.earn_pagination_list .pages_list').html(res[1]);
		});
	}

	// change sort column
	function updateSortColumn(th, table) {
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

			resortEarnTable();
		}
	}
	$('.earn_tables thead th').click(function() {
		updateSortColumn($(this), $(this).parent().parent().parent().attr('data-earn_table'));
	});
	$('.earn_page').on('click', function(event) {
		event.preventDefault();

		if ($(this).hasClass('active_page')) return;
		$('.active_page').removeClass('active_page');
		$(this).addClass('active_page');

		resortEarnTable($('table[data-earn_table][style!="display: none;"]').attr('data-earn_table'));
	});
	$('.earn_table_sizes > *').click(function() {
		if ($(this).hasClass('table_size_active'))
			return;

		$('.earn_table_sizes .table_size_active').removeClass('table_size_active');
		$(this).addClass('table_size_active');

		$('.page_wrapper').after('<script>document.cookie = "rows='+$('.earn_table_sizes .table_size_active').text()+'";</script>');

		resortEarnTable($('table[data-earn_table][style!="display: none;"]').attr('data-earn_table'));
	});


});