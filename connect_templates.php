<?php
// выбор колва отображаемых строк в таблице
function table_sizes($rows)
{
	include 'templates/table_sizes.php';
}

// пагинация
function pagination_list($page, $pages, $page_file_name)
{
	include 'templates/pagination_list.php';
}

// поле поиска в таблице
function search_table($id, $placeholder, $value='')
{
	include 'templates/search_table.php';
}

// выбор отображаемого периода дат
function change_date($period, $calendarText)
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
function table_th($text, $data_col, $class)
{
	include 'templates/table_th.php';
}

// вывести простой td
function simple_td($value, $class='')
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

function after_table_filters($hiddens, $pagination, $table_sizes)
{
	include 'templates/after_table_filters.php';
}
