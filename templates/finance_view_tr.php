<tr data-id="<?=$array['id']?>" <?=$array[$i]['accepted'] == 1 && $array[$i]['paid'] == 1 ? 'class="tr_done"' : '';?>>
	<td><img src="/img/check.svg" width="10" height="8"><?=strftime('%B %Y', strtotime($array['date']))?></td>
	<td class="table_align_center"><?=$array['sum']?> &#8381;</td>

	<td class="table_align_center"><?=$array['report_done'] == '' ? '<span class="wait_report">Ожидается</span>' : '<a href="/service/reports/'.date("m.y", strtotime($array['date'])).'_done/'.$array['report_done'].'" target="_blank" class="finance_open_report">Открыть</a>'?></td>

	<td class="table_align_center"><?=$array['act_done'] == '' ? '<span class="wait_report">Ожидается</span>' : '<a href="/service/acts/'.date("m.y", strtotime($array['date'])).'_done/'.$array['act_done'].'" target="_blank" class="finance_open_report">Открыть</a>'?></td>

	<td class="table_align_center">
		<label>
			<input type="checkbox" <?=$array['accepted'] == 0 ? '' : 'checked="checked"'?> id="accepted_report" style="display: none;">
			<span class="view_user_check"><img src="/img/check.svg" alt="check" ></span>
		</label>
	</td>

	<td class="table_align_center">
		<label>
			<input type="checkbox" <?=$array['paid'] == 0 ? '' : 'checked="checked"'?> id="accepted_paid" style="display: none;">
			<span class="view_user_check"><img src="/img/check.svg" alt="check" ></span>
		</label>
	</td>
</tr>