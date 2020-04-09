<?php
/**
 * 교환목록 엑셀다운로드 처리
 */
$Order = new ORDER('admin');
$xlsx_filename = " 교환목록_".date('YmdHis').".xlsx"; //다운로드 파일명



parse_str($g_search, $search);
//헤더
$column_arr = explode(',',$g_column);

$headers = array();
$cfg_column = $config_excel['cs_exchange'];
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
$where = "op.order_status > 0 AND op.cs_type='E' AND op.cs_status!='0' AND (op.cs_flag!='WD' OR op.cs_flag IS NULL)";  //기본검색(철회건 제외)

$where_add = $Order->adminSearch($search); //검색
$where.=$where_add;


//pre($where);exit;
$tbl_op = $Order->tbls['order_product'];
$tbl_ob = $Order->tbls['order'];
$tbl_p = $Order->tbls['product'];
$tbl_oe = $Order->tbls['order_exchange']; //교환정보
$tbl_oep = $Order->tbls['order_exchange_product']; //교환상품정보
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
	oe.date_status_1 AS date_status_1,
	oe.delivery_pay,
	oep.reason AS reason,
	oep.reason_charger AS reason_charger,
	oep.exchange_option_code AS exchange_option_code,
	p.productname
FROM
	{$tbl_op} AS op
LEFT JOIN {$tbl_p} AS p ON
	(op.productcode = p.productcode)
LEFT JOIN {$tbl_ob} AS ob ON
	(ob.order_num = op.order_num)
LEFT JOIN {$tbl_oe} AS oe ON
	(op.cs_idx = oe.idx)
LEFT JOIN {$tbl_oep} AS oep ON 
	(op.idx = oep.order_product_idx)
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

	$row['exchange_option'] = $Product->getOptionRow($row['exchange_option_code'], 'option_name, (option_quantity - option_quantity_sales) AS stock');
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
			case 'exchange_option':
				$temp[] = $row['exchange_option']['option_name'];
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