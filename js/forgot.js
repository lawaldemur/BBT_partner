jQuery(document).ready(function($) {
	var reEmail = /^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/; // regexp email

	$('.step1 .next_step').on('click', function() {
		if (!reEmail.test($('#email_forgot').val())) {
			alert('Некорректный адрес эл. почты');
			return
		}

		$.ajax({
			url: '/ajax/forgot_email.php',
			type: 'POST',
			dataType: 'html',
			data: {email: $('#email_forgot').val()},
		})
		.done(function(res) {
			if (res == 'no') {
				alert('Некорректный адрес эл. почты');
				return
			} else if (res == 'yes') {
				$('.step1').hide();
				$('.step2').css('display', 'flex');
			}
		});
		
	});


	$('.step3 .next_step').on('click', function() {
		if ($('#password_forgot').val().length == 0) {
			alert('Введите новый пароль');
			return
		} else if ($('#password_forgot').val() != $('#repeat_forgot').val()) {
			alert('Пароли не совпадают');
			return
		}

		$.ajax({
			url: '/ajax/forgot_pass.php',
			type: 'POST',
			dataType: 'html',
			data: {
				id: $('#id').val(),
				pass: $('#password_forgot').val(),
				old_pass: $('#old_pass_md5').val(),
			},
		})
		.done(function() {
			$('.step3').hide();
			$('.step4').css('display', 'flex');
		});
	});

});