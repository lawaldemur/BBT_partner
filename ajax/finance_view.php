<?php
require '../db.php';
require '../php/access.php';
require '../connect_templates.php';
setlocale(LC_ALL, 'ru_RU.UTF-8');

if (!access(intval($_POST['user_id']), $dbc))
	exit('отказано в доступе');

require '../php/get_finance_view.php';
?>
////////=============////////
<?php
for ($i=$offset; $i < $limit && $i < count($array); $i++)
	finance_view_tr($array[$i]);
?>
////////=============////////
<?php
$page_file_name = 'finance.php';
$table_prefix = '&view='.$_POST['id'];

require '../php/pagination.php';
?>
<script>
	$('#accepted_report, #accepted_paid').change(function() {
		$.ajax({
			url: '/ajax/update_report.php',
			type: 'POST',
			dataType: 'html',
			data: {
				to: $('#user_id').val(),
				id: $(this).parent().parent().parent().data('id'),
				accepted_report: $('#accepted_report').prop('checked'),
				accepted_paid: $('#accepted_paid').prop('checked'),
			},
		});
		
	});

	if ($('.user_view_tbody_after_table_filters .active_page').length == 0)
		$('.user_view_tbody_after_table_filters .page').first().addClass('active_page');
</script>
////////=============////////
<?php echo $_POST['token']; ?>
