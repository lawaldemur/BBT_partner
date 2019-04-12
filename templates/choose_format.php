<div class="choose_format">
	<?php if (!$format || $format == 'all' || $format == 'digital'): ?>
		<div class="choose choose_active" id="digital">
	<?php else: ?>
		<div class="choose" id="digital">
	<?php endif ?>
		<img src="/img/choose_digit.svg" alt="choose">
		<img src="/img/choose_digit_1.svg" alt="choose_active">
	</div>

	<?php if (!isset($format) || $format == 'all' || $format == 'audio'): ?>
		<div class="choose choose_active" id="audio">
	<?php else: ?>
		<div class="choose" id="audio">
	<?php endif ?>
		<img src="/img/choose_audio.svg" alt="choose">
		<img src="/img/choose_audio_1.svg" alt="choose_active">
	</div>
</div>