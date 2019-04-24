<tr data-id="<?=$array['id']?>">
	<td class="table_command_name">
		<div class="command_picture_wrapp" style="background-image: url(/avatars/<?=$array['picture']?>);"></div>
		<div class="command_name_wrap">
			<span class="command_name"><?=$array['name']?></span>
			<span class="command_city"><?=$array['city']?></span>
		</div>
	</td>
	<td class="table_align_center"><?=$array['clients']?></td>
	<td class="table_align_center"><?=$array['summ_sold']?> &#8381;</td>
	<td class="table_align_center"><?=$array['summ_get']?> &#8381;</td>
	<?php if ($array['summ_wait'] != 0): ?>
		<td class="table_align_center" style="color: #ff441f;"><?=$array['summ_wait']?> &#8381;</td>
	<?php else: ?>
		<td class="table_align_center"><?=$array['summ_wait']?> &#8381;</td>
	<?php endif ?>
</tr>