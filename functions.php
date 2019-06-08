<?php
session_start();
// get connect to db
require_once 'db.php';
// get template connecting functions
require_once 'connect_templates.php';
// get months names
require_once './php/months_names.php';

// path to home directory
$home = '/home/h809274500/partner.bbt-online.ru/docs';
// define active page
$active_page = explode('?', $_SERVER['REQUEST_URI'])[0];

// connecting styles to header
$header_connect = '';
$header_connect .= '<link href="https://fonts.googleapis.com/css?family=Montserrat:400,600|Prata" rel="stylesheet">'; #fonts
$header_connect .= '<link rel="stylesheet" href="/libs/bootstrap.css">';
$header_connect .= '<link rel="stylesheet" href="/libs/normalize.css">';
$header_connect .= '<link rel="stylesheet" href="/css/style.css">';
// connecting scripts to footer
$footer_connect = '';
$footer_connect .= '<script src="/libs/jquery.js" type="text/javascript"></script>';
$footer_connect .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>';
$footer_connect .= '<script src="/js/script.js" type="text/javascript"></script>';

// exist pages array
$pages = array(
	'analitic' => '/analitic.php',
	'commands' => '/commands.php',
	'partners' => '/partners.php',
	'clients' => '/clients.php',
	'view' => '/view.php',
	'finance' => '/finance.php',
	'profile' => '/profile.php',
	'settings' => '/settings.php',
	'entrance' => '/', # because '/index.php' == '/'
	'forgot_pass' => '/forgot_password.php',
);

// define user
$user = false;
if (isset($_SESSION['logged']))
	$user = $_SESSION['logged'];
elseif (isset($_COOKIE['logged']))
	$user = $_COOKIE['logged'];

if ($user) {
	// transform hash to str
	$db->set_where(['auth' => $user]);
	$db->set_table('users');
	$user = $db->select('s');

	if (!$user || $user->num_rows === 0)
		$user = false;
	else {
		$user = $user->fetch_array(MYSQLI_ASSOC);
		$user = $user['position'].'|'.$user['login'].'|'.$user['id'];
	}
}

// if user logged and on entrance page then redirect to analitics page
if ($active_page == $pages['entrance'] && $user) {
	header('Location: '.$pages['analitic']);
	exit();
} // if not logged and not on entrance page then redirect to entrance page
elseif (!$user && !($active_page == $pages['entrance'] || $active_page == $pages['forgot_pass'])) {
	header('Location: '.$pages['entrance']);
	exit();
}


// page title
if ($pages['analitic'] == $active_page) {
	$title = 'Аналитика';
	$add_class_to_content_wrapper = 'grey_bg';
} elseif ($pages['commands'] == $active_page) {
	$title = 'Команды';
	$add_class_to_content_wrapper = 'grey_bg';
} elseif ($pages['partners'] == $active_page) {
	$title = 'Партнеры';
	$add_class_to_content_wrapper = 'grey_bg';
} elseif ($pages['clients'] == $active_page) {
	$title = 'Клиенты';
	$add_class_to_content_wrapper = 'grey_bg';
} elseif ($pages['view'] == $active_page) {
	$add_class_to_content_wrapper = 'grey_bg';
} elseif ($pages['finance'] == $active_page) {
	$title = 'Финансы';
	$add_class_to_content_wrapper = 'grey_bg';
} elseif ($pages['profile'] == $active_page) {
	$title = 'Профиль';
	$add_class_to_content_wrapper = 'grey_bg';
} elseif ($pages['settings'] == $active_page) {
	$title = 'Настройки';
	$add_class_to_content_wrapper = 'grey_bg';
} elseif ($pages['entrance'] == $active_page) {
	$title = 'Вход';
	$add_class_to_content_wrapper = 'grey_bg';
} elseif ($pages['forgot_pass'] == $active_page) {
	$title = 'Восcтановить пароль';
	$add_class_to_content_wrapper = 'grey_bg align_content';
} else {
	$title = 'Ошибка 404';
	$add_class_to_content_wrapper = '';
}
// append info
$title_post_text = ' — Партнерская программа ББТ';
$title .= $title_post_text;

// save user_id
$user_id = explode('|', $user)[2];


// return zero if user not logged or user role
if ($user === false)
	$role = false;
elseif (strpos($user, 'BBT') !== false)
	$role = 'ББТ';
elseif (strpos($user, 'command') !== false)
	$role = 'Команда';
elseif (strpos($user, 'partner') !== false)
	$role = 'Партнер';


if ($active_page == $pages['finance']) {
	$header_connect .= '<link rel="stylesheet" href="/css/finance.css">';

	// charts
	$footer_connect .= '<script src="/libs/amcharts/core.js"></script>';
	$footer_connect .= '<script src="/libs/amcharts/charts.js"></script>';
	$footer_connect .= '<script src="/libs/amcharts/animated.js"></script>';
	$footer_connect .= '<script src="/libs/amcharts/ru_RU.js"></script>';


	$footer_connect .= '<script src="/js/calendar.js" type="text/javascript"></script>';
	if ($role == 'ББТ')
		$footer_connect .= '<script src="/js/finance.js" type="text/javascript"></script>';
	elseif ($role == 'Команда')
		$footer_connect .= '<script src="/js/finance_command.js" type="text/javascript"></script>';
	elseif ($role == 'Партнер')
		$footer_connect .= '<script src="/js/finance_partner.js" type="text/javascript"></script>';
} elseif ($active_page == $pages['analitic']) {
	$footer_connect .= '<script src="/js/analitic.js" type="text/javascript"></script>';
	$footer_connect .= '<script src="/js/calendar.js" type="text/javascript"></script>';
} elseif ($active_page == $pages['commands'] || $active_page == $pages['partners']) {
	$footer_connect .= '<script src="/js/user_list.js" type="text/javascript"></script>';
	$footer_connect .= '<script src="/js/calendar.js" type="text/javascript"></script>';
} elseif ($active_page == $pages['clients']) {
	$footer_connect .= '<script src="/js/clients.js" type="text/javascript"></script>';
	$footer_connect .= '<script src="/js/calendar.js" type="text/javascript"></script>';
} elseif ($active_page == $pages['view']) {
	// charts
	$footer_connect .= '<script src="/libs/amcharts/core.js"></script>';
	$footer_connect .= '<script src="/libs/amcharts/charts.js"></script>';
	$footer_connect .= '<script src="/libs/amcharts/animated.js"></script>';
	$footer_connect .= '<script src="/libs/amcharts/ru_RU.js"></script>';
	
	$footer_connect .= '<script src="/js/view.js" type="text/javascript"></script>';
	$footer_connect .= '<script src="/js/calendar.js" type="text/javascript"></script>';
} elseif ($active_page == $pages['forgot_pass']) {
	$footer_connect .= '<script src="/js/forgot.js" type="text/javascript"></script>';
}

// forbide for all roles except bbt access to commands/command page
if (($pages['commands'] == $active_page || $pages['command'] == $active_page) && $role != 'ББТ') {
	header('Location: '.$pages['analitic']);
	exit();
}
// forbide for partners access to partners/partner page
if (($pages['partners'] == $active_page || $pages['partner'] == $active_page) && $role == 'Партнер') {
	header('Location: '.$pages['analitic']);
	exit();
}


// clear bread cumbs
if ($pages['view'] != $active_page) {
	$_SESSION['bread_cumbs'] = '';
	$_SESSION['bread_array'] = array();
	$_SESSION['bread_names'] = array();
	$_SESSION['bread_cumbs_last_update'] = '';
	$_SESSION['prev_bread_cumbs'] = '';
}

// имя пользователя в header'е
if ($role != 'ББТ') {
	$db->set_table('users');
	$db->set_where(['id' => $user_id]);
	$user_name = $db->select('i')->fetch_array(MYSQLI_ASSOC)['name'];
} else {
	$user_name = 'ББТ';
}

// only for partners
if ($role == 'Партнер') {
	$db->set_table('users');
	$db->set_update(['logged' => 1]);
	$db->set_where(['id' => $user_id]);
	$db->update('ii');
}


if (isset($_COOKIE['period']) && $_COOKIE['period'] != '`date` >= CURDATE()' && strpos($_COOKIE['period'], ' = ') === false && strpos($_COOKIE['period'], '>=C') === false) {
	$_COOKIE['period'] = implode(' = ', explode('=', $_COOKIE['period']));
}
if ($_COOKIE['period'] == '`date` >=CURDATE()')
	$_COOKIE['period'] = '`date` >= CURDATE()';


// for safari
$_COOKIE['calendarText'] = urldecode($_COOKIE['calendarText']);