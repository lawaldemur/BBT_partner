jQuery(document).ready(function($) {

	// TABS
	$('.view_tabs .tab_1').click(function() {
		if ($(this).hasClass('active_tab'))
			return;

		$('.active_tab').removeClass('active_tab');
		$(this).addClass('active_tab');

		$('.view_content_container > .row').hide();
		$('.books_or_children_row').addClass('books').removeClass('graph').removeClass('children').css('display', 'flex');

		$('.sortColumn_type').remove();
		$('th.children[data-column="date"]').append(' <span class="sort_down sortColumn_type">&#9660;</span>');

		$('table#book tbody').html('');
		uploadAllBooks();
	});
	$('.view_tabs .tab_2').click(function() {
		if ($(this).hasClass('active_tab'))
			return;

		$('.active_tab').removeClass('active_tab');
		$(this).addClass('active_tab');

		$('.view_content_container > .row').hide();
		if ($('#role').val() == 'command' || $('#role').val() == 'partner') {
			$('.books_or_children_row').addClass('graph').removeClass('books').removeClass('children').css('display', 'flex');
			updateGraph();
		} else {
			$('.'+$(this).attr('data-tab')).css('display', 'flex');
		}
	});
	$('.view_tabs .tab_3').click(function() {
		if ($(this).hasClass('active_tab'))
			return;

		$('.active_tab').removeClass('active_tab');
		$(this).addClass('active_tab');

		$('.view_content_container > .row').hide();
		$('.books_or_children_row').addClass('children').removeClass('graph').removeClass('books').css('display', 'flex');

		$('.sortColumn_type').remove();
		$('th.children[data-column="name"]').append(' <span class="sort_down sortColumn_type">&#9660;</span>');

		$('table#book tbody').html('');
		uploadChildren();	
	});
	$('.view_tabs .tab_4').click(function() {
		if ($(this).hasClass('active_tab'))
			return;

		$('.active_tab').removeClass('active_tab');
		$(this).addClass('active_tab');

		$('.view_content_container > .row').hide();
		$('.'+$(this).attr('data-tab')).css('display', 'flex');
	});

	// $('.view_tabs .tab').click(function() {
	// 	if (!$(this).hasClass('active_tab')) {
	// 		$('.active_tab').removeClass('active_tab');
	// 		$(this).addClass('active_tab');

	// 		$('.view_content_container > .row').hide();
	// 		$('.'+$(this).attr('data-tab')).css('display', 'flex');
	// 	}
	// });

	
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

		$('#book').attr('data-task', sortType);

		$.ajax({
			url: '/ajax/view_books.php',
			type: 'POST',
			dataType: 'html',
			data: {
				rows_size: rows_size,
				page: page,
				period: period,
				format: format,
				table: 'books',
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
			console.log(res);
			res = res.split('===================================================================================================');
			$('#book tbody').html(res[0]);
			$('.pagination_list .pages_list').html(res[1]);

			$('.pagination_list .page').last().addClass('last_pagination');

			$('#book').after('<script>document.cookie = "sort='+sortType+'";</script>');
			$('#book').after('<script>document.cookie = "period='+period+'";</script>');
			$('#book').after('<script>document.cookie = "rows='+rows_size+'";</script>');
			$('#book').after('<script>document.cookie = "format='+format+'";</script>');
		});
	}


	// change date
	$('.change_date > *:not(.custom_date_change)').click(function() {
		if (!$(this).hasClass('change_active')) {
			$('.change_active').removeClass('change_active');
			$(this).addClass('change_active');

			if ($('.books_or_children_row').hasClass('books'))
				uploadAllBooks();
			else if ($('.books_or_children_row').hasClass('graph'))
				updateGraph(true);
			else
				uploadChildren();
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

		if ($('.books_or_children_row').hasClass('books'))
			uploadAllBooks();
		else if ($('.books_or_children_row').hasClass('graph'))
			updateGraph(true);
		else
			uploadChildren();
	});
	// change rows quantity
	$('.table_sizes .table_size').click(function() {
		if (!$(this).hasClass('table_size_active')) {
			$('.table_size_active').removeClass('table_size_active');
			$(this).addClass('table_size_active');

			if ($('.books_or_children_row').hasClass('books'))
				uploadAllBooks();
			else
				uploadChildren();
		}
	});
	// change visible formats
	$('.choose_format .choose').click(function() {
		$(this).toggleClass('choose_active');

		if ($('.books_or_children_row').hasClass('graph'))
			updateGraph();
		else
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
			$('th[data-column="name"]:eq(0)').append(' <span class="sort_upper sortColumn_type">&#9660;</span>');
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

			if ($('.books_or_children_row').hasClass('books'))
				uploadAllBooks();
			else
				uploadChildren();
		}
	});


	function uploadChildren() {
		var period = $('.change_active').attr('data-val');

		var rows_size = $('.table_size_active').text();
		var page = $('#active_page').val();
		var sortColumn = $('#book .sortColumn_type').parent().data('column');
		if ($('#book .sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';

		$.ajax({
			url: '/ajax/view_children.php',
			type: 'POST',
			dataType: 'html',
			data: {
				rows_size: rows_size,
				page: page,
				period: period,
				table: 'children',
				get_table: $('#page_table').val(),
				sortColumn: sortColumn,
				sortColumnType: sortColumnType,
				request_uri: location.pathname + location.search,
				user_id: $('#user_id').val(),
				role: $('#role').val(),
				search: $('#search_table_command').val(),
			},
		})
		.done(function(res) {
			res = res.split('===================================================================================================');
			$('#book tbody').html(res[0]);
			$('.pagination_list .pages_list').html(res[1]);

			$('.pagination_list .page').last().addClass('last_pagination');

			$('#book').after('<script>document.cookie = "period='+period+'";</script>');
			$('#book').after('<script>document.cookie = "rows='+rows_size+'";</script>');
			if ($('#role').val() == 'command')
				$('#book').after('<script>$(".books_or_children_row.children tbody tr td:first-child").click(function(){document.location="/view.php?id="+$(this).parent().data("id")});</script>');
			else
				$('#book').after('<script>$(".books_or_children_row.children tbody tr td:first-child").click(function(){document.location="/view.php?id=client"+$(this).parent().data("id")});</script>');
		});
	}

	// change serch value
	$('#search_table_command').on('input', function() {
		uploadChildren();
	});

	if ($('#page_table').val() == 'children')
		$('.tab_3').trigger('click');

	if ($('div').is('#chartdiv_')) {
		// connect graph
		//Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end
		
		// Create chart instance
		var chart = am4core.create("chartdiv_", am4charts.XYChart);
		chart.language.locale = am4lang_ru_RU;

		
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
		
		// Make bullets grow on hover
		var bullet = series.bullets.push(new am4charts.CircleBullet());
		bullet.circle.strokeWidth = 2;
		bullet.circle.radius = 4;
		bullet.circle.fill = am4core.color("#fff");
		
		var bullethover = bullet.states.create("hover");
		bullethover.properties.scale = 1.3;
		
		// Make a panning cursor
		chart.cursor = new am4charts.XYCursor();
		chart.cursor.behavior = "panXY";
		
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
	}

	$('#month_drop_list').change(function() {
		updateGraph();
	});

	function updateGraph(noUpdateChart=false) {
		var format = '';
		if (!$('#digital.choose').hasClass('choose_active') && !$('#audio.choose').hasClass('choose_active')) {
			$('.choose').toggleClass('choose_active');
			format = 'all';
		} else if ($('#digital.choose').hasClass('choose_active') && $('#audio.choose').hasClass('choose_active'))
			format = 'all';
		else
			format = $('.choose_active').attr('id');
		var period = $('.change_active').attr('data-val');

		$.ajax({
			url: '/ajax/view_graph.php',
			type: 'POST',
			dataType: 'html',
			data: {
				graph: $('#month_drop_list').val(),
				format: format,
				period: period,
				id: $('#user_id').val(),
				position: $('#role').val(),
			},
		})
		.done(function(res) {
			res = res.split('|');

			$('.fin_m_span_dogovor').html(res[0] + ' &#8381;');

			// update graph
			// if (!noUpdateChart)
				chart.data = JSON.parse(res[1]);
		});
		
	}


});