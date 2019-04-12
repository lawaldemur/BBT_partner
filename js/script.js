jQuery(document).ready(function($) {



	$('.logged_header_logo').on('click', function() {
		document.location.href = '/';
	});

	// var picker = new Lightpick({
	// 	field: document.getElementById('datepicker'),
	// 	singleDate: false,
	// 	numberOfColumns: 3,
	// 	numberOfMonths: 3,
	// 	onSelect: function(start, end){
	// 		var str = '';
	// 		str += start ? start.format('Do MMMM YYYY') + ' to ' : '';
	// 		str += end ? end.format('Do MMMM YYYY') : '...';
	// 		document.getElementById('result-5').innerHTML = str;
	// 	}
	// });

	$('.close_overlay_form').click(function(event) {
		event.preventDefault();
		$('.entrance_close').trigger('click');
	});

	// COMMON
	$('input').on('input', function() {
		if ($(this).hasClass('error_input'))
			$(this).removeClass('error_input');
	});

	var rePhone = /^((8|\+7)[\-]?)?(\(?\d{3}\)?[\-]?)?[\d\-]{7,10}$/; // regexp phone numer
	var reEmail = /^[-._a-z0-9]+@(?:[a-z0-9][-a-z0-9]+\.)+[a-z]{2,6}$/; // regexp email

	// header profile dropdown
	var profile_menu_close = false;
	$('.content_wrapper, header *:not(.dropdown_entrance_icon, .dropdown_entrance_icon *, img[alt="entrance_icon"])').mouseenter(function() {
		profile_menu_hover = true;
		if ($('.dropdown_entrance_icon').css('display') == 'block') {
			setTimeout( function(){
				if (profile_menu_hover)
					$('.dropdown_entrance_icon').removeClass('dropdown_entrance_icon_opened');
			setTimeout(function () {
				$('.dropdown_entrance_icon').fadeOut(100);
			});
			}, 300);
		}
	});
	$('.dropdown_entrance_icon, .dropdown_entrance_icon *, img[alt="entrance_icon"]').mouseenter(function() {
		profile_menu_hover = false;
	});
	var profile_menu_hover = false;
	$('img[alt="entrance_icon"]').mouseenter(function() {
		profile_menu_hover = true;
		setTimeout( function(){
			if (profile_menu_hover)
				$('.dropdown_entrance_icon').fadeIn(100);
			setTimeout(function () {
				$('.dropdown_entrance_icon').addClass('dropdown_entrance_icon_opened');
			});
		}, 200);
	});
	$('img[alt="entrance_icon"]').mouseleave(function() {
		profile_menu_hover = false;
	});
	// END header profile dropdown


	// logout
	$('.dropdown_entrance_icon_logout').click(function() {
		$.ajax({
			url: '/ajax/logout.php',
			type: 'POST',
			dataType: 'html',
		})
		.done(function() {
			// remove cookie
			document.cookie = 'logged' + '=;expires=Thu, 01 Jan 1970 00:00:01 GMT;';
			// redirect to login page
			document.location.href = '/';
		});
		
	});
	// END logout

	
	// ENTRANCE PAGE ==========================================================
	$('form#entrance').submit(function(event) {
		event.preventDefault();

		$.ajax({
			url: '/ajax/user_exist.php',
			type: 'POST',
			dataType: 'html',
			data: {
				login: $('#login').val(),
				password: $('#password').val(),
				remember: $('#remember_me').prop('checked') == true
			},
		})
		.done(function(result) {
			if (result == 'incorrect data') {
				// error
				// alert('Неверный логин или пароль');
				$('#login, #password').addClass('error_input');
			} else if (result == 'session') {
				// success
				document.location.href = '/analitic.php';
			} else if (result.split('|')[0] == 'cookie' || result.split('|')[0] == 'session') {
				// show documents
				if (result.split('|')[0] == 'cookie') {
					$('.partner_accept_form').attr({
						'data-method': 'cookie',
						'data-login': result.split('|')[1]
					});
				} else {
					$('.partner_accept_form').attr({
						'data-method': 'session',
						'data-login': result.split('|')[1]
					});
				}
				$('.partner_accept_form, #overlay_form').show();
			} else {
				var now = new Date();
               	now.setTime(now.getTime() + 1 * 3600 * 1000 * 24 * 30);
				document.cookie = "logged=" + result + '; expires=' + now.toGMTString() + ';';
				// success
				document.location.href = '/analitic.php';
			}
		})
		.fail(function() {
			// error
		});
	});
	// END ENTRANCE PAGE ==========================================================
	

	// ADD COMAND FORM
	// allow to input only numbers
	$('#get_digital, #get_audio').keypress(function(event) {
		event = event || window.event;
	  	if (event.charCode && event.charCode!=0 && event.charCode!=46 && (event.charCode < 48 || event.charCode > 57) )
		    return false;
	});

	// open add_command form
	$('#add_command_btn').click(function() {
		$('#overlay_form, .form_add_command').show();
	});

	$('#add_command_submit').click(function() {
		var incorrect = false;

		// if field is empty
		if ($('#command_name').val().length == 0) {
			$('#command_name').addClass('error_input');
			incorrect = true;
		} else
			$('#command_name').removeClass('error_input');
		if ($('#command_region').val().length == 0) {
			$('#command_region').addClass('error_input');
			incorrect = true;
		} else
			$('#command_region').removeClass('error_input');
		if ($('#get_digital').val().length == 0) {
			$('#get_digital').addClass('error_input');
			incorrect = true;
		} else
			$('#get_digital').removeClass('error_input');
		if ($('#get_audio').val().length == 0) {
			$('#get_audio').addClass('error_input');
			incorrect = true;
		} else
			$('#get_audio').removeClass('error_input');
		if ($('#command_email').val().length == 0) {
			$('#command_email').addClass('error_input');
			incorrect = true;
		} else
			$('#command_email').removeClass('error_input');

		// if email incorrect
		if (!reEmail.test($('#command_email').val())) {
			// alert('Некорректный email');
			$('#command_email').addClass('error_input');
			incorrect = true;
		} else
			$('#command_email').removeClass('error_input');

		// dont show next form if anything wrong
		if (!incorrect) {
			$('.form_add_command').hide();
			$('.form_add_command_pass_request').show();
		}
	});

	$('.form_add_command_pass_request .request_pass_cancel').click(function() {
		$('#overlay_form, .form_add_command_pass_request').hide();
	});

	// add command to database on click to btn 'ok' in check pass form
	$('.form_add_command_pass_request .request_pass_ok').on('click', function() {
		if ($('.form_add_command_pass_request').attr('data-task') == 'save_data' ||
			$('.form_add_command_pass_request').attr('data-task') == 'show_pass' ||
			$('.form_add_command_pass_request').attr('data-task') == 'remove_command')
			return;

		var name = $('#command_name').val();
		var region = $('#command_region').val();
		var get_digital = $('#get_digital').val();
		var get_audio = $('#get_audio').val();
		var email = $('#command_email').val();
		var login = $('#user_login').val();
		var id = $('#user_id').val();
		var request_pass = $('#request_pass').val();

		// if pass field is empty
		if (request_pass.length == 0) {
			$('#request_pass').addClass('error_input');
			return;
		}

		// call ajax
		$.ajax({
			url: '/ajax/add_command.php',
			type: 'POST',
			dataType: 'html',
			data: {
				name: name,
				region: region,
				get_digital: get_digital,
				get_audio: get_audio,
				email: email,
				request_pass: request_pass,
				login: login,
				id: id,
			},
		})
		.done(function(res) {
			if (res == 'incorrect password') {
				// console.log('error: ' + res);
				$('#request_pass').addClass('error_input');
			} else {
				// console.log("command added: " + (res == 1));
				// change notification text
				$('#notification_text').text('Команда успешно создана');
				// show notification on 5s
				$('#notification').addClass('active_notification');
				setTimeout(function () {
					$('#notification').removeClass('active_notification');
				}, 5000);
				// hide form and overlay
				$('#overlay_form, .form_add_command_pass_request').hide();
			}
		});
		
	});
	// END ADD COMAND FORM


	// CLOSE MODAL FORM
	$('.close_form:not(.entrance_close)').click(function() {
		$(this).parent().hide();
		$('#overlay_form').hide();
	});
	$('#overlay_form:not(.close_overlay_form)').click(function() {
		$('.close_form').trigger('click');
	});
	// END CLOSE MODAL FORM



	// ADD PARTNER FORM
	// open add_partner form
	$('#add_partner_btn').click(function() {
		$('#overlay_form, .form_add_partner').show();
	});

	// add partner to database on click to btn 'ok' in check pass form
	$('#add_partner_submit').click(function() {
		var incorrect = false;

		// if field is empty
		if ($('#partner_name').val().length == 0) {
			$('#partner_name').addClass('error_input');
			incorrect = true;
		} else
			$('#partner_name').removeClass('error_input');
		if ($('#partner_region').val().length == 0) {
			$('#partner_region').addClass('error_input');
			incorrect = true;
		} else
			$('#partner_region').removeClass('error_input');
		if ($('#get_digital').val().length == 0) {
			$('#get_digital').addClass('error_input');
			incorrect = true;
		} else
			$('#get_digital').removeClass('error_input');
		if ($('#get_audio').val().length == 0) {
			$('#get_audio').addClass('error_input');
			incorrect = true;
		} else
			$('#get_audio').removeClass('error_input');
		if ($('#partner_email').val().length == 0) {
			$('#partner_email').addClass('error_input');
			incorrect = true;
		} else
			$('#partner_email').removeClass('error_input');

		// if email incorrect
		if (!reEmail.test($('#partner_email').val())) {
			// alert('Некорректный email');
			$('#partner_email').addClass('error_input');
			incorrect = true;
		} else
			$('#partner_email').removeClass('error_input');

		// stop
		if (incorrect)
			return;

		var name = $('#partner_name').val();
		var region = $('#partner_region').val();
		var get_digital = $('#get_digital').val();
		var get_audio = $('#get_audio').val();
		var email = $('#partner_email').val();
		var login = $('#user_login').val();
		var id = $('#user_id').val();

		// call ajax
		$.ajax({
			url: '/ajax/add_partner.php',
			type: 'POST',
			dataType: 'html',
			data: {
				name: name,
				region: region,
				get_digital: get_digital,
				get_audio: get_audio,
				email: email,
				login: login,
				id: id,
			},
		})
		.done(function(res) {
			// change notification text
			$('#notification_text').text('Партнер успешно создан');
			// show notification on 5s
			$('#notification').addClass('active_notification');
			setTimeout(function () {
				$('#notification').removeClass('active_notification');
			}, 5000);
			// hide form and overlay
			$('#overlay_form, .form_add_partner').hide();
		});
		
	});
	// END ADD PARTNER FORM



	// CONTROL COMMAND FORM
	var control_el = $('img.control_command');
	control_el.on('click', function() {
		$('#new_command_password').attr('type', 'password');
		$('#overlay_form, .form_control_command').show();

		// content field in form
		$('#new_command_id').val($(this).parent().parent().data('id'));
		$('#new_command_name').val($(this).parent().parent().data('name'));
		$('#new_command_region').val($(this).parent().parent().data('region'));
		$('#new_get_digital').val($(this).parent().parent().data('digital_percent'));
		$('#new_get_audio').val($(this).parent().parent().data('audio_percent'));
		$('#new_command_email').val($(this).parent().parent().data('email'));
		$('#new_command_password').val('#'.repeat(parseInt($(this).parent().parent().data('pass_length'))));
	});

	// save data
	$('#control_command_submit').click(function() {
		var incorrect = false;

		// if field is empty
		if ($('#new_command_name').val().length == 0) {
			$('#new_command_name').addClass('error_input');
			incorrect = true;
		} else
			$('#new_command_name').removeClass('error_input');
		if ($('#new_command_region').val().length == 0) {
			$('#new_command_region').addClass('error_input');
			incorrect = true;
		} else
			$('#new_command_region').removeClass('error_input');
		if ($('#new_get_digital').val().length == 0) {
			$('#new_get_digital').addClass('error_input');
			incorrect = true;
		} else
			$('#new_get_digital').removeClass('error_input');
		if ($('#new_get_audio').val().length == 0) {
			$('#new_get_audio').addClass('error_input');
			incorrect = true;
		} else
			$('#new_get_audio').removeClass('error_input');
		if ($('#new_command_email').val().length == 0) {
			$('#new_command_email').addClass('error_input');
			incorrect = true;
		} else
			$('#new_command_email').removeClass('error_input');
		if ($('#new_command_password').val().length == 0) {
			$('#new_command_password').addClass('error_input');
			incorrect = true;
		} else
			$('#new_command_password').removeClass('error_input');

		// if email incorrect
		if (!reEmail.test($('#new_command_email').val())) {
			// alert('Некорректный email');
			$('#new_command_email').addClass('error_input');
			incorrect = true;
		} else
			$('#new_command_email').removeClass('error_input');

		// dont show next form if anything wrong
		if (!incorrect) {
			$('.form_control_command').hide();
			$('.form_add_command_pass_request').attr('data-task', 'save_data').show();
		}
	});

	// save data/show pass/remove command if user pres ok and password is right
	$('.form_add_command_pass_request .request_pass_ok').click(function() {
		var login = $('#user_login').val();
		var request_pass = $('#request_pass').val();

		// if pass field is empty
		if (request_pass.length == 0) {
			$('#request_pass').addClass('error_input');
			return;
		}

		// call ajax
		$.ajax({
			url: '/ajax/check_password.php',
			type: 'POST',
			dataType: 'html',
			data: {
				request_pass: request_pass,
				login: login,
			},
		})
		.done(function(res) {
			if (res == 1) {
				// password is correct, do anything
				var task = $('.form_add_command_pass_request').attr('data-task');
				$('.form_add_command_pass_request').removeAttr('data-task');

				// update command data
				if (task == 'save_data') {
					$.ajax({
						url: '/ajax/update_command.php',
						type: 'POST',
						dataType: 'html',
						data: {
							command_id: $('#new_command_id').val(),
							command_name: $('#new_command_name').val(),
							command_region: $('#new_command_region').val(),
							get_digital: $('#new_get_digital').val(),
							get_audio: $('#new_get_audio').val(),
							command_email: $('#new_command_email').val(),
							command_password: $('#new_command_password').val(),
						},
					})
					.done(function(res) {
						// change notification text
						$('#notification_text').text('Данные успешно сохранены');
						// show notification on 5s
						$('#notification').addClass('active_notification');
						setTimeout(function () {
							$('#notification').removeClass('active_notification');
						}, 5000);
						// hide form and overlay
						$('#overlay_form, .form_add_command_pass_request').hide();
					});
				} else if (task == 'remove_command') {
					$.ajax({
						url: '/ajax/remove_command.php',
						type: 'POST',
						dataType: 'html',
						data: {
							command_id: $('#new_command_id').val(),
						},
					})
					.done(function(res) {
						if (res == 'the command has partners') {
							$('.form_add_command_pass_request').hide();
							$('#overlay_form, .form_delete_command_error').show();
						} else {
							$('.form_add_command_pass_request').hide();
							$('#overlay_form, .form_confirm_delete').show();
							$('.form_confirm_delete').attr('data-id', $('#new_command_id').val());
						}
					});
				} else if (task == 'show_pass') {
					$.ajax({
						url: '/ajax/show_pass.php',
						type: 'POST',
						dataType: 'html',
						data: {id: $('#new_command_id').val()},
					})
					.done(function(res) {
						$('.form_add_command_pass_request').hide();
						$('#overlay_form, .form_control_command').show();
						$('#new_command_password').attr('type', 'text').val(res);
					});
				}
			} else {
				// password is not correct
				$('#request_pass').addClass('error_input');
			}
		});
	});

	// hide form if user press cancel
	$('.form_add_command_pass_request .request_pass_cancel').click(function() {
		$('#overlay_form, .form_add_command_pass_request').hide();
	});

	var allow_pass = false;
	$('img.pass_eye').click(function() {
		if (!allow_pass) {
			$('.form_control_command, .form_control_partner').hide();
			$('.form_add_command_pass_request, .form_control_partner_pass_request').attr('data-task', 'show_pass').show();
		}
	});

	$('#delete_command').click(function() {
		$('.form_control_command').hide();
		$('.form_add_command_pass_request').attr('data-task', 'remove_command').show();
	});

	$('#delete_partner').click(function() {
		$('.form_control_partner').hide();
		$('.form_control_partner_pass_request').attr('data-task', 'remove_partner').show();
	});

	$('.delete_command_error_ok').click(function() {
		$('#overlay_form, .form_delete_command_error').hide();
		$('.form_add_command_pass_request').attr('data-task', '').hide();
	});

	$('.confirm_delete_cancel').click(function() {
		$('#overlay_form, .form_confirm_delete').hide();
	});

	$('.confirm_delete_ok').click(function() {
		$.ajax({
			url: '/ajax/delete_user.php',
			type: 'POST',
			dataType: 'html',
			data: {
				id: $('.form_confirm_delete').attr('data-id'),
				parent: $('#user_id').val(),
			},
		})
		.done(function() {
			$('#overlay_form, .form_confirm_delete').hide();

			// change notification text
			$('#notification_text').text('Команда успешно удалена');
			// show notification on 5s
			$('#notification').addClass('active_notification');
			setTimeout(function () {
				$('#notification').removeClass('active_notification');
			}, 5000);
			// hide form and overlay
			$('#overlay_form, .form_add_command_pass_request').hide();
		});
		
	});
	// END CONTROL COMMAND FORM

	// CONTROL partner FORM
	var control_el = $('img.control_partner');
	control_el.on('click', function() {
		$('#new_partner_password').attr('type', 'password');
		$('#overlay_form, .form_control_partner').show();

		// content field in form
		$('#new_partner_id').val($(this).parent().parent().data('id'));
		$('#new_partner_name').val($(this).parent().parent().data('name'));
		$('#new_partner_region').val($(this).parent().parent().data('region'));
		$('#new_get_digital').val($(this).parent().parent().data('digital_percent'));
		$('#new_get_audio').val($(this).parent().parent().data('audio_percent'));
		$('#new_partner_email').val($(this).parent().parent().data('email'));
		$('#new_partner_password').val('#'.repeat(parseInt($(this).parent().parent().data('pass_length'))));
	});
	$('#new_partner_password').on('focus', function() {
		$('#new_partner_password').blur();
	});

	// save data
	$('#control_partner_submit').click(function() {
		var incorrect = false;

		// if field is empty
		if ($('#new_partner_name').val().length == 0) {
			$('#new_partner_name').addClass('error_input');
			incorrect = true;
		} else
			$('#new_partner_name').removeClass('error_input');
		if ($('#new_partner_region').val().length == 0) {
			$('#new_partner_region').addClass('error_input');
			incorrect = true;
		} else
			$('#new_partner_region').removeClass('error_input');
		if ($('#new_get_digital').val().length == 0) {
			$('#new_get_digital').addClass('error_input');
			incorrect = true;
		} else
			$('#new_get_digital').removeClass('error_input');
		if ($('#new_get_audio').val().length == 0) {
			$('#new_get_audio').addClass('error_input');
			incorrect = true;
		} else
			$('#new_get_audio').removeClass('error_input');
		if ($('#new_partner_email').val().length == 0) {
			$('#new_partner_email').addClass('error_input');
			incorrect = true;
		} else
			$('#new_partner_email').removeClass('error_input');
		if ($('#new_partner_password').val().length == 0) {
			$('#new_partner_password').addClass('error_input');
			incorrect = true;
		} else
			$('#new_partner_password').removeClass('error_input');

		// if email incorrect
		if (!reEmail.test($('#new_partner_email').val())) {
			// alert('Некорректный email');
			$('#new_partner_email').addClass('error_input');
			incorrect = true;
		} else
			$('#new_partner_email').removeClass('error_input');

		// dont show next form if anything wrong
		if (!incorrect) {
			$('.form_control_partner').hide();
			$('.form_control_partner_pass_request').attr('data-task', 'save_data').show();
		}
	});

	// save data/show pass/remove partner if user pres ok and password is right
	$('.form_control_partner_pass_request .request_pass_ok').click(function() {
		var login = $('#user_login').val();
		var request_pass = $('#request_pass').val();

		// if pass field is empty
		if (request_pass.length == 0) {
			$('#request_pass').addClass('error_input');
			return;
		}

		// call ajax
		$.ajax({
			url: '/ajax/check_password.php',
			type: 'POST',
			dataType: 'html',
			data: {
				request_pass: request_pass,
				login: login,
			},
		})
		.done(function(res) {
			if (res == 1) {
				// password is correct, do anything
				var task = $('.form_control_partner_pass_request').attr('data-task');
				$('.form_control_partner_pass_request').removeAttr('data-task');

				// update partner data
				if (task == 'save_data') {
					$.ajax({
						url: '/ajax/update_partner.php',
						type: 'POST',
						dataType: 'html',
						data: {
							partner_id: $('#new_partner_id').val(),
							partner_name: $('#new_partner_name').val(),
							partner_region: $('#new_partner_region').val(),
							get_digital: $('#new_get_digital').val(),
							get_audio: $('#new_get_audio').val(),
							partner_email: $('#new_partner_email').val(),
							partner_password: $('#new_partner_password').val(),
						},
					})
					.done(function(res) {
						// change notification text
						$('#notification_text').text('Данные успешно сохранены');
						// show notification on 5s
						$('#notification').addClass('active_notification');
						setTimeout(function () {
							$('#notification').removeClass('active_notification');
						}, 5000);
						// hide form and overlay
						$('#overlay_form, .form_control_partner_pass_request').hide();
					});
				} else if (task == 'remove_partner') {
					$.ajax({
						url: '/ajax/delete_user.php',
						type: 'POST',
						dataType: 'html',
						data: {
							id: $('#new_partner_id').val(),
						},
					})
					.done(function(res) {
						// change notification text
						$('#notification_text').text('Партнер успешно удален');
						// show notification on 5s
						$('#notification').addClass('active_notification');
						setTimeout(function () {
							$('#notification').removeClass('active_notification');
						}, 5000);
						// hide form and overlay
						$('#overlay_form, .form_control_partner_pass_request').hide();
					});
				} else if (task == 'show_pass') {
					$.ajax({
						url: '/ajax/show_pass.php',
						type: 'POST',
						dataType: 'html',
						data: {id: $('#new_partner_id').val()},
					})
					.done(function(res) {
						$('.form_control_partner_pass_request').hide();
						$('#overlay_form, .form_control_partner').show();
						$('#new_partner_password').attr('type', 'text').val(res);
					});
				}
			} else {
				// password is not correct
				$('#request_pass').addClass('error_input');
			}
		});
	});

	// hide form if user press cancel
	$('.form_control_partner_pass_request .request_pass_cancel').click(function() {
		$('#overlay_form, .form_control_partner_pass_request').hide();
	});
	// END CONTROL partner FORM

	// SETTING PAGE ==========================================================
	if ($('input').is('#change_email')) {
		var email = $('#change_email').val();
		var pass1 = $('#change_pass1').val();
		var pass2 = $('#change_pass2').val();

		$('#change_email').on('input', function() {
			//if ($(this).val() != email && reEmail.test($(this).val()))
			if ($(this).val() != email)
				$('#change_email_submit').removeAttr('disabled');
			else
				$('#change_email_submit').attr('disabled', 'disabled');
		});

		$('#change_pass1, #change_pass2').on('input', function() {
			if ($('#change_pass1').val() != pass1 && $('#change_pass2').val() != pass2)
				$('#change_pass_submit').removeAttr('disabled');
			else
				$('#change_pass_submit').attr('disabled', 'disabled');
		});

		$('#change_email_submit').click(function() {
			var email = $('#change_email').val();
			var id = $('#user_id').val();
			var auth_method = $('#auth_method').val();
			var position = $('#user_position').val();
			// if email incorrect
			if (!reEmail.test(email)) {
				$('#change_email').addClass('error_input');
				return;
			}

			$.ajax({
				url: '/ajax/change_email.php',
				type: 'POST',
				dataType: 'html',
				data: {
					id: id,
					email: email,
					auth_method: auth_method,
					position: position,
				},
			})
			.done(function(res) {
				if (res == 'не авторизованный пользователь')
					return;
				if (res != 'session')
					document.cookie = 'logged='+res+';';
				// change notification text
				$('#notification_text').text('Логин успешно изменён');
				// show notification on 5s
				$('#notification').addClass('active_notification');
				setTimeout(function () {
					$('#notification').removeClass('active_notification');
				}, 5000);
				// to start position
				email = $('#change_email').val();
				$('#change_email_submit').attr('disabled', 'disabled');
			});
			
		});

		$('#change_pass_submit').click(function() {
			if ($('#change_pass1').val() == '') {
				$('#change_pass1').addClass('error_input');
				return;
			}
			if ($('#change_pass2').val() == '') {
				$('#change_pass2').addClass('error_input');
				return;
			}
			if ($('#change_pass1').val() != $('#change_pass2').val()) {
				$('#change_pass1, #change_pass2').addClass('error_input');
				return;
			}

			$.ajax({
				url: '/ajax/change_password.php',
				type: 'POST',
				dataType: 'html',
				data: {
					id: $('#user_id').val(),
					pass: $('#change_pass1').val(),
				},
			})
			.done(function(res) {
				if (res == 'не авторизованный пользователь')
					return;
				// change notification text
				$('#notification_text').text('Пароль успешно изменён');
				// show notification on 5s
				$('#notification').addClass('active_notification');
				setTimeout(function () {
					$('#notification').removeClass('active_notification');
				}, 5000);
				// to start position
				pass1 = $('#change_pass1').val();
				pass2 = pass1;
				$('#change_pass_submit').attr('disabled', 'disabled');
			});
			
		});
	}
	// END SETTING PAGE ==========================================================


	// PROFILE PAGE
	if ($('input').is('#general_name') && $('#user_status').val() == 'ББТ') {
		var general_name = $('#general_name').val();
		var general_address = $('#general_address').val();
		var general_phone = $('#general_phone').val();
		var general_email = $('#general_email').val();
		var general_ogrn = $('#general_ogrn').val();
		var general_inn_kpp = $('#general_inn_kpp').val();
		var bank_name = $('#bank_name').val();
		var bank_bill = $('#bank_bill').val();
		var bank_chet = $('#bank_chet').val();
		var bank_bik = $('#bank_bik').val();
		var organizator_name = $('#organizator_name').val();
		var organizator_position = $('#organizator_position').val();
		var organizator_phone = $('#organizator_phone').val();
		var organizator_email = $('#organizator_email').val();
		var accountant_name = $('#accountant_name').val();
		var accountant_phone = $('#accountant_phone').val();
		var accountant_email = $('#accountant_email').val();
		var manager_name = $('#manager_name').val();
		var manager_phone = $('#manager_phone').val();
		var manager_email = $('#manager_email').val();

		$('input').on('input', function() {

			if ($('#general_name').val() == general_name &&
				$('#general_address').val() == general_address &&
				!(!$('#general_phone').val() == general_phone &&
				(rePhone.test($('#general_phone').val()))) &&
				!(!$('#general_email').val() == general_email &&
				(reEmail.test($('#general_email').val()))) &&
				$('#general_ogrn').val() == general_ogrn &&
				$('#general_inn_kpp').val() == general_inn_kpp &&
				$('#bank_name').val() == bank_name &&
				$('#bank_bill').val() == bank_bill &&
				$('#bank_chet').val() == bank_chet &&
				$('#bank_bik').val() == bank_bik &&
				$('#organizator_name').val() == organizator_name &&
				$('#organizator_position').val() == organizator_position &&
				!(!$('#organizator_phone').val() == organizator_phone &&
				(rePhone.test($('#organizator_phone').val()))) &&
				!(!$('#organizator_email').val() == organizator_email &&
				(reEmail.test($('#organizator_email').val()))) &&
				$('#accountant_name').val() == accountant_name &&
				!(!$('#accountant_phone').val() == accountant_phone &&
				(rePhone.test($('#accountant_phone').val()))) &&
				!(!$('#accountant_email').val() == accountant_email &&
				(reEmail.test($('#accountant_email').val()))) &&
				$('#manager_name').val() == manager_name &&
				!(!$('#manager_phone').val() == manager_phone &&
				(rePhone.test($('#manager_phone').val()))) &&
				!(!$('#manager_email').val() == manager_email &&
				(reEmail.test($('#manager_email').val())))) {
				$('#profile_submit').attr('disabled', 'disabled');
			} else {
				$('#profile_submit').removeAttr('disabled');
			}
		});

		// save data
		$('#profile_submit').click(function() {
			$.ajax({
				url: '/ajax/profile_data.php',
				type: 'POST',
				dataType: 'html',
				data: {
					id: $('#user_id').val(),
					status: $('#user_status').val(),
					general_name: $('#general_name').val(),
					general_address: $('#general_address').val(),
					general_phone: $('#general_phone').val(),
					general_email: $('#general_email').val(),
					general_ogrn: $('#general_ogrn').val(),
					general_inn_kpp: $('#general_inn_kpp').val(),
					bank_name: $('#bank_name').val(),
					bank_bill: $('#bank_bill').val(),
					bank_chet: $('#bank_chet').val(),
					bank_bik: $('#bank_bik').val(),
					organizator_name: $('#organizator_name').val(),
					organizator_position: $('#organizator_position').val(),
					organizator_phone: $('#organizator_phone').val(),
					organizator_email: $('#organizator_email').val(),
					accountant_name: $('#accountant_name').val(),
					accountant_phone: $('#accountant_phone').val(),
					accountant_email: $('#accountant_email').val(),
					manager_name: $('#manager_name').val(),
					manager_phone: $('#manager_phone').val(),
					manager_email: $('#manager_email').val(),
				},
			})
			.done(function(res) {
				// change notification text
				$('#notification_text').text('Данные успешно сохранены');
				// show notification on 5s
				$('#notification').addClass('active_notification');
				setTimeout(function () {
					$('#notification').removeClass('active_notification');
				}, 5000);

				general_name = $('#general_name').val();
				general_address = $('#general_address').val();
				general_phone = $('#general_phone').val();
				general_email = $('#general_email').val();
				general_ogrn = $('#general_ogrn').val();
				general_inn_kpp = $('#general_inn_kpp').val();
				bank_name = $('#bank_name').val();
				bank_bill = $('#bank_bill').val();
				bank_chet = $('#bank_chet').val();
				bank_bik = $('#bank_bik').val();
				organizator_name = $('#organizator_name').val();
				organizator_position = $('#organizator_position').val();
				organizator_phone = $('#organizator_phone').val();
				organizator_email = $('#organizator_email').val();
				accountant_name = $('#accountant_name').val();
				accountant_phone = $('#accountant_phone').val();
				accountant_email = $('#accountant_email').val();
				manager_name = $('#manager_name').val();
				manager_phone = $('#manager_phone').val();
				manager_email = $('#manager_email').val();
				$('#profile_submit').attr('disabled', 'disabled');
			});
			
		});
	}

	if ($('input').is('#general_name') && $('#user_status').val() == 'Команда') {
		var general_short_name = $('#general_short_name').val();
		var general_name = $('#general_name').val();
		var general_address = $('#general_address').val();
		var general_phone = $('#general_phone').val();
		var general_email = $('#general_email').val();
		var general_ogrn = $('#general_ogrn').val();
		var general_inn_kpp = $('#general_inn_kpp').val();
		var dogovor_number = $('#dogovor_number').val();
		var dogovor_date = $('#dogovor_date').val();
		var bank_name = $('#bank_name').val();
		var bank_bill = $('#bank_bill').val();
		var bank_chet = $('#bank_chet').val();
		var bank_bik = $('#bank_bik').val();
		var organizator_name = $('#organizator_name').val();
		var organizator_position = $('#organizator_position').val();
		var organizator_phone = $('#organizator_phone').val();
		var organizator_email = $('#organizator_email').val();
		var accountant_name = $('#accountant_name').val();
		var accountant_phone = $('#accountant_phone').val();
		var accountant_email = $('#accountant_email').val();
		var manager_name = $('#manager_name').val();
		var manager_phone = $('#manager_phone').val();
		var manager_email = $('#manager_email').val();

		$('input').on('input', function() {
			if (
				$('#general_name').val() == general_name &&
				$('#general_short_name').val() == general_short_name &&
				$('#general_address').val() == general_address &&
				// !(!$('#general_phone').val() == general_phone &&
				// (rePhone.test($('#general_phone').val()))) &&
				// !(!$('#general_email').val() == general_email &&
				// (reEmail.test($('#general_email').val()))) &&
				$('#general_ogrn').val() == general_ogrn &&
				$('#general_inn_kpp').val() == general_inn_kpp &&
				$('#dogovor_number').val() == dogovor_number &&
				$('#dogovor_date').val() == dogovor_date &&
				$('#bank_name').val() == bank_name &&
				$('#bank_bill').val() == bank_bill &&
				$('#bank_chet').val() == bank_chet &&
				$('#bank_bik').val() == bank_bik &&
				$('#organizator_name').val() == organizator_name &&
				$('#organizator_position').val() == organizator_position &&
				// !(!$('#organizator_phone').val() == organizator_phone &&
				// (rePhone.test($('#organizator_phone').val()))) &&
				// !(!$('#organizator_email').val() == organizator_email &&
				// (reEmail.test($('#organizator_email').val()))) &&
				$('#accountant_name').val() == accountant_name &&
				// !(!$('#accountant_phone').val() == accountant_phone &&
				// (rePhone.test($('#accountant_phone').val()))) &&
				// !(!$('#accountant_email').val() == accountant_email &&
				// (reEmail.test($('#accountant_email').val()))) &&
				$('#manager_name').val() == manager_name &&
				// !(!$('#manager_phone').val() == manager_phone &&
				// (rePhone.test($('#manager_phone').val()))) &&
				// !(!$('#manager_email').val() == manager_email &&
				// (reEmail.test($('#manager_email').val())))
				$('#general_phone').val() == general_phone &&
				$('#general_email').val() == general_email &&
				$('#organizator_phone').val() == organizator_phone &&
				$('#organizator_email').val() == organizator_email &&
				$('#accountant_phone').val() == accountant_phone &&
				$('#accountant_email').val() == accountant_email &&
				$('#manager_phone').val() == manager_phone &&
				$('#manager_email').val() == manager_email
				) {
				$('#profile_submit').attr('disabled', 'disabled');
			} else {
				$('#profile_submit').removeAttr('disabled');
			}
		});

		// save data
		$('#profile_submit').click(function() {
			$.ajax({
				url: '/ajax/profile_data.php',
				type: 'POST',
				dataType: 'html',
				data: {
					id: $('#user_id').val(),
					status: $('#user_status').val(),
					general_short_name: $('#general_short_name').val(),
					general_name: $('#general_name').val(),
					general_address: $('#general_address').val(),
					general_phone: $('#general_phone').val(),
					general_email: $('#general_email').val(),
					general_ogrn: $('#general_ogrn').val(),
					general_inn_kpp: $('#general_inn_kpp').val(),
					dogovor_number: $('#dogovor_number').val(),
					dogovor_date: $('#dogovor_date').val(),
					bank_name: $('#bank_name').val(),
					bank_bill: $('#bank_bill').val(),
					bank_chet: $('#bank_chet').val(),
					bank_bik: $('#bank_bik').val(),
					organizator_name: $('#organizator_name').val(),
					organizator_position: $('#organizator_position').val(),
					organizator_phone: $('#organizator_phone').val(),
					organizator_email: $('#organizator_email').val(),
					accountant_name: $('#accountant_name').val(),
					accountant_phone: $('#accountant_phone').val(),
					accountant_email: $('#accountant_email').val(),
					manager_name: $('#manager_name').val(),
					manager_phone: $('#manager_phone').val(),
					manager_email: $('#manager_email').val(),
				},
			})
			.done(function(res) {
				// change notification text
				$('#notification_text').text('Данные успешно сохранены');
				// show notification on 5s
				$('#notification').addClass('active_notification');
				setTimeout(function () {
					$('#notification').removeClass('active_notification');
				}, 5000);

				general_name = $('#general_name').val();
				general_short_name = $('#general_short_name').val();
				general_address = $('#general_address').val();
				general_phone = $('#general_phone').val();
				general_email = $('#general_email').val();
				general_ogrn = $('#general_ogrn').val();
				general_inn_kpp = $('#general_inn_kpp').val();
				dogovor_number = $('#dogovor_number').val();
				dogovor_date = $('#dogovor_date').val();
				bank_name = $('#bank_name').val();
				bank_bill = $('#bank_bill').val();
				bank_chet = $('#bank_chet').val();
				bank_bik = $('#bank_bik').val();
				organizator_name = $('#organizator_name').val();
				organizator_position = $('#organizator_position').val();
				organizator_phone = $('#organizator_phone').val();
				organizator_email = $('#organizator_email').val();
				accountant_name = $('#accountant_name').val();
				accountant_phone = $('#accountant_phone').val();
				accountant_email = $('#accountant_email').val();
				manager_name = $('#manager_name').val();
				manager_phone = $('#manager_phone').val();
				manager_email = $('#manager_email').val();
				$('#profile_submit').attr('disabled', 'disabled');
			});
			
		});

		$('#upload_picture').change(function() {
			var file_data1 = $('#upload_picture').prop('files')[0];
			var form_data1 = new FormData();
			form_data1.append('file', file_data1);

			$.ajax({
				url: '/ajax/upload_picture.php?id=' + $('#user_id').val(),
				type: 'POST',
				dataType: 'html',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data1,
			})
			.done(function(res) {
				if (res == 'file not found' || res == 'Error') {
					// change notification text
					$('#notification_text').text('Произошла ошибка. Повторите попытку позже');
					// show notification on 5s
					$('#notification').addClass('active_notification');
					setTimeout(function () {
						$('#notification').removeClass('active_notification');
					}, 5000);
				} else if (res == 'Недопустимый формат') {
					// change notification text
					$('#notification_text').text('Недопустимый формат файла');
					// show notification on 5s
					$('#notification').addClass('active_notification');
					setTimeout(function () {
						$('#notification').removeClass('active_notification');
					}, 5000);
				} else {
					$('label[for="upload_picture"]').attr('style', 'background-image: url(/avatars/'+res+');');

					// change notification text
					$('#notification_text').text('Аватар успешно изменён');
					// show notification on 5s
					$('#notification').addClass('active_notification');
					setTimeout(function () {
						$('#notification').removeClass('active_notification');
					}, 5000);

					if ($('.upload_picture_overlay').attr('data-task') == 'upload_photo')
						$('.upload_picture_overlay').attr('data-task', 'replace_photo');
				}
			});
			
		});

		$('.remove_photo').click(function(event) {
			event.preventDefault();

			$.ajax({
				url: '/ajax/remove_photo.php',
				type: 'POST',
				dataType: 'html',
				data: {id: $('#user_id').val()},
			})
			.done(function() {
				$('.upload_picture_overlay').attr('data-task', 'upload_photo');
				$('label[for="upload_picture"]').attr('style', 'background-image: url(/avatars/avatar.png);');

				// change notification text
				$('#notification_text').text('Аватар успешно удалён');
				// show notification on 5s
				$('#notification').addClass('active_notification');
				setTimeout(function () {
					$('#notification').removeClass('active_notification');
				}, 5000);
			});
			
		});

	}









	if ($('input').is('#general_name') && $('#user_status').val() == 'Партнер') {
		var general_name = $('#general_name').val();
		var general_soul_name = $('#general_soul_name').val();
		var general_address = $('#general_address').val();
		var contact_phone = $('#contact_phone').val();
		var contact_email = $('#contact_email').val();
		var pasport_seria = $('#pasport_seria').val();
		var pasport_number = $('#pasport_number').val();
		var pasport_date = $('#pasport_date').val();
		var pasport_gave = $('#pasport_gave').val();
		var bank_name = $('#bank_name').val();
		var bank_bill = $('#bank_bill').val();
		var bank_chet = $('#bank_chet').val();
		var bank_bik = $('#bank_bik').val();
		var other_inn = $('#other_inn').val();
		var other_snils = $('#other_snils').val();

		$('input').on('input', function() {

			if ($('#general_name').val() == general_name &&
				$('#general_soul_name').val() == general_soul_name &&
				$('#general_address').val() == general_address &&
				!(!$('#contact_phone').val() == contact_phone &&
				(rePhone.test($('#contact_phone').val()))) &&
				!(!$('#contact_email').val() == contact_email &&
				(reEmail.test($('#contact_email').val()))) &&
				$('#pasport_seria').val() == pasport_seria &&
				$('#pasport_number').val() == pasport_number &&
				$('#pasport_date').val() == pasport_date &&
				$('#pasport_gave').val() == pasport_gave &&
				$('#bank_name').val() == bank_name &&
				$('#bank_bill').val() == bank_bill &&
				$('#bank_chet').val() == bank_chet &&
				$('#bank_bik').val() == bank_bik &&
				$('#other_inn').val() == other_inn &&
				$('#other_snils').val() == other_snils) {
				$('#profile_submit').attr('disabled', 'disabled');
			} else {
				$('#profile_submit').removeAttr('disabled');
			}
		});

		// save data
		$('#profile_submit').click(function() {
			$.ajax({
				url: '/ajax/profile_data.php',
				type: 'POST',
				dataType: 'html',
				data: {
					id: $('#user_id').val(),
					status: $('#user_status').val(),
					general_name: $('#general_name').val(),
					general_soul_name: $('#general_soul_name').val(),
					general_address: $('#general_address').val(),
					contact_phone: $('#contact_phone').val(),
					contact_email: $('#contact_email').val(),
					pasport_seria: $('#pasport_seria').val(),
					pasport_number: $('#pasport_number').val(),
					pasport_date: $('#pasport_date').val(),
					pasport_gave: $('#pasport_gave').val(),
					bank_name: $('#bank_name').val(),
					bank_bill: $('#bank_bill').val(),
					bank_chet: $('#bank_chet').val(),
					bank_bik: $('#bank_bik').val(),
					other_inn: $('#other_inn').val(),
					other_snils: $('#other_snils').val(),
				},
			})
			.done(function(res) {
				// change notification text
				$('#notification_text').text('Данные успешно сохранены');
				// show notification on 5s
				$('#notification').addClass('active_notification');
				setTimeout(function () {
					$('#notification').removeClass('active_notification');
				}, 5000);

				general_name = $('#general_name').val();
				general_soul_name = $('#general_soul_name').val();
				general_address = $('#general_address').val();
				contact_phone = $('#contact_phone').val();
				contact_email = $('#contact_email').val();
				pasport_seria = $('#pasport_seria').val();
				pasport_number = $('#pasport_number').val();
				pasport_date = $('#pasport_date').val();
				pasport_gave = $('#pasport_gave').val();
				bank_name = $('#bank_name').val();
				bank_bill = $('#bank_bill').val();
				bank_chet = $('#bank_chet').val();
				bank_bik = $('#bank_bik').val();
				other_inn = $('#other_inn').val();
				other_snils = $('#other_snils').val();
				$('#profile_submit').attr('disabled', 'disabled');
			});
			
		});

		$('#upload_picture').change(function() {
			var file_data1 = $('#upload_picture').prop('files')[0];
			var form_data1 = new FormData();
			form_data1.append('file', file_data1);

			$.ajax({
				url: '/ajax/upload_picture.php?id=' + $('#user_id').val(),
				type: 'POST',
				dataType: 'html',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data1,
			})
			.done(function(res) {
				if (res == 'file not found' || res == 'Error') {
					// change notification text
					$('#notification_text').text('Произошла ошибка. Повторите попытку позже');
					// show notification on 5s
					$('#notification').addClass('active_notification');
					setTimeout(function () {
						$('#notification').removeClass('active_notification');
					}, 5000);
				} else if (res == 'Недопустимый формат') {
					// change notification text
					$('#notification_text').text('Недопустимый формат файла');
					// show notification on 5s
					$('#notification').addClass('active_notification');
					setTimeout(function () {
						$('#notification').removeClass('active_notification');
					}, 5000);
				} else {
					$('label[for="upload_picture"]').attr('style', 'background-image: url(/avatars/'+res+');');

					// change notification text
					$('#notification_text').text('Аватар успешно изменён');
					// show notification on 5s
					$('#notification').addClass('active_notification');
					setTimeout(function () {
						$('#notification').removeClass('active_notification');
					}, 5000);

					if ($('.upload_picture_overlay').attr('data-task') == 'upload_photo')
						$('.upload_picture_overlay').attr('data-task', 'replace_photo');
				}
			});
			
		});

		$('.remove_photo').click(function(event) {
			event.preventDefault();

			$.ajax({
				url: '/ajax/remove_photo.php',
				type: 'POST',
				dataType: 'html',
				data: {id: $('#user_id').val()},
			})
			.done(function() {
				$('.upload_picture_overlay').attr('data-task', 'upload_photo');
				$('label[for="upload_picture"]').attr('style', 'background-image: url(/avatars/avatar.png);');

				// change notification text
				$('#notification_text').text('Аватар успешно удалён');
				// show notification on 5s
				$('#notification').addClass('active_notification');
				setTimeout(function () {
					$('#notification').removeClass('active_notification');
				}, 5000);
			});
			
		});



		$('#upload_passport').change(function() {
			var file_data1 = $('#upload_passport').prop('files')[0];
			var form_data1 = new FormData();
			form_data1.append('file', file_data1);

			$.ajax({
				url: '/ajax/upload_passport.php?id=' + $('#user_id').val(),
				type: 'POST',
				dataType: 'html',
				cache: false,
				contentType: false,
				processData: false,
				data: form_data1,
			})
			.done(function(res) {
				if (res == 'file not found' || res == 'Error') {
					// change notification text
					$('#notification_text').text('Произошла ошибка. Повторите попытку позже');
					// show notification on 5s
					$('#notification').addClass('active_notification');
					setTimeout(function () {
						$('#notification').removeClass('active_notification');
					}, 5000);
				} else if (res == 'Недопустимый формат') {
					// change notification text
					$('#notification_text').text('Недопустимый формат файла');
					// show notification on 5s
					$('#notification').addClass('active_notification');
					setTimeout(function () {
						$('#notification').removeClass('active_notification');
					}, 5000);
				} else {
					var passport_file = '<div class="passport_file" data-img="'+res+'">';
					passport_file += '<img src="/img/passport_icon.svg" alt="passport_icon">';
					passport_file += '<span>Фото паспорта</span>';
					passport_file += '<img src="/img/remove_passport.svg" alt="remove_passport" class="remove_passport">';
					passport_file += '</div>';
					passport_file += '<script>$(".remove_passport").on("click",function(){var a=$(this).parent().attr("data-img");$.ajax({url:"/ajax/remove_passport.php",type:"POST",dataType:"html",data:{id:$("#user_id").val(),passport_img:a}}).done(function(t){$(\'.passport_file[data-img="\'+a+\'"]\').remove(),$("#notification_text").text("Фото паспорта удалено"),$("#notification").addClass("active_notification"),setTimeout(function(){$("#notification").removeClass("active_notification")},5e3)})});</script>';
					$('.upload_passport_files').append(passport_file);
					$('.upload_passport_files').after('<script>$(".passport_file > *:not(.remove_passport)").click(function(){$(\'#document_viewer img[alt="document"]\').attr("src",""),$("#document_viewer").show(),$(\'#document_viewer img[alt="document"]\').attr("src","/service/passports/"+$(this).parent().attr("data-img"))});</script>');

					// change notification text
					$('#notification_text').text('Фото паспорта успешно загружено');
					// show notification on 5s
					$('#notification').addClass('active_notification');
					setTimeout(function () {
						$('#notification').removeClass('active_notification');
					}, 5000);
				}
			});
			
		});


		$('.remove_passport').on('click', function() {
			var img = $(this).parent().attr('data-img');
			$.ajax({
				url: '/ajax/remove_passport.php',
				type: 'POST',
				dataType: 'html',
				data: {
					id: $('#user_id').val(),
					passport_img: img
				},
			})
			.done(function(res) {
				$('.passport_file[data-img="'+img+'"]').remove();
				// change notification text
				$('#notification_text').text('Фото паспорта удалено');
				// show notification on 5s
				$('#notification').addClass('active_notification');
				setTimeout(function () {
					$('#notification').removeClass('active_notification');
				}, 5000);
			});
			
		});

	}
	// END PROFILE PAGE


	// MODAL FORM PASSWORD
	$('#new_command_password, #new_partner_password').on('input', function() {
		if ($(this).attr('type') == 'password') {
			new_val = $(this).val().substr(-1);
			$(this).val(new_val).attr('type', 'text');
		}
	});
	// END MODAL FORM PASSWORD


	// CENTERING FORMS
	if ($('div').is('.form_control_partner_pass_request')) {
		$('.form_control_partner_pass_request').css({
			transform: 'none',
			top: Math.floor($(window).height() / 2) - Math.floor($('.form_control_partner_pass_request').height() / 2),
			left: Math.floor($(window).width() / 2) - Math.floor($('.form_control_partner_pass_request').width() / 2),
		});
		$('.form_add_partner').css({
			transform: 'none',
			top: Math.floor($(window).height() / 2) - Math.floor($('.form_add_partner').height() / 2),
			left: Math.floor($(window).width() / 2) - Math.floor($('.form_add_partner').width() / 2),
		});
		$('.form_control_partner').css({
			transform: 'none',
			top: Math.floor($(window).height() / 2) - Math.floor($('.form_control_partner').height() / 2),
			left: Math.floor($(window).width() / 2) - Math.floor($('.form_control_partner').width() / 2),
		});
	} else if ($('div').is('.form_control_command')) {
		$('.form_control_command').css({
			transform: 'none',
			top: Math.floor($(window).height() / 2) - Math.floor($('.form_control_command').height() / 2),
			left: Math.floor($(window).width() / 2) - Math.floor($('.form_control_command').width() / 2),
		});
		$('.form_add_command').css({
			transform: 'none',
			top: Math.floor($(window).height() / 2) - Math.floor($('.form_add_command').height() / 2),
			left: Math.floor($(window).width() / 2) - Math.floor($('.form_add_command').width() / 2),
		});
		$('.form_add_command_pass_request').css({
			transform: 'none',
			top: Math.floor($(window).height() / 2) - Math.floor($('.form_add_command_pass_request').height() / 2),
			left: Math.floor($(window).width() / 2) - Math.floor($('.form_add_command_pass_request').width() / 2),
		});
		$('.form_confirm_delete').css({
			transform: 'none',
			top: Math.floor($(window).height() / 2) - Math.floor($('.form_confirm_delete').height() / 2),
			left: Math.floor($(window).width() / 2) - Math.floor($('.form_confirm_delete').width() / 2),
		});
		$('.form_delete_command_error').css({
			transform: 'none',
			top: Math.floor($(window).height() / 2) - Math.floor($('.form_delete_command_error').height() / 2),
			left: Math.floor($(window).width() / 2) - Math.floor($('.form_delete_command_error').width() / 2),
		});
	} else if ($('div').is('.partner_accept_form')) {
		$('.partner_accept_form').css({
			transform: 'none',
			top: Math.floor($(window).height() / 2) - Math.floor($('.partner_accept_form').height() / 2),
			left: Math.floor($(window).width() / 2) - Math.floor($('.partner_accept_form').width() / 2),
		});
	}



	// $('.partner_accept_form, #overlay_form').show();

	$('label[for="accepted_document"]').click(function() {
		if ($('#accepted_document').prop('checked') != true) {
			$('#partner_login').removeAttr('disabled');
		} else {
			$('#partner_login').attr('disabled', 'disabled');
		}
	});

	$('.document_partner').click(function() {
		$('.partner_document').show();
		$('#overlay_form').addClass('close_overlay_form');
	});

	$('#partner_login').click(function() {
		if ($('#accepted_document').prop('checked') != true)
			return;

		if ($('.partner_accept_form').attr('data-method') == 'cookie') {
			var now = new Date();
			now.setTime(now.getTime() + 1 * 3600 * 1000 * 24 * 30);
			document.cookie = "logged=" + $('.partner_accept_form').attr('data-login') + '; expires=' + now.toGMTString() + ';';
			// success
			document.location.href = '/analitic.php';
		} else {
			$.ajax({
				url: '/ajax/set_partner_session.php',
				type: 'POST',
				dataType: 'html',
				data: {data: $('.partner_accept_form').attr('data-login')},
			})
			.done(function() {
				document.location.href = '/analitic.php';
			});
			
		}
	});

	$('.entrance_close').click(function() {
		$('.partner_document').hide();
		$('#overlay_form').removeClass('close_overlay_form');
	});
	

	$('.passport_view').click(function() {
		$('#document_viewer img[alt="document"]').attr('src', '');
		$('#document_viewer').show();
		$('#document_viewer img[alt="document"]').attr('src', '/service/passports/' + $(this).attr('data-passport'));
	});
	$('.close_document_viewer').click(function() {
		$('#document_viewer').hide();
	});

	$('.passport_file > *:not(.remove_passport)').click(function() {
		$('#document_viewer img[alt="document"]').attr('src', '');
		$('#document_viewer').show();
		$('#document_viewer img[alt="document"]').attr('src', '/service/passports/' + $(this).parent().attr('data-img'));
	});


	$('.pagination_list .page').last().addClass('last_pagination');
	$('.pagination_list > .prev_page').click(function() {
		if ($('div').is('.active_page') && $('.active_page').text() != '1')
			document.location = $('.active_page').prev().attr('href');
	});
	$('.pagination_list > .next_page').click(function() {
		if ($('div').is('.active_page') && !$('.active_page').is('.last_pagination'))
			document.location = $('.active_page').next().attr('href');
	});


	$('.change').mouseenter(function() {
		$('.change').attr('style', '');
		if ($(this).next().hasClass('change_active'))
			$(this).css('border-right', 'none');
	});
	$('.table_size').mouseenter(function() {
		$('.table_size').attr('style', '');
		if ($(this).next().hasClass('table_size_active'))
			$(this).css('border-right', 'none');
	});


	if ($('div').is('.wrapper_code')) {
		$('.code').click(function(e) {
			$('#code').attr('type', 'text').select();
			document.execCommand('copy');
			e.preventDefault();
			$('#code').attr('type', 'hidden');

			$('#notification_text').text('Код скопирован в буфер обемена');
			// show notification on 5s
			$('#notification').addClass('active_notification');
			setTimeout(function () {
				$('#notification').removeClass('active_notification');
			}, 5000);
		});
		$('.link').click(function() {
			window.open('http://' + $(this).text());
		});
	}


	// if ($('h1').is('.bread_cumbs_view')) {
	// 	if ($('.bread_cumbs_view').find('a').length > 0)
	// 		$('.about_col .referer a').attr('href', $('.bread_cumbs_view').find('a').last().attr('href'));
	// 	else
	// 		$('.about_col .referer a').attr('href', $('header .col-5 a:eq(1)').attr('href'));
	// }
	$('.about_col .referer a').click(function(event) {
		event.preventDefault();
		window.history.back();
	});


});

