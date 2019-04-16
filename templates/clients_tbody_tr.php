<tr data-id="client<?=$array['ID']?>">
	<?php
	table_command_name($array['picture'], $array['first_name'].' '.$array['last_name'], $array['city'], false);

	simple_td($array['parent']);
	simple_td($array['clients'], 'table_align_center');
	simple_td(strval($array['sold']).'&#8381;', 'table_align_center');
	simple_td(strval($array['bought']).'&#8381;', 'table_align_center');
	?>
</tr>