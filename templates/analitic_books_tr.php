<tr>
	<?php
	if ($sort == 'bydate')
		simple_td(date("d.m.Y", strtotime($array['date'])));

	product_name_td($array['img'], $array['name'], $array['other']);

	$rub = ' &#8381;';
	simple_td($array['format']);
	simple_td(strval($array['summ'] / $array['price']));
	simple_td(strval($array['price']).$rub);
	simple_td(strval($array['summ']).$rub);

	if ($role == 'ББТ')
		simple_td(strval($array['to_bbt']).$rub);
	elseif ($role == 'Команда')
		simple_td(strval($array['to_command']).$rub);
	elseif ($role == 'Партнер')
		simple_td(strval($array['to_partner']).$rub);
	?>
</tr>