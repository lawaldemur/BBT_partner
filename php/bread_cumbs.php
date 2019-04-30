<?php
$site = 'http://partner.bbt-online.ru/view.php?';

$id = $_GET['id'];
$new = $_SESSION['bread_cumbs_last_update'] != $site.'id='.$id;
$bread_cumbs = false;

if (in_array($site.'id='.$_GET['id'], $_SESSION['bread_array'])
			&& $_SESSION['bread_cumbs_last_update'] != $site.'id=client'.$_GET['id']) {
	array_splice($_SESSION['bread_array'],
		-array_search($site.'id='.$_GET['id'], $_SESSION['bread_array']));
}


if (strpos($id, 'client') !== false) {
	$id = substr($id, strlen('client') - strlen($id));

	require_once 'db_shop.php';
	$db_shop->set_table('wp_users');
	$db_shop->set_where(['ID' => $id]);
	$view = $db_shop->select('i')->fetch_array(MYSQLI_ASSOC);

	// get first name
	$db_shop->set_table('wp_usermeta');
	$db_shop->set_where(['user_id' => $id, 'meta_key' => 'first_name']);
	$first_name = $db_shop->select('is')->fetch_array(MYSQLI_ASSOC)['meta_value'];

	// get last name
	$db_shop->set_where(['user_id' => $id, 'meta_key' => 'last_name']);
	$last_name = $db_shop->select('is')->fetch_array(MYSQLI_ASSOC)['meta_value'];

	// create title
	$view['name'] = $first_name . ' ' . $last_name;
	$title = $view['name'];

	if (in_array($site.'id=client'.$id, $_SESSION['bread_array'])) {
		$index = array_search($site.'id='.$id, $_SESSION['bread_array']);
		array_splice($_SESSION['bread_array'], -$index);
	}

	// create other data
	$db_shop->set_table('wp_users');
	$db_shop->set_where([]);
	$clients = $db_shop->select();
	$bread_cumb = "Клиенты <span class=\"command_count\">{$clients->num_rows}</span>";
	if ($_SESSION['bread_cumbs'] == '') {
		$_SESSION['bread_cumbs'] = "Клиенты <span class=\"command_count\">{$clients->num_rows}</span>";
		$_SESSION['bread_array'][] = $site.'id=client'.$id;
		$_SESSION['bread_names'][$site.'id=client'.$id] = $view['name'];
	}
	elseif ($_SESSION['bread_cumbs_last_update'] != $site.'id=client'.$id) {
		$bread_cumbs = true;
		$_SESSION['bread_array'][] = $site.'id=client'.$id;
		$_SESSION['bread_names'][$site.'id=client'.$id] = $view['name'];
	}
	$_SESSION['bread_cumbs_last_update'] = $site.'id=client'.$id;

	$view_position = 'client';
} else {
	$db->set_table('users');
	$db->set_where(['id' => $id]);
	$view = $db->select('i')->fetch_array(MYSQLI_ASSOC);

	$title = $view['name'];

	if (in_array($site.'id='.$id, $_SESSION['bread_array'])) {
		$index = array_search($site.'id='.$id, $_SESSION['bread_array']);
		array_splice($_SESSION['bread_array'], -$index);
	}

	if ($view['position'] == 'command') {
		$db->set_where(['position' => 'command']);
		$commands = $db->select('s');
		$bread_cumb = "Команды <span class=\"command_count\">{$commands->num_rows}</span>";
		if ($_SESSION['bread_cumbs'] == '') {
			$_SESSION['bread_cumbs'] = "Команды <span class=\"command_count\">{$commands->num_rows}</span>";
			$_SESSION['bread_array'][] = $site.'id='.$id;
			$_SESSION['bread_names'][$site.'id='.$id] = $view['name'];
		}
		elseif ($_SESSION['bread_cumbs_last_update'] != $site.'id='.$id) {
			$bread_cumbs = true;
			$_SESSION['bread_array'][] = $site.'id='.$id;
			$_SESSION['bread_names'][$site.'id='.$id] = $view['name'];
		}
		$_SESSION['bread_cumbs_last_update'] = $site.'id='.$id;


		$view_position = 'command';
	} elseif ($view['position'] == 'partner') {
		$db->set_where(['position' => 'partner']);
		$partners = $db->select('s');
		$bread_cumb = "Партнеры <span class=\"command_count\">{$partners->num_rows}</span>";
		if ($_SESSION['bread_cumbs'] == '') {
			$_SESSION['bread_cumbs'] = "Партнеры <span class=\"command_count\">{$partners->num_rows}</span>";
			$_SESSION['bread_array'][] = $site.'id='.$id;
			$_SESSION['bread_names'][$site.'id='.$id] = $view['name'];
		}
		elseif ($_SESSION['bread_cumbs_last_update'] != $site.'id='.$id) {
			$bread_cumbs = true;
			$_SESSION['bread_array'][] = $site.'id='.$id;
			$_SESSION['bread_names'][$site.'id='.$id] = $view['name'];
		}
		$_SESSION['bread_cumbs_last_update'] = $site.'id='.$id;

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
	$db->set_table('users');
	$db->set_where(['id' => $view['parent']]);
	$command = $db->select('i')->fetch_array(MYSQLI_ASSOC);

	$cumbs .= "<a href=\"/view.php?id={$command['id']}\"><span class=\"back_bc\">&rarr;</span>{$command['name']}</a>";
} elseif ($view_position == 'client') {
	$db_shop->set_table('wp_users');
	$db_shop->set_where(['id' => $id]);
	$code = $db_shop->select('i')->fetch_array(MYSQLI_ASSOC);

	$parent = $code['parent'];
	$code = $code['code'];

	$db_shop->set_where(['code' => $parent]);
	$flag = $db_shop->select('s');
	$breads_links = array();
	while ($flag->num_rows) {
		$db_shop->set_table('wp_users');
		$db_shop->set_where(['code' => $parent]);
		$flag = $db_shop->select('s')->fetch_array(MYSQLI_ASSOC);
		$parent = $flag['parent'];

		// get name
		$db_shop->set_table('wp_usermeta');
		$db_shop->set_where(['user_id' => $flag['ID'], 'meta_key' => 'first_name']);
		$meta_array = $db_shop->select('is');
		if ($meta_array)
			$client['first_name'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];
		// get second name
		$db_shop->set_where(['user_id' => $flag['ID'], 'meta_key' => 'last_name']);
		$meta_array = $db_shop->select('is');
		if ($meta_array)
			$client['last_name'] = $meta_array->fetch_array(MYSQLI_ASSOC)['meta_value'];

		$breads_links[] = "<a href=\"/view.php?id=client{$flag['ID']}\"><span class=\"back_bc\">&rarr;</span>{$client['first_name']} {$client['last_name']}</a>";
	}

	$db->set_table('users');
	$db->set_where(['code' => $parent]);
	$partner = $db->select('s');
	if ($partner) {
		$partner = $partner->fetch_array(MYSQLI_ASSOC);

		$db->set_where(['id' => $partner['parent']]);
		$command = $db->select('i');
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
