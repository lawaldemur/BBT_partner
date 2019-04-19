<?php
// выбор колва отображаемых строк в таблице
function table_sizes($rows, $add_class='')
{
	include 'templates/table_sizes.php';
}

// пагинация
function pagination_list($page, $pages, $page_file_name, $search='', $add_class='', $page_class='')
{
	include 'templates/pagination_list.php';
}

// поле поиска в таблице
function search_table($id, $placeholder, $value='', $get_rid_autocomplete=false)
{
	include 'templates/search_table.php';
}

// выбор отображаемого периода дат
function change_date($period, $calendarText, $drop_list=false)
{
	include 'templates/change_date.php';
}

// выбор формата книг
function choose_format($format='all')
{
	include 'templates/choose_format.php';
}

// выбор сортировки по дате или по книгам
function sort_date_or_book($sort)
{
	include 'templates/sort_date_or_book.php';
}

// вывести th
function table_th($text, $data_col='', $class='', $style='')
{
	include 'templates/table_th.php';
}

// вывести простой td
function simple_td($value, $class='', $style='')
{
	include 'templates/simple_td.php';
}

// td для имени и изображения товара
function product_name_td($img, $name, $author)
{
	include 'templates/product_name_td.php';
}

// создать input:hidden для js
function create_hidden($id, $value)
{
	include 'templates/create_hidden.php';
}

// элементы после таблиц
function after_table_filters($hiddens, $pagination, $table_sizes, $add_class='', $add_class_pag_list='')
{
	include 'templates/after_table_filters.php';
}

// вкладки страницы аналитика
function analitics_tabs()
{
	include 'templates/analitics_tabs.php';
}

// просмотрщик документов
function document_viewer()
{
	include 'templates/document_viewer.php';
}

// хлебные крошки
function bread_cumbs_row($cumbs)
{
	include 'templates/bread_cumbs_row.php';
}

// вкладка О команде/партнере/клиенте на странице view.php
function view_about_row($data, $view_position)
{
	include 'templates/view_about_row.php';
}

// вкладка О команде
function view_about_command($data)
{
	include 'templates/view_about_command.php';
}

// вкладка О партнере
function view_about_partner($data)
{
	include 'templates/view_about_partner.php';
}

// вкладка О клиенте
function view_about_client($data)
{
	include 'templates/view_about_client.php';
}

// вкладка О клиенте
function view_info_row($referer, $picture, $name, $address,
	$view_position, $earn)
{
	include 'templates/view_info_row.php';
}

// вкладки на странице view.php
function view_tabs($view_position, $role)
{
	include 'templates/view_tabs.php';
}

// вкладки команд на странице view.php
function view_tabs_command()
{
	include 'templates/view_tabs_command.php';
}

// вкладки команд на странице view.php
function view_tabs_partner()
{
	include 'templates/view_tabs_partner.php';
}

// вкладки команд на странице view.php
function view_tabs_client()
{
	include 'templates/view_tabs_client.php';
}

// вкладки выручка на странице view.php
function graph_view()
{
	include 'templates/graph_view.php';
}

// блок уведомления
function notification()
{
	include 'templates/notification.php';
}

// profile_list_row.php
function profile_list_row()
{
	include 'templates/profile_list_row.php';
}

// ББТ profile.php поля
function profile_bbt($data)
{
	include 'templates/profile_bbt.php';
}

// Команда profile.php поля
function profile_command($data)
{
	include 'templates/profile_command.php';
}

// Партнер profile.php поля
function profile_partner($data)
{
	include 'templates/profile_partner.php';
}

// заголовок settings.php
function settings_title()
{
	include 'templates/settings_title.php';
}

// контент (поля смены логина/пароля) settings.php
function settings_content($id, $email, $position, $auth_method)
{
	include 'templates/settings_content.php';
}

// страница forgot_pass шаг 1
function forgot_pass_step_1()
{
	include 'templates/forgot_pass_step_1.php';
}

// страница forgot_pass шаг 2
function forgot_pass_step_2($id, $pass)
{
	include 'templates/forgot_pass_step_2.php';
}

// overlay
function overlay_form()
{
	include 'templates/overlay_form.php';
}

// заголовок
function page_title($logo, $count, $access, $role='', $btn_text='')
{
	include 'templates/page_title.php';
}

// форма проверки пароля на commands.php и partners.php
function check_password_form($class, $login, $id)
{
	include 'templates/check_password_form.php';
}

// форма подтверждения удаления команды
function delete_command_form()
{
	include 'templates/delete_command_form.php';
}

// форма контроля партнера
function control_partner_form()
{
	include 'templates/control_partner_form.php';
}

// форма контроля команды
function control_command_form()
{
	include 'templates/control_command_form.php';
}

// форма создания команды
function form_add_command()
{
	include 'templates/form_add_command.php';
}

// форма создания партнера
function form_add_partner()
{
	include 'templates/form_add_partner.php';
}

// блок изображения и имени команды
function table_command_name($picture, $name, $other, $avatars=true)
{
	include 'templates/table_command_name.php';
}

// tbody tr таблицы команд
function commands_tbody_tr($array)
{
	include 'templates/commands_tbody_tr.php';
}

// tbody tr таблицы партнеров
function partners_tbody_tr($array, $role)
{
	include 'templates/partner_tbody_tr.php';
}

// tbody tr таблицы клиентов
function clients_tbody_tr($array)
{
	include 'templates/clients_tbody_tr.php';
}

// input:file загрузки отчетов/актов
function finance_upload_report()
{
	include 'templates/finance_upload_report.php';
}

// вкладки finance.php
function finance_tabs($role)
{
	include 'templates/finance_tabs.php';
}

// finance.php блок над графиком для ББТ
function finance_brief_result_bbt($n1, $n2)
{
	include 'templates/finance_brief_result_bbt.php';
}

// finance.php блок над графиком для команд и партнеров
function finance_brief_result($n)
{
	include 'templates/finance_brief_result.php';
}

// профиль команды/партнера на странице finance.php
function finance_user_view_row($after_table_filters)
{
	include 'templates/finance_user_view_row.php';
}

// таблица отчетов для партнеров
function finance_partner_table()
{
	include 'templates/finance_partner_table.php';
}

// таблица списка отчетов от команд для ббт
function finance_bbt_reports_from_command()
{
	include 'templates/finance_bbt_reports_from_command.php';
}

// таблица загрузки отчетов от команд для ББТ
function finance_command_for_bbt()
{
	include 'templates/finance_command_for_bbt.php';
}

// таблица принятия отчетов от партнеров командой
function finance_command_from_partner()
{
	include 'templates/finance_command_from_partner.php';
}

// скелет графика на странице finance.php
function graph_row()
{
	include 'templates/graph_row.php';
}

// таблцица заработка по месяцам для ББТ
function finance_earn_table($show_earn_table, $earn, $pagination, $table_sizes)
{
	include 'templates/finance_earn_table.php';
}

// строка таблицы проданных книг на странице analitic.php
function analitic_books_tr($sort, $array, $role)
{
	include 'templates/analitic_books_tr.php';
}

// строка таблицы просмотренных книг на странице analitic.php
function analitic_views_tr($array)
{
	include 'templates/analitic_views_tr.php';
}
