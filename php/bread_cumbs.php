<?php
$id = $_GET['id'];
$new = $_SESSION['bread_cumbs_last_update'] != 'http://partner.bbt-online.ru/view.php?id='.$id;
$bread_cumbs = false;

if (in_array('http://partner.bbt-online.ru/view.php?id='.$_GET['id'], $_SESSION['bread_array'])
	&& $_SESSION['bread_cumbs_last_update'] != 'http://partner.bbt-online.ru/view.php?id=client'.$_GET['id']) {
		array_splice($_SESSION['bread_array'],
			-array_search('http://partner.bbt-online.ru/view.php?id='.$_GET['id'], $_SESSION['bread_array']));
}


if (strpos($id, 'client') !== false) {
	$id = substr($id, strlen('client') - strlen($id));

	require_once 'db_shop.php';
	$view = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `ID` = $id");
	$view = $view->fetch_array(MYSQLI_ASSOC);

	// get first name
	$first_name = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'first_name'");
	$first_name = $first_name->fetch_array(MYSQLI_ASSOC)['meta_value'];

	// get last name
	$last_name = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = $id AND `meta_key` = 'last_name'");
	$last_name = $last_name->fetch_array(MYSQLI_ASSOC)['meta_value'];

	// create title
	$view['name'] = $first_name . ' ' . $last_name;
	$title = $view['name'];

	if (in_array('http://partner.bbt-online.ru/view.php?id=client'.$id, $_SESSION['bread_array'])) {
		$index = array_search('http://partner.bbt-online.ru/view.php?id='.$id, $_SESSION['bread_array']);
		array_splice($_SESSION['bread_array'], -$index);
	}

	// create other data
	$clients = $dbc_shop->query("SELECT * FROM `wp_users`");
	$bread_cumb = "Клиенты <span class=\"command_count\">{$clients->num_rows}</span>";
	if ($_SESSION['bread_cumbs'] == '') {
		$_SESSION['bread_cumbs'] = "Клиенты <span class=\"command_count\">{$clients->num_rows}</span>";
		$_SESSION['bread_array'][] = 'http://partner.bbt-online.ru/view.php?id=client'.$id;
		$_SESSION['bread_names']['http://partner.bbt-online.ru/view.php?id=client'.$id] = $view['name'];
	}
	elseif ($_SESSION['bread_cumbs_last_update'] != 'http://partner.bbt-online.ru/view.php?id=client'.$id) {
		$bread_cumbs = true;
		$_SESSION['bread_array'][] = 'http://partner.bbt-online.ru/view.php?id=client'.$id;
		$_SESSION['bread_names']['http://partner.bbt-online.ru/view.php?id=client'.$id] = $view['name'];
	}
	$_SESSION['bread_cumbs_last_update'] = 'http://partner.bbt-online.ru/view.php?id=client'.$id;

	$view_position = 'client';
} else {
	$view = $dbc->query("SELECT * FROM `users` WHERE `id` = $id");
	$view = $view->fetch_array(MYSQLI_ASSOC);

	$title = $view['name'];

	if (in_array('http://partner.bbt-online.ru/view.php?id='.$id, $_SESSION['bread_array'])) {
		$index = array_search('http://partner.bbt-online.ru/view.php?id='.$id, $_SESSION['bread_array']);
		array_splice($_SESSION['bread_array'], -$index);
	}

	if ($view['position'] == 'command') {
		$commands = $dbc->query("SELECT * FROM `users` WHERE `position` = 'command'");
		$bread_cumb = "Команды <span class=\"command_count\">{$commands->num_rows}</span>";
		if ($_SESSION['bread_cumbs'] == '') {
			$_SESSION['bread_cumbs'] = "Команды <span class=\"command_count\">{$commands->num_rows}</span>";
			$_SESSION['bread_array'][] = 'http://partner.bbt-online.ru/view.php?id='.$id;
			$_SESSION['bread_names']['http://partner.bbt-online.ru/view.php?id='.$id] = $view['name'];
		}
		elseif ($_SESSION['bread_cumbs_last_update'] != 'http://partner.bbt-online.ru/view.php?id='.$id) {
			$bread_cumbs = true;
			$_SESSION['bread_array'][] = 'http://partner.bbt-online.ru/view.php?id='.$id;
			$_SESSION['bread_names']['http://partner.bbt-online.ru/view.php?id='.$id] = $view['name'];
		}
		$_SESSION['bread_cumbs_last_update'] = 'http://partner.bbt-online.ru/view.php?id='.$id;


		$view_position = 'command';
	} elseif ($view['position'] == 'partner') {
		$partners = $dbc->query("SELECT * FROM `users` WHERE `position` = 'partner'");
		$bread_cumb = "Партнеры <span class=\"command_count\">{$partners->num_rows}</span>";
		if ($_SESSION['bread_cumbs'] == '') {
			$_SESSION['bread_cumbs'] = "Партнеры <span class=\"command_count\">{$partners->num_rows}</span>";
			$_SESSION['bread_array'][] = 'http://partner.bbt-online.ru/view.php?id='.$id;
			$_SESSION['bread_names']['http://partner.bbt-online.ru/view.php?id='.$id] = $view['name'];
		}
		elseif ($_SESSION['bread_cumbs_last_update'] != 'http://partner.bbt-online.ru/view.php?id='.$id) {
			$bread_cumbs = true;
			$_SESSION['bread_array'][] = 'http://partner.bbt-online.ru/view.php?id='.$id;
			$_SESSION['bread_names']['http://partner.bbt-online.ru/view.php?id='.$id] = $view['name'];
		}
		$_SESSION['bread_cumbs_last_update'] = 'http://partner.bbt-online.ru/view.php?id='.$id;

		$view_position = 'partner';
	}
}

if ($bread_cumbs) {
	$bread_cumbs = $_SESSION['bread_cumbs'];
	for ($i=0; $i < count($_SESSION['bread_array']) - 1; $i++)
		$bread_cumbs .= '<a href="'.$_SESSION['bread_array'][$i].'"><span class="back_bc">&rarr;</span>'.$_SESSION['bread_names'][$_SESSION['bread_array'][$i]].'</a>';
	$_SESSION['prev_bread_cumbs'] = $bread_cumbs;
} else
	$bread_cumbs = $new ? $_SESSION['bread_cumbs'] : $_SESSION['prev_bread_cumbs'];




// хлебные крошки вывод
$cumbs = $bread_cumb;

if ($view['position'] == 'partner' && $role == 'ББТ') {
	$command = $dbc->query("SELECT * FROM `users` WHERE `id` = {$view['parent']}");
	$command = $command->fetch_array(MYSQLI_ASSOC);

	$cumbs .= "<a href=\"/view.php?id={$command['id']}\"><span class=\"back_bc\">&rarr;</span>{$command['name']}</a>";
} elseif ($view_position == 'client') {
	$code = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `id` = $id");
	$code = $code->fetch_array(MYSQLI_ASSOC);
	$parent = $code['parent'];
	$code = $code['code'];

	$flag = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `code` = '$parent'");
	$breads_links = array();
	while ($flag->num_rows) {
		$flag = $dbc_shop->query("SELECT * FROM `wp_users` WHERE `code` = '$parent'");
		$flag = $flag->fetch_array(MYSQLI_ASSOC);
		$parent = $flag['parent'];

		// get name
		$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = {$flag['ID']} AND `meta_key` = 'first_name'");
		if ($meta_array)
			$client['first_name'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];
		// get second name
		$meta_array = $dbc_shop->query("SELECT * FROM `wp_usermeta` WHERE `user_id` = {$flag['ID']} AND `meta_key` = 'last_name'");
		if ($meta_array)
			$client['last_name'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

		$breads_links[] = "<a href=\"/view.php?id=client{$flag['ID']}\"><span class=\"back_bc\">&rarr;</span>{$client['first_name']} {$client['last_name']}</a>";
	}

	$partner = $dbc->query("SELECT * FROM `users` WHERE `code` = '$parent'");
	if ($partner) {
		$partner = $partner->fetch_array(MYSQLI_ASSOC);

		$command = $dbc->query("SELECT * FROM `users` WHERE `id` = {$partner['parent']}");
		if ($command) {
			$command = $command->fetch_array(MYSQLI_ASSOC);

			if ($role == 'ББТ')
				$cumbs .= "<a href=\"/view.php?id={$command['id']}\"><span class=\"back_bc\">&rarr;</span>{$command['name']}</a>";

			if ($role == 'Команда' || $role == 'ББТ')
				$cumbs .= "<a href=\"/view.php?id={$partner['id']}\"><span class=\"back_bc\">&rarr;</span>{$partner['name']}</a>";

			foreach ($breads_links as $bread)
				$cumbs .= $bread;
		}
	}
}
