<?php
// блок уведомления
notification();

// просмотрщик документов
document_viewer();
?>

<div class="container">
	<?php
	profile_list_row($user_id, $role);

	if ($role == 'ББТ')
		profile_bbt($data);
	elseif($role == 'Команда') {
		$data['name'] = $name;
		$data['digit_perc'] = $digit_perc;
		$data['audio_perc'] = $audio_perc;
		$data['picture'] = $picture;

		profile_command($data);
	}
	elseif($role == 'Партнер') {
		$data['name'] = $name;
		$data['code'] = $code;
		$data['digit_perc'] = $digit_perc;
		$data['audio_perc'] = $audio_perc;
		$data['picture'] = $picture;

		profile_partner($data);
	}
	?>
</div>