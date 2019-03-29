jQuery(document).ready(function($) {

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



	var token = '';

	$('.tab_finance').click(function() {
		if (!$(this).hasClass('active_tab_finance')) {
			if ($('.user_view').css('display') == 'flex') {
				$('.referer').trigger('click');
			}

			$('.active_tab_finance').removeClass('active_tab_finance');
			$(this).addClass('active_tab_finance');

			$('.active_col_finance').removeClass('active_col_finance');
			$('.'+$(this).attr('data-col')).addClass('active_col_finance');

			if ($(this).attr('data-col') != 'profit')
				$('.finance_after_table_filters').css('display', 'flex');
			else
				$('.finance_after_table_filters').css('display', 'none');
		}

		if ($(this).attr('data-col') == 'reports_for_bbt') {
			uploadToBbt();
		} else if ($(this).attr('data-col') == 'reports_from_partner') {
			uploadFromPartners();
		}
	});

	if ($('#table').val() != '') {
		$('.tab_finance[data-col="reports_for_bbt"]').trigger('click');
		uploadToBbt();
	} else if ($('#table_2').val() != '') {
		$('.tab_finance[data-col="reports_from_partner"]').trigger('click');
		uploadFromPartners();
	} else if ($('#table_3').val() != '') {
		$('.tab_finance[data-col="reports_from_partner"]').trigger('click');
		// uploadFromPartners();
		// $('.user_view').css('display', 'flex');
		$('.finance_row').hide();

		var id = $('#table_3').val();
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

				$('.user_view .referer').click(function() {
					$('.finance_row').css('display', 'flex');
					$('.user_view').hide();
				});
			}
		});
	}


	function uploadToBbt() {
		var sortColumn = $('#reports_for_bbt_table .sortColumn_type').parent().data('column');
		if ($('#reports_for_bbt_table .sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';

		$.ajax({
			url: '/ajax/finance_to_bbt.php',
			type: 'POST',
			dataType: 'html',
			data: {
				id: $('#user_id').val(),
				request_uri: location.pathname + location.search,
				page: $('#active_page').val(),
				rows_size: $('.to_bbt_table_sizes .table_size_active').text(),
				sortColumn: sortColumn,
				sortColumnType: sortColumnType,
			},
		})
		.done(function(res) {
			res = res.split('////////=============////////');

			$('.reports_for_bbt_tbody').html(res[0]);
			$('.pages_list').html(res[1]);

			$('#reports_for_bbt_table').after(`<script>
				$(".finance_upload_report").on("click",function(){
					$('.finance_open_report_file').val(null).attr('data-row', $(this).parent().parent().attr('data-id') + '|' + $(this).parent().index()).trigger('click');
				});
				$(".finance_upload_report").on("mouseenter",function(){
					if ($(this).text() == ' Готово')
						$(this).addClass('ready_upload');
					if ($(this).hasClass('ready_upload'))
						$(this).html('Загрузить');
				});
				$(".finance_upload_report").on("mouseleave",function(){
					if ($(this).hasClass('ready_upload'))
						$(this).html('<img src="/img/check.svg" width="10" height="8"> Готово');
				});
				</script>`);
			// $('#reports_for_bbt_table').after('<script>$(".finance_open_report").on("click",function(){$("#overlay_document").show(),$("#overlay_document .name").text($(this).data("document")),console.log($(this).parent().parent()),$("#overlay_document .download a").attr("href",$(this).data("file"))});</script>');
		});

		setTimeout(function() {
			if ($('.pages_list .active_page').length == 0)
				$('.pages_list .page:eq('+(parseInt($('#active_page').val()) - 1)+')').addClass('active_page');
		}, 300);
	}

	$('.finance_open_report_file').on('input', function() {
		var file_data = $(this).prop('files');  
		var form_data = new FormData();
		form_data.append('file', file_data[0]);

		var file_name = $(this).val();
		if (file_name.substr(file_name.length - 3) != 'pdf' &&
				file_name.substr(file_name.length - 3) != 'jpg' &&
				file_name.substr(file_name.length - 3) != 'png') {
			// change notification text
			$('#notification_text').text('Неподдерживаемый формат файла.');
			// show notification on 5s
			$('#notification').addClass('active_notification');
			setTimeout(function () {
				$('#notification').removeClass('active_notification');
			}, 5000);
			return
		}


		var split = $(this).attr('data-row').split('|');
		$('tr[data-id="'+split[0]+"\"] td:eq("+split[1]+") .finance_upload_report").after('<div class="progress_finance_open"><div class="value_progress" style="width: 0%;"></div></div>').remove();

		// var file_data = $(this).prop('files');  
		// var form_data = new FormData();
		// form_data.append('file', file_data[0]);

		$.ajax({ 
		    type: 'POST',
		    url: '/ajax/upload_report.php?purpose=' + $('.progress_finance_open').parent().index() + '&id=' + $('.progress_finance_open').parent().parent().attr('data-id') + '&date=' + $('.progress_finance_open').parent().parent().attr('data-date'),
		    data: form_data,
		    processData: false,
		    contentType: false,
		    xhr: function() { // Отслеживаем процесс загрузки файлов
		    	var xhr = $.ajaxSettings.xhr();
		        xhr.upload.addEventListener('progress', function(evt){
		          if (evt.lengthComputable) {
		            var percentComplete = Math.ceil(evt.loaded / evt.total * 100);
		            $('.progress_finance_open > div').css('width', percentComplete + '%');
		          }
		        }, false);
		        return xhr;
		    },
		    success: function (event) {
		    	console.log(event);

		    	var upload_ident = Date.now();
		    	$('.progress_finance_open').after('<button id="'+upload_ident+'" class="finance_upload_report ready_upload"><img src="/img/check.svg" width="10" height="8"> Готово</button>').remove();

		    	// $('#reports_for_bbt_table').after(`<script>
				$("#"+upload_ident).on("click",function(){
					$('.finance_open_report_file').val(null).attr('data-row', $(this).parent().parent().attr('data-id') + '|' + $(this).parent().index()).trigger('click');
				});
				$("#"+upload_ident).on("mouseenter",function(){
					if ($(this).text() == ' Готово')
						$(this).addClass('ready_upload');
					if ($(this).hasClass('ready_upload'))
						$(this).html('Загрузить');
				});
				$("#"+upload_ident).on("mouseleave",function(){
					if ($(this).hasClass('ready_upload'))
						$(this).html('<img src="/img/check.svg" width="10" height="8"> Готово');
				});
				// </script>`);
		    }
		});
	});		



	$('.to_bbt_table_sizes .table_size').click(function() {
		if (!$(this).hasClass('table_size_active')) {
			$('.to_bbt_table_sizes .table_size_active').removeClass('table_size_active');
			$(this).addClass('table_size_active');

			uploadToBbt();
		}
	});

	$('#reports_for_bbt_table thead th').click(function() {
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

			uploadToBbt();
		}
	});





	function uploadFromPartners() {
		var sortColumn = $('#reports_from_partner_table .sortColumn_type').parent().data('column');
		if ($('#reports_from_partner_table .sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';

		$.ajax({
			url: '/ajax/finance_from_partners.php',
			type: 'POST',
			dataType: 'html',
			data: {
				id: $('#user_id').val(),
				request_uri: location.pathname + location.search,
				page: $('#active_page').val(),
				rows_size: $('.to_bbt_table_sizes .table_size_active').text(),
				sortColumn: sortColumn,
				search: $('#search_table_command').val(),
				sortColumnType: sortColumnType,
			},
		})
		.done(function(res) {
			res = res.split('////////=============////////');

			$('#reports_from_partner_table tbody').html(res[0]);
			$('.pages_list').html(res[1]);

			// <script>
	$('.reports_from_partner_tbody tr').click(function() {
		$(this).find('.viewed_icon').remove();
		var id = $(this).attr('data-id');

		$('.user_view').css('display', 'flex');
		$('.finance_row').hide();

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
			$('.user_view_tbody').html(res[1]);

			info = res[0].split('|0|');
			$('img.avatar').attr('src', '/avatars/' + info[0]);
			$('.finance_view_name .name').html(info[1] + '<span class="count">' + info[2] + '</span>');
			$('.finance_view_name .address').html(info[3]);

			$('.user_view.row').attr('data-id', id);
			$('.user_view_pagination_list .pages_list').html(res[2]);
			$('.user_view_pagination_list .page').last().addClass('last_pagination');

			$('.user_view').css('display', 'flex');

			$('.user_view .referer').click(function() {
				$('.finance_row').css('display', 'flex');
				$('.user_view').hide();
			});
		});
	});
// </script>
		});

		setTimeout(function() {
			if ($('.pages_list .active_page').length == 0)
				$('.pages_list .page:eq('+(parseInt($('#active_page').val()) - 1)+')').addClass('active_page');
		}, 300);
	}

	$('#search_table_command').on('input', function() {
		uploadFromPartners();
	});	

	$('#reports_from_partner_table thead th').click(function() {
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

			uploadFromPartners();
		}
	});

	$('#close_overlay_document').click(function() {
		$('#overlay_document').css('display', 'none');
	});


	$('.reports_from_partner_tbody tr').click(function() {
		var id = $(this).attr('data-id');
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


	$('.finance_open_report').on('click', function() {
		$('#overlay_document').show();
		$('#overlay_document .name').text($(this).data('document'));
		console.log($(this).parent().parent());
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
			url: '/ajax/update_earn_command.php',
			type: 'POST',
			dataType: 'html',
			data: {
				format: format,
				period: $('.change_active').attr('data-val'),
				graph: $('#month_drop_list').val(),
				id: $('#to').val(),
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
	updateEarn();
	

	// change date
	$('.change_date > *:not(.custom_date_change)').click(function(){
		if (!$(this).hasClass('change_active')) {
			$('.change_active').removeClass('change_active');
			$(this).addClass('change_active');

			$('.page_wrapper').after('<script>document.cookie = "period='+$('.change_active').attr('data-val')+'";</script>');

			updateEarn(true);
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
		$('table[data-earn_table]').css('display', 'none');
		$('table[data-earn_table="'+$(this).val()+'"]').css('display', 'table');

		updateEarn();
	});




});