<?php
/**
 * 교환처리
 */
$Dir = '../../';
include_once($Dir."lib/init.php");
include_once($Dir."lib/lib.php");

$layout = "inc";
include("../header.php");
$cs_idx = $_GET['csidx'];

$Product = new PRODUCT;
$Order = new ORDER;
$Order->setPlace('admin');


$tbl_basic = $Order->tbls['order'];
$tbl_p = $Order->tbls['product'];
$tbl_op = $Order->tbls['order_product'];
$tbl_oe = $Order->tbls['order_exchange'];
$tbl_oep = $Order->tbls['order_exchange_product'];


//교환요청서정보
$cs_basic  = $Order->adodb->getRow("SELECT * FROM {$tbl_oe} WHERE idx='{$cs_idx}'");
$cs_basic['receiver_mobile_arr'] = explode('-',$cs_basic['receiver_mobile']);
$cs_basic['receiver_tel_arr'] = explode('-',$cs_basic['receiver_tel']);
// pre($cs_basic);
//교환상품목록
$sql = "SELECT oep.*, p.productname, p.productcode, p.tinyimage, p.pr_type, op.option_type, op.option_code, op.order_status, op.cs_type, op.cs_status, op.cs_flag FROM  {$tbl_oep} AS oep LEFT JOIN {$tbl_op} AS op ON(oep.order_product_idx=op.idx) LEFT JOIN {$tbl_p} AS p ON(op.productcode = p.productcode) WHERE oep.exchange_idx='{$cs_idx}'";
$rs = $Order->adodb->Execute($sql);
$cnt = array(
	'active'=>'0'
);


while($row = $rs->FetchRow()) {
	//기존주문옵션
	$option = $Product->getOptionRow($row['option_code'],'option_num, option_name, (option_quantity-option_quantity_sales) AS quantity_remain'); 
	$row['option'] = $option;

	//교환요청옵션
	$option_exchange = $Product->getOptionRow($row['exchange_option_code'],'option_num, option_name, (option_quantity-option_quantity_sales) AS quantity_remain'); 
	$row['option_exchange'] = $option_exchange;


	//상태값
	$status = array(
		'order_status'=>$row['order_status'],
		'cs_type'=>$row['cs_type'],
		'cs_status'=>$row['cs_status'],
		'cs_flag'=>$row['cs_flag']
	);
	$row['step'] = $Order->getStep($status);

	//단계별 개수
	if($row['cs_flag']!= 'WD') {
		if($row['cs_status']!='4') $cnt['active']++;
		$cnt['status_'.$row['cs_status']]++;
	}
	if($row['cs_flag']) $cnt['flag_'.$row['cs_flag']]++;

	$cs_product[] = $row;
}


$assign = array(
	'cnt'=>$cnt,
	'cs'=>$cs_basic,
	'product'=>$cs_product
);

_render("cscenter/cs_exchange.pop.html", $assign, 'admin/template');

include("../copyright.php");

?>