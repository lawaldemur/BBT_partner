<div class="container">
	<div class="row">
		<div class="col-12">
			<?php
			if (!isset($_GET['reset']))
				forgot_pass_step_1();
			else
				forgot_pass_step_2($correct['id'], $correct['password']);
			?>
		</div>
	</div>
</div>