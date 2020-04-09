<?php
/**
 * 엑셀다운로드관련 처리
 */

$Dir = "../../";
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$mode = $_POST['mode'];
$act = $_POST['act'];

if($mode == 'order') { //주문서 엑셀다운로드
	// pre($_POST);
	$Order = new ORDER('admin');
	$Product = new PRODUCT;

	include DOC_ROOT.'/third_party/PHPExcel/Classes/PHPExcel.php';

	$cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_to_sqlite;
	$cacheSettings = array('memoryCacheSize' => '32MB');
	PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

	$excel = new PHPExcel();


	$where = "op.order_status>0"; //기본검색

	//검색
	parse_str($_POST['search'], $search);

	$where_add = $Order->adminSearch($search); //검색
	$where.=$where_add;

	$page = ($_POST['page'])?$_POST['page']:'1';
	$limit = ($search['limit'])?$search['limit']:'20';

	//정렬
	switch($search['sort']) {
		case 'reg_desc':
		default:
			$orderby = 'date_insert DESC';
			break;
		case 'reg_asc':
			$orderby = 'date_insert ASC';
			break;
	}


	$tbl_op = $Order->tbls['order_product'];
	$tbl_ob = $Order->tbls['order'];
	$tbl_p = $Order->tbls['product'];

	$sql = "
	SELECT
		op.order_num,
		MAX(op.order_status) AS order_status,
		MAX(op.cs_type) AS cs_type,
		MAX(op.cs_status) AS cs_status,
		MAX(op.cs_flag) AS cs_flag,
		MAX(op.option_type) AS option_type,
		MAX(op.option_code) AS option_code,
		MAX(p.productcode) AS productcode,
		MAX(op.delivery_company) AS delivery_company,
		MAX(op.delivery_no) AS delivery_no,
		COUNT(*) AS cnt,
		MAX(ob.date_insert) AS date_insert
	FROM
		{$tbl_op} AS op
	LEFT JOIN {$tbl_p} AS p ON
		((op.productcode = p.productcode AND op.option_type='option') OR (op.option_code = p.productcode AND op.option_type='product'))
	LEFT JOIN {$tbl_ob} AS ob ON
		(ob.order_num = op.order_num)
	WHERE
		{$where}
	GROUP BY
		op.order_num,
		op.order_status, op.cs_type, op.cs_status, op.cs_flag, op.productcode, op.option_type, op.option_code
	ORDER BY {$orderby}, op.order_num DESC , op.option_type ASC
	";

	// echo $sql;

	$rs = $Order->adodb->Execute($sql);
	$list = array();
	$order_num = false;
	$idx = 2;
	$deli_company = $Order->getDeliveryCompanyPair();//택배사정보
	
	while($row = $rs->FetchRow()) {
		$basic = $Order->getBasicRow($row['order_num']); //주문기본정보
		$product = $Product->getRowSimple($row['productcode']); //상품정보

		if($row['option_type'] == 'option') {
			$option_info = $Product->getOptionRow($row['option_code']); //옵션정보
			$option_name = $option_info['option_name'];
		}
		else {
			$option_name = '';
		}

		$status = $Order->getStep($row); //처리상태
		$delivery_company = $deli_company[$row['delivery_company']]['company_name'];
		$row = array(
			'order_num'=>$row['order_num'],
			'productcode'=>$product['prodcode'],
			'productname'=>$product['productname'],
			'option'=>$option_name, //옵션명
			'cnt'=>$row['cnt'],
			'endprice'=>$product['endprice'],
			'sum'=>$product['endprice']*$row['cnt'],
			'delivery_price'=>$basic['pay_delivery']-$basic['coupon_delivery_discount'],
			'pay_total'=>$basic['pay_total'],
			'pay_method'=>$_CONFIG['pay_method'][$basic['pg_paymethod']],
			'receiver_name'=>$basic['receiver_name'],
			'member_id'=>$basic['member_id'],
			'zipcode'=>$basic['receiver_zipcode'],
			'addr'=>$basic['receiver_addr'].' '.$basic['receiver_addr_detail'],
			'email'=>$basic['buyer_email'],
			'mobile'=>$basic['receiver_mobile'],
			'tel'=>$basic['receiver_tel'],
			'status'=>$status['msg']
			//'delivery_company'=>$delivery_company,
			//'delivery_no'=>$row['delivery_no']
		);
		$list[] = $row;
		
		if($order_num === false) {
			$merge_s = $idx;
			$order_num = $row['order_num'];
		}

		if($order_num != $row['order_num']) {
			$merge_e = $idx-1;
			if($merge_e != $merge_s) {
				$excel->getActiveSheet()->mergeCells("A{$merge_s}:A{$merge_e}");
				$excel->getActiveSheet()->getStyle("A{$merge_s}:A{$merge_e}")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				// $row['order_num'] = $row['order_num'];
			}
			$order_num = $row['order_num'];
			$merge_s = $idx;
		}
		
		$idx++;

	}

	$headers = array(
		'주문번호',
		'상품코드',
		'상품명',
		'옵션',
		'수량',
		'상품금액',
		'총상품금액',
		'총배송비',
		'총주문금액',
		'결제방법',
		'수령자',
		'아이디',
		'우편번호',
		'주소',
		'이메일',
		'휴대전화',
		'일반전화',
		'처리상태',
		//'택배사',
		//'송장번호'
	);

	$data = array_merge(array($headers), $list);

	
	

	$last_char = column_char( count($headers) - 1 );

	//폰트정의
	$styleArray = array(
		'font'  => array(
			'bold'  => false,
			'color' => array('rgb' => '000000'),
			'size'  => 9,
			'name'  => 'Verdana'
		),
		'alignment' => array(
			'horizontal' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
		)
	);
	$header_bgcolor = 'FFEBEBEB';

	$xlsx_filename = "주문내역_".date('YmdHis').".xlsx";
	$excel->setActiveSheetIndex(0)->getStyle( "A1:${last_char}1" )->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($header_bgcolor);
	$excel->setActiveSheetIndex(0)->getStyle( "A:$last_char" )->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER)->setWrapText(true);

	$excel->getActiveSheet()->fromArray($data,NULL,'A1');
	$excel->getActiveSheet()->getStyle($excel->getActiveSheet()->calculateWorksheetDimension() )->applyFromArray($styleArray); 

	foreach($headers as $i => $w) $excel->setActiveSheetIndex(0)->getColumnDimension( column_char($i) )->setAutoSize(true);
	$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');

	header('Content-Type: application/octet-stream');
	header('Content-Disposition: attachment; filename="'.$xlsx_filename.'"');
	header('Cache-Control: max-age=0');
	$writer->save('php://output');



}

function column_char($i) { return chr( 65 + $i ); }

?>