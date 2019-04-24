<tr data-id="<?=$array['ID']?>">
	<td class="table_command_name">
		<div class="command_picture_wrapp" style="background-image: url(<?=$array['picture']?>);"></div>
		<div class="command_name_wrap">
			<span class="command_name"><?=$array['name']?></span>
			<span class="command_city"><?=$array['city']?></span>
		</div>
	</td>
	<td class="table_align_center"><?=$array['clients']?></td>
	<td class="table_align_center"><?=$array['sold']?> &#8381;</td>
	<td class="table_align_center"><?=$array['bought']?> &#8381;</td>
</tr>