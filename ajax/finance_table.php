<?php
require '../db.php';
require '../php/access.php';
require '../connect_templates.php';

if (!access(intval($_POST['to']), $dbc))
	exit('отказано в доступе');

require '../php/get_finance_table.php';

if ($role == 'ББТ')
	for ($i=$offset; $i < $limit && $i < count($array); $i++)
		finance_from_tr($array[$i]);
?>
===================================================================================================
<?php
$page_file_name = 'finance.php';
$table_prefix = '&table=from_commands';

require '../php/pagination.php';
?>
<script>
	$('.finance_after_table_filters .pagination_list .page').last().addClass('last_pagination');
</script>
<script>
	var token = '';
	$('table[data-table="link"] tbody tr').click(function() {
		$('.finance_row').hide();

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
				user_id: $('#user_id').val(),
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
</script>
===================================================================================================
<?php echo $_POST['token']; ?>