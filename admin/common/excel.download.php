<?php
$Dir="../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");
include_once($Dir."/admin/_config/excel_format.php");
include $Dir.'/lib/PHPExcel/Classes/PHPExcel.php';

$g_mode = $_POST['type'];
$g_column = $_POST['column'];
$g_search = $_POST['search'];



switch($g_mode) {
	case 'product': //상품목록
		$inc = "./excel/product.php";
		break;
	case 'point': //포인트목록
		$inc = "./excel/point.php";
		break;
	case 'mileage': //마일리지목록
		$inc = "./excel/mileage.php";
		break;
	case 'member': //회원목록
		$inc = "./excel/member.php";
		break;
	case 'memberout': //탈퇴회원목록
		$inc = "./excel/memberout.php";
		break;
	case 'colorchip': //컬러칩목록
		$inc = "./excel/colorchip.php";
		break;
	case 'event_comment': //이벤트댓글
		$inc = "./excel/event_comment.php";
		break;
	case 'order_all': //전체주문목록
		$inc = "./excel/order_all.php";
		break;
	case 'order_step1': //주문완료목록
		$inc = "./excel/order_step1.php";
		break;
	case 'order_step2': //결제완료목록
		$inc = "./excel/order_step2.php";
		break;
	case 'order_step3': //배송준비중목록
		$inc = "./excel/order_step3.php";
		break;
	case 'order_step4': //배송중목록
		$inc = "./excel/order_step4.php";
		break;
	case 'order_step5': //배송완료목록
		$inc = "./excel/order_step5.php";
		break;
	case 'cs_cancel': //취소목록
		$inc = "./excel/cs_cancel.php";
		break;
	case 'cs_exchange': //교환목록
		$inc = "./excel/cs_exchange.php";
		break;
	case 'cs_return': //반품목록
		$inc = "./excel/cs_return.php";
		break;
	case 'deli_area': //지역별배송비
		$inc = "./excel/deli_area.php";
		break;
	case 'membersleep': //휴면회원
		$inc = "./excel/membersleep.php";
		break;
	case 'sales_price_day': //매출관리 > 일자별
		$inc = "./excel/sales_price_day.php";
		break;
	case 'sales_price_month': //매출관리 > 월별
		$inc = "./excel/sales_price_month.php";
		break;
	case 'sales_price_paymethod': //매출관리 > 결제수단별
		$inc = "./excel/sales_price_paymethod.php";
		break;
	case 'sales_price_order': //매출관리 > 주문건별
		$inc = "./excel/sales_price_order.php";
		break;
	case 'sales_price_option': //매출관리 > 품목별
		$inc = "./excel/sales_price_option.php";
		break;
	case 'sales_category': //매출분석 > 카테고리
	$inc = "./excel/sales_category.php";
		break;
	default:
		break;
}
include_once($inc); //다운로드 데이터 정의

$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_sqlite;
$cacheSettings = array('memoryCacheSize' => '32MB');
PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

$Excel = new PHPExcel();


switch($g_mode) {
	case 'sales_price_day':
	case 'sales_price_month':
		$data = array_merge($headers, $list);
		$Excel->setActiveSheetIndex(0)->mergeCells('A1:A2');
		$Excel->setActiveSheetIndex(0)->mergeCells('B1:I1');
		$Excel->setActiveSheetIndex(0)->mergeCells('J1:Q1');
		$Excel->setActiveSheetIndex(0)->mergeCells('R1:Y1');
		$Excel->getActiveSheet()->getColumnDimension('A')->setWidth("15");

		$last_char = 'Y2';
		break;
	case 'sales_price_order';
		$data = array_merge($headers, $list);
		$Excel->setActiveSheetIndex(0)->mergeCells('A1:A2');
		$Excel->setActiveSheetIndex(0)->mergeCells('B1:B2');
		$Excel->setActiveSheetIndex(0)->mergeCells('C1:C2');
		$Excel->setActiveSheetIndex(0)->mergeCells('D1:D2');
		$Excel->setActiveSheetIndex(0)->mergeCells('E1:E2');
		$Excel->setActiveSheetIndex(0)->mergeCells('F1:K1');
		$Excel->setActiveSheetIndex(0)->mergeCells('L1:Q1');
		$Excel->setActiveSheetIndex(0)->mergeCells('R1:W1');

		$Excel->getActiveSheet()->getColumnDimension('A')->setWidth("15");
		$Excel->getActiveSheet()->getColumnDimension('B')->setWidth("25");
		$Excel->getActiveSheet()->getColumnDimension('D')->setWidth("40");
		$last_num = count($data);
		$last_char = 'W2';
		$Excel->setActiveSheetIndex(0)->mergeCells("A{$last_num}:E{$last_num}");
		
		break;
	case 'sales_price_option':
		$data = array_merge(array($headers), $list);
		$Excel->getActiveSheet()->getColumnDimension('B')->setWidth("40");
		$Excel->getActiveSheet()->getColumnDimension('C')->setWidth("40");
		$last_num = count($data);
		$Excel->setActiveSheetIndex(0)->mergeCells("A{$last_num}:C{$last_num}");
		
		$last_char = column_char( count($headers) - 1 ).'1';
		break;
	case 'sales_price_paymethod':
		$data = array_merge(array($headers), $list);
		$last_char = column_char( count($headers) - 1 ).'1';
		$Excel->getActiveSheet()->getColumnDimension('A')->setWidth("15");
		break;
	case 'sales_category':
		$data = array_merge($headers, $list);
		$Excel->setActiveSheetIndex(0)->mergeCells('A1:A2');
		$Excel->setActiveSheetIndex(0)->mergeCells('B1:B2');
		$Excel->setActiveSheetIndex(0)->mergeCells('C1:C2');
		$Excel->setActiveSheetIndex(0)->mergeCells('D1:D2');
		$Excel->setActiveSheetIndex(0)->mergeCells('E1:F1');
		$Excel->setActiveSheetIndex(0)->mergeCells('G1:H1');
		$Excel->setActiveSheetIndex(0)->mergeCells('I1:J1');
		$last_char = 'J2';

		$Excel->getActiveSheet()->getColumnDimension('B')->setWidth("20");
		$Excel->getActiveSheet()->getColumnDimension('C')->setWidth("20");
		$Excel->getActiveSheet()->getColumnDimension('D')->setWidth("40");

		$last_num = count($data);
		$Excel->setActiveSheetIndex(0)->mergeCells("A{$last_num}:D{$last_num}");

		break;
	default:
		$data = array_merge(array($headers), $list);
		$last_char = column_char( count($headers) - 1 ).'1';
		break;
}

// pre($data);exit;
$header_bgcolor = 'FFF3F3F3';

//폰트정의
$styleArray = array(
	'font'  => array(
		'bold'  => false,
		'color' => array('rgb' => '000000'),
		'size'  => 9,
		'name'  => 'Verdana'
	),
	'alignment' => array(
		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER
	)
);
/*
$defaultBorder = array(
	'style' => PHPExcel_Style_Border::BORDER_THIN,
	'color' => array('rgb'=>'000000')
);
$headBorder = array(
	'borders'=> array(
	'bottom'=> $defaultBorder,
	'left'=> $defaultBorder,
	'top'=> $defaultBorder,
	'right'=> $defaultBorder
	)
);
*/




//$Excel->setActiveSheetIndex(0)->getStyle( "A1:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);

$Excel->getActiveSheet()->fromArray($data,NULL,'A1');
$Excel->getActiveSheet()->getStyle($Excel->getActiveSheet()->calculateWorksheetDimension())->getNumberFormat()->setFormatCode( PHPExcel_Style_NumberFormat::FORMAT_TEXT );
$Excel->getActiveSheet()->getStyle($Excel->getActiveSheet()->calculateWorksheetDimension())->applyFromArray($styleArray);
//foreach($headers as $i => $w) $Excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setAutoSize(true);

// $Excel->setActiveSheetIndex(0)->getStyle("A1:${last_char}")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
$Excel->setActiveSheetIndex(0)->getStyle("A1:${last_char}")->getFont()->setBold(true);


$writer = PHPExcel_IOFactory::createWriter($Excel, 'Excel2007');


header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="'.$xlsx_filename.'"');
header('Cache-Control: max-age=0');
$writer->save('php://output');


function column_char($i) { return chr( 65 + $i ); }
?>