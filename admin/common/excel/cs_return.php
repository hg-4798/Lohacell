<?php
/**
 * 반품목록 엑셀다운로드 처리
 */
$Order = new ORDER('admin');
$xlsx_filename = " 반품목록_".date('YmdHis').".xlsx"; //다운로드 파일명



parse_str($g_search, $search);
//헤더
$column_arr = explode(',',$g_column);

$headers = array();
$cfg_column = $config_excel['cs_return'];
foreach($column_arr as $c) {
	$headers[] = $cfg_column[$c]['name'];
}

switch($search['sort']) {
	case 'exchange_date_desc':
	default:
		$orderby = 'date_status_1 DESC';
		break;
	case 'exchange_date_asc':
		$orderby = 'date_status_1 ASC';
		break;
	case 'reg_desc':
		$orderby = 'date_insert DESC';
		break;
	case 'reg_asc':
		$orderby = 'date_insert ASC';
		break;
}
//검색
$where = "op.order_status > 0 AND op.cs_type='R' AND op.cs_status!='0' AND (op.cs_flag!='WD' OR op.cs_flag IS NULL)"; //기본검색(철회건 제외)

$where_add = $Order->adminSearch($search); //검색
$where.=$where_add;


//pre($where);exit;
$tbl_op = $Order->tbls['order_product'];
$tbl_ob = $Order->tbls['order'];
$tbl_p = $Order->tbls['product'];
$tbl_ord = $Order->tbls['order_refund'];
$tbl_orb = $Order->tbls['order_return']; //반품정보
$tbl_orp = $Order->tbls['order_return_product']; //반품상품정보
//목록
$sql = "
SELECT
	ob.member_id,
	ob.buyer_name,
	ob.pg_paymethod,
	ob.order_num,
	ob.date_insert AS date_insert,
	op.option_type,
	op.option_code,
	op.order_status,
	op.cs_type,
	op.cs_status,
	op.price_end,
	op.delivery_company,
	op.delivery_no,
	orb.date_status_1 AS date_status_1,
	orp.reason AS reason,
	orp.reason_charger AS reason_charger,
	ord.pay_delivery,
	p.productname
FROM
	{$tbl_op} AS op
LEFT JOIN {$tbl_p} AS p ON
	(op.productcode = p.productcode)
LEFT JOIN {$tbl_ob} AS ob ON
	(ob.order_num = op.order_num)
LEFT JOIN {$tbl_orb} AS orb ON
	(op.cs_idx = orb.idx)
LEFT JOIN {$tbl_orp} AS orp ON 
	(op.idx = orp.order_product_idx)
LEFT JOIN {$tbl_ord} AS ord ON 
	(orb.refund_idx = ord.idx)
WHERE
	{$where} ORDER BY {$orderby}, op.order_num DESC
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
	}
	$step_info = $Order->getStep($row);
	$row['info'] = $step_info['msg'];

	$payment_info = $Order->getPaymentRow($row['order_num']);
	$row['payment_detail'] = $Order->setPayInfo($row['pg_paymethod'], $payment_info['res_info'], 'all'); //결제정보

	//pre($row['exchange_option']);
	foreach($column_arr as $c) {
		switch($c) {
			case 'member_id':
				$temp[] = ($row['member_id']=='')?"비회원":$row['member_id'];
				break;
			case 'productname':
				$temp[] = $row['productname']."\n".$row['option_name'];
				break;
			case 'pg_paymethod':
				$temp[] = $row['payment_detail']['name'];
				break;
			case 'order_status':
				$temp[] = $row['info'];
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