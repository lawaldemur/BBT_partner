</div>
<!-- END CONTENT WRAPPER -->

<!-- FOOTER -->
<footer class="container-fluid">
	<div class="container">
		<div class="row">
			<div class="col-3">
				<img src="/img/footer-logo.svg" alt="footer_logo" width="32">
				<div class="footer_logo_desc">© 2018 BBT-online</div>
			</div>
			<?php if ($role == 'Команда' || $role == 'Партнер' || !$role): ?>
			<div class="offset-4 col-5">
				<a href="http://bbt-online.ru/offer/">Правовая информация</a>
				<a href="http://bbt-online.ru/faq/">Вопрос-ответ</a>
				<a href="http://bbt-online.ru/contacts/">Контакты</a>
			</div>
			<?php endif ?>
		</div>
	</div>
</footer>
<!-- END FOOTER -->

</div>
<!-- END PAGE WRAPPER -->
<?=$footer_connect?>

</body>
</html>
