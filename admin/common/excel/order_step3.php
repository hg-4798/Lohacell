<?php
/**
 * 배송준비중목록 엑셀다운로드 처리
 */
$Order = new ORDER('admin');
$xlsx_filename = "배송준비중목록_".date('YmdHis').".xlsx"; //다운로드 파일명



parse_str($g_search, $search);
//헤더
$column_arr = explode(',',$g_column);

array_push($column_arr,'idx'); //송장번호 변경 위한 필수값 기본 삽입

$headers = array();
$cfg_column = $config_excel['order_step3'];
foreach($column_arr as $c) {
	if($c=='idx'){
		$headers[] = '상품별주문번호(송장번호 일괄수정용 필수값)';
	}else {
		$headers[] = $cfg_column[$c]['name'];
	}
}

switch($search['sort']) {
	case 'reg_desc':
	default:
		$orderby = 'op.date_insert DESC';
		break;
	case 'reg_asc':
		$orderby = 'op.date_insert ASC';
		break;
}

//검색
$where = "op.order_status='3' AND (op.cs_type='0' OR op.cs_type='E')"; //기본검색

$where_add = $Order->adminSearch($search); //검색
$where.=$where_add;


//pre($where);exit;
$tbl_op = $Order->tbls['order_product'];
$tbl_ob = $Order->tbls['order'];
$tbl_p = $Order->tbls['product'];
//목록
$sql = "
SELECT
	ob.member_id,
	ob.buyer_name,
	ob.pg_paymethod,
	ob.order_num,
	op.idx,
	op.date_order_1,
	op.option_type,
	op.option_code,
	op.order_status,
	op.cs_type,
	op.cs_status,
	op.price_end,
	op.delivery_company,
	op.delivery_no,
	p.productname
FROM
	{$tbl_op} AS op
LEFT JOIN {$tbl_p} AS p ON
	(op.productcode = p.productcode)
LEFT JOIN {$tbl_ob} AS ob ON
	(ob.order_num = op.order_num)
WHERE
	{$where} ORDER BY {$orderby}, op.order_num, op.idx DESC
";
//pre($sql);exit;
$rs = $Order->adodb->Execute($sql);


$Product = new PRODUCT;
//pre($sql);exit;
$deli_company = $Order->getDeliveryCompanyPair();//택배사정보


$rs = $Order->adodb->Execute($sql);
while($row = $rs->FetchRow()) {
	$temp = array();
	if($row['option_type'] =='option') {
		$option = $Product->getOptionRow($row['option_code'],'option_name');
		$row['option_name'] = $option['option_name'];
	}else{
		$option = $Product->getRowSimple($row['option_code'], false);
		$row['productname'] = $option['productname'];
	}
	$step_info = $Order->getStep($row);
	$row['info'] = $step_info['msg'];

	$payment_info = $Order->getPaymentRow($row['order_num']);
	$row['payment_detail'] = $Order->setPayInfo($row['pg_paymethod'], $payment_info['res_info'], 'all'); //결제정보
	$row['quantity'] = 1; //수량은 고정


	//pre($product);
	foreach($column_arr as $c) {
		switch($c) {
			case 'member_id':
				$temp[] = ($row['member_id']=='')?"비회원":$row['member_id'];
				break;
			case 'productname':
				$temp[] = $row['productname'];
				break;
			case 'pg_paymethod':
				$temp[] = $row['payment_detail']['name'];
				break;
			case 'order_status':
				$temp[] = $_CONFIG['order_status'][3];
				break;
			case 'delivery_company':
				$temp[$no] = $deli_company[$row['delivery_company']]['company_name']; //택배사 이름 변환
				break;
			default:
				$temp[] = $row[$c];
			break;
		}
	}

	$list[] = $temp;
}
//pre($list);exit;
?>