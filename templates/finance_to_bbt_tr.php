<tr data-id="<?=$array['id']?>" data-date="<?=$array['date']?>" <?=$array[$i]['accepted'] == 1 && $array[$i]['paid'] == 1 ? 'class="tr_done"' : '';?>>
	<td><img src="/img/check.svg" width="10" height="8"><?=$months[intval(date('n', strtotime($array['date'])))- 1].' '.strftime('%Y', strtotime($array['date']))?></td>
	<td class="table_align_center"><?=$array['sum']?> &#8381;</td>

	<td class="table_align_center">
		<a href="/service/reports/<?=date("m.y", strtotime($array['date']))?>_raw/<?=$array['report_raw']?>.pdf" target="_blank" class="finance_open_report">Открыть</a>
		<?=$array['report_done'] == '' ? '<button class="finance_upload_report">Загрузить</button>' : "<button class='finance_upload_report' data-document='{$array['report_done']}'><img src='/img/check.svg' width='10' height='8'> Готово</button>"?>
	</td>

	<td class="table_align_center">
		<a href="/service/acts/<?=date("m.y", strtotime($array['date']))?>_raw/<?=$array['act_raw']?>.pdf" target="_blank" class="finance_open_report">Открыть</a>
		<?=$array['act_done'] == '' ? '<button class="finance_upload_report">Загрузить</button>' : '<button class="finance_upload_report"><img src="/img/check.svg" width="10" height="8"> Готово</button>'?>
	</td>

	<td class="table_align_center">
		<?php if ($array['accepted'] == '1'): ?>
			<img src="/img/checked.svg" width="10" height="8">
		<?php endif ?>
	</td>

	<td class="table_align_center">
		<?php if ($array['paid'] == '1'): ?>
			<img src="/img/checked.svg" width="10" height="8">
		<?php endif ?>
	</td>
</tr>