<?php include 'header.php'; ?>

<?php
$view = $dbc->query("SELECT * FROM `users` WHERE `id` = {$_GET['id']}");
$view = $view->fetch_array(MYSQLI_ASSOC);

$address = json_decode($view['data'])->general_address;
$address = $address == '' ? $view['city'] : $address;

$reports = $dbc->query("SELECT * FROM `reports` WHERE `from_id` = {$_GET['id']}");
?>

<div class="container">
	<div class="row">
		<div class="col command_list_col">
			<h1>Финансы</h1>
		</div>
	</div>

	<div class="row">
		<div class="col-12">
			<div class="referer"><a href="<?=$_SERVER['HTTP_REFERER']?>"><img src="/img/referer.svg" alt="referer"></a></div>
			<img src="/avatars/<?=$view['picture']?>" alt="avatar" class="avatar">
			<div class="finance_view_name">
				<div class="name"><?=$view['name']?><span class="count"></span></div>
				<div class="address"><?=$address?></div>
			</div>
		</div>
	</div>

	<!-- TABLE -->
	<ul>
		<?php foreach ($reports as $report): ?>
			<li><?=$report['date']?> | <?=$report['sum']?> | <?=$report['report'] == '' ? 'Ожидается' : $report['report']?> | <?=$report['act'] == '' ? 'Ожидается' : $report['act']?> | <?=$report['accepted']?> | <?=$report['paid']?></li>
		<?php endforeach ?>
	</ul>
	<!-- END TABLE -->
</div>







<?php include 'footer.php'; ?>