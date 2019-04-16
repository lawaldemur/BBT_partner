<tr data-id="<?=$array['id']?>" data-email="<?=$array['login']?>" data-digital_percent="<?=$array['digital_percent']?>" data-audio_percent="<?=$array['audio_percent']?>" data-pass_length="<?=strlen($array['password'])?>" data-name="<?=$array['name']?>" data-region="<?=$array['city']?>">
	<?php
	table_command_name($array['picture'], $array['name'], $role == 'Команда' ? $array['city'] : $array['parent']);

	$rub = '&#8381;';
	simple_td($array['clients'], 'table_align_center');
	simple_td(strval($array['summ_sold']).$rub, 'table_align_center');
	simple_td(strval($array['digital_percent']).'%', 'table_align_center');
	simple_td(strval($array['summ_get']).$rub, 'table_align_center');

	if ($array['summ_wait'] != 0)
		simple_td(strval($array['summ_wait']).$rub, 'table_align_center', 'color: #ff441f;');
	else
		simple_td(strval($array['summ_wait']).$rub, 'table_align_center');

	if ($role == 'Команда')
		simple_td('<img src="/img/control.svg" alt="control_partner" class="control_partner">', 'table_align_center');
	?>
</tr>