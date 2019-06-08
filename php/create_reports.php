<?php
if (!(isset($_GET['code']) && $_GET['code'] == '9e2d002473ba9abbtonlinea88eada87c25188396'))
	exit('access denied');

require_once '../db.php';
require_once '../db_shop.php';
require_once 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet as Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Xlsx;

\PhpOffice\PhpWord\Settings::setPdfRendererPath('vendor/dompdf/dompdf');
\PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');

$class = \PhpOffice\PhpSpreadsheet\Writer\Pdf\Tcpdf::class;
\PhpOffice\PhpSpreadsheet\IOFactory::registerWriter('Pdf', $class);


if (true) {
	$date_create = new DateTime("first day of this week");
	$date = $date_create->format('Y-m-d');
	$date_act_from = $date_create->format('d.m.Y');
	$date_smth = new DateTime("today");
	$date_act = $date_smth->format('d.m.Y');
	$finish_date = new DateTime("last day of this week");
	$finish_date = $finish_date->format('d.m.Y');
} else {
	$date_create = new DateTime("first day of last month");
	$date = $date_create->format('Y-m-d');
	$date_act_from = $date_create->format('d.m.Y');
	$date_smth = new DateTime("today");
	$date_act = $date_smth->format('d.m.Y');
	$finish_date = new DateTime("last day of last month");
	$finish_date = $finish_date->format('d.m.Y');
}

$folder = '../service/reports/'.$date_create->format('m.y').'_raw/';
mkdir($folder);
mkdir($folder.'excel/');
mkdir('../service/reports/'.$date_create->format('m.y').'_done/');

$folder_act = '../service/acts/'.$date_create->format('m.y').'_raw/';
$folder_act2 = $_SERVER['DOCUMENT_ROOT'].'/service/acts/'.$date_create->format('m.y').'_raw/';
mkdir($folder_act);
mkdir($folder_act.'word/');
mkdir('../service/acts/'.$date_create->format('m.y').'_done/');

$months = array(
	'Январь',
	'Февраль',
	'Март',
	'Апрель',
	'Май',
	'Июнь',
	'Июль',
	'Август',
	'Сентябрь',
	'Октябрь',
	'Ноябрь',
	'Декабрь'
);

// select all users
$db->set_table('users');
$db->set_where([]);
$users = $db->select();
foreach ($users as $user) {
	if ($user['position'] == 'BBT')
		continue;

	if ($user['position'] == 'command') {
		$to_id = 1;
		$db->set_table('sold');
		$db->set_where(['to_command_id' => $user['id'], 'date_more_equal' => $date]);
		$sold_array = $db->select('is');
		$to = 'to_command';
	} elseif ($user['position'] == 'partner') {
		$to_id = $user['parent'];
		$db->set_table('sold');
		$db->set_where(['to_partner_id' => $user['id'], 'date_more_equal' => $date]);
		$sold_array = $db->select('is');
		$to = 'to_partner';
	}

	$objPHPExcel = new Spreadsheet();
	$objPHPExcel->setActiveSheetIndex(0);
	$active_sheet = $objPHPExcel->getActiveSheet();

	$active_sheet->getStyle("B10:G10")->getAlignment()->setWrapText(true);
	$active_sheet->getStyle("B1:G1")->getAlignment()->setHorizontal('center');
	$active_sheet->getStyle("B2:G2")->getAlignment()->setHorizontal('center');
	$active_sheet->getStyle("C3:E3")->getAlignment()->setHorizontal('center');
	$active_sheet->getStyle("B7:G7")->getAlignment()->setHorizontal('center');

	// set width of columns
	$active_sheet->getColumnDimension('B')->setWidth(20);
	$active_sheet->getColumnDimension('C')->setWidth(20);
	$active_sheet->getColumnDimension('D')->setWidth(10);
	$active_sheet->getColumnDimension('E')->setWidth(10);
	$active_sheet->getColumnDimension('F')->setWidth(10);
	$active_sheet->getColumnDimension('G')->setWidth(11);
	// set height of rows
	$active_sheet->getRowDimension('10')->setRowHeight(60);

	$info = unserialize($user['data']);
	// set content
	$active_sheet->mergeCells("B1:G1");
	$active_sheet->setCellValue('B1','ПРИЛОЖЕНИЕ № 1');
	$active_sheet->getStyle("B1:G1")->getAlignment()->setWrapText(true);

	if ($user['position'] == 'command') {
		$active_sheet->mergeCells("B2:G2");
		$active_sheet->getStyle("B2:G2")->getAlignment()->setWrapText(true);
		$active_sheet->setCellValue('B2','к Агентскому договору об участии в партнерской программе №'.$info['dogovor_number']);

		$active_sheet->mergeCells("C3:E3");
		$active_sheet->getStyle("C3:E3")->getAlignment()->setWrapText(true);
		$active_sheet->setCellValue('C3','от '.$info['dogovor_date']);

		$active_sheet->mergeCells("B7:G7");
		$active_sheet->getStyle("B7:G7")->getAlignment()->setWrapText(true);
		$active_sheet->setCellValue('B7','Отчет Агента '.$user['name'].' за период с '.$date_act_from.' по '.$finish_date);
	} else {
		$active_sheet->mergeCells("B2:G2");
		$active_sheet->getStyle("B2:G2")->getAlignment()->setWrapText(true);
		$active_sheet->setCellValue('B2','к Субгентскому договору об участии в партнерской программе №1');

		$active_sheet->mergeCells("C3:E3");
		$active_sheet->getStyle("C3:E3")->getAlignment()->setWrapText(true);
		$active_sheet->setCellValue('C3','от '.$date_act_from.' г.');

		$active_sheet->mergeCells("B7:G7");
		$active_sheet->getStyle("B7:G7")->getAlignment()->setWrapText(true);
		$active_sheet->setCellValue('B7','Отчет Субагента '.$user['name'].' за период с '.$date_act_from.' по '.$finish_date);
	}
	

	// set header of table
	if ($user['position'] == 'command') {
		$active_sheet->setCellValue('B10','Полное наименование реализованного Товара');
		$active_sheet->setCellValue('C10','Количество реализованного Товара, шт.');
		$active_sheet->setCellValue('D10','Цена Товара, руб.');
		$active_sheet->setCellValue('E10','Стоимость Товара, руб.');
		$active_sheet->setCellValue('F10','Ставка вознаграждения, %');
		$active_sheet->setCellValue('G10','Сумма вознаграждения, руб.');
	} else {
		$active_sheet->setCellValue('B10','Полное наименование приобретенного Товара');
		$active_sheet->setCellValue('C10','Количество приобретенного Товара, шт.');
		$active_sheet->setCellValue('D10','Цена Товара, руб.');
		$active_sheet->setCellValue('E10','Стоимость Товара, руб.');
		$active_sheet->setCellValue('F10','Ставка вознаграждения, %');
		$active_sheet->setCellValue('G10','Сумма вознаграждения, руб.');
	}


	$active_sheet->getStyle('B7:G7')->applyFromArray(
		array(
			'font'=>array(
				'bold' => true,
			),
		)
	);
	$active_sheet->getStyle('B10:G10')->applyFromArray(
		array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'indent' => 3
			),
			'font'=>array(
				'bold' => true,
				// 'italic' => true,
				'size' => 10,
				//'name' => 'Times',
			),
			'borders'=>array(
				'allBorders' => array(
					'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,'color' => ['rgb' => '000000'],
				),
			)
		)
	);

	$content_style = array(
			'font'=>array(
				'size' => 10,
				//'name' => 'Times',
			),
			'borders'=>array(
				'allBorders' => array(
					'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => ['rgb' => '000000'],
				),
			)
		);

	$sum = 0;	
	$i = 11;
	$act_content = array();
	foreach ($sold_array as $sold) {
		$db_shop->set_table('wp_posts');
		$db_shop->set_where(['ID' => $sold['product']]);
		$product = $db_shop->select('i')->fetch_array(MYSQLI_ASSOC)['post_title'];

		$active_sheet->setCellValue('B'.$i, $product);

		$db_shop->set_table('wp_postmeta');
		$db_shop->set_where(['post_id' => $sold['variation'], 'meta_key' => '_price']);
		$price = $db_shop->select('is')->fetch_array(MYSQLI_ASSOC)['meta_value'];

		$active_sheet->setCellValue('C'.$i, $sold['summ'] / $price);
		$active_sheet->setCellValue('D'.$i, $price);
		$active_sheet->setCellValue('E'.$i, $sold['summ']);

		$active_sheet->setCellValue('F'.$i, $user[$sold['format'].'_percent']);
		$active_sheet->setCellValue('G'.$i, $sold[$to]);

		$active_sheet->getStyle('B'.$i.':G'.$i)->applyFromArray($content_style);
		$active_sheet->getStyle('B'.$i.':G'.$i)->getAlignment()->setWrapText(true);

		$sum += $sold[$to];
		$i++;

		$act_content[] = array(
			$product, $sold['summ'] / $price,
			$price, $sold['summ'],
			$user[$sold['format'].'_percent'], $sold[$to]
		);
	}


	$active_sheet->mergeCells('B'.$i.':F'.$i);
	$active_sheet->setCellValue('B'.$i, 'ИТОГО:');
	$active_sheet->setCellValue('G'.$i, $sum . ' руб.');
	$active_sheet->getStyle('B'.$i.':G'.$i)->applyFromArray(
		array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
				'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
				'indent' => 3
			),
			'font'=>array(
				'bold' => true,
				// 'italic' => true,
				'size' => 10,
				//'name' => 'Times',
			),
			'borders'=>array(
				'allBorders' => array(
					'borderStyle'=>\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,'color' => ['rgb' => '000000'],
				),
			)
		)
	);

	$i += 5;

	if ($user['position'] == 'command')
		$active_sheet->setCellValue('B'.$i, 'АГЕНТ');
	else
		$active_sheet->setCellValue('B'.$i, 'СУБАГЕНТ');
	
	$active_sheet->getStyle('B'.$i)->applyFromArray(
		array(
			'font'=>array(
				'bold' => true,
				'size' => 12,
				//'name' => 'Times',
			),
		)
	);

	$i += 2;
	$active_sheet->mergeCells("B$i:D$i");
	$active_sheet->setCellValue('B'.$i, '________________________');


	$objPHPExcel->getActiveSheet()->setShowGridLines(false);
	$objPHPExcel->getActiveSheet()->getPageSetup()
    ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);
	$objPHPExcel->getActiveSheet()->getPageSetup()
	    ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
	// save document
	$objWriter = new Xlsx($objPHPExcel);
	// $file_name = 'Отчёт – '.$user['name'].' – '.strftime('%B %Y', strtotime($date));
	$file_name = 'Отчёт – '.$user['name'].' – '.$months[intval(date('n', strtotime($date)))- 1].' '.strftime('%Y', strtotime($date));
	$file_name2 = 'excel/'.$file_name.'.xlsx';
	$objWriter->save($folder.$file_name2);
	// save as pdf
	$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, 'Pdf');
	$writer->save($folder.$file_name.'.pdf');



	// make act
	// $info = unserialize($user['data']);
	if ($user['position'] == 'command') {
		$phpWord = new \PhpOffice\PhpWord\TemplateProcessor('samples/command_act.docx');
		$phpWord->setValue('number', $info['dogovor_number']);
		$phpWord->setValue('date', $info['dogovor_date']);
		$phpWord->setValue('current_date', $date_act);
		$phpWord->setValue('number2', $info['dogovor_number']);
		$phpWord->setValue('city', $user['city']);
		$phpWord->setValue('name', $user['name']);
		$phpWord->setValue('director', $info['general_name']);
		$phpWord->setValue('base', $info['organizator_base']);
		$phpWord->setValue('from', $date_act_from);
		$phpWord->setValue('to', $date_act);

		$phpWord->cloneRow('SR', count($act_content));
		for ($i=0; $i < count($act_content); $i++) { 
			$phpWord->setValue('SR#'.($i + 1), $act_content[$i][0]);
			$phpWord->setValue('SR2#'.($i + 1), $act_content[$i][1]);
			$phpWord->setValue('SR3#'.($i + 1), $act_content[$i][2]);
			$phpWord->setValue('SR4#'.($i + 1), $act_content[$i][3]);
			$phpWord->setValue('SR5#'.($i + 1), $act_content[$i][4]);
			$phpWord->setValue('SR6#'.($i + 1), $act_content[$i][5]);
		}
		$phpWord->setValue('total', $sum);


		// save as docx
		$file_name_act = 'Акт – '.$user['name'].' – '.$months[intval(date('n', strtotime($date)))- 1].' '.strftime('%Y', strtotime($date));
		$file_act = 'word/'.$file_name_act.'.docx';
		$phpWord->saveAs($folder_act.$file_act);
		// open docx -> convert to pdf
		$phpWord = \PhpOffice\PhpWord\IOFactory::load($folder_act.$file_act); 
		$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');
		$file_pdf = $file_name_act.'.pdf';
		$xmlWriter->save($folder_act.$file_pdf);
	} elseif ($user['position'] == 'partner') {
		$db->set_table('users');
		$db->set_where(['id' => $user['parent']]);
		$parent = $db->select('i')->fetch_array(MYSQLI_ASSOC);
		$parent_info = unserialize($parent['data']);

		$phpWord = new \PhpOffice\PhpWord\TemplateProcessor('samples/partner_act.docx');
		
		$phpWord->setValue('city', $user['city']);
		$phpWord->setValue('current_date', $date_act);
		// ================================
		$phpWord->setValue('command_name', $parent['name']);
		$phpWord->setValue('command_director', $parent_info['general_name']);
		$phpWord->setValue('command_base', '"Основание"');
		$phpWord->setValue('partner_name', $user['name']);
		$phpWord->setValue('from', $date_act_from);
		$phpWord->setValue('to', $date_act);
		$phpWord->setValue('partner_addres', $info['general_address']);
		$phpWord->setValue('passport', $info['pasport_seria'].' '.$info['pasport_number']);

		$phpWord->setValue('name', $user['name']);
		$phpWord->setValue('passport2', $info['pasport_seria'].' '.$info['pasport_number']);
		$phpWord->setValue('address2', $info['general_address']);
		$phpWord->setValue('bank_info', "Р/с {$info['bank_bill']} в {$info['bank_name']}
		БИК {$info['bank_bik']}
		к/с {$info['bank_chet']}");


		$phpWord->cloneRow('SR', count($act_content));
		for ($i=0; $i < count($act_content); $i++) { 
			$phpWord->setValue('SR#'.($i + 1), $act_content[$i][0]);
			$phpWord->setValue('SR2#'.($i + 1), $act_content[$i][1]);
			$phpWord->setValue('SR3#'.($i + 1), $act_content[$i][2]);
			$phpWord->setValue('SR4#'.($i + 1), $act_content[$i][3]);
			$phpWord->setValue('SR5#'.($i + 1), $act_content[$i][4]);
			$phpWord->setValue('SR6#'.($i + 1), $act_content[$i][5]);
		}
		$phpWord->setValue('total', $sum);

		$file_name_act = 'Акт – '.$user['name'].' – '.$months[intval(date('n', strtotime($date)))- 1].' '.strftime('%Y', strtotime($date));
		$file_act = 'word/'.$file_name_act. '.docx';
		$phpWord->saveAs($folder_act.$file_act);
		// open docx -> convert to pdf
		$phpWord = \PhpOffice\PhpWord\IOFactory::load($folder_act.$file_act); 
		$xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord , 'PDF');
		$file_pdf = $file_name_act.'.pdf';
		$xmlWriter->save($folder_act.$file_pdf);
	}

	// save info to database
	$db->set_table('reports');
	$db->set_insert([
		'date' => $date,
		'from_id' => $user['id'],
		'to_id' => $to_id,
		'sum' => $sum,
		'report_raw' => $file_name,
		'act_raw' => $file_name_act,
	]);
	$db->insert('siiiss');
}

echo "success";
exit();
