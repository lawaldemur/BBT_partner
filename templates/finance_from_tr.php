<tr data-id="<?=$array['id']?>">
	<td class="table_command_name">
		<?php if ($array['view'] == 1): ?>
			<div class="viewed_icon"></div>
		<?php endif ?>
		<div class="command_picture_wrapp" style="background-image: url(/avatars/<?=$array['picture']?>);"></div>
		<div class="command_name_wrap">
			<span class="command_name"><?=$array['name']?></span>
			<span class="command_city"><?=$array['city']?></span>
		</div>
	</td>
	<td class="table_align_center"><?=$array['count']?></td>
</tr>