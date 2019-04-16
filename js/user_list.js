jQuery(document).ready(function($) {

	$('tbody tr td:first-child').click(function() {
		document.location = '/view.php?id=' + $(this).parent().data('id');
	});
	
	var token = '';
	$('.table_sizes > *').click(function() {
		if (!$(this).hasClass('table_size_active')) {
			$('.table_size_active').removeClass('table_size_active');
			$(this).addClass('table_size_active');

			var period = $('.change_active').attr('data-val');

			var rows_size = $('.table_size_active').text();
			var page = $('#active_page').val();
			token = new Date().getUTCMilliseconds();

			var sortColumn = $('.sortColumn_type').parent().data('column');
			if ($('.sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
			else  var sortColumnType = 'reverse';

			$.ajax({
				url: '/ajax/user_list.php',
				type: 'POST',
				dataType: 'html',
				data: {
					rows_size: rows_size,
					page: page,
					period: period,
					table: $('#users_table').attr('data-position'),
					sortColumn: sortColumn,
					sortColumnType: sortColumnType,
					command_partners: $('#command_partners').val(),
					search: $('#search_table_command').val(),
					request_uri: location.pathname + location.search,
					token: token,
					role: $('#role').val(),
				},
			})
			.done(function(res) {
				res = res.split('===================================================================================================');
				if (res[2] == token) {
					$('table tbody').html(res[0]);
					$('.pages_list').html(res[1]);
				
					$('table').after('<script>document.cookie = "rows='+rows_size+'";</script>');
					$('.pagination_list .page').last().addClass('last_pagination');

					if ($('#users_table').attr('data-position') == 'command')
						$('.after_table_filters').append('<script>$("tbody tr td:first-child").click(function(){document.location="/view.php?id="+$(this).parent().data("id")});$("img.control_command").on("click",function(){$("#new_command_password").attr("type","password"),$("#overlay_form, .form_control_command").show(),$("#new_command_id").val($(this).parent().parent().data("id")),$("#new_command_name").val($(this).parent().parent().data("name")),$("#new_command_region").val($(this).parent().parent().data("region")),$("#new_get_digital").val($(this).parent().parent().data("digital_percent")),$("#new_get_audio").val($(this).parent().parent().data("audio_percent")),$("#new_command_email").val($(this).parent().parent().data("email")),$("#new_command_password").val("#".repeat(parseInt($(this).parent().parent().data("pass_length"))))});</script>');
					else
						$('.after_table_filters').append('<script>$("tbody tr td:first-child").click(function(){document.location="/view.php?id="+$(this).parent().data("id")});$("img.control_partner").on("click",function(){$("#new_partner_password").attr("type","password"),$("#overlay_form, .form_control_partner").show(),$("#new_partner_id").val($(this).parent().parent().data("id")),$("#new_partner_name").val($(this).parent().parent().data("name")),$("#new_partner_region").val($(this).parent().parent().data("region")),$("#new_get_digital").val($(this).parent().parent().data("digital_percent")),$("#new_get_audio").val($(this).parent().parent().data("audio_percent")),$("#new_partner_email").val($(this).parent().parent().data("email")),$("#new_partner_password").val("#".repeat(parseInt($(this).parent().parent().data("pass_length"))))});</script>');
				}
			});
			
		}
	});


	$('#search_table_command').on('input', function(event) {
		var period = $('.change_active').attr('data-val');

		var rows_size = $('.table_size_active').text();
		var page = $('#active_page').val();
		token = new Date().getUTCMilliseconds();

		var sortColumn = $('.sortColumn_type').parent().data('column');
		if ($('.sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';

		$.ajax({
			url: '/ajax/user_list.php',
			type: 'POST',
			dataType: 'html',
			data: {
				rows_size: rows_size,
				page: page,
				period: period,
				table: $('#users_table').attr('data-position'),
				sortColumn: sortColumn,
				sortColumnType: sortColumnType,
				command_partners: $('#command_partners').val(),
				search: $('#search_table_command').val(),
				request_uri: location.pathname + location.search,
				token: token,
				role: $('#role').val(),
			},
		})
		.done(function(res) {
			res = res.split('===================================================================================================');
			if (res[2] == token) {
				$('table tbody').html(res[0]);
				$('.pages_list').html(res[1]);
				$('.pagination_list .page').last().addClass('last_pagination');

				if ($('#users_table').attr('data-position') == 'command')
					$('.after_table_filters').append('<script>$("tbody tr td:first-child").click(function(){document.location="/view.php?id="+$(this).parent().data("id")});$("img.control_command").on("click",function(){$("#new_command_password").attr("type","password"),$("#overlay_form, .form_control_command").show(),$("#new_command_id").val($(this).parent().parent().data("id")),$("#new_command_name").val($(this).parent().parent().data("name")),$("#new_command_region").val($(this).parent().parent().data("region")),$("#new_get_digital").val($(this).parent().parent().data("digital_percent")),$("#new_get_audio").val($(this).parent().parent().data("audio_percent")),$("#new_command_email").val($(this).parent().parent().data("email")),$("#new_command_password").val("#".repeat(parseInt($(this).parent().parent().data("pass_length"))))});</script>');
				else
					$('.after_table_filters').append('<script>$("tbody tr td:first-child").click(function(){document.location="/view.php?id="+$(this).parent().data("id")});$("img.control_partner").on("click",function(){$("#new_partner_password").attr("type","password"),$("#overlay_form, .form_control_partner").show(),$("#new_partner_id").val($(this).parent().parent().data("id")),$("#new_partner_name").val($(this).parent().parent().data("name")),$("#new_partner_region").val($(this).parent().parent().data("region")),$("#new_get_digital").val($(this).parent().parent().data("digital_percent")),$("#new_get_audio").val($(this).parent().parent().data("audio_percent")),$("#new_partner_email").val($(this).parent().parent().data("email")),$("#new_partner_password").val("#".repeat(parseInt($(this).parent().parent().data("pass_length"))))});</script>');

			}
		});
			
	});




	$('.change_date > *:not(.custom_date_change)').click(function() {
		if (!$(this).hasClass('change_active')) {
			$('.change_active').removeClass('change_active');
			$(this).addClass('change_active');

			var sortColumn = $('.sortColumn_type').parent().data('column');
			if ($('.sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
			else  var sortColumnType = 'reverse';

			var period = $('.change_active').attr('data-val');

			var rows_size = $('.table_size_active').text();
			var page = $('#active_page').val();
			token = new Date().getUTCMilliseconds();

			$.ajax({
				url: '/ajax/user_list.php',
				type: 'POST',
				dataType: 'html',
				data: {
					rows_size: rows_size,
					page: page,
					period: period,
					table: $('#users_table').attr('data-position'),
					sortColumn: sortColumn,
					sortColumnType: sortColumnType,
					command_partners: $('#command_partners').val(),
					search: $('#search_table_command').val(),
					request_uri: location.pathname + location.search,
					token: token,
					role: $('#role').val(),
				},
			})
			.done(function(res) {
				res = res.split('===================================================================================================');
				if (res[2] == token) {
					$('table tbody').html(res[0]);
					$('.pages_list').html(res[1]);
				
					$('table').after('<script>document.cookie = "period='+period+'";</script>');
					$('.pagination_list .page').last().addClass('last_pagination');

					if ($('#users_table').attr('data-position') == 'command')
						$('.after_table_filters').append('<script>$("tbody tr td:first-child").click(function(){document.location="/view.php?id="+$(this).parent().data("id")});$("img.control_command").on("click",function(){$("#new_command_password").attr("type","password"),$("#overlay_form, .form_control_command").show(),$("#new_command_id").val($(this).parent().parent().data("id")),$("#new_command_name").val($(this).parent().parent().data("name")),$("#new_command_region").val($(this).parent().parent().data("region")),$("#new_get_digital").val($(this).parent().parent().data("digital_percent")),$("#new_get_audio").val($(this).parent().parent().data("audio_percent")),$("#new_command_email").val($(this).parent().parent().data("email")),$("#new_command_password").val("#".repeat(parseInt($(this).parent().parent().data("pass_length"))))});</script>');
					else
						$('.after_table_filters').append('<script>$("tbody tr td:first-child").click(function(){document.location="/view.php?id="+$(this).parent().data("id")});$("img.control_partner").on("click",function(){$("#new_partner_password").attr("type","password"),$("#overlay_form, .form_control_partner").show(),$("#new_partner_id").val($(this).parent().parent().data("id")),$("#new_partner_name").val($(this).parent().parent().data("name")),$("#new_partner_region").val($(this).parent().parent().data("region")),$("#new_get_digital").val($(this).parent().parent().data("digital_percent")),$("#new_get_audio").val($(this).parent().parent().data("audio_percent")),$("#new_partner_email").val($(this).parent().parent().data("email")),$("#new_partner_password").val("#".repeat(parseInt($(this).parent().parent().data("pass_length"))))});</script>');

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

		$('.page_wrapper').after('<script>document.cookie = "calendarText='+$('.custom_date_change span').text()+'";</script>');
		$('.page_wrapper').after('<script>document.cookie = "period='+$('.custom_date_change').attr('data-val')+'";</script>');

		var sortColumn = $('.sortColumn_type').parent().data('column');
		if ($('.sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
		else  var sortColumnType = 'reverse';

		var period = $('.change_active').attr('data-val');

		var rows_size = $('.table_size_active').text();
		var page = $('#active_page').val();
		token = new Date().getUTCMilliseconds();

		$.ajax({
			url: '/ajax/user_list.php',
			type: 'POST',
			dataType: 'html',
			data: {
				rows_size: rows_size,
				page: page,
				period: period,
				table: $('#users_table').attr('data-position'),
				sortColumn: sortColumn,
				sortColumnType: sortColumnType,
				command_partners: $('#command_partners').val(),
				search: $('#search_table_command').val(),
				request_uri: location.pathname + location.search,
				token: token,
				role: $('#role').val(),
			},
		})
		.done(function(res) {
			res = res.split('===================================================================================================');
			if (res[2] == token) {
				$('table tbody').html(res[0]);
				$('.pages_list').html(res[1]);
			
				$('table').after('<script>document.cookie = "period='+period+'";</script>');
				$('.pagination_list .page').last().addClass('last_pagination');

				if ($('#users_table').attr('data-position') == 'command')
					$('.after_table_filters').append('<script>$("tbody tr td:first-child").click(function(){document.location="/view.php?id="+$(this).parent().data("id")});$("img.control_command").on("click",function(){$("#new_command_password").attr("type","password"),$("#overlay_form, .form_control_command").show(),$("#new_command_id").val($(this).parent().parent().data("id")),$("#new_command_name").val($(this).parent().parent().data("name")),$("#new_command_region").val($(this).parent().parent().data("region")),$("#new_get_digital").val($(this).parent().parent().data("digital_percent")),$("#new_get_audio").val($(this).parent().parent().data("audio_percent")),$("#new_command_email").val($(this).parent().parent().data("email")),$("#new_command_password").val("#".repeat(parseInt($(this).parent().parent().data("pass_length"))))});</script>');
				else
					$('.after_table_filters').append('<script>$("tbody tr td:first-child").click(function(){document.location="/view.php?id="+$(this).parent().data("id")});$("img.control_partner").on("click",function(){$("#new_partner_password").attr("type","password"),$("#overlay_form, .form_control_partner").show(),$("#new_partner_id").val($(this).parent().parent().data("id")),$("#new_partner_name").val($(this).parent().parent().data("name")),$("#new_partner_region").val($(this).parent().parent().data("region")),$("#new_get_digital").val($(this).parent().parent().data("digital_percent")),$("#new_get_audio").val($(this).parent().parent().data("audio_percent")),$("#new_partner_email").val($(this).parent().parent().data("email")),$("#new_partner_password").val("#".repeat(parseInt($(this).parent().parent().data("pass_length"))))});</script>');

			}
		});
	});


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

			var sortColumn = $('.sortColumn_type').parent().data('column');
			if ($('.sortColumn_type').hasClass('sort_upper')) var sortColumnType = 'default';
			else  var sortColumnType = 'reverse';

			var period = $('.change_active').attr('data-val');

			var rows_size = $('.table_size_active').text();
			var page = $('#active_page').val();
			token = new Date().getUTCMilliseconds();

			$.ajax({
				url: '/ajax/user_list.php',
				type: 'POST',
				dataType: 'html',
				data: {
					rows_size: rows_size,
					page: page,
					period: period,
					table: $('#users_table').attr('data-position'),
					sortColumn: sortColumn,
					sortColumnType: sortColumnType,
					command_partners: $('#command_partners').val(),
					search: $('#search_table_command').val(),
					request_uri: location.pathname + location.search,
					token: token,
					role: $('#role').val(),
				},
			})
			.done(function(res) {
				res = res.split('===================================================================================================');
				if (res[2] == token) {
					$('table tbody').html(res[0]);
					$('.pages_list').html(res[1]);
				
					$('.pagination_list .page').last().addClass('last_pagination');

					if ($('#users_table').attr('data-position') == 'command')
						$('.after_table_filters').append('<script>$("tbody tr td:first-child").click(function(){document.location="/view.php?id="+$(this).parent().data("id")});$("img.control_command").on("click",function(){$("#new_command_password").attr("type","password"),$("#overlay_form, .form_control_command").show(),$("#new_command_id").val($(this).parent().parent().data("id")),$("#new_command_name").val($(this).parent().parent().data("name")),$("#new_command_region").val($(this).parent().parent().data("region")),$("#new_get_digital").val($(this).parent().parent().data("digital_percent")),$("#new_get_audio").val($(this).parent().parent().data("audio_percent")),$("#new_command_email").val($(this).parent().parent().data("email")),$("#new_command_password").val("#".repeat(parseInt($(this).parent().parent().data("pass_length"))))});</script>');
					else
						$('.after_table_filters').append('<script>$("tbody tr td:first-child").click(function(){document.location="/view.php?id="+$(this).parent().data("id")});$("img.control_partner").on("click",function(){$("#new_partner_password").attr("type","password"),$("#overlay_form, .form_control_partner").show(),$("#new_partner_id").val($(this).parent().parent().data("id")),$("#new_partner_name").val($(this).parent().parent().data("name")),$("#new_partner_region").val($(this).parent().parent().data("region")),$("#new_get_digital").val($(this).parent().parent().data("digital_percent")),$("#new_get_audio").val($(this).parent().parent().data("audio_percent")),$("#new_partner_email").val($(this).parent().parent().data("email")),$("#new_partner_password").val("#".repeat(parseInt($(this).parent().parent().data("pass_length"))))});</script>');

				}
			});
		}
	});




});